<?php
define('servername', "localhost");
define('username', "pablo");
define('password', "test1");


class datosasa
{
public $nombre = 'No data';
public $unidad = 'units';
public $cantidad = 1;
public $decimales = 6;
public $url = 'No data';
public $verificado = '';
public $telegram;
}

function precio_algo()
{
	$conn = new mysqli(servername, username, password, "precios_live"); if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}
	$sqlA = "SELECT precio FROM 31566704_0 ORDER BY id DESC LIMIT 1";
	$resultA = $conn->query($sqlA);
	if ($resultA->num_rows > 0) { while ($row = $resultA->fetch_assoc()) { $usd = $row["precio"]; } }
	$usd = (1/$usd);
	$conn->close();
	return $usd;
}

function response($status,$status_message,$data)
{
        header("HTTP/2 ".$status);
        header('Access-Control-Allow-Origin: *');
        header("Content-Type:application/json");
        $response['status']=$status;
        $response['status_message']=$status_message;
        $response['data']=$data;

        $json_response = json_encode($response);
        echo $json_response;
}


function nombres($dbpares, $asset)
{
	$conn = new mysqli(servername, username, password, $dbpares); if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}
	$sql = "SELECT * FROM nombres where asset_id=".$asset."";
	$result = $conn->query($sql);
	if ($result ==! false && $result->num_rows > 0) { while($row = $result->fetch_assoc()) { $datosasa = new datosasa();
	$datosasa->nombre = $row["nombre"]; $datosasa->unidad = $row["unidad"]; $datosasa->cantidad = $row["cantidad"]; $datosasa->decimales = $row["decimales"]; $datosasa->url = $row["url"]; $datosasa->verificado = $row["verify"]; $datosasa->telegram = $row['telegram']; } 
	} else { $conn = new mysqli(servername, username, password, "pares"); $result = $conn->query($sql);
	if ($result ==! false && $result->num_rows > 0) { while($row = $result->fetch_assoc()) { $datosasa = new datosasa();
        $datosasa->nombre = $row["nombre"]; $datosasa->unidad = $row["unidad"]; $datosasa->cantidad = $row["cantidad"]; $datosasa->decimales = $row["decimales"]; $datosasa->url = $row["url"]; $datosasa->verificado = $row["verify"]; $datosasa->telegram = $row['telegram']; } } }

	$datosasa->cantidad = $datosasa->cantidad/(1*(10**$datosasa->decimales));
	$conn->close();
	return $datosasa;
}


function selector_lista($dbpares)
{
	$conn = new mysqli(servername, username, password, $dbpares); if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}
	$sql3 = "select asset_id, nombre, unidad, verify from pares.nombres where asset_id != 0 AND CONCAT(asset_id, '_0') NOT IN (select pool_id from precios_diario.liquidez where liqa1 < 1000000);";
	$result3 = $conn->query($sql3);
	$conn->close();
	if ($result3->num_rows > 0) { while ($row = mysqli_fetch_array($result3)) { echo "<option value=" . $row['asset_id'] . ">" . $row['asset_id'] ." - $".$row['unidad']." - ". $row['nombre'].$row['verify']. "</option>"; } }
}

function validar_entrada($dbname) 
{
	$asset_in = $_GET['asset_in'];
	$asset_out = $_GET['asset_out'];
	if(!isset($asset_out)) { $asset_out = "0"; }
	if ( filter_var($asset_in, FILTER_VALIDATE_INT) === false ) { exit(); }
	if ( filter_var($asset_out, FILTER_VALIDATE_INT) === false ) { exit(); }
	$conn = new mysqli(servername, username, password, $dbname); if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}

	$result = $conn->query("SELECT * FROM ".$asset_in."_".$asset_out." LIMIT 1");
    	if($result->num_rows > 0) { return array ($asset_in, $asset_out, "OK"); 
	} else { 
	$result = $conn->query("SELECT * FROM ".$asset_out."_".$asset_in." LIMIT 1");
	if($result->num_rows > 0) { return array ($asset_in, $asset_out, "INV"); } else { return array ($asset_in, $asset_out, "NO"); } }
	$conn->close();

}

function precios_html($dbname, $asset_in, $cantidad1, $asset_out, $cantidad2)
{
	$usd = precio_algo();
	if ($asset_in != "0") {
	$conn = new mysqli(servername, username, password, $dbname); if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}
	$sqlB = "SELECT precio FROM ".$asset_in."_0 ORDER BY id DESC LIMIT 1";
	$resultB = $conn->query($sqlB);
	if ($resultB->num_rows > 0) { while($row = $resultB->fetch_assoc()) { $precio_assetin = $row["precio"];
	$infoprecio_assetin = "<p>Last USD value:</p><div></div><span class=\"grey\">".sprintf("%.3f",$precio_assetin*$usd)." USD</span>"; }
	} else { $infoprecio_assetin = "<p>Token USD value:</p><div></div><span class=\"grey\">No data</span>"; }
	} else { $infoprecio_assetin = "<p>Algorand USD value:</p><div></div><span class=\"grey\">".sprintf("%.3f", $usd)." USD</span>"; $precio_assetin = 1; }

	$last_value_raw = ($precio_assetin*$usd);
	$last_value = sprintf("%.3f",$precio_assetin*$usd);

	if ($infoprecio_assetin != "<p>Token USD value:</p><div></div><span class=\"grey\">No data</span>") { $marketcap_assetin = number_format(sprintf("%.2f",($precio_assetin*$usd*$cantidad1)))." USD"; 
	} else { $marketcap_assetin = "No data"; }

	if ($asset_out != "0") {
	$conn = new mysqli(servername, username, password, $dbname); if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}
	$sqlC = "SELECT precio FROM ".$asset_out."_0 ORDER BY id DESC LIMIT 1";
	$resultC = $conn->query($sqlC);
	if ($resultC->num_rows > 0) { while($row = $resultC->fetch_assoc()) { $precio_assetout = $row["precio"];
	$infoprecio_assetout = "<p>Last USD value:</p><div></div><span class=\"grey\">".sprintf("%.3f",$precio_assetout*$usd)." USD</span>"; }
	} else { $infoprecio_assetout= "<p>Token USD value:</p><div></div><span class=\"grey\">No data</span>"; }
	} else { $infoprecio_assetout = "<p>Algorand USD value:</p><div></div><span class=\"grey\">".sprintf("%.3f", $usd)." USD</span>"; $precio_assetout = 1; }

	if ($infoprecio_assetout != "<p>Token USD value:</p><div></div><span class=\"grey\">No data</span>") { $marketcap_assetout = number_format(sprintf("%.2f",($precio_assetout*$usd*$cantidad2)))." USD"; 
	} else { $marketcap_assetout = "No data"; }
	$conn->close();
	return array ("infoprecio_assetin" => $infoprecio_assetin, "marketcap_assetin" => $marketcap_assetin, "infoprecio_assetout" => $infoprecio_assetout, "marketcap_assetout" => $marketcap_assetout, "last_value" => $last_value, "last_value_raw" => $last_value_raw);
}

function resultado_precios($frame, $dbname, $asset_in, $asset_out)
{
	$conn = new mysqli(servername, username, password, $dbname);
	if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}
	$sqlA = "SELECT * FROM (SELECT * FROM ".$asset_in."_".$asset_out." ORDER BY id DESC LIMIT 196) t2 ORDER BY t2.id ASC";
	if ($frame == "1mon") { $sqlA = "SELECT * FROM (SELECT * FROM ".$asset_in."_".$asset_out." WHERE id % 14 = 0 ORDER BY id DESC LIMIT 195) t2 ORDER BY t2.id ASC"; }
	if ($frame == "1year") { $sqlA = "SELECT * FROM (SELECT * FROM ".$asset_in."_".$asset_out." WHERE id % 182 = 0 ORDER BY id DESC LIMIT 195) t2 ORDER BY t2.id ASC"; }
	$sqlB = "SELECT * FROM ".$asset_in."_".$asset_out." ORDER BY id DESC LIMIT 1";
	$resultA = $conn->query($sqlA);
	if ($frame == "1mon" || $frame == "1year" ) { $resultB = $conn->query($sqlB); }
	$conn->close();
	$resultado_precios = array();
	$resultado_fechas = array();
	if ($resultA->num_rows > 0) { while ($row = $resultA->fetch_assoc()) { $resultado_precios[] = sprintf("%.8f", $row['precio']); $resultado_fechas[] = $row['fecha']; }  }
	if ($frame == "1mon" || $frame == "1year" ) { if ($resultB->num_rows > 0) { while ($row = $resultB->fetch_assoc()) { 
	$valor = $row['precio']; array_push($resultado_precios, sprintf("%.8f",$valor)); $ultima_fecha = $row['fecha']; array_push($resultado_fechas, $ultima_fecha); } } } else { $valor = $resultado_precios[array_key_last($resultado_precios)]; }

	return array("resultado_precios" => $resultado_precios, "resultado_fechas" => $resultado_fechas, "valor" => $valor);
}
function resultado_precios_inverso($frame, $dbname, $asset_in, $asset_out)
{
        $conn = new mysqli(servername, username, password, $dbname);
        if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}
        $sqlA = "SELECT * FROM (SELECT * FROM ".$asset_in."_".$asset_out." ORDER BY id DESC LIMIT 196) t2 ORDER BY t2.id ASC";
        if ($frame == "1mon") { $sqlA = "SELECT * FROM (SELECT * FROM ".$asset_in."_".$asset_out." WHERE id % 14 = 0 ORDER BY id DESC LIMIT 195) t2 ORDER BY t2.id ASC"; }
        if ($frame == "1year") { $sqlA = "SELECT * FROM (SELECT * FROM ".$asset_in."_".$asset_out." WHERE id % 182 = 0 ORDER BY id DESC LIMIT 195) t2 ORDER BY t2.id ASC"; }
        $sqlB = "SELECT * FROM ".$asset_in."_".$asset_out." ORDER BY id DESC LIMIT 1";
        $resultA = $conn->query($sqlA);
        if ($frame == "1mon" || $frame == "1year" ) { $resultB = $conn->query($sqlB); }
        $conn->close();
        $resultado_precios = array();
        $resultado_fechas = array();
        if ($resultA->num_rows > 0) { while ($row = $resultA->fetch_assoc()) { if ($row['precio']>0) { $resultado_precios[] = sprintf("%.8f", (1/$row['precio'])); $resultado_fechas[] = $row['fecha']; } else { $resultado_precios[] = 0; $resultado_fechas[] = $row['fecha']; } } }
        if ($frame == "1mon" || $frame == "1year" ) { if ($resultB->num_rows > 0) { while ($row = $resultB->fetch_assoc()) { 
        $valor = $row['precio']; array_push($resultado_precios, sprintf("%.8f",(1/$valor))); $ultima_fecha = $row['fecha']; array_push($resultado_fechas, $ultima_fecha); } } } else { $valor = (1/$resultado_precios[array_key_last($resultado_precios)]); }

        return array("resultado_precios" => $resultado_precios, "resultado_fechas" => $resultado_fechas, "valor" => $valor);
}
function liquidez($dbname, $asset_in, $decimales1, $asset_out, $decimales2)
{
	//La base de datos está invertida
	$conn = new mysqli(servername, username, password, $dbname);
	if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}
	$result = $conn->query("SELECT * FROM liquidez WHERE pool_id='".$asset_in."_".$asset_out."'");
        if($result->num_rows > 0) { while ($row = $result->fetch_assoc()) { $liqa1 = $row['liqa1']; $liqa2 = $row['liqa2']; $vola1 = $row['vola1']; $vola2 = $row['vola2']; $pool = $row['pool'];
	$liqa2 = $liqa2/(1*(10**$decimales1)); $liqa1 = $liqa1/(1*(10**$decimales2)); $vola2 = $vola2/(1*(10**$decimales1)); $vola1 = $vola1/(1*(10**$decimales2));
	return array("liqa1" => $liqa1, "liqa2" => $liqa2, "vola1" => $vola1, "vola2" => $vola2, "pool" => $pool);  } }
        else { 
        $result = $conn->query("SELECT * FROM liquidez WHERE pool_id='".$asset_out."_".$asset_in."'");
        if($result->num_rows > 0) { while ($row = $result->fetch_assoc()) { $liqa2 = $row['liqa1']; $liqa1 = $row['liqa2']; $vola2 = $row['vola1']; $vola1 = $row['vola2']; $pool = $row['pool'];
        $liqa2 = $liqa2/(1*(10**$decimales1)); $liqa1 = $liqa1/(1*(10**$decimales2)); $vola2 = $vola2/(1*(10**$decimales1)); $vola1 = $vola1/(1*(10**$decimales2));
        return array("liqa1" => $liqa1, "liqa2" => $liqa2, "vola1" => $vola1, "vola2" => $vola2, "pool" => $pool); } }
        else { return array("liqa1" => $liqa1, "liqa2" => $liqa2, "vola1" => $vola1, "vola2" => $vola2, "pool" => $pool); } }
        $conn->close();
}

function api_obtener_precio1($asset_in, $asset_out, $dbname)
{
        $servername = "localhost"; $username = "pablo"; $password = "test1"; // $dbname = "precios_diario";
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}
        $result = $conn->query("SELECT precio FROM ".$asset_in."_".$asset_out." ORDER BY id DESC LIMIT 1");
        if($result->num_rows > 0) { while ($row = $result->fetch_assoc()) { return $row['precio']; } } else {
        $result = $conn->query("SELECT precio FROM ".$asset_out."_".$asset_in." ORDER BY id DESC LIMIT 1");
	if($result->num_rows > 0) { while ($row = $result->fetch_assoc()) { return (1/$row['precio']); } } else { return Null; } }
        $conn->close();
}

function api_obtener_precio2($asset_in, $asset_out)
{
	$inverso = 0;
	$servername = "localhost"; $username = "pablo"; $password = "test1"; $dbname = "precios_diario";
	$conn = new mysqli($servername, $username, $password, $dbname); if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}
	$usd = precio_algo();
	$sqlL = "SELECT precio FROM (SELECT * FROM ".$asset_in."_".$asset_out." ORDER BY id DESC LIMIT 196) t2 ORDER BY t2.id ASC";
	$resultado_precios = array(); $resultL = $conn->query($sqlL);
	if ($resultL->num_rows > 0) { while ($row = $resultL->fetch_assoc()) { $resultado_precios[] = $row['precio']; } 
	} else { 
	$sqlL = "SELECT precio FROM (SELECT * FROM ".$asset_out."_".$asset_in." ORDER BY id DESC LIMIT 196) t2 ORDER BY t2.id ASC"; 
	$resultL = $conn->query($sqlL); if ($resultL->num_rows > 0) { while ($row = $resultL->fetch_assoc()) { $resultado_precios[] = 1/$row['precio']; $inverso = 1; } } }
	$conn->close();
	$dbname = "pares";
	$conn = new mysqli($servername, $username, $password, $dbname);
	$sqlJ = "SELECT cantidad,decimales FROM nombres where asset_id='".$asset_in."'";
	$resultJ = $conn->query($sqlJ);
	if ($resultJ->num_rows > 0) { while($row = $resultJ->fetch_assoc()) { $cantidad1 = $row["cantidad"]; $decimales1 = $row["decimales"]; } }
	$cantidad1 = $cantidad1/(1*(10**$decimales1));
	if (isset($resultado_precios[195])) { $cambio = ((($resultado_precios[195]-$resultado_precios[99])/$resultado_precios[99])*100); } else { $cambio = "0"; };
	$conn->close();
	if ($inverso == 0) { $usdv = ($resultado_precios[195]*$usd);} else { $usdv = (1/$resultado_precios[195])*$usd; }
	return array (floatval($resultado_precios[195]), floatval($cambio), floatval($usdv), floatval($usd));
}

?>
