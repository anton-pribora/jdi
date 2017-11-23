<?php

namespace ApCode\Console\ArgumentParser\Parser;

class Result
{
    private $opts = [];
    private $unknowns = [];
    private $arguments = [];
    
    public function opt($id, $default = NULL)
    {
        return $this->opts[$id] ?? $default;
    }
    
    public function setOpt($id, $value)
    {
        $this->opts[$id] = $value;
    }
    
    public function addOpt($id, $value)
    {
        if (!isset($this->opts[$id])) {
            $this->opts[$id] = [];
        }
        
        $this->opts[$id][] = $value;
    }
    
    public function opts()
    {
        return $this->opts;
    }
    
    public function hasOpts()
    {
        return count($this->opts) > 0;
    }
    
    public function hasOpt($id)
    {
        return array_key_exists($id, $this->opts);
    }
    
    public function addUnknown($id, $value)
    {
        if (!isset($this->unknowns[$id])) {
            $this->unknowns[$id] = [];
        }
        
        $this->unknowns[$id][] = $value;
    }
    
    public function unknowns()
    {
        return $this->unknowns;
    }
    
    public function hasUnknowns()
    {
        return count($this->unknowns) > 0;
    }
    
    public function argument($index, $default = NULL)
    {
        return $this->arguments[$index] ?? $default;
    }
    
    public function addArgument($argument)
    {
        $this->arguments[] = $argument;
    }
    
    public function arguments()
    {
        return $this->arguments;
    }
    
    public function hasArgument()
    {
        return count($this->arguments) > 0;
    }
}