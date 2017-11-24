<?php

Config()->setup([
    'owner' => [
        'user'  => 'root',
        'group' => 'root',
    ],
    'db' => [
        'dsn' => 'sqlite:@sys/database.sq3',
        
        'default'   => [
            'sqlite' => [
                'file' => '@sys/database.sq3',
            ],
            'mysql' => [
                'database' => 'just_do_it',
                'login'    => 'test',
                'password' => 'test',
            ],
        ],
    ],
    
    'service' => [
        'name'   => 'jdi',  // Имя сервиса в системе
        
        'server' => '@sys/server.sh',
        'socket' => '@sys/server.sock',
        'next'   => '@exec next &',
        
        'systemd' => [
            'description' => 'Just Do it! Service',
            'file'        => '@sys/systemd.service',
            'service'     => '/etc/systemd/system/@service.service',
        ],
        
        'cron' => [
            'file'   => '@sys/cron',
            'job'    => '/etc/cron.d/@service',
            'config' => [
                '# Запуск следующего задания раз в минуту',
                '* *  * * *  root @exec next',
                '',
                '# Очистка очереди раз в день',
                '13 5 * * *  root @exec clean',
            ],
        ],
    ],
    
    'limit' => [
        'run_at_once' => 5,
        'fails'       => 3,
        'expire'      => [
            \Jdi\Task::STATUS_DONE    => '-1 day',
            \Jdi\Task::STATUS_ADDING  => '-7 days',
            \Jdi\Task::STATUS_READY   => '-7 days',
            \Jdi\Task::STATUS_RUNNING => '-7 days',
            \Jdi\Task::STATUS_FAIL    => '-7 days',
            
            'any' => '-1 month',
        ],
    ],
    
    'blob' => [
        'folder' => '@sys/blob',
    ],
]);