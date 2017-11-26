<?php 

use Jdi\TaskRepository;
use ApCode\Console\ArgumentParser\ArgumentParser;

/* @var $this ApCode\Executor\RuntimeInterface */

$params = [
    'json'   => '--json',
    'remove' => '--remove',
];

$options = (new ArgumentParser($params))->parse($this->paramList());

if ($options->hasUnknowns()) {
    printf("Неверные параметры %s. Используйте %s для справки.\n", 
        join(', ', array_keys($options->unknowns())),
        ExpandPath('@app help @command')
    );
    exit(-2);
}

$status = $options->arguments();

if (in_array('all', $status)) {
    $status = [];
}

if ($status) {
    $list = TaskRepository::findMany(['status' => $status]);
} else {
    $list = TaskRepository::findMany([]);
}

if ($options->hasOpt('remove')) {
    foreach ($list as $task) {
        $message = sprintf('Task #%s: Удаление задания', $task->id());
        $task->delete();
        
        printf("%s\n", $message);
        Logger()->log('common', $message);
    }
    
    printf("Очередь очищена\n");
} elseif ($list) {
    if ($options->hasOpt('json')) {
        echo json_encode_array_pretty_print($list), PHP_EOL;
    } else {
        printf("ID      Дата создания        Дата запуска          Статус  Команда\n");
        foreach ($list as $item) {
            printf("% 6s  %10s  %10s % 8s  %s\n", $item->id(), $item->date(), $item->runAt(), $item->status(), $item->command());
        }
    }
} else {
    printf("Нет подходящих заданий\n");
}
