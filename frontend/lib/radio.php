        <div class="row">
        <div class="col">
        <div class="col-xs-6 btn-group-vertical btn-group-toggle" data-toggle="buttons">
        <?php if ($assets[1] == "0") { if (in_array($assets[0], $minute)) {  if ($chart !== "1min") { echo "<label class=\"btn btn-secondary\"><a href=".'"'."chart.php?market=".$market."&chart=1min&asset_in=".$assets[0]."&amp;asset_out=".$assets[1].'"'."><input type=\"radio\" name=\"options\" id=\"option1\" autocomplete=\"off\">1min chart</label></a>"; }
	else { echo "<label class=\"btn btn-secondary active\"><a href=".'"'."chart.php?market=".$market."&chart=1min&asset_in=".$assets[0]."&amp;asset_out=".$assets[1].'"'."><input type=\"radio\" name=\"options\" id=\"option1\" autocomplete=\"off\" checked>1min chart</label></a>"; } } }
	
	if ($assets[0] == "0" && in_array($assets[1], $minute)) { echo "<label class=\"btn btn-secondary\"><a href=".'"'."chart.php?market=".$market."&chart=1min&asset_in=".$assets[0]."&amp;asset_out=".$assets[1].'"'."><input type=\"radio\" name=\"options\" id=\"option1\" autocomplete=\"off\">1min chart</label></a>"; }

	if ($assets[1] == "0") { if (in_array($assets[0], $minute)) { if ($chart !== "1minc") { echo "<label class=\"btn btn-secondary\"><a href=".'"'."chart.php?market=".$market."&amp;chart=1minc&asset_in=".$assets[0]."&amp;asset_out=".$assets[1].'"'."><input type=\"radio\" name=\"options\" id=\"option1\" autocomplete=\"off\">1min candlestick</label></a>"; } 
	else { echo "<label class=\"btn btn-secondary active\"><a href=".'"'."chart.php?market=".$market."&chart=1minc&asset_in=".$assets[0]."&amp;asset_out=".$assets[1].'"'."><input type=\"radio\" name=\"options\" id=\"option1\" autocomplete=\"off\" checked>1min candlestick</label></a>"; } } }

	if ($assets[0] == "0" && in_array($assets[1], $minute)) { echo "<label class=\"btn btn-secondary\"><a href=".'"'."chart.php?market=".$market."&chart=1minc&asset_in=".$assets[0]."&amp;asset_out=".$assets[1].'"'."><input type=\"radio\" name=\"options\" id=\"option1\" autocomplete=\"off\">1min chart</label></a>"; }

	?>
        <label class="btn btn-secondary <?php if ($chart == "15min") { echo "active"; }?>"><a href=<?php echo '"'."chart.php?market=".$market."&chart=15min&asset_in=".$assets[0]."&amp;asset_out=".$assets[1].'"' ?>><input type="radio" name="options" id="option1" autocomplete="off" <?php if ($chart == "15min") { echo "checked"; }?> >15min chart</a></label>
        <label class="btn btn-secondary <?php if ($chart == "15minc") { echo "active"; }?>"><a href=<?php echo '"'."chart.php?market=".$market."&chart=15minc&asset_in=".$assets[0]."&amp;asset_out=".$assets[1].'"' ?>><input type="radio" name="options" id="option2" autocomplete="off" <?php if ($chart == "15minc") { echo "checked"; }?>>15min candlestick</a></label>
        <label class="btn btn-secondary <?php if ($chart == "1mon") { echo "active"; }?>"><a href=<?php echo '"'."chart.php?market=".$market."&chart=1mon&asset_in=".$assets[0]."&amp;asset_out=".$assets[1].'"' ?>><input type="radio" name="options" id="option3" autocomplete="off" <?php if ($chart == "1mon") { echo "checked"; }?>>4h chart</a></label>
        <label class="btn btn-secondary <?php if ($chart == "1year") { echo "active"; }?>"><a href=<?php echo '"'."chart.php?market=".$market."&chart=1year&asset_in=".$assets[0]."&amp;asset_out=".$assets[1].'"' ?>><input type="radio" name="options" id="option4" autocomplete="off" <?php if ($chart == "1year") { echo "checked"; }?>>48h chart</a></label>
        </div>
        </div>
        <div class="col">
        <div class="col-xs-6 btn-group-vertical btn-group-toggle">
<?php
if ($market == "") { echo "<label class=\"btn btn-secondary\"><a href=\"#\" onclick='window.swapDetails={\"assetid\":".$assets[0].",\"assetid2\":".$assets[1].",\"pool\":".'"'.$liquidez['pool'].'"'.",\"input\":false};getalgoswap();'
role=\"button\"  aria-haspopup=\"true\" aria-expanded=\"false\"><img src=\"img/algoswap.png\" class=\"circulito\" alt=\"\"><span>SELL ".$datos1->nombre."</span></a></label>"; }

if ($market == "") { echo "<label class=\"btn btn-secondary\"><a href=\"#\" onclick='window.swapDetails={\"assetid\":".$assets[1].",\"assetid2\":".$assets[0].",\"pool\":".'"'.$liquidez['pool'].'"'.",\"input\":false};getalgoswap();'
role=\"button\"  aria-haspopup=\"true\" aria-expanded=\"false\"><img src=\"img/algoswap.png\" class=\"circulito\" alt=\"\"><span>SELL ".$datos1->nombre."</span></a></label>"; }

if ($market == "") { echo "<label class=\"btn btn-secondary\"><a href=".'"'."https://app.tinyman.org/#/swap?asset_in=".$assets[0]."&amp;asset_out=".$assets[1].'"'."role=\"button\" target=\"_blank\"  aria-haspopup=\"true\"
aria-expanded=\"false\"><img src=\"img/tinyman.webp\" class=\"circulito\" alt=\"\"><span>Swap in Tinyman</span></a></label>"; }

if ($market == "pactfi") { echo "<label class=\"btn btn-secondary\"><a href=\"https://app.pact.fi/swap\" role=\"button\" target=\"_blank\"  aria-haspopup=\"true\"
aria-expanded=\"false\"><img src=\"img/pactfi.webp\" class=\"circulito\" alt=\"\"><span>Swap in Pact.fi</span></a></label>"; }

if ($market == "algofi") {
if ($assets[0]=="0") { $token1 = "1"; } else { $token1 = $assets[0]; }
if ($assets[1]=="0") { $token2 = "1"; } else { $token2 = $assets[1]; }
echo "<label class=\"btn btn-secondary\"><a href=".'"'."https://app.algofi.org/swap?token1=".$token1."&amp;token2=".$token2.'"'."role=\"button\" target=\"_blank\"  aria-haspopup=\"true\"
aria-expanded=\"false\"><img src=\"img/algofi.webp\" class=\"circulito\" alt=\"\"><span>Swap in AlgoFi</span></a></label>";
}

?>



<label class="btn btn-secondary"><a href=<?php echo '"'."chart.php?asset_in=".$assets[1]."&amp;asset_out=".$assets[0]."&amp;market=".$market."&amp;chart=".$chart.'"' ?> role="button"  aria-haspopup="true" aria-expanded="false"><img src="img/switch.webp" class="circulito" alt=""><span>Switch</span></a></label>

<?php if ($assets[0]!="0") { echo "<label class=\"btn btn-secondary\"><a href=https://algoexplorer.io/asset/".$assets[0]." target=\"_blank\" role=\"button\" id=\"dropdownLang\" aria-haspopup=\"true\" aria-expanded=\"false\"><img src=\"img/algoexplorer.webp\" class=\"circulito\"><span>".$datos1->nombre." on Algoexplorer</span></a></label>"; }
if ($assets[1]!="0") { echo "<label class=\"btn btn-secondary\"><a href=https://algoexplorer.io/asset/".$assets[1]." target=\"_blank\" role=\"button\" id=\"dropdownLang\" aria-haspopup=\"true\" aria-expanded=\"false\"><img src=\"img/algoexplorer.webp\" class=\"circulito\"><span>".$datos2->nombre." on Algoexplorer</span></a></label>"; } ?>
<?php if ($assets[0]!="0") { echo "<label class=\"btn btn-secondary\"><a href=https://analyzer.algocharts.net/?".$assets[0]." target=\"_blank\" role=\"button\" id=\"dropdownLang\" aria-haspopup=\"true\" aria-expanded=\"false\"><img src=\"img/lupa.webp\" class=\"circulito\"><span>".$datos1->nombre." on Analyzer</span></a></label>"; }
if ($assets[1]!="0") { echo "<label class=\"btn btn-secondary\"><a href=https://analyzer.algocharts.net/?".$assets[1]." target=\"_blank\" role=\"button\" id=\"dropdownLang\" aria-haspopup=\"true\" aria-expanded=\"false\"><img src=\"img/lupa.webp\" class=\"circulito\"><span>".$datos2->nombre." on Analyzer</span></a></label>"; } ?>
        </div>
        </div>

        </div>
