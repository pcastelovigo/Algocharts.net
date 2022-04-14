<?php
require "../lib/funciones.php";
function api() {
require "billboard.php";
response(200,"OK",$listado_billboard);
}
$max_calls_limit = 50;
$endpoint = "AV3";
include '../lib/apikeys.php';






