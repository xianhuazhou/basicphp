<?php
require 'config/app.php';

if (count($_POST) < 1) {
    require APP_DIR . '/index.php';
    exit;
}

if ($orderInfo->create($_POST) != 1) {
    $errors = $orderInfo->getErrors();
    require APP_DIR . '/index.php';
    exit;
}

require APP_DIR . '/order_finish.php';
