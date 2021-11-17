<?php

function get_price($name)
{
$servername = "localhost";
$username = "pablo";
$password = "test1";
$dbname = "precios_diario";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}
$sqlK = "SELECT precio FROM 0_312769 ORDER BY id DESC LIMIT 1";
$sqlL = "SELECT precio FROM (SELECT * FROM ".$name." ORDER BY id DESC LIMIT 196) t2 ORDER BY t2.id ASC";
$resultK = $conn->query($sqlK);
$resultL = $conn->query($sqlL);
if ($resultK->num_rows > 0) { while ($row = $resultK->fetch_assoc()) { $usd = $row["precio"]; } }
$resultado_precios = array();
if ($resultL->num_rows > 0) { while ($row = $resultL->fetch_assoc()) { $resultado_precios[] = $row['precio']; } }
$conn->close();
$dbname = "pares";
$conn = new mysqli($servername, $username, $password, $dbname);
$sqlJ = "SELECT * FROM nombres where asset_id='".$asset_in."'";
$resultJ = $conn->query($sqlJ);
if ($resultJ->num_rows > 0) { while($row = $resultJ->fetch_assoc()) { $nombre1 = $row["nombre"]; $unidad1 = $row["unidad"]; $cantidad1 = $row["cantidad"]; $decimales1 = $row["decimales"]; } }
$cantidad1 = $cantidad1/(1*(10**$decimales1));
if (isset($resultado_precios[195])) { $cambio = ((($resultado_precios[195]-$resultado_precios[99])/$resultado_precios[99])*100); } else { $cambio = "0"; };
$conn->close();
$usdv = ($resultado_precios[195]*$usd);
return array (floatval($resultado_precios[195]), floatval($cambio), floatval($usdv), floatval($usd)); } ?>
