<?php
$servername = "localhost";
$username = "pablo";
$password = "test1";
$asset_in = $_GET['asset_in'];
$asset_out = $_GET['asset_out'];
if(isset($asset_in)) {
if(isset($asset_out)) { header('Location: https://algocharts.net/chart.php?asset_in='.$asset_in.'&asset_out='.$asset_out.''); } }

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
$sql3 = "select asset_id, nombre, unidad, verify from nombres where asset_id > 0";
$result3 = $conn->query($sql3);
$conn->close();

include 'precio_algo.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>AlgoCharts - Charts for Algorand assets</title>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link id="theme" rel="stylesheet" href="claro-estilos.css?v=1.13">
<link rel="icon" href="favicon.ico">
<link rel="apple-touch-icon" href="apple-touch-icon.png">
<meta name="description" content="Algocharts (previously FreeTinycharts) is a free, opensource service that that automagically grabs all new&existing Tinyman pools, stores price data and create charts for them.">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="scripts.js?v=1.5"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@dmester/sffjs@1.17.0/dist/stringformat.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<body>
<div class="w3-container w3-row">
<div class="w3-container w3-twothird">
<p>AlgoCharts is a free, opensource service that automagically grabs all new&amp;existing Tinyman pools, stores price data and creates charts for them.</p>
<div class="wrapper">
  <details>
    <summary>
      Read more
    </summary>
<p>It's on early stage and so if you want to help me to develop this and other Tinyman software and maintain this server, you can:</p>
<p>- Buy official AlgoCharts <a href="https://app.tinyman.org/#/swap?asset_in=0&amp;asset_out=330109984">ASA ID 330109984</a>. It can be used to buy AlgoCharts services.</p>
<p>Thank you very much!</p>
  </details>
</div>
<br>


<script>
$(document).ready(function() {
    $('.selector').select2();
});
</script>

        <select class="selector" id="ASSET-IN" onchange="change_asset();">
        <option value="330109984">Search asset...</option>
<?php if ($result3->num_rows > 0) { while ($row = mysqli_fetch_array($result3)) { echo "<option value=" . $row['asset_id'] . ">" . $row['asset_id'] ." - $".$row['unidad']." - ". $row['nombre'].$row['verify']. "</option>"; } } ?>
        </select>
<select class="selector" id="ASSET-OUT">
<option value="0">0 - Algorand</option>
</select>
<input type="button" value="Go to chart" style="margin-bottom:12px; vertical-align: middle; line-height: 28px" onclick = "Goto()" />
<p><small>If no chart shown: Pool has NOT liquidity or deleted asset. Verified assets are shown with a ✅ mark.</small><p>
</div>

<div class="w3-container w3-third w3-center w3-card">
<small>These months we are sponsored by...</small><br>
<a href="https://app.tinyman.org/#/swap?asset_in=383581973&asset_out=0"><img src="xbull.jpg"></a>
</div>
</div>

<div class="w3-container w3-row">

<div class="w3-container w3-twothird">
<div id="tabla">
<table style="margin: 0 auto; margin-top: 15px;">
<tr>
<th onclick="sortTable(0)">Token ⇕</th>
<th class="w3-hide-small" onclick="sortTable(1)">Last price ⇕</th>
<th class="w3-hide-small" onclick="sortTable(2)">USD price ⇕</th>
<th class="w3-hide-small w3-hide-medium" onclick="sortTable(3)">Liquidity ⇕</th>
<th class="w3-hide-small w3-hide-medium" onclick="sortTable(4)">1h change ⇕</th>
<th onclick="sortTable(5)">24h change ⇕</th>
<th class="w3-hide-small" onclick="sortTable(6)">Market Cap ⇕</th>
</tr>
<?php
$dbname = "precios_diario";
$conn1 = new mysqli($servername, $username, $password, $dbname);
if ($conn1->connect_error) {die("Connection failed: " . $conn->connect_error);}

$dbname = "pares";
$conn2 = new mysqli($servername, $username, $password, $dbname);
if ($conn2->connect_error) {die("Connection failed: " . $conn->connect_error);}
$arrayLength = count($pools_billboard);
$i = 0;
        while ($i < $arrayLength)
        {
$resultado_precios = array();
$sqlT = "SELECT precio FROM (SELECT * FROM ".$pools_billboard[$i]." ORDER BY id DESC LIMIT 196) t2 ORDER BY t2.id ASC";
$resultT = $conn1->query($sqlT);
if ($resultT->num_rows > 0) { while($row = $resultT->fetch_assoc()) { $resultado_precios[] = sprintf("%.12f", $row['precio']); } }

$num_asset = substr($pools_billboard[$i], 0, strlen($pools_billboard[$i])-2);
$sqlY = "SELECT * from nombres where asset_id=".$num_asset."";
$resultY = $conn2->query($sqlY);
if ($resultY->num_rows > 0) { while($row = $resultY->fetch_assoc()) { $nombre_asset = $row['nombre']; $decimales = $row['decimales']; $cantidad1 = $row["cantidad"]; $verificado1 = $row['verify']; } }
$cantidad1 = $cantidad1/(1*(10**$decimales));

$sqlU = "SELECT * from liquidez where pool_id='".$pools_billboard[$i]."'";
$resultU = $conn1->query($sqlU);
if ($resultU->num_rows > 0) { while($row = $resultU->fetch_assoc()) { $liquidez = $row['liqa1']; } }

$longitud_array = count($resultado_precios);
echo "<tr><td><a class=\"orden\" href=\"chart1m.php?asset_in=".$num_asset."&amp;asset_out=0\">".$nombre_asset.$verificado1."</a><br><small class=\"numerito\">".$num_asset."</small></td>";
echo "<td class=\"w3-hide-small\"><small class=\"orden\">".sprintf("%.6f",$resultado_precios[$longitud_array-1])."Ⱥ</small></td>";
echo "<td class=\"w3-hide-small\"><small class=\"orden\">".sprintf("%.3f",$resultado_precios[$longitud_array-1]*$usd)."USD</small></td>";
echo "<td class=\"w3-hide-small w3-hide-medium\" style=\"text-align: right\"><small class=\"orden\">".sprintf("%.0f",($liquidez/1000000))."Ⱥ</small></td>";
if ($longitud_array > 96) { $cambio = ((($resultado_precios[array_key_last($resultado_precios)]-$resultado_precios[$longitud_array-97])/$resultado_precios[$longitud_array-97])*100); }
if ($longitud_array > 5) { $cambio1h = ((($resultado_precios[array_key_last($resultado_precios)]-$resultado_precios[$longitud_array-5])/$resultado_precios[$longitud_array-5])*100); }
if ($longitud_array > 5) { if ($cambio1h>0) { echo "<td class=\"w3-hide-small w3-hide-medium\" style=\"color: green;text-align: right\"><small class=\"orden\" >".sprintf("%.2f",$cambio1h)."%</small></td>"; } else { echo "<td class=\"w3-hide-small w3-hide-medium\" style=\"color: red;text-align: right\"><small class=\"orden\" >".sprintf("%.2f",$cambio1h)."%</small></td>"; } } else { echo "<td class=\"w3-hide-small w3-hide-medium\" style=\"text-align: right\"><small class=\"orden\">0%</small></td>"; }
if ($longitud_array > 96) { if ($cambio>0) { echo "<td style=\"color: green;text-align: right\"><small class=\"orden\">".sprintf("%.2f",$cambio)."%</small></td>"; } else { echo "<td style=\"color: red;text-align: right\"><small class=\"orden\">".sprintf("%.2f",$cambio)."%</small></td>"; } } else { echo "<td style=\"text-align: right\"><small class=\"orden\">0%</small></td>"; }
if ($longitud_array > 0) { echo "<td class=\"w3-hide-small\" style=\"text-align: right\"><small class=\"orden\">".sprintf("%.0f",($resultado_precios[$longitud_array-1]*$cantidad1*$usd))." USD</small></td></tr>"; } else { echo "<td class=\"w3-hide-small\" style=\"text-align: right\"><small class=\"orden\">0 USD</small></td></tr>"; }
            $i++;
        }
$conn1->close();
$conn2->close();
 ?>
</table>
</div>
</div>
<div class="w3-container w3-card w3-third" style="max-width: 360px; margin-bottom: 30px; margin-top: 30px; margin-left:1px; margin-right:10px;">
<b>1 Algorand: <?php echo sprintf("%.3f", $usd)." USD" ?></b>
<p><a href="portfolio.php">Portfolio calculator🌟</a></p>
<p><a href="tokenprogram.html">Token program</a></p>
<p id="theme-toggle" style="cursor: pointer;"><u>Toggle dark/light mode</u></p>
</div>
<div class="w3-container w3-card w3-third" style="max-width: 360px; margin-bottom: 30px; margin-top: 30px; margin-left:1px; margin-right:10px;">
<small>If no change or market cap: No data yet</small>
<h2>Useful links</h2>
<p><a href="https://app.tinyman.org/#/swap?asset_in=0">Trade on Tinyman</a></p>
<p>Trade on AlgoDEX</p>
<p><a href="https://algoexplorer.io/">Algoexplorer Block explorer</a></p>
<p><a href="https://goalseeker.purestake.io/algorand/mainnet">Purestake Block explorer</a></p>
<p><a href="https://app.tinyman.org/#/swap?asset_in=0&asset_out=330109984">Buy AlgoChart token</a></p>
</div>

</div>

<br>
<br>
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
