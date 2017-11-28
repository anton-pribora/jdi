<?php

namespace Jdi\Task;

/**
 * 
 */
abstract class RunBase extends \ApCode\Billet\AbstractBillet
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

    public function taskId()
    {
        return $this->data['taskId'] ?? null;
    }

    public function setTaskId($value)
    {
        $this->data['taskId'] = $value;
        return $this;
    }

    public function start()
    {
        return $this->data['start'] ?? null;
    }

    public function setStart($value)
    {
        $this->data['start'] = $value;
        return $this;
    }

    public function end()
    {
        return $this->data['end'] ?? null;
    }

    public function setEnd($value)
    {
        $this->data['end'] = $value;
        return $this;
    }

    protected function stdout()
    {
        return $this->data['stdout'] ?? null;
    }

    protected function setStdout($value)
    {
        $this->data['stdout'] = $value;
        return $this;
    }

    protected function stderr()
    {
        return $this->data['stderr'] ?? null;
    }

    protected function setStderr($value)
    {
        $this->data['stderr'] = $value;
        return $this;
    }

    public function exitCode()
    {
        return $this->data['exitCode'] ?? null;
    }

    public function setExitCode($value)
    {
        $this->data['exitCode'] = $value;
        return $this;
    }

    /**
     * @return \Jdi\Task\Extra
     */
    public function extra()
    {
        return new Extra($this->data['extra']);
    }
}