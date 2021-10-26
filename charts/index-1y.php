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
$sql = "SELECT asset_id, nombre FROM nombres where asset_id=".$asset_in."";
$sql2 = "SELECT asset_id, nombre FROM nombres where asset_id=".$asset_out."";
$result = $conn->query($sql);
$result2 = $conn->query($sql2);
$sql3 = "select asset_id, nombre from nombres";
$result3 = $conn->query($sql3);

if ($result->num_rows > 0) { while($row = $result->fetch_assoc()) { $nombre1 = $row["nombre"]; } } else { echo "Error! You choosed non existing pairs."; }
if ($result2->num_rows > 0) { while($row = $result2->fetch_assoc()) { $nombre2 = $row["nombre"]; } } else { echo "Error! You choosed non existing pairs."; }
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title> FreeTinyCharts - <?php echo "".$nombre1." TO ".$nombre2." chart"; ?> </title>
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
<div class="w3-container w3-row principal">

<div class="w3-container w3-twothird">
<p>FreeTinycharts is a free, opensource service that that automagically grabs all new&amp;existing Tinyman pools, stores price data and create charts for them.</p>
<p><a href=readmore.html>Read more about FreeTinycharts &amp; FAQ.</a></p>

<p>If you want to help me to develop this and other Tinyman software and maintain this server, you can:</p>
<p>- <a href="https://github.com/pcastelovigo/freetinycharts">Contribute to my github</a></p>
<p>- Donate $algo to ARSXJS26M6M3MZUXJUYIWA4HOF5XKDEDDBYHEJS3OCCGUVCKQN3JNHXGOQ <br> Any amount will be appreciated.</p>
<p>- Buy my <a href="https://app.tinyman.org/#/swap?asset_in=0&amp;asset_out=330109984">ASA ID 330109984</a></p>
<p>Thank you very much!</p>
<br>

<script>
$(document).ready(function() {
    $('.selector').select2();
});
</script>

        <select class="selector" id="ASSET-IN" onchange="change_asset();">
        <option value="Select asset IN">ASSET IN</option>
<?php
    while ($row = mysqli_fetch_array($result3)) { echo "<option value=" . $row['asset_id'] . ">" . $row['asset_id'] ." - ". $row['nombre']. "</option>"; }
?>
        </select>
<select class="selector" id="ASSET-OUT">
<option value="Select asset OUT">ASSET OUT</option>
</select>
<input type="button" value="Go to chart" onclick = "Goto()" />
<p><small>If no chart shown: Pool has NOT liquidity or deleted asset.</small></p>
</div>
<div class="w3-container w3-third">
@
</div>
</div>


<div class="w3-container w3-row principal">
<div class="w3-twothird w3-container">
<h2><?php echo "".$nombre1." TO ".$nombre2." 48h view"; ?></h2>
<canvas id="grafica" width="1000" style="margin: auto; max-width: 100%"></canvas>
</div>
<div class="w3-container w3-third">
<br>
<h2>Other charts</h2>
<a href=<?php echo '"'."index.php?asset_in=".$asset_in."&amp;asset_out=".$asset_out.'"' ?>>48 hours chart</a>
<br>
<a href=<?php echo '"'."index-30.php?asset_in=".$asset_in."&amp;asset_out=".$asset_out.'"' ?>>1 month chart</a>
<br>
<a href=<?php echo '"'."index-1y.php?asset_in=".$asset_in."&amp;asset_out=".$asset_out.'"' ?>>1 year chart</a>
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
<div class="icons" style="margin-left: 42px">
<figure>
<a href=<?php echo '"'."index-1y.php?asset_in=".$asset_out."&amp;asset_out=".$asset_in.'"' ?>>
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
<?php
$dbname = "precios_diario";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}
$sql = "SELECT fecha FROM (SELECT * FROM ".$asset_in."_".$asset_out." WHERE id % 176 = 0 ORDER BY id DESC LIMIT 196) t2 ORDER BY t2.id ASC";
$sql2 = "SELECT precio FROM (SELECT * FROM ".$asset_in."_".$asset_out." WHERE id % 176 = 0 ORDER BY id DESC LIMIT 196) t2 ORDER BY t2.id ASC";
$result = $conn->query($sql);
$result2 = $conn->query($sql2);
if ($result->num_rows > 0) { while($row = $result->fetch_assoc()) { echo "'". $row["fecha"]. "', "; } } else { echo "Error! You choosed non existing pairs."; }
$conn->close(); ?>

];
const data = {
  labels: labels,
  datasets: [{
    label: <?php echo "'Exchange rate for ".$nombre1." TO ".$nombre2."'"; ?>,
    borderColor: 'rgb(75, 192, 192)',
    data: [
<?php
if ($result2->num_rows > 0) { while($row = $result2->fetch_assoc()) { echo $row["precio"]. ", "; } } else { echo "Error! You choosed non existing pairs."; }
$conn->close(); ?>
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
scales: { yAxes: [{ ticks: { beginAtZero: true } }] } }
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
