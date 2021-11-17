<?php
$dbname = "precios_diario";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}
$sql4 = "SELECT fecha FROM (SELECT * FROM ".$asset_in."_".$asset_out." WHERE id % 182 = 0 ORDER BY id DESC LIMIT 196) t2 ORDER BY t2.id ASC";
$sql5 = "SELECT precio FROM (SELECT * FROM ".$asset_in."_".$asset_out." WHERE id % 182 = 0 ORDER BY id DESC LIMIT 196) t2 ORDER BY t2.id ASC";
$sql6 = "SELECT * FROM liquidez where pool_id='".$asset_in."_".$asset_out."'";
$sql7 = "SELECT precio FROM ".$asset_in."_".$asset_out." ORDER BY id DESC LIMIT 1";
$result4 = $conn->query($sql4);
$result5 = $conn->query($sql5);
$result6 = $conn->query($sql6);
$result7 = $conn->query($sql7);
$conn->close();
$resultado_precios = array();
if ($result5->num_rows > 0) { while($row = $result5->fetch_assoc()) { $resultado_precios[] = sprintf("%.8f", $row['precio']); } }
if ($result6->num_rows > 0) { while ($row = $result6->fetch_assoc()) { $liqa1 = $row['liqa1']; $liqa2 = $row['liqa2']; } }
if ($result7->num_rows > 0) { while ($row = $result7->fetch_assoc()) { $valor = $row['precio']; } }
//Estan invertidos :D
$liqa2 = $liqa2/(1*(10**$decimales1));
$liqa1 = $liqa1/(1*(10**$decimales2));
?>
