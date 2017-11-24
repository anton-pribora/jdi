<?php

/* @var $this ApCode\Executor\RuntimeInterface */
/* @var $task Jdi\Task */
$task = $this->argument(0);

?>
Задание #<?=$task->id()?> 
Статус: <?=$task->status()?> 
Добалено: <?=$task->date()?> 
Старт запланирован на: <?=$task->runAt()?> 
Количество провальных запусков: <?=$task->fails() ?? '-'?> 
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