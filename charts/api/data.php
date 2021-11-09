<?php

function get_price($name)
{
$servername = "localhost";
$username = "pablo";
$password = "test1";
$dbname = "precios_diario";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}
$sql = "SELECT precio FROM ".$name." ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);
if ($result->num_rows > 0) { while($row = $result->fetch_assoc()) { $price = $row["precio"]; } } else { echo "Error! You choosed non existing pairs."; }
$conn->close();
return floatval($price);
}
?>
