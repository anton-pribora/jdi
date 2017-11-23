<?php
use Jdi\TaskRepository;

/* @var $this ApCode\Executor\RuntimeInterface */

$limits = Config()->get('limit.expire');

foreach ($limits as $status => $expire) {
    $where = [
        'status'   => $status, 
        'run_at<=' => mysql_datetime($expire)
    ];
    
    if ($status == 'any') {
        unset($where['status']);
    }
    
    foreach (TaskRepository::findMany($where) as $task) {
        $message = sprintf('Task #%s: Удаление устаревшего задания', $task->id());
        $task->delete();
        
        printf("%s\n", $message);
        Logger()->log('common', $message);
    }
}