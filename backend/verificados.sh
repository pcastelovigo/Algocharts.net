#!/bin/sh
curl https://mobile-api.algorand.com/api/verified-assets/ | jq '.results[].asset_id' | tail +1 | while read t; do echo "update nombres set verify = '✅' where asset_id = $t;"; done | mysql --database pares
sleep 2
curl https://mobile-api.algorand.com/api/verified-assets/ | jq '.results[].asset_id' | tail +1 | while read t; do echo "update pares set verify = '✅' where assetout = $t;"; done | mysql --database pares
sleep 10
curl https://mobile-api.algorand.com/api/verified-assets/ | jq '.results[].asset_id' | tail +1 | while read t; do echo "update nombres set verify = '✅' where asset_id = $t;"; done | mysql --database PACTFIpares
sleep 2
curl https://mobile-api.algorand.com/api/verified-assets/ | jq '.results[].asset_id' | tail +1 | while read t; do echo "update pares set verify = '✅' where assetout = $t;"; done | mysql --database PACTFIpares
sleep 10
curl https://mobile-api.algorand.com/api/verified-assets/ | jq '.results[].asset_id' | tail +1 | while read t; do echo "update nombres set verify = '✅' where asset_id = $t;"; done | mysql --database ALGOFIpares
sleep 2
curl https://mobile-api.algorand.com/api/verified-assets/ | jq '.results[].asset_id' | tail +1 | while read t; do echo "update pares set verify = '✅' where assetout = $t;"; done | mysql --database ALGOFIpares


