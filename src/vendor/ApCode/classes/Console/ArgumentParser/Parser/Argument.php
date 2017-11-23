<?php

namespace ApCode\Console\ArgumentParser\Parser;

class Argument
{
    private $definition;
    
    private $container;
    private $multiple;
    
    private $shortOpts;
    private $longOpts;
    
    public function __construct($definition)
    {
        $this->definition = $definition;
        $this->parseDefinition();
    }
    
    private function parseDefinition()
    {
        $tokens = preg_split('~[ ,]+~', $this->definition, -1, PREG_SPLIT_NO_EMPTY);
        
        $this->shortOpts = [];
        $this->longOpts  = [];
        
        foreach ($tokens as $token) {
            if (preg_match('~^--[\w-]+$~u', $token)) {
                $this->longOpts[$token] = ltrim($token, '-');
                continue;
            } elseif (preg_match('~^-\w$~u', $token)) {
                $this->shortOpts[] = ltrim($token, '-');
                continue;
            }
            
            switch ($token) {
                case '=':
                case 'container':
                    $this->container = true;
                    break;
                    
                case '+':
                case 'multiple':
                    $this->multiple = true;
                    break;
                    
                default:
                    throw new Exception(sprintf("Неверный токен `%s' для определения параметра парсера аргументов", $token));
                    break;
            }
        }
    }
    
    public function shortOpts()
    {
        return $this->shortOpts;
    }
    
    public function longOpts()
    {
        return $this->longOpts;
    }
    
    public function isContainer()
    {
        return (bool) $this->container;
    }
    
    public function isMultiple()
    {
        return (bool) $this->multiple;
    }
}