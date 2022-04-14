<?php
require "../lib/funciones.php";
function api() {
if(isset($_GET['asset_out'])) { $asset_out = $_GET['asset_out']; } else { $asset_out="0"; }
if(isset($_GET['asset_out'])) { if(filter_var($_GET['asset_out'], FILTER_VALIDATE_INT) === false) { response(400,"Invalid Request",NULL); exit(); } }

if(isset($_GET['asset_in'])) { 
if(filter_var($_GET['asset_in'], FILTER_VALIDATE_INT) === false) { response(400,"Invalid Request",NULL); exit(); }
$asset_in=$_GET['asset_in']; $price = api_obtener_precio2($asset_in, $asset_out); 
        if(empty($price)){ response(404,"Not Found",NULL); }
        else { response(200,"Found",$price); }
} else { response(400,"Invalid Request",NULL); }
}
$max_calls_limit = 2000;
$endpoint = "AV2";
include '../lib/apikeys.php';
