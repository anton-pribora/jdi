<?php

namespace Data;

class BasicData
{
    protected $data;
    
    public function __construct(&$data)
    {
        $this->data = &$data;
    }
}