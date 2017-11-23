<?php 

use Jdi\TaskRepository;

/* @var $this ApCode\Executor\RuntimeInterface */

$status = $this->paramList();

if (in_array('all', $status)) {
    $status = [];
}

if ($status) {
    $list = TaskRepository::findMany(['status' => $status]);
} else {
    $list = TaskRepository::findMany([]);
}

if ($list) {
    printf("ID      Дата создания        Дата запуска          Статус  Команда\n");
    foreach ($list as $item) {
        printf("% 6s  %10s  %10s % 8s  %s\n", $item->id(), $item->date(), $item->runAt(), $item->status(), $item->command());
    }
} else {
    printf("Нет подходящих заданий\n");
}
