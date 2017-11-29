<?php

use Jdi\Task;

/* @var $this ApCode\Executor\RuntimeInterface */
/* @var $task Jdi\Task */
$task = $this->argument(0);

$task->setRunAt(mysql_datetime());
$task->setStatusAndSave(Task::STATUS_READY);

$message = sprintf('Task #%s: Выставлен статус \'готов к выполнению\'', $task->id());

printf("%s\n", $message);
Logger()->log('common', $message);