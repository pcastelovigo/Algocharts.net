<?php
$cookie_name = "portfolio";
if (isset($_GET['algoaddr'])) $cookie_value = $_GET['algoaddr'];
setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>AlgoCharts - Portfolio tracker</title>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link id="theme" rel="stylesheet" href="claro-estilos.css?v=1.13">
<meta name="description" content="Algocharts portfolio tracker allows you to grab your Algorand financial position in just one place.">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="scripts.js?v=1.2"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@dmester/sffjs@1.17.0/dist/stringformat.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</head>
<body>
<div class="w3-container">
<h1>AlgoCharts portfolio tracker</h1>
<p> Insert your Algorand address (or any other address) and get data about total monetary value of held assets.</p>

<div class="w3-center w3-container">
<form action="portfolio.php" method="get">
<?php if(isset($_COOKIE['portfolio'])) { echo "<input type=\"text\" name=\"algoaddr\" value=\"".$_COOKIE['portfolio']."\"/>"; } else { echo "<input type=\"text\" name=\"algoaddr\" placeholder=\"Algorand Address\" />"; } ?>
<input type="submit"/>
</form> 
<small>No data shown: incorrect or incompete Algorand address</small>
</div>
<div class="w3-center w3-container">
<?php if (isset($_GET['algoaddr'])) { echo "<input type=\"button\" value=\"Don't show no value assets\" style=\"margin-bottom:12px; vertical-align: middle; line-height: 28px\" onclick=\"borrarvacios()\">"; } ?>
</div>

<div class="w3-container" id="tabla">

<table id="tabla1" style="margin: 0 auto; margin-top: 20px;">
<tr>
<th onclick="sortTable(0)">Token ⇕</th>
<th onclick="sortTable(1)">Amount ⇕</th>
<th class="w3-hide-small" onclick="sortTable(2)">Last price ⇕</th>
<th onclick="sortTable(3)">USD Value  ⇕</th>
<th onclick="sortTable(4)">Total value ⇕</th>
<th onclick="sortTable(5)">24h change ⇕</th>
</tr>
<?php
if (isset($_GET['algoaddr'])) {
$algoaddr = $_GET['algoaddr'];
$servername = "localhost";
$username = "pablo";
$password = "test1";


$api_url = "https://algoexplorerapi.io/idx2/v2/accounts/".$algoaddr."";

$json_data = file_get_contents($api_url);
$response_data = json_decode($json_data, true);

include 'precio_algo.php';

$lista_assets = array();
$lista_cantidades = array();

for ($i = 0; $i <= 100; $i++) {
    if (!isset($response_data['account']['assets'][$i])) { break; } else {
    $lista_assets[$i] = $response_data['account']['assets'][$i]['asset-id'];
    $lista_cantidades[$i] = $response_data['account']['assets'][$i]['amount'];
 } }
$algorand_en_cuenta = $response_data['account']['amount'];


$dbname = "pares"; $conn1 = new mysqli($servername, $username, $password, $dbname);
if ($conn1->connect_error) {die("Connection failed: " . $conn1->connect_error);}

$dbname = "precios_diario"; $conn2 = new mysqli($servername, $username, $password, $dbname);
if ($conn2->connect_error) {die("Connection failed: " . $conn2->connect_error);}

$total_value = 0;

for ($i = 0; $i <= 100; $i++) {
    if (!isset($lista_assets[$i])) { break; } else {
$sqlZ = "SELECT * FROM nombres where asset_id=".$lista_assets[$i]."";
$resultZ = $conn1->query($sqlZ);
if ($resultZ->num_rows > 0) { while($row = $resultZ->fetch_assoc()) { $nombre1 = $row["nombre"]; $unidad1 = $row["unidad"]; $cantidad1 = $row["cantidad"]; $decimales1 = $row["decimales"]; $url1 = $row["url"]; $verificado1 = $row["verify"]; } } else { $nombre1 = "No asset data"; $verificado1 = ""; $decimales1 = "0"; $unidad1 = "units"; }

$resultado_precios = array();
$sqlT = "SELECT precio FROM (SELECT * FROM ".$lista_assets[$i]."_0 ORDER BY id DESC LIMIT 96) t2 ORDER BY t2.id ASC";
$resultT = $conn2->query($sqlT);
if ($resultT->num_rows > 0) { while($row = $resultT->fetch_assoc()) { $resultado_precios[] = $row['precio']; } } else { $resultado_precios[95] = 0; $resultado_precios[0] = 0; }

if ($nombre1 != "No asset data") { echo "<tr><td><a class=\"orden\" href=\"chart.php?asset_in=".$lista_assets[$i]."&amp;asset_out=0\">".$nombre1.$verificado1."</a><br><small class=\"numerito\">".$lista_assets[$i]."</small></td>"; } else { echo "<tr><td class=\"orden\">".$nombre1."<br><small class=\"numerito\">".$lista_assets[$i]."</small></td>"; }
$lista_cantidades[$i] = $lista_cantidades[$i]/(1*(10**$decimales1));
echo "<td style=\"text-align: right\"><small class=\"orden\">".$lista_cantidades[$i]." ".$unidad1."</small></td>";
echo "<td class=\"w3-hide-small\" style=\"text-align: right\"><small class=\"orden\">".sprintf("%.6f",$resultado_precios[95])."Ⱥ</small></td>";
echo "<td style=\"text-align: right\"><small class=\"orden\">".sprintf("%.3f",$resultado_precios[95]*$usd)." USD</small></td>";
echo "<td style=\"text-align: right\"><small class=\"orden valortotal\">".sprintf("%.2f",$lista_cantidades[$i]*$resultado_precios[95]*$usd)." USD</small></td>";
if (isset($resultado_precios[95])) { if ($resultado_precios[95] != 0) { $cambio = ((($resultado_precios[95]-$resultado_precios[0])/$resultado_precios[0])*100); } else $cambio = 0; }
if (isset($resultado_precios[95])) { if ($cambio>0) { echo "<td style=\"color: green;text-align: right\"><small class=\"orden\">".sprintf("%.2f",$cambio)."%</small></td></tr>"; } else { echo "<td style=\"color: red;text-align: right\"><small class=\"orden\">".sprintf("%.2f",$cambio)."%</small></td></tr>"; } }
$total_value = $total_value+($lista_cantidades[$i]*$resultado_precios[95]*$usd);
} } }
?>
</table>
<br>
</div>
<div class="w3-container">
<table style="margin: 0 auto; margin-top: 15px;">
<?php
if (isset($_GET['algoaddr'])) {
echo "<tr><td>Algorand in account:</td><td style=\"text-align: right\">".($algorand_en_cuenta/1000000)."Ⱥ</td><td style=\"text-align: right\">".sprintf("%.2f",($algorand_en_cuenta/1000000)*$usd)." USD</td></tr>";
echo "<tr><td>Total token value:</td><td style=\"text-align: right\">".sprintf("%.6f",($total_value/$usd))."Ⱥ</td><td style=\"text-align: right\">".sprintf("%.2f",$total_value)." USD</td></tr>";
$total_value = $total_value+(($algorand_en_cuenta/1000000)*$usd);
echo "<tr><td>Total portfolio value:</td><td></td><td style=\"text-align: right\">".sprintf("%.2f",$total_value)." USD </td></tr>";
$conn1->close();
$conn2->close();
}
?>
</table>
</div>
<br><br><br>
<footer>
<div class="w3-bar w3-indigo" style="margin: auto; position:relative;" id="paginatop">
<a href="index.php" class="w3-bar-item w3-button w3-padding-16">Main page</a>
<a href="pricing.html" class="w3-hide-small w3-bar-item w3-button w3-padding-16" style="float:right;">Pricing</a>
<a href="freeapi.html" class="w3-hide-small w3-bar-item w3-button w3-padding-16" style="float:right;">Free API</a>
<a href="roadmap.html" class="w3-hide-small w3-bar-item w3-button w3-padding-16" style="float:right;">Roadmap</a>
<a href="about.html" class="w3-bar-item w3-button w3-padding-16" style="float:right;">About</a>
</div>
</footer>
</body>
</html>
