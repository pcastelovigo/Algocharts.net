<?php
include '../precio_algo.php';
$servername = "localhost";
$username = "pablo";
$password = "test1";

$dbname = "precios_diario";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}
$sqlR = "select * from liquidez where pool_id REGEXP '_0' AND liqa1 > 500000000";
$resultR = $conn->query($sqlR);
$conn->close();
$pools_billboard = array();
if ($resultR->num_rows > 0) { while ($row = $resultR->fetch_assoc()) { $pools_billboard[] = $row['pool_id']; } }

$dbname = "pares";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}
$sql3 = "select asset_id, nombre, unidad, verify from pares.nombres where asset_id != 0 AND CONCAT(asset_id, '_0') NOT IN (select pool_id from precios_diario.liquidez where liqa1 < 1000000);";
$result3 = $conn->query($sql3);
$conn->close();

$dbname = "precios_diario";
$conn1 = new mysqli($servername, $username, $password, $dbname);
if ($conn1->connect_error) {die("Connection failed: " . $conn->connect_error);}

$dbname = "pares";
$conn2 = new mysqli($servername, $username, $password, $dbname);
if ($conn2->connect_error) {die("Connection failed: " . $conn->connect_error);}

$dbname = "precios_live";
$conn3 = new mysqli($servername, $username, $password, $dbname);
if ($conn3->connect_error) {die("Connection failed: " . $conn->connect_error);}

$arrayLength = count($pools_billboard);
$listado_billboard = array();
$i = 0;
        while ($i < $arrayLength)
        {
$resultado_precios = array();
$sqlT = "SELECT precio FROM (SELECT * FROM ".$pools_billboard[$i]." ORDER BY id DESC LIMIT 196) t2 ORDER BY t2.id ASC";
$resultT = $conn1->query($sqlT);
if ($resultT->num_rows > 0) { while($row = $resultT->fetch_assoc()) { $resultado_precios[] = sprintf("%.12f", $row['precio']); } }

$resultado_precios_live = array();
$sqlT = "SELECT precio FROM (SELECT * FROM ".$pools_billboard[$i]." ORDER BY id DESC LIMIT 196) t2 ORDER BY t2.id ASC";
$resultT = $conn3->query($sqlT);
if ($resultT->num_rows > 0) { while($row = $resultT->fetch_assoc()) { $resultado_precios_live[] = sprintf("%.12f", $row['precio']); } }

$num_asset = substr($pools_billboard[$i], 0, strlen($pools_billboard[$i])-2);
$sqlY = "SELECT * from nombres where asset_id=".$num_asset."";
$resultY = $conn2->query($sqlY);
if ($resultY->num_rows > 0) { while($row = $resultY->fetch_assoc()) { $nombre_asset = $row['nombre']; $decimales = $row['decimales']; $cantidad1 = $row["cantidad"]; $verificado1 = $row['verify']; } }
$cantidad1 = $cantidad1/(1*(10**$decimales));

$sqlU = "SELECT * from liquidez where pool_id='".$pools_billboard[$i]."'";
$resultU = $conn1->query($sqlU);
if ($resultU->num_rows > 0) { while($row = $resultU->fetch_assoc()) { $liquidez = $row['liqa1']; } }

$longitud_array = count($resultado_precios);
$longitud_array_live = count($resultado_precios_live);

$apiprecio = sprintf("%.12f",$resultado_precios[$longitud_array-1]);
$apidolares = sprintf("%.4f",$resultado_precios[$longitud_array-1]*$usd);
$apiliquidez = sprintf("%.0f",($liquidez/1000000));
if ($longitud_array > 96) { $cambio = ((($resultado_precios[array_key_last($resultado_precios)]-$resultado_precios[$longitud_array-97])/$resultado_precios[$longitud_array-97])*100); }
if ($longitud_array > 96) { $cambio1h = ((($resultado_precios_live[array_key_last($resultado_precios_live)]-$resultado_precios_live[$longitud_array_live-97])/$resultado_precios_live[$longitud_array_live-97])*100); }
if ($longitud_array > 5) {
if ($cambio1h>0) { $apicambio1h = sprintf("%.2f",$cambio1h); } else { $apicambio1h = sprintf("%.2f",$cambio1h); } }
else { $apicambio1h = 0; }


if ($longitud_array > 96) {
if ($cambio>0) { $apicambio24 = sprintf("%.2f",$cambio); } else { $apicambio24 = sprintf("%.2f",$cambio); } }
else { $apicambio24 = 0; }

if ($longitud_array > 0) { $apimc = sprintf("%.0f",($resultado_precios[$longitud_array-1]*$cantidad1*$usd)); } else { $apimc = 0; }
$listado_billboard[$i] = array($num_asset, $nombre_asset, $apiprecio, $apidolares, $apiliquidez, $apicambio1h, $apicambio24, $apimc);
            $i++;
        }
$conn1->close();
$conn2->close();
$conn3->close();
return $listado_billboard;
 ?>
