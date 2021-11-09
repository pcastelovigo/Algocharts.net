<?php
$servername = "localhost";
$username = "pablo";
$password = "test1";
$asset_in = $_GET['asset_in'];
$asset_out = $_GET['asset_out'];
if(!isset($asset_in)) { $asset_in = "330109984"; }
if(!isset($asset_out)) { $asset_out = "0"; }
//if(!$_GET){ $asset_in = "330109984"; $asset_out = "0"; }

$dbname = "pares";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}
$sql = "SELECT * FROM nombres where asset_id=".$asset_in."";
$sql2 = "SELECT * FROM nombres where asset_id=".$asset_out."";
$sql3 = "select asset_id, nombre, verify from nombres where asset_id > 0";

$result = $conn->query($sql);
$result2 = $conn->query($sql2);
$result3 = $conn->query($sql3);
$conn->close();

if ($result->num_rows > 0) { while($row = $result->fetch_assoc()) { $nombre1 = $row["nombre"]; $unidad1 = $row["unidad"]; $cantidad1 = $row["cantidad"]; $decimales1 = $row["decimales"]; $url1 = $row["url"]; $verificado1 = $row["verify"]; $telegram1 = $row['telegram']; } } else { echo "Error! You choosed non existing pairs."; }
if ($result2->num_rows > 0) { while($row = $result2->fetch_assoc()) { $nombre2 = $row["nombre"]; $unidad2 = $row["unidad"]; $cantidad2 = $row["cantidad"]; $decimales2 = $row["decimales"]; $url2 = $row["url"]; $verificado2 = $row["verify"]; $telegram2 = $row['telegram']; } } else { echo "Error! You choosed non existing pairs."; } 
$cantidad1 = $cantidad1/(1*(10**$decimales1));
$cantidad2 = $cantidad2/(1*(10**$decimales2));
include 'tinychart.php';
include 'precios.php';

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
<!DOCTYPE html>
<html lang="en">
<head>
<title> AlgoCharts - <?php if(!$_GET) { echo "Charts for Algorand assets"; } else { echo "".$nombre1." TO ".$nombre2." chart"; } ?> </title>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link id="theme" rel="stylesheet" href="claro-estilos.css?v=1.13">
<link rel="icon" href="favicon.ico">
<link rel="apple-touch-icon" href="apple-touch-icon.png">
<meta name="description" content="Algocharts is a free, opensource service that that automagically grabs all new&existing Tinyman pools, stores price data and create charts for them.">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="scripts.js?v=1.1"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@dmester/sffjs@1.17.0/dist/stringformat.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</head>

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
<?php if ($result3->num_rows > 0) { while ($row = mysqli_fetch_array($result3)) { echo "<option value=" . $row['asset_id'] . ">" . $row['asset_id'] ." - ". $row['nombre'].$row['verify']. "</option>"; } } ?>
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

<div class="w3-twothird w3-container">
<h2><?php echo "".$nombre1.$verificado1." TO ".$nombre2.$verificado2." 1 year view"; ?></h2>
<div id="grafica" style="height:600px"></div>
<?php if (isset($resultado_precios[195])) { $cambio = ((($resultado_precios[195]-$resultado_precios[99])/$resultado_precios[99])*100);
if ($cambio>0) { echo "<small><b>1 year change:</b> </small><small style=\"color: green;\">".sprintf("%.2f",$cambio)." %</small>"; } else {echo "<small>1 year change: </small><small style=\"color: red;\">".sprintf("%.2f",$cambio)." %</small>"; } } else { echo "<small> 1 year change: No enougth data yet</small>";} ?>
&nbsp;&nbsp;&nbsp;<small><b> Last value:</b> <?php echo sprintf("%.6f",$valor)." ".$nombre2."</small>&nbsp;&nbsp;&nbsp;".$infoprecio_assetin ?></small>&nbsp;&nbsp;&nbsp;<small><b>Liquidity in pool: </b><?php echo sprintf("%.2f",$liqa2)." ".$unidad1.", ".sprintf("%.2f",$liqa1)." ".$unidad2.""; ?> </small>
<div class="w3-bar w3-indigo selector_grafica w3-round-xlarge" id="selecion_graficas" style="margin-top: 10px; margin-bottom: 10px">
<a href=<?php echo '"'."chart.php?asset_in=".$asset_in."&amp;asset_out=".$asset_out.'"' ?> class="w3-bar-item w3-button" style="margin: auto;">48h chart</a>
<a href=<?php echo '"'."chart-candle.php?asset_in=".$asset_in."&amp;asset_out=".$asset_out.'"' ?> class="w3-bar-item w3-button" style="margin: auto;">48h candlestick</a>
<a href=<?php echo '"'."chart-30.php?asset_in=".$asset_in."&amp;asset_out=".$asset_out.'"' ?> class="w3-bar-item w3-button" style="margin: auto;">1 month chart</a>
<a href=<?php echo '"'."chart-1y.php?asset_in=".$asset_in."&amp;asset_out=".$asset_out.'"' ?> class="w3-bar-item w3-button" style="margin: auto;">1 year chart</a>
</div>
<div class="w3-container w3-row">
<div class="icons">
<figure>
<a href=<?php echo '"'."chart-1y.php?asset_in=".$asset_out."&amp;asset_out=".$asset_in.'"' ?>>
<img src="shuffle.webp" width="64" title="<?php echo 'Exchange rate for '.$nombre2.' TO '.$nombre1 ?>"></a>
<figcaption>Switch assets</figcaption>
</figure>
</div>

<div class="icons">
<figure>
<a href=<?php echo '"'."https://app.tinyman.org/#/swap?asset_in=".$asset_in."&amp;asset_out=".$asset_out.'"' ?> target="_blank">
<img src="tinyman.png" width="64" title="<?php echo 'Swap in Tinyman '.$nombre1.' TO '.$nombre2 ?>"></a>
<figcaption>Swap&nbspin Tinyman</figcaption>
</figure>
</div>
</div>
</div>
<div class="w3-container w3-third w3-card" style="margin-bottom: 30px">
<b>1 Algorand: <?php echo sprintf("%.3f", $usd)." USD" ?></b>
<p><a href="index.php">Go back to billboard</a></p>
<p><a href="portfolio.php">Portfolio calculator🌟</a></p>
<p><a href="tokenprogram.html">Token program</a></p>
<p id="theme-toggle" style="cursor: pointer;"><u>Toggle dark/ligth mode</u></p>
</div>
<div class="w3-container w3-third w3-card" style="margin-bottom: 30px">
<?php echo "<small><b>".$nombre1." data:<br> Unit name: </b>".$unidad1." <br><b>Total supply: </b>".$cantidad1." ".$unidad1." <br><b>Decimals: </b>".$decimales1."</small><br>".$infoprecio_assetin."<br><b>Market cap: </b>".$marketcap_assetin."</small>"; if (!filter_var($url1, FILTER_VALIDATE_URL) === false) { echo("<br><small><b>URL:</b> <a href=".$url1." target=\"_blank\">".$url1."</a></small>"); } if (!empty($telegram1)) { echo("<br><small><b>Telegram:</b> <a href=".$telegram1." target=\"_blank\">".$telegram1."</a></small>"); }  ?>
</div>
<div class="w3-container w3-third w3-card" style="margin-bottom: 30px">
<?php echo "<small><b>".$nombre2." data:<br> Unit name: </b>".$unidad2." <br><b>Total supply: </b>".$cantidad2." ".$unidad2." <br><b>Decimals: </b>".$decimales2."</small><br>".$infoprecio_assetout."<br><b>Market cap: </b>".$marketcap_assetout."</small>"; if (!filter_var($url2, FILTER_VALIDATE_URL) === false) { echo("<br><small><b>URL:</b> <a href=".$url2." target=\"_blank\">".$url2."</a></small>"); } if (!empty($telegram2)) { echo("<br><small><b>Telegram:</b> <a href=".$telegram2." target=\"_blank\">".$telegram2."</a></small>"); }  ?>
</div>
<div class="w3-container w3-third w3-card" style="margin-bottom: 30px">
<h3>View assets in AlgoExplorer</h3>
<?php if ($asset_in!="0") { echo "<a href=https://algoexplorer.io/asset/".$asset_in." target=\"_blank\">View ".$nombre1." on AlgoExplorer</a><br>"; }
if ($asset_out!="0") { echo "<a href=https://algoexplorer.io/asset/".$asset_out." target=\"_blank\">View ".$nombre2." on AlgoExplorer</a>"; } ?> </div>
<div class="w3-container w3-third">
<img src="compatible.webp" title="URLs are compatible so you can c&amp;p" style="width:300px"> <p><small>Tinyman and AlgoCharts URLs are compatible so you can copy&amp;paste in both directions</small></p>
</div>
</div>


<script>
    const array_fechas = [];
    const array_fechas_f = [];
    var resultado_fechas = [ <?php if ($result4->num_rows > 0) { while($row = $result4->fetch_assoc()) { echo "'". $row["fecha"]. "', "; } } else { echo "Error! You choosed non existing pairs."; } ?>];
    for(var i=0; i<resultado_fechas.length; i++){
    array_fechas.push(convertFromStringToDate (resultado_fechas[i])); }
    for(var i=0; i<array_fechas.length; i++){
    array_fechas_f.push(array_fechas[i].format("dd/MM/yyy HH:mm")); }
</script>

<script>
var options = {
  chart: {
    type: 'line',
    height: 600
  },
  series: [{
    name: <?php echo "'1 ".htmlspecialchars($nombre1, ENT_QUOTES)." TO ".htmlspecialchars($nombre2, ENT_QUOTES)."'"; ?>,
    data: [<?php echo implode(", ", $resultado_precios); ?>]
  }],
  xaxis: {
    categories: array_fechas_f
  },
  yaxis: {
     title: {
          text: <?php echo "'".htmlspecialchars($nombre1, ENT_QUOTES)." TO ".htmlspecialchars($nombre2, ENT_QUOTES)."'"; ?>, 
          },
  },
  stroke: {
    curve: 'smooth',
    width: 2
  },
theme: {
      mode: 'light'
}
}

var chart = new ApexCharts(document.querySelector("#grafica"), options);

chart.render();
</script>
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
