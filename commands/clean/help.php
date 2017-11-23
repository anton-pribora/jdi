<?php
/* @var $this ApCode\Executor\RuntimeInterface */
$limits = Config()->get('limit.expire');
?>
JDI CLEAN   Help

Чистит очередь заданий от устаревших записей.

Синопсис

  <?=app()?> clean

Описание

Удаляет задачу из очереди выполнения, если она запускалась раньше определённого скрока.

  Статус  Дата истечения срока годности
<?php
foreach ($limits as $status => $limit) {
    printf("% 8s  %s\n", $status, $limit);
}
?>

Время считается от даты последнего запуска задания.
