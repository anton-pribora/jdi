<?php

namespace Jdi;

use Data\BlobFile;

class Task extends TaskBase
{
    const STATUS_ADDING  = 'adding';
    const STATUS_READY   = 'ready';
    const STATUS_RUNNING = 'running';
    const STATUS_DONE    = 'done';
    const STATUS_FAIL    = 'fail';
    
    private $stdin = null;
    
    function stdin()
    {
        if (empty($this->stdin)) {
            $this->stdin = new BlobFile(parent::stdin());
            
            if (empty(parent::stdin())) {
                parent::setStdin($this->stdin->guid());
            }
        }
        
        return $this->stdin;
    }
    
    public function setStatusAndSave($value)
    {
        $this->setStatus($value);
        $this->save();
    }
    
    public function makeNewRun()
    {
        $run = new Task\Run();
        $run->setTaskId($this->id());
        
        return $run;
    }
    
    /**
     * @return \Jdi\Task\Run[]
     */
    public function runs()
    {
        return Task\RunRepository::findMany(['taskId' => $this->id()]);
    }
    
    public function delete()
    {
        TaskRepository::delete($this);
    }
}