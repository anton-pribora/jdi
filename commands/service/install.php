<?php

use ApCode\Console\ArgumentParser\ArgumentParser;

/* @var $this ApCode\Executor\RuntimeInterface */

$params = [
    'onlyCommands' => '-c',
];

$options = (new ArgumentParser($params))->parse($this->paramList());

if ($options->hasUnknowns()) {
    printf("Неверные параметры %s. Используйте %s для справки.\n", 
        join(', ', array_keys($options->unknowns())),
        ExpandPath('@app help @command')
    );
    exit(-2);
}

$onlyCommands = $options->hasOpt('onlyCommands') || !posix_isatty(STDOUT);

$list = $options->arguments();

if (empty($list)) {
    $list = ['cron', 'logrotate', exec('which systemd') ? 'systemd' : 'inetd'];
}

$commands = [];

foreach ($list as $service) {
    $action = ExpandPath("@command/install/$service.php");
    
    if (!$this->canExecute($action, $this->paramList())) {
        printf("Неверный параметр %s. Используйте %s для справки.\n", 
            $service,
            ExpandPath('@app help @command')
        );
        exit(-2);
    }
    
    $result = $this->executeOnce($action, [
        'onlyCommands' => $onlyCommands,
    ] + $this->paramList());
    
    if ($result === true) {
        continue;
    }
    
    $commands = array_merge($commands, $result);
}

if ($onlyCommands) {
    echo join(PHP_EOL, $commands), PHP_EOL;
    return ;
}

?>

Для завершения установки выполните под логином суперпользователя следующие команды:

<?=join(PHP_EOL, $commands)?>


Для проверки статуса сервиса используйте:

service <?=Config()->get('service.name')?> status

