<?php

/* @var $this ApCode\Executor\RuntimeInterface */
$onlyCommands = $this->param(0) == '-c' || !posix_isatty(STDOUT);

$cronJob      = ExpandPath(Config()->get('service.cron.job'));
$serviceDest  = ExpandPath(Config()->get('service.systemd.service'));
$logrotateJob = ExpandPath(Config()->get('service.logrotate.job'));

$serviceName = pathinfo($serviceDest, PATHINFO_FILENAME);

$commands = [
    "systemctl stop $serviceName",
    "systemctl disable $serviceName",
    "rm $cronJob",
    "rm $serviceDest",
    "rm $logrotateJob",
    '',
];

if ($onlyCommands) {
    echo join(PHP_EOL, $commands);
    return ;
}

?>
Для удаления сервиса приложения выполните следующе команды от суперпользователя:

<?=join(PHP_EOL, $commands)?>

