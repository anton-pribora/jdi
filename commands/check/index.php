<?php
use Jdi\TaskRepository;
use Jdi\Task;

/* @var $this ApCode\Executor\RuntimeInterface */

$checkAdding = function (Task $task) {
    $pid     = $task->extra()->get('pid');
    $cmdline = "/proc/$pid/cmdline";
    
    if (!file_exists($cmdline)) {
        return false;
    }
    
    $text = file_get_contents($cmdline);
    $jdi  = basename(ExpandPath('@exec'));
    
    if (!preg_match("~{$jdi}[\s\S]*add~", $text)) {
        return false;
    }
    
    return true;
};

$checkRunning = function (Task $task) {
    $pid     = $task->extra()->get('pid');
    $cmdline = "/proc/$pid/cmdline";
    
    if (!file_exists($cmdline)) {
        return false;
    }
    
    $text = file_get_contents($cmdline);
    $jdi  = basename(ExpandPath('@exec'));
    
    if (!preg_match("~{$jdi}~", $text)) {
        return false;
    }
    
    foreach ($task->runs() as $run) {
        $pid = $run->extra()->get('pid');
        
        if (empty($pid)) {
            continue;
        }
        
        $env = "/proc/$pid/environ";
        
        if ($pid && file_exists($env)) {
            $content = file_get_contents($env);
            
            if (preg_match("~\bJDI_RUN={$run->id()}\b~", $content)) {
                return true;
            }
        }
    }
    
    return false;
};

$list = TaskRepository::findMany(['status'=> [Task::STATUS_RUNNING, Task::STATUS_ADDING]]);

foreach ($list as $task) {
    if ($task->isStatus(Task::STATUS_ADDING)) {
        $result = $checkAdding($task);
    } elseif ($task->isStatus(Task::STATUS_RUNNING)) {
        $result = $checkRunning($task);
    } else {
        throw new Exception('Неизвестный статус задания '. $task->status());
    }
    
    if ($result) {
        $message = sprintf("Task #%s: Активно", $task->id());
        printf("%s\n", $message);
    } else {
        $message = sprintf("Task #%s: Выставлен статус 'прервано'", $task->id());
        printf("%s\n", $message);
        
        $task->extra()->remove('pid');
        $task->setStatusAndSave(Task::STATUS_INTERRUPTED);
        
        foreach ($task->runs() as $run) {
            if ($run->extra()->get('pid')) {
                $run->extra()->remove('pid');
                $run->save();
            }
        }
        
        Logger()->log('common', $message);
    }
}