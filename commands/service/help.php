<?php

/* @var $this ApCode\Executor\RuntimeInterface */

$serviceFile = ExpandPath(Config()->get('service.systemd.file'));
$serviceName = pathinfo($serviceFile, PATHINFO_FILENAME);

?>
JDI SERVICE   Help

Управление сервисом.

Синопсис
  
  <?=app()?> service КОМАНДА [-c] [cron] [logrotate] [systemd] [inetd]
  
Допустимые команды

  install    Устанавливает сервис приложения в систему.
  remove     Удаляет сервис приложения из системы.
  reinstall  Сначала удаляет, затем устанавливает сервис приложения.
  
Параметры

  -c        Вывод только команд для установки/удаления сервиса.
  cron      Установка конфигов крона.
  logrotate Установка конфигов для автоматической ротайии логов.
  systemd   Настройка системного сервиса для операционных систем,
            с установленным systemd (Debian 8+, Ubuntu 15.10+)
  inetd     Настройка системного сервиса, используя SysV. Для систем,
            не поддерживающих systemd.

Описание

Сервис устанавливает настройки для системных служб. Если службы не заданы, то 
устанавливается сron, logrotate и системный севрис systemd или inetd.

Для установки сервиса в систему выполните:

  % ./jdi.php service install | sudo sh
  
Для удаления сервиса из системы выполните:

  % ./jdi.php service remove | sudo sh
  
Для проверки статуса сервиса используйте системную команду service:

  % service <?=$serviceName?> status

