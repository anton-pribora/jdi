<?php

/* @var $this ApCode\Executor\RuntimeInterface */

$serviceFile = ExpandPath(Config()->get('service.systemd.file'));
$serviceName = pathinfo($serviceFile, PATHINFO_FILENAME);

?>
JDI LIST   Help

Управление сервисом

Синопсис
  
  <?=app()?> list [all|done|fail|ready|running]
  
Описание

Выводит список заданий в очереди. Так же может фильтровать по статусу выполнения.
