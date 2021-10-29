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

if ($result->num_rows > 0) { while($row = $result->fetch_assoc()) { $nombre1 = $row["nombre"]; $unidad1 = $row["unidad"]; $cantidad1 = $row["cantidad"]; $decimales1 = $row["decimales"]; $url1 = $row["url"]; $verificado1 = $row["verify"]; } } else { echo "Error! You choosed non existing pairs."; }
if ($result2->num_rows > 0) { while($row = $result2->fetch_assoc()) { $nombre2 = $row["nombre"]; $unidad2 = $row["unidad"]; $cantidad2 = $row["cantidad"]; $decimales2 = $row["decimales"]; $url2 = $row["url"]; $verificado2 = $row["verify"]; } } else { echo "Error! You choosed non existing pairs."; } 
$conn->close();


$cantidad1 = $cantidad1/(1*(10**$decimales1));
$cantidad2 = $cantidad2/(1*(10**$decimales2));
include 'tinychart.php';

$dbname = "precios_diario";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}
$sql4 = "SELECT fecha FROM (SELECT * FROM ".$asset_in."_".$asset_out." ORDER BY id DESC LIMIT 196) t2 ORDER BY t2.id ASC";
$sql5 = "SELECT precio FROM (SELECT * FROM ".$asset_in."_".$asset_out." ORDER BY id DESC LIMIT 196) t2 ORDER BY t2.id ASC";
$sql6 = "SELECT precio FROM 0_312769 ORDER BY id DESC LIMIT 1";
$sql7 = "SELECT precio FROM ".$asset_in."_".$asset_out." ORDER BY id DESC LIMIT 1";
$result4 = $conn->query($sql4);
$result5 = $conn->query($sql5);
$result6 = $conn->query($sql6);
$result7 = $conn->query($sql7);

$conn->close();
if ($result6->num_rows > 0) { while ($row = $result6->fetch_assoc()) { $usd = $row['precio']; } }
if ($result7->num_rows > 0) { while ($row = $result7->fetch_assoc()) { $valor = $row['precio']; } }


?>
<!DOCTYPE html>
<html lang="en">
<head>
<title> FreeTinyCharts - <?php if(!$_GET) { echo "Charts for Tinyman assets"; } else { echo "".$nombre1." TO ".$nombre2." chart"; } ?> </title>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="estilos.css">
<link rel="canonical" href="https://freetinycharts.ovh/index.php">
<meta name="description" content="FreeTinycharts is a free, opensource service that that automagically grabs all new&existing Tinyman pools, stores price data and create charts for them.">
<script src="scripts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>

<body>
<div class="w3-bar w3-aqua principal" id="paginatop">
<a href="pricing.html" class="w3-bar-item w3-button w3-padding-16" style="float:right;">Pricing</a>
<a href="freeapi.html" class="w3-bar-item w3-button w3-padding-16" style="float:right;">Free API</a>
<a href="roadmap.html" class="w3-bar-item w3-button w3-padding-16" style="float:right;">Roadmap</a>
<a href="about.html" class="w3-bar-item w3-button w3-padding-16" style="float:right;">About</a>
<a href="#" class="w3-bar-item w3-button w3-padding-16" style="float:left;">1 Algorand: <?php echo sprintf("%.3f", $usd)." USD" ?></a>

</div>
<div class="w3-container w3-row principal">

<div class="w3-container w3-twothird">
<p>FreeTinycharts is a free, opensource service that that automagically grabs all new&amp;existing Tinyman pools, stores price data and create charts for them.</p>

<p>It's on early stage and so if you want to help me to develop this and other Tinyman software and maintain this server, you can:</p>
<p>- Donate $algo to ARSXJS26M6M3MZUXJUYIWA4HOF5XKDEDDBYHEJS3OCCGUVCKQN3JNHXGOQ <br> Any amount will be appreciated.</p>
<p>- Buy official FreeTinycharts <a href="https://app.tinyman.org/#/swap?asset_in=0&amp;asset_out=330109984">ASA ID 330109984</a>. It can be used to buy FreeTinycharts services.</p>
<p>Thank you very much!</p>
<br>

<script>
$(document).ready(function() {
    $('.selector').select2();
});
</script>

        <select class="selector" id="ASSET-IN" onchange="change_asset();">
        <option value="Select asset IN">ASSET IN</option>
<?php if ($result3->num_rows > 0) { while ($row = mysqli_fetch_array($result3)) { echo "<option value=" . $row['asset_id'] . ">" . $row['asset_id'] ." - ". $row['nombre'].$row['verify']. "</option>"; } } ?>
        </select>
<select class="selector" id="ASSET-OUT">
<option value="Select asset OUT">ASSET OUT</option>
</select>
<input type="button" value="Go to chart" onclick = "Goto()" />
<p><small>If no chart shown: Pool has NOT liquidity or deleted asset. Verified assets are shown with a ✅ mark.</small><p>
</div>
<div class="w3-container w3-third">
@
</div>
</div>


<div class="w3-container w3-row principal">
<div class="w3-twothird w3-container">
<h2><?php echo "".$nombre1.$verificado1." TO ".$nombre2.$verificado2." 48h view"; ?></h2>
<canvas id="grafica" width="1000" style="margin: auto; max-width: 100%"></canvas>
</div>
<div class="w3-container w3-third">
<br>
<b>1 Algorand: <?php echo sprintf("%.3f", $usd)." USD" ?></b>
<br>
<h2>Other charts</h2>
<a href=<?php echo '"'."index.php?asset_in=".$asset_in."&amp;asset_out=".$asset_out.'"' ?>>48 hours chart</a>
<br>
<a href=<?php echo '"'."index-30.php?asset_in=".$asset_in."&amp;asset_out=".$asset_out.'"' ?>>1 month chart</a>
<br>
<a href=<?php echo '"'."index-1y.php?asset_in=".$asset_in."&amp;asset_out=".$asset_out.'"' ?>>1 year chart</a>
<br>
<?php if (in_array($asset_in, $tinychart)) { echo "<a href=https://tinychart.org/asset/".$asset_in." target=\"_blank\">View ".$nombre1." on Tinychart.org</a><br>"; } ?>
<h2>Popular pools</h2>
<a href="index.php?asset_in=226701642&amp;asset_out=0">Yieldy/Algorand</a>
<br>
<a href="index.php?asset_in=27165954&amp;asset_out=0">Planet/Algorand</a>
<br>
<a href="index.php?asset_in=230946361&amp;asset_out=31566704">AlgoGems/USDC</a>
<br>
<a href="index.php?asset_in=230946361&amp;asset_out=0">AlgoGems/Algorand</a>
<br>
<a href="index.php?asset_in=137594422&amp;asset_out=0">HEADLINE/Algorand</a>
<h2>View assets in AlgoExplorer</h2>
<?php if ($asset_in!="0") { echo "<a href=https://algoexplorer.io/asset/".$asset_in." target=\"_blank\">View ".$nombre1." on AlgoExplorer</a><br>"; }
if ($asset_out!="0") { echo "<a href=https://algoexplorer.io/asset/".$asset_out." target=\"_blank\">View ".$nombre2." on AlgoExplorer</a>"; } ?>
</div>


<div class="w3-container w3-twothird">
<div class="w3-container w3-row" style="width:100%">
<div class="w3-container w3-half">
<?php echo "<small><b>".$nombre1." data:<br> Unit name: </b>".$unidad1." <br><b>Total supply: </b>".$cantidad1." ".$unidad1." <br><b>Decimals: </b>".$decimales1."</small>"; if ($asset_out == "0" ) { echo "<small><br><b>Token USD value: </b>".sprintf("%.3f", $valor*$usd)." USD<br><b>Market cap: </b>".sprintf("%.3f", $valor*$usd*$cantidad1)." USD</small>"; } if (!filter_var($url1, FILTER_VALIDATE_URL) === false) { echo("<br><small><b>URL:</b> <a href=".$url1." target=\"_blank\">".$url1."</a></small>"); } ?>
</div>
<div class="w3-container w3-half">
<?php echo "<small><b>".$nombre2." data:<br> Unit name: </b>".$unidad2." <br><b>Total supply: </b>".$cantidad2." ".$unidad2." <br><b>Decimals: </b>".$decimales1."</small>"; if ($asset_in == "0" ) { echo "<small><br><b>Token USD value: </b>".sprintf("%.3f", $valor*$usd)." USD<br><b>Market cap: </b>".sprintf("%.3f", $valor*$usd*$cantidad2)." USD</small>"; } if (!filter_var($url2, FILTER_VALIDATE_URL) === false) { echo("<br><small><b>URL:</b> <a href=".$url2." target=\"_blank\">".$url2."</a></small>"); } ?>
</div>
</div>
</div>

<div class="w3-container w3-twothird">
<div class="icons" style="margin-left: 42px">
<figure>
<a href=<?php echo '"'."index.php?asset_in=".$asset_out."&amp;asset_out=".$asset_in.'"' ?>>
<img class="icons" src="shuffle.webp" width="64" title="<?php echo 'Exchange rate for '.$nombre2.' TO '.$nombre1 ?>"></a>
<figcaption>Switch assets</figcaption>
</figure>
</div>

<div class="icons" style="float: right; margin-right: 42px">
<figure>
<a href=<?php echo '"'."https://app.tinyman.org/#/swap?asset_in=".$asset_in."&amp;asset_out=".$asset_out.'"' ?> target="_blank">
<img class="icons" src="tinyman.png" width="64" title="<?php echo 'Swap in Tinyman '.$nombre1.' TO '.$nombre2 ?>"></a>
<figcaption>Swap in Tinyman</figcaption>
</figure>
</div>
</div>

<div class="w3-container w3-third">
<img src="compatible.webp" title="URLs are compatible so you can c&amp;p">
<p><small>Tinyman and FreeTinycharts URLs are compatible so you can copy&amp;paste in both directions</small></p>
</div>
</div>

<script>
const labels = [
<?php if ($result4->num_rows > 0) { while($row = $result4->fetch_assoc()) { echo "'". $row["fecha"]. "', "; } } else { echo "Error! You choosed non existing pairs."; } ?>

];
const data = {
  labels: labels,
  datasets: [{
    label: <?php echo "'Exchange rate for ".$nombre1." TO ".$nombre2."'"; ?>,
    borderColor: 'rgb(75, 192, 192)',
    data: [
<?php if ($result5->num_rows > 0) { while($row = $result5->fetch_assoc()) { echo sprintf("%.10f",$row["precio"]). ", "; } } else { echo "Error! You choosed non existing pairs."; } ?>
],
  }]
};
</script>

<script>
const config = {
  type: 'line',
  data: data,
  options: {
responsive:false,
maintainAspectRatio: true,
scales: { yAxes: [{ ticks: { beginAtZero: true }, afterDataLimits(scale) { scale.max = scale.max * 2; } }] } }
};
</script>

<script>
  var myChart = new Chart(
    document.getElementById('grafica'),
    config
  );
</script>
<br>
<br>
</body>
</html>
