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

function convertFromStringToDate(responseDate) {
    let dateComponents = responseDate.split(' ');
    let datePieces = dateComponents[0].split("/");
    let timePieces = dateComponents[1].split(":");
    return(new Date(Date.UTC(datePieces[2], (datePieces[1] - 1), datePieces[0],
                         timePieces[0], timePieces[1], 0))) }

function Goto() {
  var x = document.getElementById("ASSET-IN").value;
  var y = document.getElementById("ASSET-OUT").value;
  if ( y == "ASSET-OUT" ) { var y = "0"; }
  var url = "https://freetinycharts.ovh/index.php?asset_in=" + encodeURIComponent(x) + "&asset_out=" + encodeURIComponent(y);
  window.location.href = url;
};
