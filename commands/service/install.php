<?php

/* @var $this ApCode\Executor\RuntimeInterface */

$action = ExpandPath("@command/install/all.php");
$onlyCommands = $this->param(0) == '-c' || !posix_isatty(STDOUT);

$this->execute($action, ['onlyCommands' => $onlyCommands]);

$cronFile = ExpandPath(Config()->get('service.cron.local'));
$cronJob  = ExpandPath(Config()->get('service.cron.dest'));

$logrotateFile = ExpandPath(Config()->get('service.logrotate.local'));
$logrotateJob  = ExpandPath(Config()->get('service.logrotate.dest'));

$serviceSrc  = ExpandPath(Config()->get('service.systemd.local'));
$serviceDest = ExpandPath(Config()->get('service.systemd.dest'));

$serverSrc  = ExpandPath(Config()->get('service.server.local'));
$serverDest = ExpandPath(Config()->get('service.server.dest'));

$serviceName = pathinfo($serviceDest, PATHINFO_FILENAME);

$commands = [
    "cp '$cronFile' '$cronJob'",
    "cp '$logrotateFile' '$logrotateJob'",
    "cp '$serverSrc' '$serverDest'",
    "cp '$serviceSrc' '$serviceDest'",
    "systemctl enable $serviceName",
    "systemctl start $serviceName",
    '',
];

if ($onlyCommands) {
    echo join(PHP_EOL, $commands);
    return ;
}

?>

Для завершения установки выполните под логином суперпользователя следующие команды:

<?=join(PHP_EOL, $commands)?>

Для проверки статуса сервиса используйте:

service <?=$serviceName?> status

