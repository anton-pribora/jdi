<?php

function __dir($path = '') {
    $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
    $folder = dirname($backtrace[0]['file']) .'/';

    return $folder . $path;
}

function json_encode_array($data) {
    return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR);
}

function json_encode_array_pretty_print($data) {
    return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_PRETTY_PRINT);
}

function json_decode_array($data) {
    return json_decode($data, true);
}