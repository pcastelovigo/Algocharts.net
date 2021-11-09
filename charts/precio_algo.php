<?php
$servername = "localhost";
$username = "pablo";
$password = "test1";
$dbname = "precios_diario";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}
$sqlA = "SELECT precio FROM 0_312769 ORDER BY id DESC LIMIT 1";
$resultA = $conn->query($sqlA);
if ($resultA->num_rows > 0) { while ($row = $resultA->fetch_assoc()) { $usd = $row["precio"]; } }
$conn->close();
?>
