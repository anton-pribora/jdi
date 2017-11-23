<?php

/* @var $this ApCode\Executor\RuntimeInterface */

$config = Config()->get('service.cron.config');
$file   = ExpandPath(Config()->get('service.cron.file'));

$content = join(PHP_EOL, $config) . PHP_EOL;
$content = ExpandPath($content);

if (!$this->param('onlyCommands')) {
    printf("Запись файла %s\n", $file);
}

file_put_contents($file, $content);
