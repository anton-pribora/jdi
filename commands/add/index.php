<?php

use Jdi\Task;

/* @var $this ApCode\Executor\RuntimeInterface */

$args    = $this->paramList();
$params  = [];
$command = [];

foreach ($args as $i => $arg) {
    if (substr($arg, 0, 2) == '--') {
        $params[] = $arg;
        unset($args[$i]);
        continue;
    }
    
    $command = $args;
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

$task = new Task();
$task->setCommand(join(' ', $command));
$task->setDate(mysql_datetime());
$task->setRunAt(mysql_datetime());
$task->setStatusAndSave(Task::STATUS_ADDING);
$task->extra()->set('pwd', getcwd());

if ($stdin && !feof($stdin)) {
    $task->stdin()->touch();
    $task->save();
    
    $fp = fopen($task->stdin()->path(), 'w');
    
    stream_copy_to_stream($stdin, $fp);
    
    fclose($fp);
    fclose($stdin);
    
    $task->stdin()->removeIfEmpty();
}

$task->setStatusAndSave(Task::STATUS_READY);

$message = sprintf("Task #%s: Добавлено задание для выполнения команды %s", $task->id(), $task->command());

if (posix_isatty(STDOUT)) {
    printf("%s\n", $message);
} else {
    printf("%s\n", $task->id());
}

Logger()->log('common', $message);

jdi_next();
