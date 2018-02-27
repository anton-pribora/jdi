<?php

/* @var $this ApCode\Executor\RuntimeInterface */

$commands = $this->executeOnce(ExpandPath('@command/install/server.php'), $this->paramList());

if ($commands === true) {
    $commands = [];
}

$serviceFile = ExpandPath(Config()->get('service.inetd.local'));
$serviceDest = ExpandPath(Config()->get('service.inetd.dest'));
$serviceName = ExpandPath(Config()->get('service.inetd.name'));
$description = Config()->get('service.inetd.description');
$socket      = ExpandPath(Config()->get('service.socket'));

ob_start();
?>
#!/bin/sh
### BEGIN INIT INFO
# Provides:          <?php echo $serviceName?> 
# Required-Start:    $local_fs $network $named $time $syslog
# Required-Stop:     $local_fs $network $named $time $syslog
# Default-Start:     2 3 4 5
# Default-Stop:      0 1 6
# Description:       <?php echo $description?> 
### END INIT INFO

SCRIPT=<?=ExpandPath(Config()->get('service.server.dest'))?> 
RUNAS=root

PIDFILE=/var/run/<?php echo $serviceName?>.pid
LOGFILE=/var/log/<?php echo $serviceName?>.log

start() {
  if [ -f $PIDFILE ] && kill -0 $(cat $PIDFILE); then
    echo 'Service already running' >&2
    return 1
  fi
  echo 'Starting service…' >&2
  local CMD="$SCRIPT &> \"$LOGFILE\" & echo \$!"
  su -c "$CMD" $RUNAS > "$PIDFILE"
  echo 'Service started' >&2
}

stop() {
  if [ ! -f "$PIDFILE" ] || ! kill -0 $(cat "$PIDFILE"); then
    echo 'Service not running' >&2
    return 1
  fi
  echo 'Stopping service…' >&2
  kill -15 $(cat "$PIDFILE") && rm -f "$PIDFILE"
  rm -f "<?php echo $socket?>"
  echo 'Service stopped' >&2
}

status() {
  if [ -f $PIDFILE ] && kill -0 $(cat $PIDFILE); then
    echo 'Service running' >&2
    return 0
  fi

  echo 'Service not running' >&2
  return 1
}

case "$1" in
  start)
    start
    ;;
  stop)
    stop
    ;;
  restart)
    stop
    start
    ;;
  status)
    status
    ;;
  *)
    echo "Usage: $0 {start|stop|restart|status}"
esac

<?php 
$config = ob_get_clean();

if (!$this->param('onlyCommands')) {
    printf("Запись файла %s\n", $serviceFile);
}

file_put_contents($serviceFile, $config);
chmod($serviceFile, 0755);

$commands[] = "cp '$serviceFile' '$serviceDest'";
$commands[] = "update-rc.d $serviceName defaults";
$commands[] = "update-rc.d $serviceName enable";
$commands[] = "service $serviceName start";

return $commands;