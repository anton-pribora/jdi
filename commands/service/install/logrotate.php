<?php

/* @var $this ApCode\Executor\RuntimeInterface */

$file = ExpandPath(Config()->get('service.logrotate.local'));
$job  = ExpandPath(Config()->get('service.logrotate.dest'));

ob_start();
?>
<?=ExpandPath('@logs/')?>*.log {
        monthly
        rotate 4
        compress
        create
}
<?php 
$config = ob_get_clean();

if (!$this->param('onlyCommands')) {
    printf("Запись файла %s\n", $file);
}

file_put_contents($file, $config);

return [
    "cp '$file' '$job'",
];