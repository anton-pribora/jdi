#!/usr/bin/env php
<?php

setlocale(LC_ALL, "ru_RU.UTF-8");

$jdi_task = getenv('JDI_TASK');
$jdi_exec = getenv('JDI_EXEC');

$updateProgress = function ($percent, $text) use ($jdi_task, $jdi_exec) {
    if (empty($jdi_exec) || empty($jdi_task)) {
        return ;
    }
    
    $command = [
        escapeshellcmd($jdi_exec),
        'task',
        $jdi_task,
        '--set-progress='. escapeshellarg($percent),
        '--set-progress-text='. escapeshellarg($text),
    ];
    
    exec(join(' ', $command));
};

$steps = [
    'Инициализация пространства и времени',
    'Создаём 1мг материи',
    'Начинаем большой взрыв',
    'Формируем водород',
    'Начинаем процесс охлаждения',
    'Зарождаем галактики',
    'Добавляем планеты',
    'Формируем жизнь',
    'Берём с полки пирожок',
    'Процесс завершён',
];

$time  = rand(10, 60);
$sleep = ceil($time / count($steps));

foreach ($steps as $i => $step) {
    if ($i) {
        sleep($sleep);
    }
    
    $percent = ceil($i / (count($steps) - 1) * 100);
    
    $updateProgress($percent, $step);
}
