<?php

/**
 * @return \ApCode\Alias\AliasInterface
 */
function PathAlias() {
    static $alias;

    if (empty($alias)) {
        $alias = new ApCode\Alias\Alias();
    }

    return $alias;
}

function ExpandPath($alias) {
    return PathAlias()->expand($alias);
}

/**
 * @param string $name
 * @return \ApCode\Executor\ExecutorInterface
 */
function Module($name) : \ApCode\Executor\ExecutorInterface {
    static $modules = [];

    if (!isset($modules[$name])) {
        $modules[$name] = require ExpandPath("@modules/$name/module.php");
    }

    return $modules[$name];
}

function Run($command, ...$argsAndLastParams) {
    list($module, $action) = explode('/', 2) + [null, null];
    return Module($module)->execute($action, ...$argsAndLastParams);
}

function RunOnce($command, ...$argsAndLastParams) {
    list($module, $action) = explode('/', $command, 2) + [null, null];
    return Module($module)->executeOnce($action, ...$argsAndLastParams);
}

function RequireLib($lib) {
    RunOnce("misc/lib/$lib/enable.php");
}

/**
 * @return \ApCode\Log\LoggerInterface
 */
function Logger() {
    static $logger;

    if (empty($logger)) {
        $logger = new ApCode\Log\Logger(ExpandPath('@logs/'), 'jdi_');

        $logger->format()->setFormat(
            "{datetime} {ip} {userId} [{userName}] «{message}»  Вр.раб: {workTime}  Запр: {queries}  Вр.запр: {queriesTime}  Пам: {memory}  Файлы: {files}  req: {requestId}  ses: {sessionId}  {method} {uri}\n"
        );
        
        $logger->format()->setStaticVariables([
            'datetime'    => function() {return date('[d-M-Y H:i:s T]');},
            'ip'          => '-',
            'requestId'   => uniqid('req'),
            'sessionId'   => '-',
            'userId'      => '-',
            'userName'    => '-',
            'workTime'    => function() {return sprintf('%.3f', Timer('system')->elapsed());},
            'queries'     => function() {return Db()->totalQueries();},
            'queriesTime' => function() {return sprintf('%.3f', Db()->totalTime());},
            'memory'      => function() {return sprintf('%.1fМб', memory_get_peak_usage(true) / 1024 / 1024);},
            'files'       => function() {return count(get_included_files());},
            'method'      => 'console',
            'uri'         => $GLOBALS['argv'][0],
            'message'     => '-',
        ]);
    }

    return $logger;
}

/**
 * @return \ApCode\Config\Config
 */
function Config() {
    static $config;

    if (empty($config)) {
        $config = new ApCode\Config\Config();
    }

    return $config;
}

/**
 * @return ApCode\Database\Pdo\PdoDriver
 */
function Db() {
    static $db;

    if (empty($db)) {
        $db = new ApCode\Database\Pdo\PdoDriver(
            ExpandPath(Config()->get('db.dsn')),
            Config()->get('db.login'),
            Config()->get('db.password'),
            Config()->get('db.options', [])
        );
    }

    return $db;
}

/**
 * @param string $name
 * @return \ApCode\Misc\Timer
 */
function Timer($name = 'default') {
    static $timers = [];

    if (empty($timers[$name])) {
        $timers[$name] = new ApCode\Misc\Timer();
    }

    return $timers[$name];
}

/**
 * @param string $path
 * @return \ApCode\Misc\FileMeta\FileMeta
 */
function Meta($path) {
    return ApCode\Misc\FileMeta\MetaManager::fileMeta($path);
}
