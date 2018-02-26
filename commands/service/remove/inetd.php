<?php

/* @var $this ApCode\Executor\RuntimeInterface */

$commands = $this->executeOnce(ExpandPath('@command/remove/server.php'), $this->paramList());

if ($commands === true) {
    $commands = [];
}

$serviceDest = ExpandPath(Config()->get('service.inetd.dest'));
$serviceName = ExpandPath(Config()->get('service.inetd.name'));

$commands[] = "service $serviceName stop";
$commands[] = "update-rc.d $serviceName disable";
$commands[] = "update-rc.d -f $serviceName remove";
$commands[] = "rm -f '$serviceDest'";

return $commands;