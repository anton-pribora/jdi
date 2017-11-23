<?php

namespace ApCode\Log;

interface LoggerInterface
{
    /**
     * @return \ApCode\Log\Format 
     */
    function format();
    function log($log, $message);
    function error($message = NULL);
    function info($message = NULL);
}