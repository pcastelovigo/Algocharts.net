<?php
include '../../lib/funciones.php';
$asset_out="0";
$asset_in="226701642";
$precio_yieldly = api_obtener_precio1("226701642", "0", "precios_live");
$usd=precio_algo();
response(200, "OK", ($precio_yieldly*$usd));





