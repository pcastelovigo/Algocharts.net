<?php
require "../../lib/funciones.php";
function api(){
if (isset($_GET['algoaddr'])) {
$algoaddr = $_GET['algoaddr'];
$servername = "localhost";
$username = "pablo";
$password = "test1";

class Asa
{
public $name;
public $assetid;
public $amount;
public $algotokenvalue;
public $usdtokenvalue;
public $algototalvalue;
public $usdtotalvalue;
public $pricechange;
}


$context = stream_context_create(array("http" => array("header" => array(
"accept: application/json",
"X-Algo-API-Token: api-token",
"protocol_version" => 1.1,
))));

$api_url = "https://nodo.algocharts.net/v2/accounts/".$algoaddr."";

$json_data = file_get_contents($api_url, false, $context);
$response_data = json_decode($json_data, true);


$lista_assets = array();
$lista_cantidades = array();
$salida = new Asa();
$salida_final = array();

for ($i = 0; $i <= 250; $i++) {
    if (!isset($response_data['assets'][$i])) { break; } else {
    $lista_assets[$i] = $response_data['assets'][$i]['asset-id'];
    $lista_cantidades[$i] = $response_data['assets'][$i]['amount'];
 } }
$algorand_en_cuenta = $response_data['amount'];


$dbname = "pares"; $conn1 = new mysqli($servername, $username, $password, $dbname);
if ($conn1->connect_error) {die("Connection failed: " . $conn1->connect_error);}

$dbname = "precios_diario"; $conn2 = new mysqli($servername, $username, $password, $dbname);
if ($conn2->connect_error) {die("Connection failed: " . $conn2->connect_error);}

$total_value = 0;
$your_money = array();
$your_asset = array();

for ($i = 0; $i <= 250; $i++) {
    if (!isset($lista_assets[$i])) { break; } else {
$sqlZ = "SELECT * FROM nombres where asset_id=".$lista_assets[$i]."";
$resultZ = $conn1->query($sqlZ);
if ($resultZ->num_rows > 0) { while($row = $resultZ->fetch_assoc()) { $nombre1 = $row["nombre"]; $unidad1 = $row["unidad"]; $cantidad1 = $row["cantidad"]; $decimales1 = $row["decimales"]; $url1 = $row["url"]; $verificado1 = $row["verify"]; } } else { $nombre1 = "No asset data"; $verificado1 = ""; $decimales1 = "0"; $unidad1 = "units"; }

$resultado_precios = array();
$sqlT = "SELECT precio FROM (SELECT * FROM ".$lista_assets[$i]."_0 ORDER BY id DESC LIMIT 96) t2 ORDER BY t2.id ASC";
$resultT = $conn2->query($sqlT);
if ($resultT->num_rows > 0) { while($row = $resultT->fetch_assoc()) { $resultado_precios[] = $row['precio']; } } else { $resultado_precios[0] = 0; }
$longitud_array = count($resultado_precios);

if ($nombre1 != "No asset data") { $salida->name = $nombre1.$verificado1; $salida->assetid = $lista_assets[$i]; } else { $salida->name = $nombre1; $salida->assetid = $lista_assets[$i]; }
$lista_cantidades[$i] = $lista_cantidades[$i]/(1*(10**$decimales1));
$salida->amount = $lista_cantidades[$i]." ".$unidad1;
$salida->algotokenvalue = sprintf("%.6f",$resultado_precios[array_key_last($resultado_precios)]);
$salida->usdtokenvalue = sprintf("%.6f",$resultado_precios[array_key_last($resultado_precios)]*$usd);
$salida->algototalvalue = sprintf("%.2f",$lista_cantidades[$i]*$resultado_precios[array_key_last($resultado_precios)]);
$salida->usdtotalvalue= sprintf("%.2f",$lista_cantidades[$i]*$resultado_precios[array_key_last($resultado_precios)]*$usd);
if ($longitud_array > 94) { $cambio = ((($resultado_precios[array_key_last($resultado_precios)]-$resultado_precios[$longitud_array-95])/$resultado_precios[$longitud_array-95])*100); } else { $cambio = 0; }
if ($cambio > 0 ) { $salida->pricechange = sprintf("%.2f",$cambio); } else { $salida->pricechange = sprintf("%.2f",$cambio); }
$total_value = $total_value+($lista_cantidades[$i]*$resultado_precios[array_key_last($resultado_precios)]*$usd);
if (sprintf("%.2f",$lista_cantidades[$i]*$resultado_precios[array_key_last($resultado_precios)]*$usd) > 0) { $your_money[] = (sprintf("%.2f",$lista_cantidades[$i]*$resultado_precios[array_key_last($resultado_precios)]*$usd)); $your_asset[] = htmlspecialchars($nombre1, ENT_QUOTES); }
array_push($salida_final, $salida);
unset($salida);
$salida = new Asa();
} } }
if (sizeof($your_money) > 0 ) { $your_money[] = sprintf("%.2f", (( $algorand_en_cuenta/1000000)*$usd)); $your_asset[] = "Algorand"; }
response(200,"OK",$salida_final);
}

$max_calls_limit  = 66;
$endpoint = "PF";
include '../../lib/apikeys.php';
