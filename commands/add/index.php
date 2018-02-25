<?php

use Jdi\Task;

/* @var $this ApCode\Executor\RuntimeInterface */

$args    = $this->paramList();
$params  = [];
$commandArray = [];

foreach ($args as $i => $arg) {
    if (substr($arg, 0, 2) == '--') {
        $params[] = $arg;
        unset($args[$i]);
        continue;
    }
    
    $commandArray = $args;
    break;
}

$captureStdin = !posix_isatty(STDIN);

foreach ($params as $param) {
    switch ($param) {
        case '--stdin':
            $captureStdin = true;
            break;
            
        default:
            printf("Неизвестный параметр `%s'\n", $param);
            exit(-1);
    }
}

if ($captureStdin) {
    $stdin = STDIN;
} else {
    $stdin = false;
}

$command = $commandArray[0] ?? '';

if (empty($command)) {
    printf("Пустая команда\n");
    exit(-1);
}

$fullPath = exec('which '. escapeshellarg($command));

if (empty($fullPath)) {
    printf("Не удалось установить полный путь к команде %s\n", escapeshellarg($command));
    exit(-1);
}

$fullPath = realpath($fullPath);
$matched = false;

foreach (Config()->get('allow_exec', []) as $pattern) {
    if (fnmatch($pattern, $fullPath)) {
        $matched = true;
        break;
    }
}

if (!$matched) {
    printf("Команда %s не разрешена к выполнению\n", escapeshellarg($fullPath));
    exit(-1);
}

$commandArray[0] = $fullPath;

foreach ($commandArray as $i => $argument) {
    $commandArray[$i] = escapeshellarg($argument);
}

$task = new Task();
$task->setCommand(join(' ', $commandArray));
$task->setDate(mysql_datetime());
$task->setRunAt(mysql_datetime());
$task->extra()->set('pwd', getcwd());
$task->extra()->set('pid', posix_getpid());
$task->setStatusAndSave(Task::STATUS_ADDING);

if ($stdin && !feof($stdin)) {
    $task->stdin()->touch();
    $task->save();
    
    $fp = fopen($task->stdin()->path(), 'w');
    
    stream_copy_to_stream($stdin, $fp);
    
    fclose($fp);
    fclose($stdin);
    
    $task->stdin()->removeIfEmpty();
}

$task->extra()->remove('pid');
$task->setStatusAndSave(Task::STATUS_READY);

Db()->disconnect();

$message = sprintf("Task #%s: Добавлено задание для выполнения команды %s", $task->id(), $task->command());

if (posix_isatty(STDOUT)) {
    printf("%s\n", $message);
} else {
    printf("%s\n", $task->id());
}

Logger()->log('common', $message);

jdi_next();
