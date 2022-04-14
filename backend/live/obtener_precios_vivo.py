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
	pool_name = "commansito",
	pool_size = 20,
	host="localhost",
	user="pablo",
	password="test1",
	database="precios_live"
	)

ADDRESS="BX4EKQO7URGCGBXYQ56CXMPX2LFNVUXH5REOHQ7DW7HLPH3A2PAVGP2EHQ"
MNEMONIC="riot short artefact mammal similar daughter visual cute name hat arrive slim general review promote utility hollow squeeze level autumn manual better foil absorb doll"

account = {
	'address': ADDRESS,
	'private_key': mnemonic.to_private_key(MNEMONIC)
	}
with open("/scripts/live/lista_vivo", "rb") as fp:
	lista_pares = pickle.load(fp)
with open("/scripts/decimales", "rb") as fp:
	decimales = pickle.load(fp)
decimales.update({0:6})
piscina_size = 10

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
			unidades1 = 1 * pow(10,0)
			unidades2 = 1 * pow(10,6)
			pass
		client = TinymanMainnetClient(user_address=account['address'])
		ASA = client.fetch_asset(ASSET_ID)
		ALGO = client.fetch_asset(ALGO_ID)
		pool = client.fetch_pool(ALGO, ASA)

		informacion = pool.info()
		lppool = informacion['address']
		if informacion['asset1_id'] == ASSET_ID:
			cantidad_asset1 = informacion['asset1_reserves']
			cantidad_asset2 = informacion['asset2_reserves']
		else:
			cantidad_asset2 = informacion['asset1_reserves']
			cantidad_asset1 = informacion['asset2_reserves']
		try:
			precioalgoXasa = float((cantidad_asset1/unidades1)/(cantidad_asset2/unidades2))
		except ZeroDivisionError:
			precioalgoXasa = float(1*unidades2)
		try:
			precioasaXalgo = float((cantidad_asset2/unidades2)/(cantidad_asset1/unidades1))
		except ZeroDivisionError:
			precioasaXalgo = float(1*unidades1)
		if precioalgoXasa < 0:
			precioalgoXasa = (1 / unidades1)
		if precioasaXalgo < 0:
			precioasaXalgo = (1 / unidades2)

		nombre_fichero2 = str(ASSET_ID) + "_" + str(ALGO_ID)
		ahora = datetime.now()
		fecha = ahora.strftime("%d/%m/%Y %H:%M")
		conexion = dbdiario.get_connection()
		cursor_vivo = conexion.cursor()
		sql = "CREATE TABLE IF NOT EXISTS %s (id INT AUTO_INCREMENT PRIMARY KEY, fecha VARCHAR(24) NOT NULL, precio VARCHAR(32) NOT NULL)" % nombre_fichero2
		cursor_vivo.execute(sql)
		conexion.commit()
		insercion = "INSERT INTO " + nombre_fichero2 + " (fecha, precio) VALUES (%s, %s)"
		valores = (fecha, precioasaXalgo)
		cursor_vivo.execute(insercion, valores)
		conexion.commit()
		sql = "select max(id) from %s" % nombre_fichero2
		cursor_vivo.execute(sql)
		result_set = cursor_vivo.fetchone()
		if result_set[0] > 250:
			borrar = result_set[0] - 250
			sql = ("delete from " + nombre_fichero2 + " where id < " + str(borrar))
			cursor_vivo.execute(sql)
			conexion.commit()
		cursor_vivo.close()
		conexion.close()

	except Exception as e:
		print(e)
		pass

piscina = PISCINA(piscina_size)
for elemento in lista_pares:
	piscina.apply_async(worker, (elemento,))

piscina.close()
piscina.join()

