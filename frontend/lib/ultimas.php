<div>
<h2><small>Last 15 min trades</small></h2>
<p style="display:none" id="poolid"><?php echo $liquidez['pool']; ?></p>
<small>Liquidity pool address: <a href=https://algoexplorer.io/address/<?php echo $liquidez['pool'].">".substr($liquidez['pool'], 0, 15);?>...</a></small>&nbsp&nbsp<button style="color:white" onclick="navigator.clipboard.writeText(document.getElementById('poolid').innerText)">Copy</button>
<table class="table table-striped table-dark bordered">
<?php
$context = stream_context_create(array("http" => array("header" => array(
"accept: application/json",
"X-Algo-API-Token: api-token",
"protocol_version" => 1.1,
))));

$api_url = "https://nodo.algocharts.net/v2/status";

$json_data = file_get_contents($api_url, false, $context);
$response_data = json_decode($json_data, true);
$last_block = $response_data['last-round'];

$api_url = "https://nodo.algocharts.net/v1/account/".$liquidez['pool']."/transactions?firstRound=".($last_block-300)."&lastRound=".$last_block;
$json_data = file_get_contents($api_url, false, $context);
$response_data = json_decode($json_data, true);

$grupos = array();

for ($i = 0; $i <= 200; $i++) {
    if (!isset($response_data['transactions'][$i])) { break; } else {
        if (array_key_exists('payment', $response_data['transactions'][$i])) {
        if(isset($response_data['transactions'][$i]['group'])) { if ($response_data['transactions'][$i]['payment']['amount'] > 2000) { $grupos[$response_data['transactions'][$i]['group']][0]=$response_data['transactions'][$i]['payment']['amount']; } } }
        if(isset($response_data['transactions'][$i]['curxfer']['amt'])) { $grupos[$response_data['transactions'][$i]['group']][$response_data['transactions'][$i]['curxfer']['id']]=$response_data['transactions'][$i]['curxfer']['amt']; 
                $grupos[$response_data['transactions'][$i]['group']]['receptor']=$response_data['transactions'][$i]['curxfer']['rcv'];
                $grupos[$response_data['transactions'][$i]['group']]['pagador']=$response_data['transactions'][$i]['from'];
        }
 } }

$txasset_in = array();
$txasset_out = array();
$receptor = array();
$pagador = array();
$i = 0;
foreach ($grupos as $array_value) {
if (isset($array_value[$assets[0]])) { if (isset($array_value[$assets[1]])) { $txasset_in[$i] = $array_value[$assets[0]]; $txasset_out[$i] = $array_value[$assets[1]]; $receptor[$i] = $array_value['receptor']; $pagador[$i] = $array_value['pagador']; $i++;
} } }


for ($i = array_key_last($txasset_in); $i > 0; $i--) {
$txasset_in[$i] = ($txasset_in[$i]/(1*(10**$datos1->decimales))); $txasset_out[$i] = ($txasset_out[$i]/(1*(10**$datos2->decimales)));
echo "<tr>";
if ($receptor[$i] != $liquidez['pool']) { echo "<td><small class=\"green\">Buy</small></td>"; } else { echo "<td><small class=\"red\">Sell</small></td>"; }
echo "<td><small>".$txasset_in[$i]." ".$datos1->unidad." / "; echo $txasset_out[$i]." ".$datos2->unidad."</small></td>";
if ($receptor[$i] != $liquidez['pool']) { echo "<td><small><a href=https://algoexplorer.io/address/".$receptor[$i].">".substr($receptor[$i], 0, 10)."...</a></small></td>"; }
else { echo "<td><small><a href=https://algoexplorer.io/address/".$pagador[$i].">".substr($pagador[$i], 0, 10)."...</a></small></td>"; }
echo "</tr>";
} ?>
</table>
<br>
</div>
