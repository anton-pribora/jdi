#!/usr/bin/env php
<?php

ini_set('display_errors', true);

use ApCode\Executor\PhpFileExecutor;

define('ROOT_DIR', __DIR__);

function glob_include($pattern, $flags = NULL) {
    foreach (glob($pattern, $flags) as $file) {
        include $file;
    }
}

glob_include(__DIR__ .'/src/init/*.php');

if (!defined('STDIN')) {
    die('You must use this command only from console');
}

$params = $argv;

array_shift($params);

$command = $params ? array_shift($params) : 'help';
$action  = $command .'/index.php';

$executor = new PhpFileExecutor(ExpandPath('@commands'));

PathAlias()->set('@exec', __FILE__);
PathAlias()->set('@app' , './'. basename(__FILE__));

if ($executor->canExecute($action, $params)) {
    PathAlias()->set('@command', $command);
    $executor->execute($action, $params);
} else {
    printf("Command `%s' does not exist. Try `%s help' for more information.\n", $command, ExpandPath(app()));
    exit(-1);
}