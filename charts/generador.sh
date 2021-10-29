#!/bin/bash
#sed -i 's/SEARCH_REGEX/REPLACEMENT/g' INPUTFILE
echo "1"
sed -i 's/ORDER BY id DESC LIMIT 196/WHERE id % 14 = 0 ORDER BY id DESC LIMIT 196/g' index-30.php
echo "2"
sed -i 's/48h view/30 day view/g' index-30.php
echo "2b"
sed -i 's/index.php?asset_in=\".$asset_out/index-30.php?asset_in=".$asset_out/g' index-30.php
echo "3"
sed -i 's/ORDER BY id DESC LIMIT 196/WHERE id % 176 = 0 ORDER BY id DESC LIMIT 196/g' index-1y.php
echo "4"
sed -i 's/48h view/1 year view/g' index-1y.php
echo "5"
sed -i 's/index.php?asset_in=\".$asset_out/index-1y.php?asset_in=".$asset_out/g' index-1y.php



