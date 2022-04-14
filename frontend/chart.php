<?php
require 'lib/funciones.php';
$dbprecios = "precios_diario"; $dbpares = "pares"; $market = ""; $dbselector = "";
if (isset($_GET['market'])){ if ($_GET['market'] == "pactfi") { $dbselector = "PACTFI"; $market = "pactfi"; $addliqurl = "https://app.pact.fi/create-pair"; } }
if (isset($_GET['market'])){ if ($_GET['market'] == "algofi") { $dbselector = "ALGOFI"; $market = "algofi"; $addliqurl = "https://app.algofi.org/pool"; } }
if (isset($_GET['chart'])){ $chart = $_GET['chart']; } else { $chart = "15min"; }
include $dbselector.'minute.php';
if ($chart == "1min" || $chart == "1minc") { $dbprecios = "precios_live";}
$dbprecios = $dbselector.$dbprecios;
$assets = validar_entrada($dbprecios); // Lee y hace cosas
if ($chart == "1min" && $assets[2] == "NO") { $chart = "15min"; $dbprecios = $dbselector."precios_diario"; $assets = validar_entrada($dbprecios); }
if ($chart == "1minc" && $assets[2] == "NO") { $chart = "15minc"; $dbprecios = $dbselector."precios_diario"; $assets = validar_entrada($dbprecios); }
if ($assets[2] !== "INV") {
if ( $chart == "1min" ) { if (!in_array($assets[0], $minute)) { $chart = "15min"; $dbprecios = "precios_diario"; $assets[2]="OK"; } }
if ( $chart == "1minc") { if (!in_array($assets[0], $minute)) { $chart = "15minc"; $dbprecios = "precios_diario"; $assets[2]="OK"; } }
}
$dbpares = $dbselector.$dbpares;
$dbliquidez = $dbselector."precios_diario";
$datos1 = nombres($dbpares, $assets[0]); $datos2 = nombres($dbpares, $assets[1]); //Recoge los datos
$info = precios_html($dbprecios, $assets[0], $datos1->cantidad, $assets[1], $datos2->cantidad); // Genera algo de HTML
if ($assets[2] == "INV") { $principal = resultado_precios_inverso($chart, $dbprecios, $assets[1], $assets[0]); } else {  $principal = resultado_precios($chart, $dbprecios, $assets[0], $assets[1]); } //Datos para la gráfica
$liquidez = liquidez($dbliquidez, $assets[0], $datos1->decimales, $assets[1], $datos2->decimales); //Obtiene liquidez
if ($market == "algofi" && ($assets[0]!=="0" && $assets[1]!=="0")) { $liquidez = liquidez($dbliquidez, $assets[1], $datos2->decimales, $assets[0], $datos1->decimales); }
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
	<link href="css/algoswap.css" rel="stylesheet">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet">
	<!-- Favicons -->
	<script src="js/scripts.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
	<script src="https://cdn.jsdelivr.net/npm/@dmester/sffjs@1.17.0/dist/stringformat.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>
	<link rel="icon" href="favicon.ico">
	<link rel="apple-touch-icon" href="apple-touch-icon.png">
	<meta name="description" content=<?php echo '"'.$datos1->nombre." TO ".$datos2->nombre." ".$chart.". AlgoCharts is a free, opensource service that tracks Algorand ASA price data and creates charts for them".'"' ?> >
	<meta name="keywords" content="Algorand, ASA, price, charts">
	<meta name="author" content="design by Awminux">
	<title> <?php echo $dbselector." AlgoCharts"; ?> - <?php if(!$_GET) { echo "Charts for Algorand assets"; } else { echo "".$datos1->nombre." TO ".$datos2->nombre." chart"; } ?> </title>
	<style>.table td, .table th{padding:0px 3px 0px 3px !important; text-align:left;}</style>
</head>
<body onload="change_asset();">
<div style="display:none">
<option id="pares"><?php echo $dbselector;?></option>
<p id="poolid"><?php echo $liquidez['pool']; ?></p>
<p id="market"><?php echo $market; ?></p>
<p id="chart"><?php echo $chart; ?></p>
</div>
<?php include 'lib/header.php'; ?>

<main class="main">

<?php include 'lib/selector.php'; ?>

<?php 
//DEBUG
//echo $assets[2]; 
//echo "img/asa-list/assets/".$datos1->unidad."-".$assets[0]."/icon.png"
?>

<div class="container">
        <div class="row">
                <div class="col-12 col-xl-8">
                        <div class="asset__item">
                        <h1 style="color:white; font-size:2rem">
			<?php
			if ( $assets[2] !== "NO" ) {echo ucfirst($market)."&nbsp;".$datos1->nombre.$datos1->verificado." TO ".$datos2->nombre.$datos2->verificado." ".$chart." chart</h1>"; }
			if ( $assets[2] == "NO" ) {echo "</h1><div style=\"min-height:615px;\"><p>No ".$datos1->nombre.$datos1->verificado." liquidity on ".ucfirst($market)."<a href=".$addliqurl."><br>Link to ".ucfirst($market)." pool🔗.</a></p></div>"; echo "<div id=\"grafica\" style=\"min-height:615px;display:none\">"; } else { echo "<div id=\"grafica\" style=\"min-height:615px;\">"; }
			?>
</div>
<?php if ($market == "") { include 'lib/ultimas.php'; } ?>

<?php include 'lib/radio.php'; ?>

	</div>
</div>
<!-- sidebar -->
<div class="col-12 col-xl-4">
	<div class="asset__info">
	<ul class="asset__authors">
	<li>
		<div class="asset__author">
<?php
if (file_exists("img/asa-list/assets/".$datos1->unidad."-".$assets[0]."/icon.png")) { echo "<img src=\"img/asa-list/assets/".$datos1->unidad."-".$assets[0]."/icon.png\" alt=\"\">"; } else {echo "<img src=\"img/asset-logo-default.webp\" alt=\"\">"; }
?>
			<div class="asset__desc">
			<span class="token-name"><?php echo $datos1->nombre; ?></span>
			<br>
			<span class="token-desc"><?php echo $datos1->nombre; ?> - ASA ID : <?php echo $assets[0]; ?></span>
			</div>
		</div>
	</li>
	</ul>

	<!-- tabs -->
	<ul class="nav nav-tabs asset__tabs" role="tablist">
	<li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#tab-1" role="tab" aria-controls="tab-1" aria-selected="true"><?php echo $datos1->nombre; ?></a></li>
	<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-2" role="tab" aria-controls="tab-2" aria-selected="false"><?php echo $datos2->nombre; ?></a></li>
	<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-3" role="tab" aria-controls="tab-3" aria-selected="false">Calculator</a></li>
	</ul>

	<div class="tab-content">
		<div class="tab-pane fade show active" id="tab-1" role="tabpanel">
			<div class="asset__actions" id="asset__actions">
				<div class="asset__action">
<?php 
$html_cambio = "<p>24 hour change:</p><div></div>";
if ($chart == "1mon") { $html_cambio = "<p>2 weeks change:</p><div></div>"; }
if ($chart == "1year") { $html_cambio = "<p>6 months change:</p><div></div>"; }
if ($chart == "1min") { $html_cambio = "<p>1 hour change:</p><div></div>"; }
if ($chart == "1minc") { $html_cambio = "<p>1 hour change:</p><div></div>"; }
echo $html_cambio;

$longitud_array = count($principal['resultado_precios']);
if ($longitud_array > 96) { $cambio = ((($principal['resultado_precios'][array_key_last($principal['resultado_precios'])]-$principal['resultado_precios'][$longitud_array-97])/$principal['resultado_precios'][$longitud_array-97])*100);
if ($cambio>0) { echo "<span class=\"green\">".sprintf("%.2f",$cambio)." %</span>"; } else {echo "<span class=\"red\">".sprintf("%.2f",$cambio)." %</span>"; } } else { echo "<span class=\"grey\">No enough data</span>";} ?>
				</div>
				<div class="asset__action"><p>Last value:</p><div></div>
				<span class="grey"><?php echo sprintf("%.6f",$principal['valor'])." ".$datos2->nombre ?></span>
				</div>
				<div class="asset__action">
				<?php echo $info['infoprecio_assetin'] ?>
				</div>
				<div class="asset__action"><p>Market cap:</p><div></div>
				<span class="grey"> <?php echo $info['marketcap_assetin'] ?></span>
				</div>
				<div class="asset__action"><p><?php echo ucfirst($market)."&nbsp;"; ?>Liquidity in pool:</p><div></div>
				<span class="grey"><?php echo number_format(sprintf("%.2f",$liquidez['liqa2']))." ".$datos1->unidad.", ".number_format(sprintf("%.2f",$liquidez['liqa1']))." ".$datos2->unidad.""; ?></span>
				</div>
				<div class="asset__action"><p><?php echo ucfirst($market)."&nbsp;"; ?>15 min liquidity change:</p><div></div>
				<span class="grey"><?php echo number_format(sprintf("%.0f",($liquidez['liqa1'] - $liquidez['vola1'])))." ".$datos2->unidad.", ".number_format(sprintf("%.0f",($liquidez['liqa2'] - $liquidez['vola2'])))." ".$datos1->unidad ?></span>
				</div>
				<div class="asset__action"><p>Unit name</p><div></div>
				<span class="grey"><?php echo $datos1->nombre; ?></span>
				</div>
				<div class="asset__action"><p>Total supply:</p><div></div>
				<span class="grey"><?php echo number_format($datos1->cantidad); ?></span>
				</div>
				<div class="asset__action"><p>Decimals:</p><div></div>
				<span class="grey"><?php echo $datos1->decimales; ?></span>
				</div>
				<div class="asset__action">
				<?php if (!filter_var($datos1->url, FILTER_VALIDATE_URL) === false) { echo "<p>URL:</p><div></div><span><a href=".$datos1->url." target=\"_blank\">".$datos1->url."</a></span>"; } ?> </div>
			        <div class="asset__action">
				<?php if (!empty($datos1->telegram)) { echo "<p>Telegram:</p><div></div><span><a href=".$datos1->telegram." target=\"_blank\">".$datos1->telegram."</a></span>"; } ?> </div>
				</div>
			</div>

			<div class="tab-pane fade" id="tab-2" role="tabpanel">
				<div class="asset__actions">
					<div class="asset__action">
					<?php echo $info['infoprecio_assetout'] ?>
					</div>
					<div class="asset__action">
					<p>Market cap:</p><div></div>
					<span class="grey"> <?php echo $info['marketcap_assetout'] ?></span>
					</div>
					<div class="asset__action">
					<p><?php echo ucfirst($market)."&nbsp;"; ?>Liquidity in pool:</p><div></div>
					<span class="grey"><?php echo number_format(sprintf("%.2f",$liquidez['liqa1']))." ".$datos2->unidad.", ".number_format(sprintf("%.2f",$liquidez['liqa2']))." ".$datos1->unidad.""; ?></span>
					</div>
					<div class="asset__action">
					<p><?php echo ucfirst($market)."&nbsp;"; ?>15 min liquidity change:</p><div></div>
					<span class="grey"><?php echo number_format(sprintf("%.0f",($liquidez['liqa2'] - $liquidez['vola2'])))." ".$datos1->unidad.", ".number_format(sprintf("%.0f",($liquidez['liqa1'] - $liquidez['vola1'])))." ".$datos2->unidad ?></span>
					</div>
					<div class="asset__action">
					<p>Unit name</p><div></div>
					<span class="grey"><?php echo $datos2->nombre; ?></span>
					</div>
					<div class="asset__action">
					<p>Total supply:</p><div></div>
					<span class="grey"><?php echo number_format($datos2->cantidad); ?></span>
					</div>
					<div class="asset__action">
					<p>Decimals:</p><div></div>
					<span class="grey"><?php echo $datos2->decimales; ?></span>
					</div>
					<div class="asset__action">
					<?php if (!filter_var($datos2->url, FILTER_VALIDATE_URL) === false) { echo "<p>URL:</p><div></div><span><a href=".$datos2->url." target=\"_blank\">".$datos2->url."</a></span>"; } ?> </div>
					<div class="asset__action">
					<?php if (!empty($datos2->telegram)) { echo "<p>Telegram:</p><div></div><span><a href=".$datos2->telegram." target=\"_blank\">".$datos2->telegram."</a></span>"; } ?>
					</div>
				</div>
			</div>

		<div class="tab-pane fade" id="tab-3" role="tabpanel">
			<div class="sign__group">
				<div class="col-md-12">
					<div class="form-control-wrap">
						<div class="form-text-hint">
						<span class="overline-title">Usd</span>
						</div>
<script>
function dividir(valor){
var last_value = <?php echo $info['last_value_raw']; ?>;
let resultado = valor / last_value;
document.getElementById("calcasa").value = resultado.toFixed(6);
}
</script>
<script>
function multiplicar(valor){
var last_value = <?php echo $info['last_value_raw']; ?>;
let resultado = last_value * valor;
document.getElementById("calcdolar").value = resultado.toFixed(6);
}
</script>
					<input type="number" class="form-control" id="calcdolar" placeholder="Enter USD Value" oninput="dividir(this.value)">
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-control-wrap">
						<div class="form-text-hint">
						<span class="overline-title"><?php echo $datos1->nombre; ?></span>
						</div>
					<input type="number" class="form-control" id="calcasa" placeholder=<?php echo "Enter ".$datos1->nombre."Value"; ?> oninput="multiplicar(this.value)">
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="swap-root"></div>
</div>
</div>
</div>
<?php include 'lib/ads.php'; ?>
</div>
</main>
<?php
if ($chart =="15minc"||$chart=="1minc") { include 'lib/libcandle.php'; } else { include 'lib/libline.php'; }
include 'lib/footer.php';
?>
</body>
</html>
