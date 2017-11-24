<?php

namespace Jdi\Task;

use Data\BasicData;

class Extra extends BasicData implements \JsonSerializable
{
    public function exists()
    {
        return (bool) $this->data;
    }
    
    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }
    
    public function remove($key)
    {
        unset($this->data[$key]);
    }
    
    public function __toString()
    {
        return json_encode_array_pretty_print($this->data);
    }
    
    public function jsonSerialize()
    {
        return $this->data;
    }
}