<?php
require "../../lib/funciones.php";

function api() {
if(isset($_GET['asset_in'])) {
        $dbselector = "";
        if (isset($_GET['market'])){ if ($_GET['market'] == "pactfi") { $dbselector = "PACTFI"; $market = "pactfi"; } }
        if (isset($_GET['market'])){ if ($_GET['market'] == "algofi") { $dbselector = "ALGOFI"; $market = "algofi"; } }
        if (isset($_GET['chart'])){ $chart = $_GET['chart']; } else { $chart = "15min"; }
        $dbprecios = $dbselector."precios_diario";
        $assets = validar_entrada($dbprecios);
        if ($assets[2] == "NO") { response(404,"Not Found",NULL); } else {
        if ($assets[2] == "INV") { $principal = resultado_precios_inverso($chart, $dbprecios, $assets[1], $assets[0]); } else {  $principal = resultado_precios($chart, $dbprecios, $assets[0], $assets[1]); }
        if (isset($_GET['dates'])){ response(200,"Found",$principal); } else { response(200,"Found",$principal['resultado_precios']); }
        }
        } else { response(400,"Invalid Request",NULL); }
}
$endpoint = "HIS";
$max_calls_limit  = 200;
include '../../lib/apikeys.php';
