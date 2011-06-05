<?php
require 'config/app.php';

$field = isset($_GET['field']) ? $_GET['field'] : '';
$value = isset($_GET['value']) ? $_GET['value'] : '';

if (!$field) {
    exit;
}

$method = 'validate' . ucfirst($field);
if (!$orderInfo->validateField($field, $value)) {
    echo $orderInfo->getError($field);
}
