from tinyman.v1.client import *
from algosdk import mnemonic
from datetime import datetime
import requests
import json
import pickle
import mysql.connector
from mysql.connector import pooling
from multiprocessing.pool import ThreadPool as PISCINA


dbdiario = mysql.connector.pooling.MySQLConnectionPool(
	pool_name = "pumba",
	pool_size = 24,
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
piscina_size = 16

class conexion(object):
	def __init__(self):
		codigo=""


def worker(elemento):
	try:
		ASSET_ID = elemento[0]
		ALGO_ID = elemento[1]
		try:
			unidades1 = 1 * pow(10,decimales[ASSET_ID])
			unidades2 = 1 * pow(10,decimales[ALGO_ID])
		except KeyError:
			unidades1 = 1 * pow(10,6)
			unidades2 = 1 * pow(10,6)
			pass

		client = TinymanMainnetClient(user_address=account['address'])
		#print(ASSET_ID)
		ASA = client.fetch_asset(ASSET_ID)
		#print(ALGO_ID)
		ALGO = client.fetch_asset(ALGO_ID)
		pool = client.fetch_pool(ALGO, ASA)
		informacion = pool.info()
		#print(informacion)
		lppool = informacion['address']
		if informacion['asset1_id'] == ASSET_ID:
			cantidad_asset1 = informacion['asset1_reserves']
			cantidad_asset2 = informacion['asset2_reserves']
			extra_asset1 = informacion['outstanding_asset1_amount']
			extra_asset2 = informacion['outstanding_asset2_amount']
		else:
			cantidad_asset2 = informacion['asset1_reserves']
			cantidad_asset1 = informacion['asset2_reserves']
			extra_asset2 = informacion['outstanding_asset1_amount']
			extra_asset1 = informacion['outstanding_asset2_amount']
		try:
			precioalgoXasa = float((cantidad_asset1/unidades1)/(cantidad_asset2/unidades2))
		except ZeroDivisionError:
			precioalgoXasa = float(0)
		try:
			precioasaXalgo = float((cantidad_asset2/unidades2)/(cantidad_asset1/unidades1))
		except ZeroDivisionError:
			precioasaXalgo = float(0)
		if precioalgoXasa < 0:
			precioalgoXasa = float(0)
		if precioasaXalgo < 0:
			precioasaXalgo = float(0)

		nombre_fichero1 = str(ALGO_ID) + "_" + str(ASSET_ID)
		nombre_fichero2 = str(ASSET_ID) + "_" + str(ALGO_ID)
		ahora = datetime.now()
		fecha = ahora.strftime("%d/%m/%Y %H:%M")
		conexion = dbdiario.get_connection()
		cursor_diario = conexion.cursor()
		sql = "CREATE TABLE IF NOT EXISTS %s (id INT AUTO_INCREMENT PRIMARY KEY, fecha VARCHAR(24) NOT NULL, precio VARCHAR(32) NOT NULL)" % nombre_fichero2
		cursor_diario.execute(sql)
		conexion.commit()
		insercion = "INSERT INTO " + nombre_fichero2 + " (fecha, precio) VALUES (%s, %s)"
		valores = (fecha, precioasaXalgo)
		cursor_diario.execute(insercion, valores)
		conexion.commit()
		insercion = "INSERT INTO liquidez (pool_id, liqa1, liqa2, pool) VALUES (" + repr(nombre_fichero2) + ", %s, %s, %s) ON DUPLICATE KEY UPDATE liqa1 = %s, liqa2 = %s"
		valores = (cantidad_asset2, cantidad_asset1, lppool, cantidad_asset2, cantidad_asset1)
		cursor_diario.execute(insercion, valores)
		conexion.commit()
		cursor_diario.close()
		conexion.close()

	except Exception as e:
		cursor_diario.close()
		conexion.close()
		#print(e)
		pass

piscina = PISCINA(piscina_size)
for elemento in lista_pares:
	piscina.apply_async(worker, (elemento,))

piscina.close()
piscina.join()

