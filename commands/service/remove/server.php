<?php

/* @var $this ApCode\Executor\RuntimeInterface */

$serverDest = ExpandPath(Config()->get('service.server.dest'));

return [
    "rm -f '$serverDest'",
];