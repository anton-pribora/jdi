<?php

namespace ApCode\Misc\FileMeta;

interface FileMetaInterface
{
    function metaFileName();
    
    function has($name);
    function get($name, $default = NULL);
    function set($name, $value);
}