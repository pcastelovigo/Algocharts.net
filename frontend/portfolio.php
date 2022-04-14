<?php
require 'lib/funciones.php';
$usd = precio_algo();
$cookie_name = "portfolio";
if (isset($_GET['algoaddr'])) $cookie_value = $_GET['algoaddr'];
setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
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
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet">
	<!-- Favicons -->
	<link rel="icon" href="favicon.ico">
	<link rel="apple-touch-icon" href="apple-touch-icon.png">
        <!-- JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="js/scripts.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
        <script src="js/main.js"></script> 
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/@dmester/sffjs@1.17.0/dist/stringformat.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script src="https://unpkg.com/gridjs/dist/gridjs.umd.js"></script>
	<meta name="description" content="Algocharts Portfolio calculator. Insert your Algorand wallet address (or any other) and get info about your portfolio performance, charts and price change over time">
	<meta name="keywords" content="Algorand, ASA, charts, price, portfolio tracker, portfolio calculator">
	<meta name="author" content="design by Awminux">
	<title>AlgoCharts - Portfolio calculator</title>
</head>
<body>
<?php
include 'lib/header2.php'
?>
	<main class="main">

		<div class="container">
			<div class="row row--grid">
				<div class="col-12 col-xl-10 offset-xl-1">
					<div class="article rounded">
						<!-- article content -->
						<div class="article__content">
							<h1 class="roadmap-title">AlgoCharts portfolio calculator</h1>
                            <p>
                                Insert your Algorand address (or any other address) and get data about total monetary value of held assets.
                            </p>

<div class="col-md-8 mx-auto" id="pie">

</div>

		</div><!-- end article content -->
                        <br>

                        <div class="row">

							<div class="col-12 col-md-8">
								<div>
<form action="portfolio.php" method="get">
<?php if(isset($_COOKIE['portfolio'])) { echo "<input class=\"sign__input\" id=\"size\" type=\"text\" name=\"algoaddr\" value=\"".$_COOKIE['portfolio']."\"/>"; } else { echo "<input class=\"sign__input\" id=\"size\" type=\"text\" name=\"algoaddr\" placeholder=\"Algorand Address\" />"; } ?>

								</div>
							</div>

							<div class="col-12 col-md-4">
								<input type="submit" class="sign__btn">
</form>
<br>
<?php if (isset($_GET['algoaddr'])) { echo "<input class=\"sign__input\" type=\"button\" value=\"Don't show no value assets\" style=\"margin-bottom:12px; vertical-align: middle; line-height: 28px\" onclick=\"borrarvacios()\">"; } ?>
</div>
							</div>
                        </div>


                        <div class="container">
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="table-tokens" class="table-tokens"></div>
                                </div>
                            </div>
                        </div>

<script>
    new gridjs.Grid({
columns:[{name:"Token",sort:{compare:(a,b)=>{a=a.props.content.toLowerCase();let aHTML=new DOMParser().parseFromString(a,"text/html");b=b.props.content.toLowerCase();let bHTML=new DOMParser().parseFromString(b,"text/html");a=aHTML.firstChild.innerHTML;b=bHTML.firstChild.innerHTML;let first=a.localeCompare(b,undefined,{numeric:true});let second=b.localeCompare(a,undefined,{numeric:true});if(second<0){return 1}else if(first<0){return -1}else{return 0}}}},{name:"Amount",sort:{compare:(a,b)=>{let first=parseFloat(a.replace(/[^\d\.]*/g,''));let second=parseFloat(b.replace(/[^\d\.]*/g,''));if(first>second){return 1}else if(second>first){return -1}else{return 0}}}},{name:"Last price",sort:{compare:(a,b)=>{let first=parseFloat(a.replace(/[^\d\.]*/g,''));let second=parseFloat(b.replace(/[^\d\.]*/g,''));if(first>second){return 1}else if(second>first){return -1}else{return 0}}}},{name:"USD Value",sort:{compare:(a,b)=>{let first=parseFloat(a.replace(/[^\d\.]*/g,''));let second=parseFloat(b.replace(/[^\d\.]*/g,''));if(first>second){return 1}else if(second>first){return -1}else{return 0}}}},{name:"Total value",sort:{compare:(a,b)=>{let first=parseFloat(a.replace(/[^-?\d\.]*/g,''));let second=parseFloat(b.replace(/[^-?\d\.]*/g,''));if(first>second){return 1}else if(second>first){return -1}else{return 0}}}},{name:"24h change",sort:{compare:(a,b)=>{a=a.props.content;b=b.props.content;let first=parseFloat(a.replace(/[^-?\d\.]*/g,''));let second=parseFloat(b.replace(/[^-?\d\.]*/g,''));if(first>second){return 1}else if(second>first){return -1}else{return 0}}}},{name:"Tinyman",sort:false}],
                search: true,
                sort: true,
                height: '100%',
                width: '100%',
                autoWidth : true,
                pagination: {
                    limit: 100
                },
                data: [

<?php
if (isset($_GET['algoaddr'])) {
$algoaddr = $_GET['algoaddr'];
$servername = "localhost";
$username = "pablo";
$password = "test1";


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


echo "[";
if ($nombre1 != "No asset data") { echo 'gridjs.html(`'."<a href=\"chart.php?asset_in=".$lista_assets[$i]."&amp;asset_out=0\">".$nombre1.$verificado1."</a><br><small>".$lista_assets[$i]."</small>".'`),'; } else { echo 'gridjs.html(`'."<small>".$nombre1."</small><br><small>".$lista_assets[$i]."</small>".'`),'; }
$lista_cantidades[$i] = $lista_cantidades[$i]/(1*(10**$decimales1));
echo '"'.$lista_cantidades[$i]." ".$unidad1.'",';
echo '"'.sprintf("%.6f",$resultado_precios[array_key_last($resultado_precios)])." Ⱥ".'",';
echo '"'.sprintf("%.3f",$resultado_precios[array_key_last($resultado_precios)]*$usd)." USD".'",';
echo '"'.sprintf("%.2f",$lista_cantidades[$i]*$resultado_precios[array_key_last($resultado_precios)]*$usd)." USD".'",';
if ($longitud_array > 94) { $cambio = ((($resultado_precios[array_key_last($resultado_precios)]-$resultado_precios[$longitud_array-95])/$resultado_precios[$longitud_array-95])*100); } else { $cambio = 0; }
if ($cambio > 0 ) { echo 'gridjs.html(`<span class=green>'.sprintf("%.2f",$cambio).' %</span>`),'; } else { echo 'gridjs.html(`<span class=red>'.sprintf("%.2f",$cambio).' %</span>`),'; }
echo 'gridjs.html(`<a href=\"https://app.tinyman.org/#/swap?asset_in='.$lista_assets[$i].'&amp;asset_out=0\">Sell</a>`)'."],";
$total_value = $total_value+($lista_cantidades[$i]*$resultado_precios[array_key_last($resultado_precios)]*$usd);
if (sprintf("%.2f",$lista_cantidades[$i]*$resultado_precios[array_key_last($resultado_precios)]*$usd) > 0) { $your_money[] = (sprintf("%.2f",$lista_cantidades[$i]*$resultado_precios[array_key_last($resultado_precios)]*$usd)); $your_asset[] = htmlspecialchars($nombre1, ENT_QUOTES); }
} } }
if (sizeof($your_money) > 0 ) { $your_money[] = sprintf("%.2f", (( $algorand_en_cuenta/1000000)*$usd)); $your_asset[] = "Algorand"; }
?>

                    ],
                className: {
                    table: 'table-body'
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
                },
                style: {
                    table: {
                    'font-size': '13px'
                    },
                    footer: {
                    'font-size': '13px'
                    }
                }
                }).render(document.getElementById("table-tokens"));
        </script>

    <script>
        var options = {
          series:  [ <?php echo implode(", ", $your_money); ?> ],
          chart: {
          width: 500,
	  height: 600,
          type: 'pie',
        },
        labels:  [ <?php echo "'".implode("', '", $your_asset)."'"; ?> ],
        theme: { mode: 'dark', pallete: 'pallete2' },
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              width: '100%',
	      height: 600,
            },
            legend: {
              position: 'bottom'
            },
          }
        }]
        };

<?php if (sizeof($your_money) > 0 ) { echo " var chart = new ApexCharts(document.querySelector(\"#pie\"), options); chart.render();"; } ?>
      
      
    </script>



                        <div class="container">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-striped table-dark bordered">
<tbody>
<?php
if (isset($_GET['algoaddr'])) {
echo "<tr><td>Algorand in account:</td><td style=\"text-align: right\">".($algorand_en_cuenta/1000000)." Ⱥ</td><td style=\"text-align: right\">".sprintf("%.2f",($algorand_en_cuenta/1000000)*$usd)." USD</td></tr>";
echo "<tr><td>Total token value:</td><td style=\"text-align: right\">".sprintf("%.6f",($total_value/$usd))." Ⱥ</td><td style=\"text-align: right\">".sprintf("%.2f",$total_value)." USD</td></tr>";
$total_value = $total_value+(($algorand_en_cuenta/1000000)*$usd);
echo "<tr><td>Total portfolio value:</td><td></td><td style=\"text-align: right\">".sprintf("%.2f",$total_value)." USD </td></tr>";
$conn1->close();
$conn2->close();
}
?>
                                        </tbody>
                                    </table>	
                                </div>
                            </div>
                        </div>				
                    </div>
				</div>
			</div>
		</div></div>
	</main><!-- end main content -->

<?php
include 'lib/footer.php'
?>
</body>
</html>
