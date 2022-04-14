<?php
$conn = new mysqli("localhost", "pablo", "test1", "precios_diario");
if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}
$sqlA = "SELECT precio FROM 31566704_0 ORDER BY id DESC LIMIT 1";
$resultA = $conn->query($sqlA);
if ($resultA->num_rows > 0) { while ($row = $resultA->fetch_assoc()) { $usd = $row["precio"]; } }
$usd = (1/$usd);
$conn->close();
?>
