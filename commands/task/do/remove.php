<?php

use Jdi\Task;

/* @var $this ApCode\Executor\RuntimeInterface */
/* @var $task Jdi\Task */
$task = $this->argument(0);

$task->delete();

$message = sprintf('Task #%s: Задание было удалено', $task->id());

printf("%s\n", $message);
Logger()->log('common', $message);