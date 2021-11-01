#!/bin/bash
#sed -i 's/SEARCH_REGEX/REPLACEMENT/g' INPUTFILE
cp index.php index-30.php
cp index.php index-1y.php
echo "1"
sed -i 's/ORDER BY id DESC LIMIT 196/WHERE id % 14 = 0 ORDER BY id DESC LIMIT 196/g' index-30.php
echo "2"
sed -i 's/48h view/30 day view/g' index-30.php
sed -i 's/24h change/30 day change/g' index-30.php
echo "3"
sed -i 's/index.php?asset_in=\".$asset_out/index-30.php?asset_in=".$asset_out/g' index-30.php
echo "4"
sed -i 's/ORDER BY id DESC LIMIT 196/WHERE id % 176 = 0 ORDER BY id DESC LIMIT 196/g' index-1y.php
echo "5"
sed -i 's/48h view/1 year view/g' index-1y.php
sed -i 's/24h change/1 year change/g' index-1y.php
echo "6"
sed -i 's/index.php?asset_in=\".$asset_out/index-1y.php?asset_in=".$asset_out/g' index-1y.php



