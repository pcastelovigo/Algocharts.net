import json
import pickle
import mysql.connector

dbdiario = mysql.connector.connect(
	host="localhost",
	user="pablo",
	password="test1",
	database="precios_diario"
	)


cursor_diario = dbdiario.cursor()
sql = "select pool_id from liquidez where pool_id REGEXP '_0' AND liqa1 > 500000000"
cursor_diario.execute(sql)
salida = cursor_diario.fetchall()

pares_assets = []
for x in salida:
	par_assets = []
	par_assets.clear()
	par_assets.append(int(x[0][:-2]))
	par_assets.append(0)
	pares_assets.append(par_assets)

with open("/scripts/live/lista_vivo", "wb") as fichero:
    pickle.dump(pares_assets, fichero)
    fichero.close()


with open('/var/www/html/minute.php', 'w') as f:
	f.write('<?php')
	f.write(' $minute = array(')
	for x in salida:
		f.write(x[0][:-2] + ',')
	f.write(')')
	f.write('?>')
