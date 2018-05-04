<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include 'include.php';

// get message ID and info
$id = intval($_GET["id"]);

$db = new mysqli($mysql_host, $mysql_username, $mysql_password) or die(__LINE__ . ' Invalid connect: ' . mysqli_error());
$db->select_db($mysql_database) or die( "Unable to select database. Run setup first.");

$stmt = $db->prepare("select balance, transaction_hash, confirmations from transactions WHERE advertisement_id= ? ORDER by confirmations ASC");
$stmt->bind_param("i", $id);
$success = $stmt->execute();

if (!$success) {
    die(__LINE__ . ' Invalid query: ' . mysql_error());
}

$stmt->store_result();
if ($stmt->num_rows == 0 ) {echo '<p align="center">Waiting payment...</p>';} else {

	$stmt->bind_result($balance, $transaction_hash, $confirmations);

	while ($stmt->fetch()) {
		echo '<p align="center">';
		if ($confirmations == 0) {echo "<b style='color:blue;'>Got transaction. Waiting confirmation.</b> ";}
		if ($confirmations > 0 and $confirmations < $count_of_confirmations)  {echo "<b style='color:navy;'>Confirmations: " . $confirmations . ".</b> ";}
		if ($confirmations == $count_of_confirmations)  {echo "<b style='color:green;'>Payment done.</b> ";}
		printf ('Balance: <b>%s</b> BTC. Transaction hash: <b>%s</b></p>', $balance, $transaction_hash);
		echo '</p>';
	}

}

$stmt->close(); 

?>