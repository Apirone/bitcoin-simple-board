<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Site example. Payment page.</title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
<a href="./">Home</a>
<h1 align="center">Payment page</h1>

<?php

include 'include.php';

	
if (isset($_GET["id"])) {
	
	// get message ID and info
	$id = intval($_GET["id"]);

	$db = new mysqli($mysql_host, $mysql_username, $mysql_password) or die(__LINE__ . ' Invalid connect: ' . mysqli_error());
	$db->select_db($mysql_database) or die( "Unable to select database. Run setup first.");

	$stmt = $db->prepare("SELECT * FROM `advertisement` WHERE id= ?");
	$stmt->bind_param("i", $id);
	$success = $stmt->execute();
	if (!$success) {
		die(__LINE__ . ' Invalid query: ' . mysql_error());
	}

	$result = $stmt->get_result();

	// check message existance
	if ($result->num_rows > 0) {
	
		// fetch array		
		while($row = $result->fetch_array()) {
			$address = $row['address'];
			$message = $row['message'];
			$balance = $row['balance'];
		}

		$result->close();
		$stmt->close(); 

		echo '<p align="center">Message: <b>'. $message .'</b><p align="center">Please send the payment to the following bitcoin address: <br><b>' . $address . "</b></p>";

		// show QR code
		echo '<p align="center"><img src="' . $blockchain_root . "qr?message=". urlencode ("bitcoin:". $address) . "&format=png" . '" width="300" alt="QR code"></p>';

		echo '<div id="server-name"></div>';
		
 

	
	} else { echo '<p align="center">Message is not found</p>'; }

} else { echo '<p align="center">Wrong message ID.</p>'; }

?>

	<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
	<script type="text/javascript"> $(document).ready(function () {
    var interval = 500;   //number of mili seconds between each call
    var refresh = function() {
        $.ajax({
            url: "status.php?id=<?php echo $id; ?>",
            cache: false,
            success: function(html) {
                $('#server-name').html(html);
                setTimeout(function() {
                    refresh();
                }, interval);
            }
        });
    };
    refresh();
});
	</script> 

</body>
</html>