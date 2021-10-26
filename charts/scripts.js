function change_asset()
{
	var pares = $("#ASSET-IN").val();
	   $.ajax({
		type: "POST",
		url: "pares.php",
		data: "pares="+pares,
		cache: false,
		success: function(response)
			{
					//alert(response);return false;
				$("#ASSET-OUT").html(response);
			}
			});
}

function Goto() {
  var x = document.getElementById("ASSET-IN").value;
  var y = document.getElementById("ASSET-OUT").value;
  var url = "https://freetinycharts.ovh/index.php?asset_in=" + encodeURIComponent(x) + "&asset_out=" + encodeURIComponent(y);
  window.location.href = url;
};
