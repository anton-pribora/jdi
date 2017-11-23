<?php

/* @var $this ApCode\Executor\RuntimeInterface */

$socket = ExpandPath(Config()->get('service.socket'));
$next   = ExpandPath(Config()->get('service.next'));

$socket = escapeshellarg($socket);

$code = <<<SH
#!/bin/bash

trap remove_fifo 2 3 6

SOCKET={$socket}

make_fifo() {
    [ -f "\$SOCKET" ] || mkfifo "\$SOCKET"
}

remove_fifo() {
    unlink "\$SOCKET"
    exit 1
}

make_fifo

while :; do
    while read -r line; do
        case "\$line" in
            exit) break 2;;
            next) $next;;
            *) echo Неизвестная команда \$line;;
        esac
    done < "\$SOCKET"
done

remove_fifo
SH
;

$file = ExpandPath(Config()->get('service.server'));

if (!$this->param('onlyCommands')) {
    printf("Запись файла %s\n", $file);
}

file_put_contents($file, $code);
chmod($file, 0755);