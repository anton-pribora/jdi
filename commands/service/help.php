<?php

/* @var $this ApCode\Executor\RuntimeInterface */

$serviceFile = ExpandPath(Config()->get('service.systemd.file'));
$serviceName = pathinfo($serviceFile, PATHINFO_FILENAME);

?>
JDI SERVICE   Help

Управление сервисом

Синопсис
  
  <?=app()?> service КОМАНДА [-c]
  
Допустимые команды

  install   Устанавливает сервис приложения в систему
  remove    Удаляет сервис приложения из системы
  
Параметры

  -c        Вывод только команд для установки/удаления сервиса

Описание

Сервис устанавливает настройки для crontab и systemd.

Для установки сервиса в систему выполните:

  % ./jdi.php service install | sudo sh
  
Для удаления сервиса из системы выполните:

  % ./jdi.php service remove | sudo sh
  
Для проверки статуса сервиса используйте системную команду service:

  % service <?=$serviceName?> status

