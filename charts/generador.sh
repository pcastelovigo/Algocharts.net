#!/bin/bash
#sed -i 's/SEARCH_REGEX/REPLACEMENT/g' INPUTFILE
cp chart.php chart-30.php
cp chart.php chart-1y.php
echo "1"
sed -i 's/ORDER BY id DESC LIMIT 196/WHERE id % 14 = 0 ORDER BY id DESC LIMIT 196/g' chart-30.php
echo "2"
sed -i 's/48h view/30 day view/g' chart-30.php
sed -i 's/24h change/30 day change/g' chart-30.php
echo "3"
sed -i 's/chart.php?asset_in=\".$asset_out/chart-30.php?asset_in=".$asset_out/g' chart-30.php
echo "4"
sed -i 's/ORDER BY id DESC LIMIT 196/WHERE id % 182 = 0 ORDER BY id DESC LIMIT 196/g' chart-1y.php
echo "5"
sed -i 's/48h view/1 year view/g' chart-1y.php
sed -i 's/24h change/1 year change/g' chart-1y.php
echo "6"
sed -i 's/chart.php?asset_in=\".$asset_out/chart-1y.php?asset_in=".$asset_out/g' chart-1y.php



