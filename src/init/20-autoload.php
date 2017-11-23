<?php

glob_include(ROOT_DIR .'/src/vendor/*/bootstrap.php');

spl_autoload_register(function ($class) {
    $file = ROOT_DIR .'/src/classes/'. strtr($class, '\\', '/') .'.php';
    
    if (file_exists($file)) {
        require $file;
    }
});