<?php

/* @var $this ApCode\Executor\RuntimeInterface */

$serviceFile = ExpandPath(Config()->get('service.systemd.file'));
$serviceName = pathinfo($serviceFile, PATHINFO_FILENAME);

?>
JDI LIST   Help

Управление списоком заданий

Синопсис
  
  <?=app()?> list [--json|--remove] [all|done|fail|ready|running]

Параметры
  
  --json    Вывод списка в формате JSON
  --remove  Удалить задания из списка
  
Описание

Выводит список заданий в очереди. Так же может фильтровать по статусу выполнения.
