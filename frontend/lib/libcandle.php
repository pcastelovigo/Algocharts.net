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
          series: [{
          data: [
<?php   $longitud_array = count($principal['resultado_precios']);
for ($i = 0; $i <= $longitud_array - 4; $i+=3) {
        $valores = array($principal['resultado_precios'][($i)], $principal['resultado_precios'][($i+1)], $principal['resultado_precios'][($i+2)], $principal['resultado_precios'][($i+3)]);
        echo "{ x: array_fechas_f[".$i."], y: [".$principal['resultado_precios'][$i].", ".max($valores).", ".min($valores).", ".$principal['resultado_precios'][($i+3)]."]},"; } ?>
]
        }],
          chart: {
          type: 'candlestick',
          height: 600
        },
        theme: { mode: 'dark' },
        xaxis: {
          enabled: false
        },
        yaxis: {
        opposite: true,
        tickAmount: 6,
          title: { text: <?php echo "'".htmlspecialchars($datos1->nombre, ENT_QUOTES)." TO ".htmlspecialchars($datos2->nombre, ENT_QUOTES)."'"; ?>, },
          max: Math.max(...resultado_precios)*1.1  ,
          min: Math.min(...resultado_precios)*0.9  ,
          tooltip: { enabled: true }
        }
        };

var chart = new ApexCharts(document.querySelector("#grafica"), options);

chart.render();
</script>

</script>
        <!-- JS -->
        <script src="js/main.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@dmester/sffjs@1.17.0/dist/stringformat.min.js"></script>
