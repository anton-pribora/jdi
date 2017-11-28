<?php
// 'db.table'   - Обязательное поле, назание таблицы
// 'db.idfield' - Обязательное поле, первичный ключ таблицы
// 'db.map'     - Карта сопоставления свойств объекта полям таблицы
//   'field'    - Обязательное поле, соответсвует столбцу в базе данных
//   'title'    - Необязательное поле, содержит "человеческое" название поля
//   'encode'   - Необязательное поле, функция для кодирования значения поля при сохранении в базу
//   'decode'   - Необязательное поле, функция для декодирования значения поля при загрузке из базы 
return [
    'db.table'   => 'jdi_task_run',
    'db.idfield' => 'id',
    'db.map'     => [
        'id'       => [
            'field'  => 'id',
            'title'  => NULL,
            'encode' => NULL,
            'decode' => NULL,
        ],
        'taskId'   => [
            'field'  => 'task_id',
            'title'  => NULL,
            'encode' => NULL,
            'decode' => NULL,
        ],
        'start'    => [
            'field'  => 'start',
            'title'  => NULL,
            'encode' => NULL,
            'decode' => NULL,
        ],
        'end'      => [
            'field'  => 'end',
            'title'  => NULL,
            'encode' => NULL,
            'decode' => NULL,
        ],
        'stdout'   => [
            'field'  => 'stdout',
            'title'  => NULL,
            'encode' => NULL,
            'decode' => NULL,
        ],
        'stderr'   => [
            'field'  => 'stderr',
            'title'  => NULL,
            'encode' => NULL,
            'decode' => NULL,
        ],
        'exitCode' => [
            'field'  => 'exit_code',
            'title'  => NULL,
            'encode' => NULL,
            'decode' => NULL,
        ],
        'extra'    => [
            'field'  => 'extra',
            'title'  => NULL,
            'encode' => 'json_encode_array',
            'decode' => 'json_decode_array',
        ],
    ],
];