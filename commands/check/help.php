<?php
/* @var $this ApCode\Executor\RuntimeInterface */
$limits = Config()->get('limit.expire');
?>
JDI CHECK   Help

Проверяет соответствие PID выполняемой команде. Если процесса такого нет, то ставт статус 'прервано'.

Синопсис

  <?=app()?> check
