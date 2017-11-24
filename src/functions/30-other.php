<?php

function mysql_datetime($time = NULL) {
    if (is_null($time)) {
        $time = time();
    }
    
    if (!is_numeric($time)) {
        $time = strtotime($time);
    }
    
    return date('Y-m-d H:i:s', $time);
}

function jdi_next() {
    $socket = ExpandPath(Config()->get('service.socket'));
    
    if (file_exists($socket) && is_writable($socket)) {
        file_put_contents($socket, "next\n");
    }
}

function js_datetime($time) {
    if (!is_numeric($time)) {
        $time = strtotime($time);
    }
    
    return date(DateTime::RFC2822, $time);
}