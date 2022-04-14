<?php
require "../../lib/funciones.php";
function api(){
if(isset($_GET['asset_in'])) {
        $par = $_GET['asset_in'];
        $dbselector = "";
        if (isset($_GET['market'])){ if ($_GET['market'] == "pactfi") { $dbselector = "PACTFI"; $market = "pactfi"; } }
        if (isset($_GET['market'])){ if ($_GET['market'] == "algofi") { $dbselector = "ALGOFI"; $market = "algofi"; } }
        if (isset($_GET['chart'])){ $chart = $_GET['chart']; } else { $chart = "15min"; }
        $dbprecios = $dbselector."precios_diario";
        $host= "localhost"; $username= "pablo"; $password = "test1";
        $db = new mysqli($host,$username,$password,$dbselector."pares"); if($db->connect_error) { die("connection failed:". $db->connect_error); }
        if ( filter_var($par, FILTER_VALIDATE_INT) === false ) { exit(); }
        $sql= "select assetout, nombre, verify from pares where assetin='$par'";
        $result = $db->query($sql); $resultado = array();
        if ($result ==! false && $result->num_rows > 0) {
        while($res = $result->fetch_assoc()){ $resultado[] = $res['assetout']; }
        response(200,"OK", $resultado); } else { response(404,"Not Found", NULL); }
	} else { response(400,"Invalid Request",NULL); }
}
$max_calls_limit  = 200;
$endpoint = "PAR";
include '../../lib/apikeys.php';


