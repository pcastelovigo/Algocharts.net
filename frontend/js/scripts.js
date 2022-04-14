function borrarvacios() {
var table, rows, x = 0;
table = document.getElementsByClassName("gridjs-table");
var table1 = table[0];
rows = table1.getElementsByTagName("TR");
for (i = 1; i < (rows.length - 1); i++) {

     x = rows[i].getElementsByClassName("gridjs-td")[4];
  xText = x.innerHTML;

      if (xText == "0.00 USD") {
        table1.deleteRow(i);
        i = i - 1;
      }
}
}


function change_asset()
{
        var pares = $("#ASSET-IN").val();
	var dbselector = $("#pares").val();
           $.ajax({
                type: "POST",
                url: "pares.php",
                data: "pares="+pares+"&dbselector="+dbselector,
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
  var z = document.getElementById("market").innerHTML;
  var w = document.getElementById("chart").innerHTML;

  if ( y == "ASSET-OUT" ) { var y = "0"; }
  var url = "https://algocharts.net/chart.php?asset_in=" + encodeURIComponent(x) + "&asset_out=" + encodeURIComponent(y) + "&market=" + encodeURIComponent(z) + "&chart=" + encodeURIComponent(w);
  window.location.href = url;
};

function Goto2() {
  var x = document.getElementById("ASSET-IN").value;
  var y = document.getElementById("ASSET-OUT").value;
  if ( y == "ASSET-OUT" ) { var y = "0"; }
  var url = "https://algocharts.net/chart-candle.php?asset_in=" + encodeURIComponent(x) + "&asset_out=" + encodeURIComponent(y);
  window.location.href = url;
};

function convertFromStringToDate(responseDate) {
    let dateComponents = responseDate.split(' ');
    let datePieces = dateComponents[0].split("/");
    let timePieces = dateComponents[1].split(":");
    return(new Date(Date.UTC(datePieces[2], (datePieces[1] - 1), datePieces[0],
                         timePieces[0], timePieces[1], 0))) }
function getalgoswap(){
        $.getScript('https://unpkg.com/algoswap@1.1.2/dist/src.a2b27638.js').done(function(){$('#algoswap-btn').click();});
}
