<?php

$uid = posix_getuid();

if ($uid == 0) {
    $user  = Config()->get('owner.user', posix_getuid());
    $group = Config()->get('owner.group', posix_getgid());
    
    if (!is_numeric($user)) {
        $info = posix_getpwnam($user);
        
        if ($info) {
            $user = $info['uid'];
        } else {
            printf("Не удалось получить информацию о пользователе `%s'\n", $user);
            $user = false;
        }
    }
    
    if (!is_numeric($group)) {
        $info = posix_getgrnam($group);
        
        if ($info) {
            $group = $info['gid'];
        } else {
            printf("Не удалось получить информацию о группе `%s'\n", $group);
            $group = false;
        }
    }
    
    if ($group !== false) {
        posix_setgid($group);
    }
    
    if ($user !== false) {
        posix_setuid($user);
    }
}