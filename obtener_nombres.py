import pickle
import requests
import json
import mysql.connector


dbnombres = mysql.connector.connect(
	host="localhost",
	user="pablo",
	password="test1",
	database="nombres"
	)

with open("lista_pares", "rb") as fp:
    lista_pares = pickle.load(fp)

cursor_nombres = dbnombres.cursor()
for elemento in lista_pares:
	for par in elemento:
		if par != 0:
			respuesta_algoexplorer = requests.get('https://algoexplorerapi.io/idx2/v2/assets?asset-id=' + str(par))
			respuesta_algoexplorer_p1 = respuesta_algoexplorer.text
			respuesta_algoexplorer_p2 = json.loads(respuesta_algoexplorer_p1)
			try:
				nombre = respuesta_algoexplorer_p2['assets'][0]['params']['name']
				sql = "INSERT IGNORE INTO nombres (asset_id, nombre) VALUES (%s, %s)"
				valores = (par, nombre)
				cursor_nombres.execute(sql, valores)
				dbnombres.commit()
			except:
				pass
cursor_nombres.close()
dbnombres.close()
