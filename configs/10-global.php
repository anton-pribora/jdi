<?php

Config()->setup([
    'owner' => [
        'user'  => 'www-data',
        'group' => 'www-data',
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
        'socket' => '@sys/server.sock',
        'next'   => '@exec next &',
        
        'server' => [
            'local' => '@sys/server.sh',
            'dest'  => '/usr/local/bin/@service-server.sh',
        ],
        
        'systemd' => [
            'description' => 'Just Do it! Service',
            'local'       => '@sys/systemd.service',
            'dest'        => '/etc/systemd/system/@service.service',
        ],
        
        'cron' => [
            'local'  => '@sys/cron',
            'dest'   => '/etc/cron.d/@service',
            'config' => [
                '# Запуск следующего задания раз в минуту',
                '* *  * * *  root @exec next',
                '',
                '# Очистка очереди раз в день',
                '13 5 * * *  root @exec clean',
            ],
        ],
        
        'logrotate' => [
            'local' => '@sys/logrotate.conf',
            'dest'  => '/etc/logrotate.d/@service'
        ]
    ],
    
    'limit' => [
        'run_at_once' => 1,  // Для sqlite это оптимальное значение!
        'fails'       => 3,
        'expire'      => [
            \Jdi\Task::STATUS_DONE    => '-1 day',
            \Jdi\Task::STATUS_READY   => '-7 days',
            \Jdi\Task::STATUS_FAIL    => '-7 days',
            
            'any' => '-1 month',
        ],
    ],
    
    'blob' => [
        'folder' => '@sys/blob',
    ],
]);