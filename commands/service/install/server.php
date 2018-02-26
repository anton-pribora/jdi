<?php

/* @var $this ApCode\Executor\RuntimeInterface */

$serverSrc  = ExpandPath(Config()->get('service.server.local'));
$serverDest = ExpandPath(Config()->get('service.server.dest'));

$socket = escapeshellarg(Config()->get('service.socket'));
$next   = Config()->get('service.next');
$limit  = Config()->get('limit.run_at_once');

$preStart = join(PHP_EOL, array_fill(0, $limit, 'run_next'));

$code = <<<SH
#!/bin/bash

trap remove_fifo 2 3 6

SOCKET={$socket}

make_fifo() {
    [ -p "\$SOCKET" ] || mkfifo -m 0666 "\$SOCKET"
}

remove_fifo() {
    rm -f "\$SOCKET"
    exit 1
}

run_next() {
    sleep 0.5s
    @exec check
    $next
}

make_fifo

@exec clean
$preStart

while :; do
    while read -r line; do
        case "\$line" in
            next) run_next;;
            *) echo Неизвестная команда \$line;;
        esac
    done < "\$SOCKET"
done

remove_fifo
SH
;

$serverSrc = ExpandPath(Config()->get('service.server.local'));

if (!$this->param('onlyCommands')) {
    printf("Запись файла %s\n", $serverSrc);
}

file_put_contents($serverSrc, ExpandPath($code));
chmod($serverSrc, 0755);

return [
    "cp '$serverSrc' '$serverDest'",
];