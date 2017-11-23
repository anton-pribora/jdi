<?php

PathAlias()->set('@root'    , ROOT_DIR);
PathAlias()->set('@modules' , '@root/modules');
PathAlias()->set('@commands', '@root/commands');
PathAlias()->set('@sys'     , '@root/sys');
PathAlias()->set('@logs'    , '@sys/logs');
PathAlias()->set('@blob'    , Config()->get('blob.folder'));