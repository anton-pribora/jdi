<?php

/* @var $this ApCode\Executor\RuntimeInterface */

$type = (string) $this->param(0);
$file = ExpandPath('@root/configs/25-database.local.php');

$writeSettings = function ($settings) use ($file) {
    ob_start();
    echo "<?php\n\n";
?>
Config()->setup([
    'db' => [
<?php foreach ($settings as $key => $value) {?>
        <?=var_export($key, true)?> => <?=var_export($value, true)?>,
<?php }?>
    ],
]);
<?php
    file_put_contents($file, ob_get_clean());
};

$action = ExpandPath("@command/setup/$type.php");

if ($this->canExecute($action)) {
    printf("Установка конфига $type\n");
    
    $config = $this->execute($action, array_slice($this->paramList(), 1));
    
    if ($config) {
        $writeSettings($config);
    }
} else {
    printf("Нужно указать тип базы данных. Используйте `%s' для справки.\n", ExpandPath('@app help @command'));
}
