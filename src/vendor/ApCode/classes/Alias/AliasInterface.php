<?php

namespace ApCode\Alias;

interface AliasInterface
{
    function has($alias);
    function set($alias, $value);
    function append($alias, $value);
    function get($alias);
    function expand($alias);
}