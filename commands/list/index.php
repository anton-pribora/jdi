<?php 

use Jdi\TaskRepository;
use ApCode\Console\ArgumentParser\ArgumentParser;

/* @var $this ApCode\Executor\RuntimeInterface */

$params = [
    'json'   => '--json',
    'remove' => '--remove',
    'owner'  => '-o, --owner, =, +',
];

$options = (new ArgumentParser($params))->parse($this->paramList());

$splitOwners = function ($str) {
    return preg_split('/[\s,;]+/', $str, null, PREG_SPLIT_NO_EMPTY);
};

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

$owners = [];

foreach ($options->opt('owner', []) as $value) {
    $owners = array_merge($owners, $splitOwners($value));
}

$where = [];

if ($status) {
    $where['status'] = $status;
}

if ($owners) {
    $where['owner'] = $owners;
}

$list = TaskRepository::findMany($where);

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
        printf("ID      Дата создания        Дата запуска        Статус       PID    Владелец  Команда\n");
        foreach ($list as $item) {
            printf("% 6s  %10s  %10s % -11s  % -6s %-9s %s\n", $item->id(), $item->date(), $item->runAt(), $item->status(), $item->extra()->get('pid'), join(',', $item->owners()), $item->command());
        }
    }
} elseif ($options->hasOpt('json')) {
    echo json_encode_array([]);
} else {
    printf("Нет подходящих заданий\n");
}
