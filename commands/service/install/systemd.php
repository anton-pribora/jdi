<?php

/* @var $this ApCode\Executor\RuntimeInterface */

$commands = $this->executeOnce(ExpandPath('@command/install/server.php'), $this->paramList()) ?: [];

if ($commands === true) {
    $commands = [];
}

$serviceFile = ExpandPath(Config()->get('service.systemd.local'));
$serviceDest = ExpandPath(Config()->get('service.systemd.dest'));
$serviceName = pathinfo($serviceFile, PATHINFO_FILENAME);

ob_start();
?>
[Unit]
Description=<?=Config()->get('service.systemd.description')?> 

[Service]
Type=simple
ExecStart=<?=ExpandPath(Config()->get('service.server.dest'))?> 
KillSignal=6

[Install]
WantedBy=multi-user.target
<?php 
$config = ob_get_clean();

if (!$this->param('onlyCommands')) {
    printf("Запись файла %s\n", $serviceFile);
}

file_put_contents($serviceFile, $config);

$commands[] = "cp '$serviceFile' '$serviceDest'";
$commands[] = "systemctl enable $serviceName";
$commands[] = "systemctl start $serviceName";

return $commands;