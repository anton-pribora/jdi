<?php

/* @var $this ApCode\Executor\RuntimeInterface */
$this->execute(ExpandPath('@command/install/systemd.php'), $this->paramList());
$this->execute(ExpandPath('@command/install/cron.php'), $this->paramList());