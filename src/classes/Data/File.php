<?php

namespace Data;

class File
{
    private $path;
    
    public function __construct($path)
    {
        $this->path = $path;
    }
    
    public function path()
    {
        return $this->path;
    }
    
    public function folder()
    {
        return dirname($this->path);
    }
    
    public function touch()
    {
        if (!file_exists($this->folder())) {
            mkdir($this->folder(), 0755, true);
        }
        
        touch($this->path);
    }
    
    public function exists()
    {
        return file_exists($this->path) && is_file($this->path);
    }
    
    public function remove()
    {
        if ($this->exists()) {
            return unlink($this->path);
        }
        
        return null;
    }
}