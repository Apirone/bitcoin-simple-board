<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Bitcoin Simple Advertisement Board</title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>

<h1 align="center">Hall of fame:</h1>
<?php
	
include 'include.php';

$db = new mysqli($mysql_host, $mysql_username, $mysql_password) or die(__LINE__ . ' Invalid connect: ' . mysqli_error());
$db->select_db($mysql_database) or die( "Unable to select database. Run setup first.");

$stmt = $db->prepare("select id, message, balance from advertisement WHERE balance>0 ORDER BY balance DESC");
$success = $stmt->execute();

if (!$success) {
    die(__LINE__ . ' Invalid query: ' . mysql_error());
}

$stmt->store_result();
if ($stmt->num_rows == 0 ) {echo "No results";} else {

	$stmt->bind_result($id, $message, $balance);

	while ($stmt->fetch()) {
		printf ("<p>Balance: <a href='paymentpage.php?id=%s'>%s BTC</a> Message: <b>%s</b></p>", $id, $balance, $message);
	}
}

$stmt->close(); 
?>
<h3>Last 5 not paid messages:</h3>
<?php
	
$stmt = $db->prepare("select id, message from advertisement WHERE balance = 0 ORDER BY id DESC LIMIT 5");
$success = $stmt->execute();

if (!$success) {
    die(__LINE__ . ' Invalid query: ' . mysql_error());
}

$stmt->store_result();
if ($stmt->num_rows == 0 ) {echo "No results";} else {

	$stmt->bind_result($id, $message);

	while ($stmt->fetch()) {
		printf ("<p><a href='paymentpage.php?id=%s'> Message:</a> <b>%s</b></p>", $id, $message);
	}
}

$stmt->close(); 
?>

<form action="addmessage.php" method="post" >
	<p align="center">
		<label for="textfield">Publish your message:</label>
		<input type="text" name="message" id="textfield" placeholder="max: 200 chars"  size="50" maxlength="200">
		<input type="submit" name="submit" id="submit" value="Go to payment page">
	</p>
</form>

</body>
</html>