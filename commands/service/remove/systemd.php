<?php

/* @var $this ApCode\Executor\RuntimeInterface */

$commands = $this->executeOnce(ExpandPath('@command/remove/server.php'), $this->paramList());

if ($commands === true) {
    $commands = [];
}

$serviceFile = ExpandPath(Config()->get('service.systemd.local'));
$serviceDest = ExpandPath(Config()->get('service.systemd.dest'));
$serviceName = pathinfo($serviceDest, PATHINFO_FILENAME);

$commands[] = "systemctl stop $serviceName";
$commands[] = "systemctl disable $serviceName";
$commands[] = "rm -f '$serviceDest'";

return $commands;