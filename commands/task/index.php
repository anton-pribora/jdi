<?php 

use Jdi\Task;
use ApCode\Console\ArgumentParser\ArgumentParser;

/* @var $this ApCode\Executor\RuntimeInterface */

$params = [
    'json'   => '--json',
    'ready'  => '--ready',
    'cancel' => '--cancel',
    'remove' => '--remove',
    
    'set'    => '--set-*',
    'unset'  => '--unset-*',
];

$options = (new ArgumentParser($params))->parse($this->paramList());

if ($options->hasUnknowns()) {
    printf("Неверные параметры %s. Используйте %s для справки.\n", 
        join(', ', array_keys($options->unknowns())),
        ExpandPath('@app help @command')
    );
    exit(-2);
}

$taskId = $options->arguments();

if (empty($taskId)) {
    printf("Необходимо указать номер задания\n");
    return false;
}

$do = ExpandPath('@command/do');
$printJson = [];

foreach ($taskId as $id) {
    $task  = Task::getInstance($id);
    $print = true;
    
    if (empty($task->id())) {
        if ($options->hasOpt('json')) {
            ;
        } else {
            printf("Задание #%s не найдено\n", $id);
        }
        continue;
    }
    
    if ($options->hasOpt('ready')) {
        $this->execute("$do/ready.php", $task);
        $print = false;
    }
    
    if ($options->hasOpt('remove')) {
        $this->execute("$do/remove.php", $task);
        $print = false;
    }
    
    if ($options->hasOpt('cancel')) {
        $this->execute("$do/cancel.php", $task);
        $print = false;
    }
    
    if ($options->hasOpt('set')) {
        $this->execute("$do/set.php", $task, $options->opt('set'));
        $print = false;
    }
    
    if ($options->hasOpt('unset')) {
        $this->execute("$do/unset.php", $task, $options->opt('unset'));
        $print = false;
    }
    
    if ($print) {
        if ($options->hasOpt('json')) {
            $printJson[] = $task;
        } else {
            $this->execute("$do/print.php", $task);
        }
    }
}

if ($printJson) {
    if (count($taskId) === 1) {
        echo json_encode_array_pretty_print($task), PHP_EOL;
    } else {
        echo json_encode_array_pretty_print($printJson), PHP_EOL;
    }
}