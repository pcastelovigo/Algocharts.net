import pickle
import requests
import json


diccionario_decimales = {}

with open("/scripts/algofi/lista_pares", "rb") as fichero:
	lista_pares = pickle.load(fichero)
	fichero.close()

try:
	with open("/scripts/algofi/decimales", "rb") as fichero:
		diccionario_decimales = pickle.load(fichero)
		fichero.close()
except:
	pass

sesion = requests.Session()
sesion.headers.update({'X-Algo-API-Token':'api-token'})

for elemento in lista_pares:
	for par in elemento:
		if par != 0:
			if par not in diccionario_decimales:
				respuesta_algoexplorer = sesion.get('https://nodo.algocharts.net/v2/assets/' + str(par))
				respuesta_algoexplorer_p1 = respuesta_algoexplorer.text
				respuesta_algoexplorer_p2 = json.loads(respuesta_algoexplorer_p1)
				try:
					decimales = respuesta_algoexplorer_p2['params']['decimals']
					diccionario_decimales.update({par:decimales})
				except KeyError:
					pass

with open("/scripts/algofi/decimales", "wb") as fichero:
	pickle.dump(diccionario_decimales, fichero)
	fichero.close()
