<?php

// Функции
glob_include(ROOT_DIR .'/src/functions/*.php');

// Подключение конфигов
glob_include(ROOT_DIR .'/configs/*.php');

function app() {
    return ExpandPath('@app');
}

Timer('system')->start();