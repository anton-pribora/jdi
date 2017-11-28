<?php

/* @var $this ApCode\Executor\RuntimeInterface */

$this->execute(ExpandPath("@command/remove.php"), $this->paramList());
$this->execute(ExpandPath("@command/install.php"), $this->paramList());