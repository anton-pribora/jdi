<?php

/* @var $this ApCode\Executor\RuntimeInterface */

$job = ExpandPath(Config()->get('service.cron.dest'));

return [
    "rm -f '$job'",
];