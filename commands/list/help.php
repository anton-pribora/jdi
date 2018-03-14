<?php

/* @var $this ApCode\Executor\RuntimeInterface */

$serviceFile = ExpandPath(Config()->get('service.systemd.file'));
$serviceName = pathinfo($serviceFile, PATHINFO_FILENAME);

?>
JDI LIST   Help

Управление списоком заданий

Синопсис
  
  <?=app()?> list [--json|--remove] [--owner=владелец1,владелец2] [all|done|fail|ready|running]

Параметры
  
  --json    Вывод списка в формате JSON
  --remove  Удалить задания из списка
  --owner   Валделец задания. Произвольное текстовое значение (например, user.123)
            Допускается несколько значений, разделённых запятой, пробелом или
            точкой с запятой.
  
Описание

Выводит список заданий в очереди. Так же может фильтровать по статусу выполнения.
