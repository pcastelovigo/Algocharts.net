import pickle
import requests
import json
import mysql.connector


dbpares = mysql.connector.connect(
	host="localhost",
	user="pablo",
	password="test1",
	database="PACTFIpares"
	)

with open("/scripts/pactfi/lista_pares", "rb") as fp:
	lista_pares = pickle.load(fp)

cursor_pares = dbpares.cursor()
sql = "select asset_id from nombres"
cursor_pares.execute(sql)
salida = [item[0] for item in cursor_pares.fetchall()]
#print(salida)
cursor_pares.close()

sesion = requests.Session()
sesion.headers.update({'X-Algo-API-Token':'api-token'})
cursor_pares = dbpares.cursor()
for elemento in lista_pares:
	for par in elemento:
		if par != 0:
			if par not in salida:
				respuesta_algoexplorer = sesion.get('https://nodo.algocharts.net/v2/assets/' + str(par))
				respuesta_algoexplorer_p1 = respuesta_algoexplorer.text
				respuesta_algoexplorer_p2 = json.loads(respuesta_algoexplorer_p1)
				nombre, total, unidades, decimales, pagina = "Deleted asset", None, None, None, None
				try:
					nombre = respuesta_algoexplorer_p2['params']['name']
				except:
					pass
				try:
					total = respuesta_algoexplorer_p2['params']['total']
				except:
					pass
				try:
					unidades = respuesta_algoexplorer_p2['params']['unit-name']
				except:
					pass
				try:
					decimales = respuesta_algoexplorer_p2['params']['decimals']
				except:
					pass
				try:
					pagina = respuesta_algoexplorer_p2['params']['url']
				except:
					pass
				sql = "INSERT IGNORE INTO nombres (asset_id, nombre, unidad, url, cantidad, decimales) VALUES (%s, %s, %s, %s, %s, %s)"
				valores = (par, nombre, unidades, pagina, total, decimales)
				cursor_pares.execute(sql, valores)
				dbpares.commit()



ya_enlazados = []
sql = ("SELECT assetin, assetout FROM pares")
cursor_pares.execute(sql)
for item in list(cursor_pares.fetchall()):
	ya_enlazados.append(list(item))
lista_pares_nuevos = []
for elemento in lista_pares:
	if elemento not in ya_enlazados:
		lista_pares_nuevos.append(elemento)

for elemento in lista_pares_nuevos:
        sql = ("SELECT * FROM pares WHERE assetin = %s AND assetout= %s")
        valores = (int(elemento[0]), int(elemento[1]))
        cursor_pares.execute(sql, valores)
        cursor_pares.fetchall()
        if not cursor_pares.rowcount:
                try:
                        sqln = ("SELECT nombre FROM nombres WHERE asset_id = %s")
                        valor = elemento[1]
                        cursor_pares.execute(sqln, (valor,))
                        resultado = cursor_pares.fetchone()
                        sql = ("INSERT INTO pares (assetin, assetout, nombre) VALUES (%s, %s, %s)")
                        valores = (elemento[0], elemento[1], resultado[0])
                        cursor_pares.execute(sql, valores)
                        dbpares.commit()
                except:
                        pass
for elemento in lista_pares_nuevos:
        sql = ("SELECT * FROM pares WHERE assetin = %s AND assetout= %s")
        valores = (int(elemento[1]), int(elemento[0]))
        cursor_pares.execute(sql, valores)
        cursor_pares.fetchall()
        if not cursor_pares.rowcount:
                try:
                        sqln = ("SELECT nombre FROM nombres WHERE asset_id = %s")
                        valor = elemento[0]
                        cursor_pares.execute(sqln, (valor,))
                        resultado = cursor_pares.fetchone()
                        sql = ("INSERT INTO pares (assetin, assetout, nombre) VALUES (%s, %s, %s)")
                        valores = (elemento[1], elemento[0], resultado[0])
                        cursor_pares.execute(sql, valores)
                        dbpares.commit()
                except:
                        pass

cursor_pares.close()
dbpares.close()
