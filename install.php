<?php
require 'config/app.php';
$db = $config['db'];
$conn = mysql_connect($db['host'], $db['username'], $db['password']);
mysql_select_db($db['database'], $conn);
mysql_query('DROP TABLE IF EXISTS order_info');
$sql = '
CREATE TABLE order_info(
  id INT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  mobile VARCHAR(11) NOT NULL,
  address VARCHAR(250) NOT NULL,
  zip VARCHAR(6) NOT NULL,
  type TINYINT NOT NULL,
  created_at INT UNSIGNED NOT NULL,
  user_comment TEXT NOT NULL
)ENGINE=MyISAM DEFAULT CHARSET UTF8
';
mysql_query($sql);
$error = mysql_error($conn);
if ($error) {
    echo 'Error: ' . $error;
} else {
    echo 'Create success.';
}
mysql_close($conn);
