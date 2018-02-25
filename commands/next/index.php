<?php

use Jdi\Task;
use Jdi\TaskRepository;
use ApCode\Billet\BilletRepository;
use Jdi\Task\Run;

/* @var $this ApCode\Executor\RuntimeInterface */

$runningLimit = Config()->get('limit.run_at_once');
$failsLimit   = Config()->get('limit.fails');

if (!TaskRepository::checkRunningLimit($runningLimit)) {
    printf("Достигнут лимит выполнения заданий (%s), запуск нового задания пока невозможен\n", $runningLimit);
    return false;
}

$task = TaskRepository::findOne(['status' => Task::STATUS_READY, 'run_at<=' => mysql_datetime()]);

if (!$task) {
    printf("Нет заданий, готовых к выполнению\n");
    return false;
}

$command = escapeshellcmd($task->command());
$matched = false;

foreach (Config()->get('allow_exec', []) as $pattern) {
    if (fnmatch($pattern, trim($command, '"\''))) {
        $matched = true;
        break;
    }
}

if (!$matched) {
    $message = sprintf("Task #%s: Выполнение задания не разрешено конфигом allow_exec", $task->id());
    
    printf("%s\n", $message);
    Logger()->log('common', $message);
    
    $task->setStatusAndSave(Task::STATUS_FORBIDDEN);
    exit(-2);
}

printf("Task #%s: Выполняем задание $command\n", $task->id());

$task->extra()->set('pid', posix_getpid());
$task->setStatusAndSave(Task::STATUS_RUNNING);

$run = $task->makeNewRun();

$run->setStart(mysql_datetime());
$run->stdout()->touch();
$run->stderr()->touch();

$run->save();

$taskId = $task->id();
$runId  = $run->id();

$stdin = $task->stdin()->exists() ? $task->stdin()->path() : '/dev/null';

$descriptorspec = [
   0 => ["file", $stdin, "r"],
   1 => ["file", $run->stdout()->path(), "w"],
   2 => ["file", $run->stderr()->path(), "w"],
];

$env = [
    'JDI_EXEC' => ExpandPath('@exec'),
    'JDI_TASK' => $taskId,
    'JDI_RUN'  => $runId,
];

$pwd = $task->extra()->get('pwd');

$process = proc_open($command, $descriptorspec, $pipes, $pwd, $env);

if (is_resource($process)) {
    $status = proc_get_status($process);
    
    $run->extra()->set('pid', $status['pid']);
    $run->save();
    
    Db()->disconnect();
}

$exitCode = proc_close($process);

// Повторно соединяемся с базой
Db()->checkConnection();

// Перезагружаем объекты из базы данных, чтобы не потерять изменения,
// которые могли произойти во время выполнения задания
BilletRepository::clearCache();

$task = Task::getInstance($taskId);
$run  = Run::getInstance($runId);

if (empty($task->id()) || empty($run->id())) {
    // Пока выполнялось задание, базу подчистили
    $message = sprintf("Task #%s: Во время выполнения задания база данных была сброшена!", $taskId);
    printf("%s\n", $message);
    Logger()->log('common', $message);
    exit(-2);
}

$run->setExitCode($exitCode);
$run->setEnd(mysql_datetime());
$run->stderr()->removeIfEmpty();
$run->stdout()->removeIfEmpty();
$run->extra()->remove('pid');

$run->save();

$task->extra()->remove('pid');

if ($exitCode > 0 || $exitCode < 0) {
    $task->setFails($task->fails() + 1);
    
    if ($task->fails() < $failsLimit) {
        $task->setRunAt(mysql_datetime('+1 minute'));
        $task->setStatusAndSave(Task::STATUS_READY);
    } else {
        $task->setStatusAndSave(Task::STATUS_FAIL);
    }
    
    $message = sprintf("Task #%s: Задание выполнено с ошибками, код завершения %s", $task->id(), $exitCode);
} else {
    $task->setStatusAndSave(Task::STATUS_DONE);
    $message = sprintf("Task #%s: Задание успешно выполнено", $task->id());
}

printf("%s\n", $message);
Logger()->log('common', $message);

Db()->disconnect();

jdi_next();
