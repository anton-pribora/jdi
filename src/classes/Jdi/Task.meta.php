<?php
// 'db.table'   - Обязательное поле, назание таблицы
// 'db.idfield' - Обязательное поле, первичный ключ таблицы
// 'db.map'     - Карта сопоставления свойств объекта полям таблицы
//   'field'    - Обязательное поле, соответсвует столбцу в базе данных
//   'title'    - Необязательное поле, содержит "человеческое" название поля
//   'encode'   - Необязательное поле, функция для кодирования значения поля при сохранении в базу
//   'decode'   - Необязательное поле, функция для декодирования значения поля при загрузке из базы 
return [
    'db.table'   => 'jdi_task',
    'db.idfield' => 'id',
    'db.map'     => [
        'id'     => [
            'field'  => 'id',
            'title'  => NULL,
            'encode' => NULL,
            'decode' => NULL,
        ],
        'command' => [
            'field'  => 'command',
            'title'  => NULL,
            'encode' => NULL,
            'decode' => NULL,
        ],
        'fails' => [
            'field'  => 'fails',
            'title'  => NULL,
            'encode' => NULL,
            'decode' => NULL,
        ],
        'stdin'   => [
            'field'  => 'stdin',
            'title'  => NULL,
            'encode' => NULL,
            'decode' => NULL,
        ],
        'date'   => [
            'field'  => 'date',
            'title'  => NULL,
            'encode' => NULL,
            'decode' => NULL,
        ],
        'runAt'   => [
            'field'  => 'run_at',
            'title'  => NULL,
            'encode' => NULL,
            'decode' => NULL,
        ],
        'status' => [
            'field'  => 'status',
            'title'  => NULL,
            'encode' => NULL,
            'decode' => NULL,
        ],
        'extra'  => [
            'field'  => 'extra',
            'title'  => NULL,
            'encode' => 'json_encode_array',
            'decode' => 'json_decode_array',
        ],
    ],
];