<?php 

use Jdi\Task;
use ApCode\Console\ArgumentParser\ArgumentParser;

/* @var $this ApCode\Executor\RuntimeInterface */

$params = [
    
];

$options = (new ArgumentParser($params))->parse($this->paramList());

$taskId = $options->arguments();

if (empty($taskId)) {
    printf("Необходимо указать номер задания\n");
    return false;
}

$printTask = function (Task $task) {
?>
Задание #<?=$task->id()?> 
Добалено: <?=$task->date()?> 
Дата выполнения <?=$task->runAt()?> 
количество провальных запусков: <?=$task->fails() ?? '-'?> 
Команда: <?=$task->command()?> 
STDIN: <?=$task->stdin()->exists() ? $task->stdin()->path() : 'нет'?> 
Дополнительные данные: <?=$task->extra()->exists() ? $task->extra()->__toString() : 'нет'?> 

<?php
    foreach ($task->runs() as $run) {
?>
Запуск #<?=$run->id()?> 
Начало выполнения: <?=$run->start()?> 
Окончание выполнения: <?=$run->end()?> 
Код завершения: <?=$run->exitCode()?> 
STDOUT: <?=$run->stdout()->exists() ? $run->stdout()->path() : 'нет'?> 
STDERR: <?=$run->stderr()->exists() ? $run->stderr()->path() : 'нет'?> 

<?php
    }
};

foreach ($taskId as $id) {
    $task = Task::getInstance($id);
    
    if (empty($task->id())) {
        printf("Задание #%s не найдено\n", $id);
        continue;
    }
    
    $printTask($task);
}