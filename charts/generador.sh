#!/bin/bash
#sed -i 's/SEARCH_REGEX/REPLACEMENT/g' INPUTFILE
echo "Generando ficheros"
cp chart.php chart-30.php
cp chart.php chart-1y.php
cp chart.php chart1m.php
cp chart-candle.php chart-candle1m.php
echo "Cambiando includes"
sed -i 's/include15/include30/g' chart-30.php
sed -i 's/include15/include1y/g' chart-1y.php
sed -i 's/include15/include1m/g' chart1m.php
sed -i 's/include15/include1m/g' chart-candle1m.php
echo "Cambiando enlaces"
sed -i 's/chart.php?asset_in=\".$asset_out/chart-30.php?asset_in=".$asset_out/g' chart-30.php
sed -i 's/chart.php?asset_in=\".$asset_out/chart-1y.php?asset_in=".$asset_out/g' chart-1y.php
echo "Cambiando textos"
sed -i 's/15min view/4h view/g' chart-30.php
sed -i 's/15min view/48h view/g' chart-1y.php
sed -i 's/15min view/1min view/g' chart1m.php
sed -i 's/24h change/2 week change/g' chart-30.php
sed -i 's/24h change/6 month change/g' chart-1y.php
sed -i 's/24h change/1 hour change/g' chart1m.php
sed -i 's/15min view/1min view/g' chart-candle1m.php
echo "Cambiando javascript"
sed -i 's/HH:59/HH:mm/g' chart-candle1m.php


