from tinyman.v1.client import *
from algosdk import mnemonic
from datetime import datetime
import requests
import json
import pickle
import mysql.connector

dbdiario = mysql.connector.connect(
	host="localhost",
	user="pablo",
	password="test1",
	database="precios_diario"
	)

ADDRESS="BX4EKQO7URGCGBXYQ56CXMPX2LFNVUXH5REOHQ7DW7HLPH3A2PAVGP2EHQ"
MNEMONIC="riot short artefact mammal similar daughter visual cute name hat arrive slim general review promote utility hollow squeeze level autumn manual better foil absorb doll"

account = {
	'address': ADDRESS,
	'private_key': mnemonic.to_private_key(MNEMONIC)
	}
with open("/scripts/lista_pares", "rb") as fp:
	lista_pares = pickle.load(fp)
with open("/scripts/decimales", "rb") as fp:
	decimales = pickle.load(fp)
decimales.update({0:6})

for elemento in lista_pares:
	try:
		ASSET_ID = elemento[0]
		ALGO_ID = elemento[1]
		try:
			unidades1 = 1 * pow(10,decimales[ASSET_ID])
			unidades2 = 1 * pow(10,decimales[ALGO_ID])
		except KeyError:
			unidades1 = 6
			unidades2 = 6
			pass
		client = TinymanMainnetClient(user_address=account['address'])
#		print(ASSET_ID)
		ASA = client.fetch_asset(ASSET_ID)
#		print(ALGO_ID)
		ALGO = client.fetch_asset(ALGO_ID)
		pool = client.fetch_pool(ALGO, ASA)
		try:
			quote_algoXasa = pool.fetch_fixed_input_swap_quote(ALGO(unidades2), slippage=0.01)
			quote_asaXalgo = pool.fetch_fixed_output_swap_quote(ALGO(unidades2), slippage=0.01)
		except Exception as excepcion:
			pass
#informacion = pool.info()
		try:
			precioalgoXasa = float(quote_algoXasa.price*(unidades2/unidades1))
			precioasaXalgo = float(quote_asaXalgo.price*(unidades1/unidades2))
		except ZeroDivisionError:
			precioalgoXasa = float(1*(unidades2/unidades1))
			precioasaXalgo = float(1*(unidades1/unidades2))
		if precioalgoXasa < 0:
			precioalgoXasa = (1 / unidades1)
		if precioasaXalgo < 0:
			precioasaXalgo = (1 / unidades2)
		nombre_fichero1 = str(ALGO_ID) + "_" + str(ASSET_ID)
		nombre_fichero2 = str(ASSET_ID) + "_" + str(ALGO_ID)
		ahora = datetime.now()
		fecha = ahora.strftime("%d/%m/%Y %H:%M")
		cursor_diario = dbdiario.cursor()
		sql = "CREATE TABLE IF NOT EXISTS %s (id INT AUTO_INCREMENT PRIMARY KEY, fecha VARCHAR(24) NOT NULL, precio VARCHAR(32) NOT NULL)" % nombre_fichero1
		cursor_diario.execute(sql)
		dbdiario.commit()
		sql = "CREATE TABLE IF NOT EXISTS %s (id INT AUTO_INCREMENT PRIMARY KEY, fecha VARCHAR(24) NOT NULL, precio VARCHAR(32) NOT NULL)" % nombre_fichero2
		cursor_diario.execute(sql)
		dbdiario.commit()
		insercion = "INSERT INTO " + nombre_fichero1 + " (fecha, precio) VALUES (%s, %s)"
		valores = (fecha, precioalgoXasa)
		cursor_diario.execute(insercion, valores)
		dbdiario.commit()
		insercion = "INSERT INTO " + nombre_fichero2 + " (fecha, precio) VALUES (%s, %s)"
		valores = (fecha, precioasaXalgo)
		cursor_diario.execute(insercion, valores)
		dbdiario.commit()
#print(informacion)
	except:
		print("excepcion")
		pass
cursor_diario.close()
dbdiario.close()
