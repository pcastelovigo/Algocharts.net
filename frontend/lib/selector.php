<script>
$(document).ready(function() {
    $('.selector').select2();
});
</script>
<div class="container">
<div class="row row--grid">
<div class="col-12 col-md-8">
    <select class="selector" id="ASSET-IN" style="margin-bottom:10px" onchange="change_asset();">

        <option value=<?php $datos = nombres($dbpares, $assets[0]); echo '"'.$assets[0].'">'; echo $assets[0]." - $".$datos->unidad." - ". $datos->nombre.$datos->verificado; ?></option>
	<?php selector_lista($dbpares); ?>
        </select>
<br>
<select class="selector" id="ASSET-OUT">
<option value="0">0 - Algorand</option>
</select>
</div>
<div class="col-12 col-md-4">
<input class="sign__btn" type="button" value="Go to chart" style="margin-top:14px; vertical-align: middle; line-height: 28px" onclick = "Goto()" />
</div>
</div>
</div>
