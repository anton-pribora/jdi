<?php

/* @var $this ApCode\Executor\RuntimeInterface */
$onlyCommands = $this->param(0) == '-c' || !posix_isatty(STDOUT);

$cronJob      = ExpandPath(Config()->get('service.cron.dest'));
$serviceDest  = ExpandPath(Config()->get('service.systemd.dest'));
$serverDest   = ExpandPath(Config()->get('service.server.dest'));
$logrotateJob = ExpandPath(Config()->get('service.logrotate.dest'));

$serviceName = pathinfo($serviceDest, PATHINFO_FILENAME);

$commands = [
    "systemctl stop $serviceName",
    "systemctl disable $serviceName",
    "rm '$cronJob'",
    "rm '$serviceDest'",
    "rm '$logrotateJob'",
    "rm '$serverDest'",
    '',
];

if ($onlyCommands) {
    echo join(PHP_EOL, $commands);
    return ;
}

?>
Для удаления сервиса приложения выполните следующе команды от суперпользователя:

<?=join(PHP_EOL, $commands)?>

