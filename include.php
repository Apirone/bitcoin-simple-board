<?php

$blockchain_root = "https://apirone.com/api/v1/"; 			// URL to API server, don't touch this
$mysite_root = "http://mysite.com/"; 						// your website URL where callback.php will be located
$secret = "CHANGE_TO_RANDOM_SECRET"; 						// any secret string without spaces
$my_bitcoin_address = "1DonateWffyhwAjskoEwXt83pHZxhLTr8H"; // CHANGE IT to your own bitcoin address to receive payments
$count_of_confirmations = 1;								// minimum 1 network confirmation of transation, recomend 3

//Database
$mysql_host = 'localhost';
$mysql_username = 'root';
$mysql_password = 'root';
$mysql_database = 'mysite'


?>