<?php

ini_set('display_errors', true);

setlocale(LC_ALL, "ru_RU.UTF-8");

chdir(__DIR__);

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : null;
$data   = isset($_REQUEST['data'  ]) ? $_REQUEST['data'  ] : null;

$jdi_exec = dirname(__DIR__) . '/jdi.php';
$big_deal = './big_deal.php';

$returnAsJson = function ($text) {
    header('Content-type: application/json; charset=utf8');
    die($text);
};

$returnJson = function ($data) use ($returnAsJson) {
    $returnAsJson(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
};

$returnJsonError = function ($text, $code = '500') use ($returnJson) {
    http_response_code($code);
    $returnJson([
        'error' => $text,
    ]);
};

session_start();

if (!isset($_SESSION['tasks'])) {
    $_SESSION['tasks'] = [];
}

switch ($action) {
    case 'add':
        $taskId = exec("$jdi_exec add $big_deal", $data);
        $_SESSION['tasks'][] = $taskId;
        $returnJson(true);
        break;
        
    case 'list':
        $list = join(' ', $_SESSION['tasks']);
        exec("$jdi_exec task --json -- $list", $data);
        $returnAsJson(join($data));
        break;
        
    case 'clear':
        $list = join(' ', $_SESSION['tasks']);
        exec("$jdi_exec task --remove -- $list", $data);
        $_SESSION['tasks'] = [];
        $returnJson(true);
        break;
        
    default:
        $returnJsonError(sprintf('Действие %s не найдено в нашем API', $action));
        break;
}