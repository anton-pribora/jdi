<?php

use ApCode\Console\ArgumentParser\ArgumentParser;

/* @var $this ApCode\Executor\RuntimeInterface */

$parser = new ArgumentParser([
    'user'     => '-u, --user, =',
    'password' => '-p, --password, =',
    'host'     => '-h, --host, =',
    'port'     => '-P, --port, =',
]);

$result = $parser->parse($this->paramList());

if ($result->hasUnknowns()) {
    printf("Неизвестные параметры вызова %s\n", join(', ', array_keys($result->unknowns())));
    return ;
}

$user = $result->opt('user', Config()->get('db.defaults.postgres.user'));

$dsn = [
    'dbname='. $result->argument(0, Config()->get('db.default.postgres.database')),
];

if ($result->hasOpt('host')) {
    $dsn[] = 'host='. $result->opt('host');
}

if ($result->hasOpt('port')) {
    $dsn[] = 'port='. $result->opt('port');
}

$config = [
    'db' => [
        'dsn'      => 'pgsql:'. join(';', $dsn),
        'login'    => $result->opt('user', Config()->get('db.default.postgres.login')),
        'password' => $result->opt('password', Config()->get('db.default.postgres.password')),
    ],
    'limits' => [
        'run_at_once' => 5,
    ],
];

return $config;