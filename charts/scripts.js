document.addEventListener('DOMContentLoaded', () => {
    const themeStylesheet = document.getElementById('theme');
    const storedTheme = localStorage.getItem('theme');
    if(storedTheme){
        themeStylesheet.href = storedTheme;
    }
    const themeToggle = document.getElementById('theme-toggle');
    themeToggle.addEventListener('click', () => {
        if(themeStylesheet.href.includes('claro')){
            themeStylesheet.href = 'oscuro-estilos.css';
        } else {
            themeStylesheet.href = 'claro-estilos.css';
        }
        localStorage.setItem('theme',themeStylesheet.href)
	window.location.reload(true)
    })
})



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
  var url = "https://algocharts.net/chart.php?asset_in=" + encodeURIComponent(x) + "&asset_out=" + encodeURIComponent(y);
  window.location.href = url;
};

function Goto2() {
  var x = document.getElementById("ASSET-IN").value;
  var y = document.getElementById("ASSET-OUT").value;
  if ( y == "ASSET-OUT" ) { var y = "0"; }
  var url = "https://algocharts.net/chart-candle.php?asset_in=" + encodeURIComponent(x) + "&asset_out=" + encodeURIComponent(y);
  window.location.href = url;
};

function sortTable(n) {
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById("tabla");
  switching = true;

  dir = "asc";

  while (switching) {

    switching = false;
    rows = table.getElementsByTagName("TR");

    for (i = 1; i < (rows.length - 1); i++) {

      shouldSwitch = false;

      x = rows[i].getElementsByClassName("orden")[n];
      y = rows[i + 1].getElementsByClassName("orden")[n];


      if (n == 0) {
        xText = x.innerHTML.toLowerCase();
        yText = y.innerHTML.toLowerCase();
      } else {
        xText = parseFloat(x.innerHTML.replace(/Ⱥ/g, ''));
        yText = parseFloat(y.innerHTML.replace(/Ⱥ/g, ''));
      }


      if (dir == "asc") {
        if (xText > yText) {
          shouldSwitch = true;
          break;
        }
      } else if (dir == "desc") {
        if (xText < yText) {
          shouldSwitch = true;
          break;
        }
      }
    }
    if (shouldSwitch) {

      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;

      switchcount++;
    } else {

      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
}

function borrarvacios() {
var table, rows, x = 0;
table = document.getElementById("tabla1");
rows = table.getElementsByTagName("TR");
for (i = 1; i < (rows.length - 1); i++) {

     x = rows[i].getElementsByClassName("valortotal")[0];
  xText = x.innerHTML;
	
      if (xText == "0.00 USD") {
	document.getElementById("tabla1").deleteRow(i);
        i = i - 1;
      }
} 

 }
