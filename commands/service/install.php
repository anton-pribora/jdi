<?php

/* @var $this ApCode\Executor\RuntimeInterface */

$action = ExpandPath("@command/install/all.php");
$onlyCommands = $this->param(0) == '-c' || !posix_isatty(STDOUT);

$this->execute($action, ['onlyCommands' => $onlyCommands]);

$cronFile = ExpandPath(Config()->get('service.cron.file'));
$cronJob  = ExpandPath(Config()->get('service.cron.job'));

$logrotateFile = ExpandPath(Config()->get('service.logrotate.file'));
$logrotateJob  = ExpandPath(Config()->get('service.logrotate.job'));

$serviceSrc  = ExpandPath(Config()->get('service.systemd.file'));
$serviceDest = ExpandPath(Config()->get('service.systemd.service'));

$serviceName = pathinfo($serviceDest, PATHINFO_FILENAME);

$commands = [
    "cp '$cronFile' '$cronJob'",
    "cp '$logrotateFile' '$logrotateJob'",
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

