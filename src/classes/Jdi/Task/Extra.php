<?php

namespace Jdi\Task;

use Data\BasicData;

class Extra extends BasicData
{
    public function exists()
    {
        return (bool) $this->data;
    }
    
    public function __toString()
    {
        return json_encode_array_pretty_print($this->data);
    }
}