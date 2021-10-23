from tinyman.v1.client import *
from algosdk import mnemonic
from datetime import date
import requests
import json
import pickle
import mysql.connector

dbhistorico = mysql.connector.connect(
	host="localhost",
	user="pablo",
	password="test1",
	database="precios"
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
	ASSET_ID = elemento[0]
	ALGO_ID = elemento[1]
	try:
		unidades1 = 1 * pow(10,decimales[ASSET_ID])
		unidades2 = 1 * pow(10,decimales[ALGO_ID])
		client = TinymanMainnetClient(user_address=account['address'])
		ASA = client.fetch_asset(ASSET_ID)
		ALGO = client.fetch_asset(ALGO_ID)
		pool = client.fetch_pool(ALGO, ASA)
		quote_algoXasa = pool.fetch_fixed_input_swap_quote(ALGO(unidades2), slippage=0.01)
		quote_asaXalgo = pool.fetch_fixed_output_swap_quote(ALGO(unidades2), slippage=0.01)
		#informacion = pool.info()
		precioalgoXasa = float(quote_algoXasa.price*(unidades2/unidades1))
		precioasaXalgo = float(quote_asaXalgo.price*(unidades1/unidades2))
		if precioalgoXasa < 0:
			precioalgoXasa = (1 / unidades1)
		if precioasaXalgo < 0:
			precioasaXalgo = (1 / unidades2)
		nombre_fichero1 = str(ALGO_ID) + "_" + str(ASSET_ID)
		nombre_fichero2 = str(ASSET_ID) + "_" + str(ALGO_ID)
		ahora = date.today()
		fecha = ahora.strftime("%d/%m/%Y")
		cursor_historico = dbhistorico.cursor()
		cursor_historico.execute("CREATE TABLE IF NOT EXISTS " + nombre_fichero1 + " (id INT AUTO_INCREMENT PRIMARY KEY, fecha VARCHAR(24) NOT NULL, precio VARCHAR(32) NOT NULL)")
		cursor_historico.execute("CREATE TABLE IF NOT EXISTS " + nombre_fichero2 + " (id INT AUTO_INCREMENT PRIMARY KEY, fecha VARCHAR(24) NOT NULL, precio VARCHAR(32) NOT NULL)")
		insercion = "INSERT INTO " + nombre_fichero1 + " (fecha, precio) VALUES (%s, %s)"
		valores = (fecha, precioalgoXasa)
		cursor_historico.execute(insercion, valores)
		dbhistorico.commit()
		insercion = "INSERT INTO " + nombre_fichero2 + " (fecha, precio) VALUES (%s, %s)"
		valores = (fecha, precioasaXalgo)
		cursor_historico.execute(insercion, valores)
		dbhistorico.commit()
		#print(informacion)
	except:
		pass
cursor_historico.close()
dbhistorico.close()




