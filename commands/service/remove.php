<?php

/* @var $this ApCode\Executor\RuntimeInterface */
$onlyCommands = $this->param(0) == '-c' || !posix_isatty(STDOUT);

$cronFile = ExpandPath(Config()->get('service.cron.file'));
$cronJob  = ExpandPath(Config()->get('service.cron.job'));

$serviceSrc  = ExpandPath(Config()->get('service.systemd.file'));
$serviceDest = ExpandPath(Config()->get('service.systemd.service'));

$serviceName = pathinfo($serviceDest, PATHINFO_FILENAME);

$commands = [
    "systemctl stop $serviceName",
    "systemctl disable $serviceName",
    "rm $cronJob", 
    "rm $serviceDest",
    '',
];

if ($onlyCommands) {
    echo join(PHP_EOL, $commands);
    return ;
}

?>
Для удаления сервиса приложения выполните следующе команды от суперпользователя:

<?=join(PHP_EOL, $commands)?>

