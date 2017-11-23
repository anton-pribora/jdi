<?php

/* @var $this ApCode\Executor\RuntimeInterface */
$onlyCommands = $this->param(0) == '-c' || !posix_isatty(STDOUT);

$cronFile = ExpandPath(Config()->get('service.cron.file'));
$cronJob  = pathinfo($cronFile, PATHINFO_FILENAME);

$serviceFile = ExpandPath(Config()->get('service.systemd.file'));
$serviceName = pathinfo($serviceFile, PATHINFO_FILENAME);

$commands = [
    "systemctl stop $serviceName",
    "systemctl disable $serviceName",
    "rm /etc/cron.d/$cronJob", 
    "rm /etc/systemd/system/$serviceName.service",
    '',
];

if ($onlyCommands) {
    echo join(PHP_EOL, $commands);
    return ;
}

?>
Для удаления сервиса приложения выполните следующе команды от суперпользователя:

<?=join(PHP_EOL, $commands)?>

