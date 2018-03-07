<?php

namespace Jdi\Task;

use Data\BlobFile;

class Run extends RunBase implements \JsonSerializable
{
    private $stdout;
    private $stderr;
    
    function stdout()
    {
        if (empty($this->stdout)) {
            $this->stdout = new BlobFile(parent::stdout());
            
            if (empty(parent::stdout())) {
                parent::setStdout($this->stdout->guid());
            }
        }
        
        return $this->stdout;
    }
    
    function stderr()
    {
        if (empty($this->stderr)) {
            $this->stderr = new BlobFile(parent::stderr());
            
            if (empty(parent::stderr())) {
                parent::setStderr($this->stderr->guid());
            }
        }
        
        return $this->stderr;
    }
    
    public function delete()
    {
        return RunRepository::delete($this);
    }

    public function jsonSerialize()
    {
        return [
            'id'       => $this->id(),
            'start'    => js_datetime($this->start()),
            'end'      => js_datetime($this->end()),
            'exitCode' => $this->exitCode(),
            'data'     => $this->extra(),
            'stdout'   => $this->stdout()->exists() ? $this->stdout()->path() : false,
            'stderr'   => $this->stderr()->exists() ? $this->stderr()->path() : false,
        ];
    }
}