<?php

include 'include.php';

// compare secret string
if ($_GET['secret'] != $secret) { die ('Invalid Secret');}

// check another parameters
if (!isset($_GET['input_transaction_hash'])) { die ('No input transaction hash');}
if (!isset($_GET['confirmations'])) { die ('Empty confirmation query');}
if (!isset($_GET['value'])) { die ('Empty value');}

// get message ID and balance
$advertisement_id = intval($_GET["id"]);
$balance = $_GET['value'] / 100000000;

$db = new mysqli($mysql_host, $mysql_username, $mysql_password) or die(__LINE__ . ' Invalid connect: ' . mysqli_error());
$db->select_db($mysql_database) or die( "Unable to select database. Run setup first.");

// minimum 1 confirmation, recomend 3. Zero confirmation is not applicable at this code.
if ($_GET['confirmations'] == 0) {
	// Add new unconfirmed transaction to table
	$stmt = $db->prepare("INSERT INTO `transactions` (`id`, `advertisement_id`, `transaction_hash`, `balance`, `confirmations`) VALUES (NULL, ?, ?, ?, '0');");
	$stmt->bind_param("iss", $advertisement_id, $_GET['input_transaction_hash'], $balance);
	$success = $stmt->execute();
	if (!$success) { die(__LINE__ . ' Invalid query: ' . mysql_error());}
} else {

	// compare owner address
	if ($_GET['destination_address'] != $my_bitcoin_address) { die ('Incorrect Receiving Address');}

	if ($_GET['confirmations'] < $count_of_confirmations) {
		// Update count of confirmations
		$stmt = $db->prepare("UPDATE `transactions` SET `confirmations` = ? WHERE `advertisement_id` = ? and `transaction_hash`= ?");
		$stmt->bind_param("iis", $_GET['confirmations'], $advertisement_id, $_GET['input_transaction_hash']);
		$stmt->execute();
		$success = $stmt->execute();
		if (!$success) { die(__LINE__ . ' Invalid query: ' . mysql_error());}
	}


	if ($_GET['confirmations'] == $count_of_confirmations) {
		// Update count of confirmations
		$stmt = $db->prepare("UPDATE `transactions` SET `confirmations` = ? WHERE `advertisement_id` = ? and `transaction_hash`= ?");
		$stmt->bind_param("iis", $_GET['confirmations'], $advertisement_id, $_GET['input_transaction_hash']);
		$stmt->execute();
		$success = $stmt->execute();
		if (!$success) { die(__LINE__ . ' Invalid query: ' . mysql_error());}

		// Add balance to the message
		$stmt = $db->prepare("UPDATE `advertisement` SET `balance` = `balance` + ? WHERE `id` = ?");
		$stmt->bind_param("si", $balance, $advertisement_id);
		$result = $stmt->execute();

		// Success. Return response *ok* to callback server
		if($result) {
			echo "*ok*";
		} else
		{ 
			die(__LINE__ . ' Invalid query: ' . mysql_error());
		}

	}
}

?>
