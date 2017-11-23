<?php

spl_autoload_register(function($class) {
    if (substr_compare($class, 'ApCode\\', 0, 7) === 0) {
        include __DIR__ .'/classes/'. strtr($class, ['\\' => '/', 'ApCode\\' => '']) .'.php';
    }
});
