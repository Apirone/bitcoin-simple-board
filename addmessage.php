<?php

include 'include.php';

// add new message
if ($_POST["message"]) {
	
	$message = htmlentities(substr($_POST["message"],0,200));

	$db = new mysqli($mysql_host, $mysql_username, $mysql_password) or die(__LINE__ . ' Invalid connect: ' . mysqli_error());
	$db->select_db($mysql_database) or die( "Unable to select database. Run setup first.");

	// add message to table
	$stmt = $db->prepare("INSERT INTO `advertisement` (`id`, `ts`, `message`, `balance`, `address`) VALUES (NULL, CURRENT_TIMESTAMP, ?, 0, '');");
	$stmt->bind_param("s", $message);
	$success = $stmt->execute();
	if (!$success) {
		die(__LINE__ . ' Invalid query: ' . mysql_error());
	}

	$added_row_id= $stmt->insert_id;

	// Now generate new bitcoin address for this message
	// prepare callback URL
	$my_callback_url = $mysite_root . "callback.php?id=" . $added_row_id . "&secret=" . $secret;

	// generate bitcoin address for payment via cURL query. Also you can use file_get_contents, see manual examples
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $blockchain_root . "receive?method=create&address=" . $my_bitcoin_address . "&callback=" . urlencode($my_callback_url)
    ));
    $response = curl_exec($curl);
    $http_status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
 
    if ($http_status_code == 200) {
        $decoded = json_decode($response, true);
        
		// update message row with generated bitcoin address
		$stmt = $db->prepare("UPDATE `advertisement` SET `address` = ? WHERE `id` = ?;");
		$stmt->bind_param("si", $decoded["input_address"], $added_row_id);
		$success = $stmt->execute();
		if (!$success) {
			die(__LINE__ . ' Invalid query: ' . mysql_error());
		}
		
		// Success. Redirect to payment page
		header('Location: paymentpage.php?id=' . $added_row_id);
		
    } else {
        echo "Sorry, an API error occurred.";
    }

	if (!$success) {
		die(__LINE__ . ' Invalid query: ' . mysql_error());
	}

	$stmt->close(); 
}
else
{
	// Nothing to add, return to main page 
	header('Location: index.php');
}

?>