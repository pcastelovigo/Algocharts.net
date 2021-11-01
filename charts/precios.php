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

if ($asset_in != "0") {
$sqlB = "SELECT precio FROM ".$asset_in."_0 ORDER BY id DESC LIMIT 1";
$resultB = $conn->query($sqlB);
if ($resultB->num_rows > 0) { while($row = $resultB->fetch_assoc()) { $precio_assetin = $row["precio"]; $infoprecio_assetin = "<small><b>Token USD value: </b>".sprintf("%.3f",$precio_assetin*$usd)." USD"; } } else {
 "<small><b>Token USD value: </b>".$infoprecio_assetin= "<small><b>Token USD value: </b>"."No data"; } } else { $infoprecio_assetin = "<small><b>Algorand USD value: </b>".$usd." USD"; $precio_assetin = 1; }


if ($infoprecio_assetin != "<small><b>Token USD value: </b>"."No data") { $marketcap_assetin = sprintf("%.2f",($precio_assetin*$usd*$cantidad1))." USD"; } else { $marketcap_assetin = "No data"; }

if ($asset_out != "0") {
$sqlC = "SELECT precio FROM ".$asset_out."_0 ORDER BY id DESC LIMIT 1";
$resultC = $conn->query($sqlC);
if ($resultC->num_rows > 0) { while($row = $resultC->fetch_assoc()) { $precio_assetout = $row["precio"]; $infoprecio_assetout = "<small><b>Token USD value: </b>".sprintf("%.3f",$precio_assetout*$usd)." USD"; } } else {
 "<small><b>Token USD value: </b>".$infoprecio_assetout= "<small><b>Token USD value: </b>"."No data"; } } else { $infoprecio_assetout = "<small><b>Algorand USD value: </b>".$usd." USD"; $precio_assetout = 1; }

if ($infoprecio_assetout != "<small><b>Token USD value: </b>"."No data") { $marketcap_assetout = sprintf("%.2f",($precio_assetout*$usd*$cantidad2))." USD"; } else { $marketcap_assetout = "No data"; }

$conn->close();
?>
