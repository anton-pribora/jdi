<?php

use ApCode\Console\ArgumentParser\ArgumentParser;

/* @var $this ApCode\Executor\RuntimeInterface */

$parser = new ArgumentParser([
]);

$result = $parser->parse($this->paramList());

if ($result->hasUnknowns()) {
    printf("Неизвестные параметры вызова %s\n", join(', ', array_keys($result->unknowns())));
    return ;
}

$dsn = [
    $result->argument(0, Config()->get('db.default.sqlite.file')),
];

$config = [
    'dsn' => 'sqlite:'. join(';', $dsn),
];

return $config;