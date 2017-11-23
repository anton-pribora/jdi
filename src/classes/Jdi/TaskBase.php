<?php

namespace Jdi;

abstract class TaskBase extends \ApCode\Billet\AbstractBillet
{
    protected $data = [
        'extra' => []
    ];
    
    public function id()
    {
        return $this->data['id'] ?? null;
    }

    public function setId($value)
    {
        $this->data['id'] = $value;
        return $this;
    }

    public function command()
    {
        return $this->data['command'] ?? null;
    }

    public function setCommand($value)
    {
        $this->data['command'] = $value;
        return $this;
    }
    
    public function fails()
    {
        return $this->data['fails'] ?? null;
    }

    public function setFails($value)
    {
        $this->data['fails'] = $value;
        return $this;
    }

    protected function stdin()
    {
        return $this->data['stdin'] ?? null;
    }

    protected function setStdin($value)
    {
        $this->data['stdin'] = $value;
        return $this;
    }
    
    public function setDate($value)
    {
        $this->data['date'] = $value;
    }
    
    public function date()
    {
        return $this->data['date'] ?? null;
    }

    public function runAt()
    {
        return $this->data['runAt'] ?? null;
    }

    public function setRunAt($value)
    {
        $this->data['runAt'] = $value;
        return $this;
    }

    public function status()
    {
        return $this->data['status'] ?? null;
    }

    public function setStatus($value)
    {
        $this->data['status'] = $value;
        return $this;
    }
    
    public function isStatus($value)
    {
        return $this->status() == $value;
    }

    /**
     * @return \Jdi\Task\Extra
     */
    public function extra()
    {
        return new Task\Extra($this->data['extra']);
    }
}