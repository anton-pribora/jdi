<?php

$driver = Db()->pdo()->getAttribute(PDO::ATTR_DRIVER_NAME);

$createTable = 'CREATE TABLE IF NOT EXISTS';

$varchar  = 'VARCHAR(255)';
$text     = 'TEXT';
$int      = 'INT UNSIGNED';
$datetime = 'DATETIME';
$engine   = '';

switch ($driver) {
    case 'sqlite':
        $primaryKey = 'INTEGER PRIMARY KEY AUTOINCREMENT';
        $int        = 'INTEGER';
        break;
        
    case 'pgsql':
        $primaryKey = 'SERIAL PRIMARY KEY';
        $int        = 'INT';
        $datetime   = 'TIMESTAMP WITHOUT TIME ZONE';
        break;
        
    case 'mysql':
        $primaryKey = 'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY';
        $engine     = 'ENGINE MyISAM DEFAULT CHARSET=utf8';
        break;
        
    default:
        printf("Неизвестный драйвер %s\n", $driver);
        return;
}

$sql = <<<SQL
$createTable "jdi_task" (
    "id" $primaryKey,
    "command" $text,
    "stdin" $varchar,
    "date" $datetime,
    "fails" $int,
    "run_at" $datetime,
    "status" $varchar,
    "extra" $text
) $engine;

$createTable "jdi_task_run" (
    "id" $primaryKey,
    "task_id" $int,
    "start" $datetime,
    "end" $datetime,
    "stdout" $varchar,
    "stderr" $varchar,
    "exit_code" $varchar,
    "extra" $text
) $engine;

$createTable "jdi_task_owner" (
    "task_id" $int,
    "owner" $varchar
) $engine;
SQL
;

foreach (explode(';', $sql) as $query) {
    $query = trim($query);
    
    if ($query) {
        printf("Выполняем запрос\n%s\n\n", $query);
        Db()->query($query);
    }
}

