<script>
    const array_fechas = [];
    const array_fechas_f = [];
    var resultado_fechas = [ <?php echo "'".implode("', '", $principal['resultado_fechas'])."'," ?>];
    for(var i=0; i<resultado_fechas.length; i++){
    array_fechas.push(convertFromStringToDate (resultado_fechas[i])); }
    for(var i=0; i<array_fechas.length; i++){
    array_fechas_f.push(array_fechas[i].format("dd/MM/yyy HH:mm")); }
</script>
<script>
var resultado_precios = [ <?php echo implode(", ", $principal['resultado_precios'])."," ?>]
var options = {
  chart: {
    type: 'line',
    height: 600
  },
  series: [{
    name: <?php echo "'1 ".htmlspecialchars($datos1->nombre, ENT_QUOTES)." TO ".htmlspecialchars($datos2->nombre, ENT_QUOTES)."'"; ?>,
    data: resultado_precios
  }],
  xaxis: {
    categories: array_fechas_f
  },
  yaxis: {
     tickAmount: 6,
     title: { text: <?php echo "'".htmlspecialchars($datos1->nombre, ENT_QUOTES)." TO ".htmlspecialchars($datos2->nombre, ENT_QUOTES)."'"; ?>,  },
     max: Math.max(...resultado_precios)*1.1  ,
     min: Math.min(...resultado_precios)*0.9  ,
  },
  stroke: {
    curve: 'smooth',
    width: 2
  },
theme: { mode: 'dark' },
}

var chart = new ApexCharts(document.querySelector("#grafica"), options);

chart.render();
</script>
<!-- JS -->
<script src="js/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@dmester/sffjs@1.17.0/dist/stringformat.min.js"></script>
