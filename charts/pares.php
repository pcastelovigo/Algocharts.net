<?php
$host= "localhost";
$username= "pablo";
$password = "test1";
$db_name = "pares";
$db = new mysqli($host,$username,$password,$db_name);

if($db->connect_error) { die("connection failed:". $db->connect_error); }

$par = $_POST['pares'];
$sql= "select assetout, nombre, verify from pares where assetin='$par' and assetout > 0";
$query = $db->query($sql);
echo '<option value="ASSET-OUT">0 - Algorand</option>';
while($res = $query->fetch_assoc()){
echo '<option value="'.$res['assetout'].'">'.$res['assetout']." - ".$res['nombre'].$res['verify'].'</option>';
}
?>
