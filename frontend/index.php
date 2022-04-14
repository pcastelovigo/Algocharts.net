<?php
require 'lib/funciones.php';
$dbprecios = "precios_diario"; $dbpares = "pares"; $market = ""; $dbselector = ""; $asset_in = 330109984; $asset_out = 0;
if (isset($_GET['market'])){ if ($_GET['market'] == "pactfi") { $dbselector = "PACTFI"; $market = "pactfi"; } }
if (isset($_GET['market'])){ if ($_GET['market'] == "algofi") { $dbselector = "ALGOFI"; $market = "algofi"; } }
if (isset($_GET['chart'])){ $chart = $_GET['chart']; } else { $chart = "15min"; }
$dbprecios = $dbselector.$dbprecios;
$dbpares = $dbselector.$dbpares;
$dbliquidez = $dbselector."precios_diario";

$usd = precio_algo();

$servername = "localhost"; $username = "pablo"; $password = "test1";
$conn = new mysqli($servername, $username, $password, $dbprecios); if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}
$sqlR = "select pool_id from liquidez where pool_id REGEXP '_0' AND liqa1 > 500000000";
$resultR = $conn->query($sqlR); $conn->close(); $pools_billboard = array(); if ($resultR->num_rows > 0) { while ($row = $resultR->fetch_assoc()) { $pools_billboard[] = $row['pool_id']; } }
?>


<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport"><!-- CSS -->
        <link rel="stylesheet" href="css/extra.css">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" href="css/mermaid.min.css">
	<link rel="canonical" href="https://algocharts.net"/>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet">
        <!-- Favicons -->
        <link rel="icon" href="favicon.ico">
        <link rel="apple-touch-icon" href="apple-touch-icon.png">
        <!-- JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="js/scripts.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>
        <script src="js/main.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script src="https://unpkg.com/gridjs/dist/gridjs.umd.js"></script>
        <meta name="description" content="Algocharts is a free, opensource service that that automagically grabs all new&existing Tinyman, Pact.fi and AlgoFi pools, stores price data and create charts for them.">
        <meta name="keywords" content="Algorand ASA">
        <meta name="author" content="design by Awminux">	<title>AlgoCharts - Charts for Algorand assets</title>
</head>
<body>
<div style="display:none">
<option id="pares"><?php echo $dbselector;?></option>
<p id="poolid"><?php echo $liquidez['pool']; ?></p>
<p id="market"><?php echo $market; ?></p>
<p id="chart"><?php echo $chart; ?></p>
</div>

<?php
include 'lib/header.php';
//var_dump($_GET['market']);
?>

	<!-- main content -->
	<main class="main">

<script>
$(document).ready(function() {
    $('.selector').select2();
});
</script>
<div class="container">
<div class="row row--grid">
<div class="col-12 col-md-8">
    <select class="selector" id="ASSET-IN" style="margin-bottom:10px" onchange="change_asset();">
        <option value="330109984">Search asset...</option>
<?php selector_lista($dbpares); ?>
        </select>
<br>
<select class="selector" id="ASSET-OUT">
<option value="0">0 - Algorand</option>
</select>
</div>
<div class="col-12 col-md-4">
<input class="sign__btn" type="button" value="Go to chart" style="margin-top:14px; vertical-align: middle; line-height: 28px" onclick = "Goto()" />
</div>
</div>
</div>
</div>

<?php
include 'lib/ads.php';
?>
		<!-- home -->   
		<div class="home">
		<div class="container">
		<div class="row">
                <div class="col-md-12">
		<div id="table-tokens" class="table-tokens"></div>
                </div></div></div></div>
		<!-- end home -->
	</main>
	<!-- end main content -->

<?php
include 'lib/footer.php'
?>


	<script>
		new gridjs.Grid({
columns:[{name:"Token",sort:{compare:(a,b)=>{a=a.props.content.toLowerCase();let aHTML=new DOMParser().parseFromString(a,"text/html");b=b.props.content.toLowerCase();let bHTML=new DOMParser().parseFromString(b,"text/html");a=aHTML.firstChild.innerHTML;b=bHTML.firstChild.innerHTML;let first=a.localeCompare(b,undefined,{numeric:true});let second=b.localeCompare(a,undefined,{numeric:true});if(second<0){return 1}else if(first<0){return -1}else{return 0}}}},{name:"Last price",sort:{compare:(a,b)=>{let first=parseFloat(a.replace(/[^\d\.]*/g,''));let second=parseFloat(b.replace(/[^\d\.]*/g,''));if(first>second){return 1}else if(second>first){return -1}else{return 0}}}},{name:"USD Price",sort:{compare:(a,b)=>{let first=parseFloat(a.replace(/[^\d\.]*/g,''));let second=parseFloat(b.replace(/[^\d\.]*/g,''));if(first>second){return 1}else if(second>first){return -1}else{return 0}}}},{name:"Liquidity",sort:{compare:(a,b)=>{let first=parseFloat(a.replace(/[^\d\.]*/g,''));let second=parseFloat(b.replace(/[^\d\.]*/g,''));if(first>second){return 1}else if(second>first){return -1}else{return 0}}}},{name:"1h change",sort:{compare:(a,b)=>{a=a.props.content;b=b.props.content;let first=parseFloat(a.replace(/[^-?\d\.]*/g,''));let second=parseFloat(b.replace(/[^-?\d\.]*/g,''));if(first>second){return 1}else if(second>first){return -1}else{return 0}}}},{name:"24h change",sort:{compare:(a,b)=>{a=a.props.content;b=b.props.content;let first=parseFloat(a.replace(/[^-?\d\.]*/g,''));let second=parseFloat(b.replace(/[^-?\d\.]*/g,''));if(first>second){return 1}else if(second>first){return -1}else{return 0}}}},{name:"Market cap",sort:{compare:(a,b)=>{let first=parseFloat(a.replace(/[^\d\.]*/g,''));let second=parseFloat(b.replace(/[^\d\.]*/g,''));if(first>second){return 1}else if(second>first){return -1}else{return 0}}}}],

			search: true,
			sort: true,
			height: '100%',
			width: '100%',
			autoWidth : true,
			pagination: {
				limit: 50
			},
			data: [

<?php
$conn1 = new mysqli($servername, $username, $password, $dbprecios); if ($conn1->connect_error) {die("Connection failed: " . $conn->connect_error);}

$dbprecios_live = $dbselector."precios_live";
$conn3 = new mysqli($servername, $username, $password, $dbprecios_live); if ($conn3->connect_error) {die("Connection failed: " . $conn->connect_error);}

$conn2 = new mysqli($servername, $username, $password, $dbpares); if ($conn3->connect_error) {die("Connection failed: " . $conn->connect_error);}

$arrayLength = count($pools_billboard);
$i = 0;
        while ($i < $arrayLength)
        {
$respre = array();
$sqlT = "SELECT precio FROM (SELECT * FROM ".$pools_billboard[$i]." ORDER BY id DESC LIMIT 196) t2 ORDER BY t2.id ASC";
$resultT = $conn1->query($sqlT);
if ($resultT->num_rows > 0) { while($row = $resultT->fetch_assoc()) { $respre[] = sprintf("%.12f", $row['precio']); } }

$respre_live = array();
$sqlT = "SELECT precio FROM (SELECT * FROM ".$pools_billboard[$i]." ORDER BY id DESC LIMIT 196) t2 ORDER BY t2.id ASC";
$resultT = $conn3->query($sqlT);
if ($resultT->num_rows > 0) { while($row = $resultT->fetch_assoc()) { $respre_live[] = sprintf("%.12f", $row['precio']); } }

$num_asset = substr($pools_billboard[$i], 0, strlen($pools_billboard[$i])-2);
$sqlY = "SELECT * from nombres where asset_id=".$num_asset."";
$resultY = $conn2->query($sqlY);
if ($resultY->num_rows > 0) { while($row = $resultY->fetch_assoc()) { $nombre_asset = $row['nombre']; $decimales = $row['decimales']; $cantidad1 = $row["cantidad"]; $verificado1 = $row['verify']; } }
$cantidad1 = $cantidad1/(1*(10**$decimales));

$sqlU = "SELECT * from liquidez where pool_id='".$pools_billboard[$i]."'";
$resultU = $conn1->query($sqlU);
if ($resultU->num_rows > 0) { while($row = $resultU->fetch_assoc()) { $liquidez = $row['liqa1']; } }

$longitud_array = count($respre);
$longitud_array_live = count($respre_live);

echo "[";
echo 'gridjs.html(`'."<a href=\"chart.php?asset_in=".$num_asset."&amp;asset_out=0&amp;market=".$market."\">".$nombre_asset.$verificado1."</a><br><small>".$num_asset."</small>".'`),';
echo '"'.sprintf("%.8f",$respre[$longitud_array-1])." Ⱥ".'",';
echo '"'.sprintf("%.3f",$respre[$longitud_array-1]*$usd)." USD".'",';
echo '"'.sprintf("%.0f",($liquidez/1000000))."Ⱥ".'",';

if (array_key_exists($longitud_array-97, $respre)) {
if ($respre[$longitud_array-97] <> 0)  {
        if ($longitud_array > 96) { $cambio = ((($respre[array_key_last($respre)]-$respre[$longitud_array-97])/($respre[$longitud_array-97])*100)); } }
        else { $cambio = 0; }
}

if (array_key_exists($longitud_array_live-97, $respre_live)) {
if ($respre_live[$longitud_array_live-97] <> 0)  {
	if ($longitud_array_live > 96) { $cambio1h = ((($respre_live[array_key_last($respre_live)]-$respre_live[$longitud_array_live-97])/$respre_live[$longitud_array_live-97])*100); } }
        else { $cambio1h = 0; }
}


if ($longitud_array > 5) {
if ($cambio1h>0) { echo "gridjs.html(`<span class=green>".sprintf("%.2f",$cambio1h)." %</span>`),"; } else { echo "gridjs.html(`<span class=red>".sprintf("%.2f",$cambio1h)." %</span>`),"; } }
else { echo "gridjs.html(`<span class=red>0 %</span>`),"; }


if ($longitud_array > 96) {
if ($cambio>0) { echo "gridjs.html(`<span class=green>".sprintf("%.2f",$cambio)." %</span>`),"; } else { echo "gridjs.html(`<span class=red>".sprintf("%.2f",$cambio)." %</span>`),"; } }
else { echo "gridjs.html(`<span class=red>0 %</span>`),"; }

if ($longitud_array > 0) { echo '"'.number_format(sprintf("%.0f",($respre[$longitud_array-1]*$cantidad1*$usd)))." USD".'"'."],"; } else { echo '"'."0 USD".'"'."],"; }
            $i++;
        }
echo "],";
$conn1->close();
$conn2->close();
$conn3->close();
?>

			className: {
				table: 'table-body'
			},
			style: {
                    table: {
                    'font-size': '13px'
                    },
                    footer: {
                    'font-size': '13px'
                    }
                },
			language: {
				'search': {
				'placeholder': '🔍 Search asset...'
				},
				'pagination': {
				'previous': '⬅️',
				'next': '➡️',
				'showing': '👓 Displaying',
				'results': () => 'Tokens'
				}
			}
			}).render(document.getElementById("table-tokens"));
	</script>
</body>
</html>
