<?php

/* @var $this ApCode\Executor\RuntimeInterface */

$this->executeOnce(ExpandPath('@command/install/server.php'), $this->paramList());

$serviceFile = ExpandPath(Config()->get('service.systemd.file'));
$servceName = pathinfo($serviceFile, PATHINFO_FILENAME);

ob_start();
?>
[Unit]
Description=<?=Config()->get('servicce.systemd.description')?> 

[Service]
Type=simple
ExecStart=<?=ExpandPath(Config()->get('service.server'))?> 
KillSignal=6

[Install]
WantedBy=multi-user.target
<?php 
$config = ob_get_clean();

if (!$this->param('onlyCommands')) {
    printf("Запись файла %s\n", $serviceFile);
}

file_put_contents($serviceFile, $config);
