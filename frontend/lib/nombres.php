<?php
$servername = "localhost";
$username = "pablo";
$password = "test1";
$asset_in = $_GET['asset_in'];
$asset_out = $_GET['asset_out'];
if(!isset($asset_in)) { $asset_in = "330109984"; }
if(!isset($asset_out)) { $asset_out = "0"; }
if ( filter_var($asset_in, FILTER_VALIDATE_INT) === false ) { echo "8===D"; exit(); }
if ( filter_var($asset_out, FILTER_VALIDATE_INT) === false ) { echo "8===D"; exit(); }
//if(!$_GET){ $asset_in = "330109984"; $asset_out = "0"; }

$dbname = "pares";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}
$sql = "SELECT * FROM nombres where asset_id=".$asset_in."";
$sql2 = "SELECT * FROM nombres where asset_id=".$asset_out."";
$sql3 = "select asset_id, nombre, unidad, verify from pares.nombres where asset_id != 0 AND CONCAT(asset_id, '_0') NOT IN (select pool_id from precios_diario.liquidez where liqa1 < 1000000);";

$result = $conn->query($sql);
$result2 = $conn->query($sql2);
$result3 = $conn->query($sql3);
$conn->close();

if ($result->num_rows > 0) { while($row = $result->fetch_assoc()) { $nombre1 = $row["nombre"]; $unidad1 = $row["unidad"]; $cantidad1 = $row["cantidad"]; $decimales1 = $row["decimales"]; $url1 = $row["url"]; $verificado1 = $row["verify"]; $telegram1 = $row['telegram']; } } else { echo "Error! You choosed non existing pairs."; }
if ($result2->num_rows > 0) { while($row = $result2->fetch_assoc()) { $nombre2 = $row["nombre"]; $unidad2 = $row["unidad"]; $cantidad2 = $row["cantidad"]; $decimales2 = $row["decimales"]; $url2 = $row["url"]; $verificado2 = $row["verify"]; $telegram2 = $row['telegram']; } } else { echo "Error! You choosed non existing pairs."; } 
$cantidad1 = $cantidad1/(1*(10**$decimales1));
$cantidad2 = $cantidad2/(1*(10**$decimales2));
?>
