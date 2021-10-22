<?php
$servername = "localhost";
$username = "test";
$password = "test1";
$asset_in = $_GET['asset_in'];
$asset_out = $_GET['asset_out'];
if(!$_GET){ $asset_in = "0"; $asset_out = "330109984"; }
$dbname = "nombres";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}
$sql = "SELECT asset_id, nombre FROM nombres where asset_id=".$asset_in."";
$sql2 = "SELECT asset_id, nombre FROM nombres where asset_id=".$asset_out."";
$result = $conn->query($sql);
$result2 = $conn->query($sql2);

if ($result->num_rows > 0) { while($row = $result->fetch_assoc()) { $nombre1 = $row["nombre"]; } } else { echo "Error! You choosed non existing pairs."; }
if ($result2->num_rows > 0) { while($row = $result2->fetch_assoc()) { $nombre2 = $row["nombre"]; } } else { echo "Error! You choosed non existing pairs."; }
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title> FreeTinyCharts </title>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<style>
div.icons {
width: 150px;
display: inline-block;
}
image.icons {
  display: block;
  margin-left: auto;
  margin-right: auto;
}
figcaption {
text-align: center;
}
.coin_icon > img {
    height: 30px;
    max-height: 30px;
}
.principal {
width: 1360px;
margin: auto;
}
</style>
</head>

<body>
<div class="w3-container w3-row principal">
<p>Hello tinies!! I made a <a href="https://github.com/pcastelovigo/freetinycharts">small, easy to setup software</a> that automagically grabs all new&existing Tinyman pools, stores price data and create charts for them.</p>
<p>I'm opensourcing it so everybody can start their own Tinyman charts service! Also all historical ASA price data is avaliable for download for free <a href="precios.tar.bzip2">here</a>.</p>
<br>
<p>If you want to help me to develop this and other Tinyman software and maintain this server, you can:</p>
<p>- Contributing to my github</p>
<p>- Donating $algo to ARSXJS26M6M3MZUXJUYIWA4HOF5XKDEDDBYHEJS3OCCGUVCKQN3JNHXGOQ .Any amount will be appreciated.</p>
<p>- Buying my for-fun <a href="https://app.tinyman.org/#/swap?asset_in=0&asset_out=330109984">ASA ID 330109984</a></p>
<p>Thank you very much!</p>

</div>

<div class="w3-container w3-row principal">
<div class="w3-twothird w3-container">
<h2><?php echo "".$nombre1." TO ".$nombre2." 48h view"; ?></h2>
<canvas id="grafica" width="1000" style="margin: auto; max-width: 100%"></canvas>
</div>
<div class="w3-container w3-third">
<br>
<a href="index.php">48 hours chart</a>
<br>
<a href="index-30.php">1 month chart</a>
<br>
<a href="index-1y.php">1 year chart</a>
</div>

<div class="w3-container w3-twothird">
<div class="icons" style="margin-left: 42px">
<figure>
<a href=<?php echo '"'."https://freetinycharts.ovh/index.php?asset_in=".$asset_out."&amp;asset_out=".$asset_in.'"' ?>>
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
X
</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<script>
const labels = [
<?php
$dbname = "precios_diario";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}
$sql = "SELECT fecha FROM ".$asset_in."_".$asset_out." ORDER BY id ASC LIMIT 196";
$result = $conn->query($sql);
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
$dbname = "precios_diario";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }
$sql = "SELECT precio FROM ".$asset_in."_".$asset_out." ORDER BY id ASC LIMIT 196";
$result = $conn->query($sql);
if ($result->num_rows > 0) { while($row = $result->fetch_assoc()) { echo $row["precio"]. ", "; } } else { echo "Error! You choosed non existing pairs."; }
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
    maintainAspectRatio: false
}
};
</script>

<script>
 // === include 'setup' then 'config' above ===

  var myChart = new Chart(
    document.getElementById('grafica'),
    config
  );
</script>
<div class="principal">
Be sure of choosing existing Tinyman pairs.
<?php
$dbname = "nombres";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}
$sql = "select asset_id, nombre from nombres";
$result = $conn->query($sql);
?>
	<select id="ASSET-IN">
	<option value="Select asset IN">ASSET IN</option>
<?php
    while ($row = mysqli_fetch_array($result)) {
        echo "<option value=" . $row['asset_id'] . ">" . $row['asset_id'] ." - ". $row['nombre']. "</option>"; }
?>
	</select>
	<select id="ASSET-OUT">
	<option value="Select asset OUT">ASSET OUT</option>
<?php
	mysqli_data_seek($result, 0);
	while ($row = mysqli_fetch_array($result)) {
        echo "<option value=" . $row['asset_id'] . ">" . $row['asset_id'] ." - ". $row['nombre']. "</option>"; }
?>
	</select>

<input type="button" value="Go to chart" onclick = "Goto()" />
</div>
<script type="text/javascript">
function Goto() {
  var x = document.getElementById("ASSET-IN").value;
  var y = document.getElementById("ASSET-OUT").value;
  var url = "https://freetinycharts.ovh/index.php?asset_in=" + encodeURIComponent(x) + "&asset_out=" + encodeURIComponent(y);
  window.location.href = url;
};
</script>
<br>
<br>
</body>
</html>
