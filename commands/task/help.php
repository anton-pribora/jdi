<?php

/* @var $this ApCode\Executor\RuntimeInterface */

$serviceFile = ExpandPath(Config()->get('service.systemd.file'));
$serviceName = pathinfo($serviceFile, PATHINFO_FILENAME);

?>
JDI TASK   Help

Управление заданием

Синопсис
  
  <?=app()?> task [НОМЕР ЗАДАНИЯ]
  
Описание

Выводит информацию по номеру задания.
