<?php

/* @var $this ApCode\Executor\RuntimeInterface */

$action = ExpandPath("@command/install/all.php");
$onlyCommands = $this->param(0) == '-c' || !posix_isatty(STDOUT);

$this->execute($action, ['onlyCommands' => $onlyCommands]);

$cronFile = ExpandPath(Config()->get('service.cron.file'));
$cronJob  = pathinfo($cronFile, PATHINFO_FILENAME);

$serviceFile = ExpandPath(Config()->get('service.systemd.file'));
$serviceName = pathinfo($serviceFile, PATHINFO_FILENAME);

$commands = [
    "cp $cronFile /etc/cron.d/$cronJob", 
    "cp $serviceFile /etc/systemd/system/$serviceName.service",
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

