<?php
include 'lib/funciones.php';
$servername = "localhost";
$username = "pablo";
$password = "test1";
$dbname = "precios_diario";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}
$sqlR = "select * from liquidez where pool_id REGEXP '_0' AND liqa1 < 1000000";
$resultR = $conn->query($sqlR);
$sql4 = "SELECT * FROM (SELECT * FROM rugs ORDER BY id DESC LIMIT 365) t2 ORDER BY t2.id ASC";
$result4 = $conn->query($sql4);
$conn->close();
$resultado_rugs = array();
$resultado_fechas = array();
if ($result4->num_rows > 0) { while ($row = $result4->fetch_assoc()) { $resultado_fechas[] = $row['fecha']; $resultado_rugs[] = $row['cantidad']; }  }

$conn->close();

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
        <script src="https://cdn.jsdelivr.net/npm/@dmester/sffjs@1.17.0/dist/stringformat.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script src="https://unpkg.com/gridjs/dist/gridjs.umd.js"></script>
        <meta name="description" content="Algocharts  Tinyman pool list.">
        <meta name="keywords" content="Algorand ASA">
        <meta name="author" content="design by Awminux">        <title>AlgoCharts - Tinyman illiquid pools statistics </title>
</head>
<body>
<?php
include 'lib/header2.php';
?>

        <main class="main">
                <div class="home">
                <div class="container">
                <div class="row">
                <div class="col-md-12">
		<h1 style="color:white; font-size:2rem">Tinyman illiquid pools statistics </h1>
		<div id="grafica"></div>
		<div class="article">
		<div class="article__content">
<p> 24h change: <?php echo ($resultado_rugs[array_key_last($resultado_rugs)]-$resultado_rugs[array_key_last($resultado_rugs)-1]); ?> pools</p>
		</div></div>
                <div id="wrapper" class="table-tokens"></div>

<script>
new gridjs.Grid({
columns: ["Token", "ASA ID"],
search: true,
sort: true,
height: '100%',
width: '100%',
autoWidth : true,
pagination: { className: 'paginacion', limit: 500},
  data: [
<?php
$counter = 0;
$dbname = "pares";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}
if ($resultR->num_rows > 0) { while ($row = mysqli_fetch_array($resultR)) { $asa_id = substr($row['pool_id'], 0, -2); 
$sql3 = "select nombre from nombres where asset_id =".$asa_id."";
$result3 = $conn->query($sql3); $row = mysqli_fetch_array($result3);
echo "[".'"'.$row['nombre'].'"'." ,".$asa_id."],";
$counter = $counter+1; }}
$conn->close();
?>  ],
className: {table: 'table-body'},style: {table: {'font-size': '13px'},footer: {'font-size': '13px'}},
language: {'search': {'placeholder': '🔍 Search asset...'},'pagination': {'previous': '⬅️','next': '➡️','showing': '👓 Displaying','results': () => 'Out of liquidity tokens'}}
}).render(document.getElementById("wrapper"));

</script>
</div></div></div></div>
<script>
var options = {
  chart: {
    type: 'line',
    height: 600
  },
  series: [{
    name: "Out of liquidity pools",
    data: [<?php echo implode(", ", $resultado_rugs); ?>]
  }],
  xaxis: {
    categories: [<?php echo "'".implode("', '", $resultado_fechas)."',"; ?>]
  },
  yaxis: {
     tickAmount: 6,
     title: { text: "Amount of Tinyman illiquid pools over time",  },
     max: Math.max(<?php echo implode(", ", $resultado_rugs); ?>)*1.1  ,
     min: Math.min(<?php echo implode(", ", $resultado_rugs); ?>)*0.9  ,
  },
  stroke: {
    curve: 'smooth',
    width: 2
  },
theme: { mode: 'dark' },
}

var chart = new ApexCharts(document.querySelector("#grafica"), options);

chart.render();
</script>
<?php
include 'lib/footer.php';
?>
</body>
</html>
