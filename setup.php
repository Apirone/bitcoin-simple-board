<?php

//Run this file one to init the database

include 'include.php';

$result = mysql_connect($mysql_host, $mysql_username, $mysql_password);
if (!$result) {
    die(__LINE__ . ' Invalid connect: ' . mysql_error());
}

$result = mysql_query('CREATE DATABASE IF NOT EXISTS `' . $mysql_database . '`');

if (!$result) {
    die(__LINE__ . ' Invalid query: ' . mysql_error());
}

mysql_select_db($mysql_database) or die( "Unable to select database. Run setup first.");

$result = mysql_query('CREATE TABLE `advertisement` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `message` varchar(200) NOT NULL,
  `balance` double NOT NULL,
  `address` char(35) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;');

if (!$result) {
    die(__LINE__ . ' Invalid query: ' . mysql_error());
}

$result = mysql_query('CREATE TABLE `transactions` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `advertisement_id` int(11) NOT NULL,
  `transaction_hash` char(64) NOT NULL,
  `balance` double NOT NULL,
  `confirmations` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
     
if (!$result) {
    die(__LINE__ . ' Invalid query: ' . mysql_error());
}

echo 'Done. <a href="index.php">Go to home page</a>';

?>