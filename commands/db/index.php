<?php

/* @var $this ApCode\Executor\RuntimeInterface */

$params  = $this->paramList();
$command = $params ? array_shift($params) : 'help';
$action  = ExpandPath("@command/{$command}.php");

if ($this->canExecute($action)) {
    $this->execute($action, $params);
} else {
    printf("Неизвестная команда `%s'. Используйте `%s help %s' для справки.\n", $command, app(), ExpandPath('@command'));
}