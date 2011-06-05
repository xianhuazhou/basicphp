<?php
define('APP_DIR', realpath(dirname(__FILE__) . '/..'));
require APP_DIR  . '/config/config.php';
require APP_DIR  . '/lib/OrderInfo.php';
require APP_DIR  . '/lib/swift/swift_required.php';
$orderInfo = new OrderInfo($config['db'], $config['mail'], $config['settings']);

header('Content-Type: text/html; charset=UTF-8');

function h($text) {
    return htmlspecialchars($text);
}

// trim
if ($_POST) {
    foreach ($_POST as $k => $v) {
        $_POST[$k] = trim($v);
    }
}
