<?php

/* @var $this ApCode\Executor\RuntimeInterface */
/* @var $task Jdi\Task */
$task = $this->argument(0);

$makeParamName = function ($param) {
    $result = preg_replace('~^set-~', '', $param);
    return preg_replace_callback('~-(\w)~ui', function($matches){
        return mb_strtoupper($matches[1]);
    }, $result);
};

foreach ($this->paramList() as $param => $value) {
    $task->extra()->set($makeParamName($param), $value);
}

$task->save();

$message = sprintf("Task #%s: Добавлены дополнительные данные", $task->id());

printf("%s\n", $message);