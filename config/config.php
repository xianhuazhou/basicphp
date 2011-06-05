<?php
$config = array(
    'db' => array(
        'host' => 'localhost',
        'database' =>'zhn',
        'username' => 'root',
        'password' => 'abcdefg',
        'port' => 3306,
        'table' => 'order_info'
    ),
    'mail' => array(
        'host' => 'localhost',
        'port' => 25,
        'username' => 'user@localhst.com',
        'password' => 'paSSword',
        'from_email' => 'user@localhost.com',
        'from_name' => 'user',

        'to' => 'user@email.com',
        'subject' => '新的订单',
        'body' => file_get_contents(dirname(__FILE__) . '/mail_template.html'),
    ),
    'admin' => array(
        'user' => 'admin',
        'pass' => 'pass',
    ),
    'settings' => array(
        'type' => array(
           1 => '一套298', 
           2 => '两套568', 
           3 => '三套808'
        ),
    ),
);
?>
