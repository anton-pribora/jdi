<?php

/* @var $this ApCode\Executor\RuntimeInterface */

$job = ExpandPath(Config()->get('service.logrotate.dest'));

return [
    "rm -f '$job'",
];