from datetime import datetime
import requests
import json
import pickle
import mysql.connector
from mysql.connector import pooling
from multiprocessing.pool import ThreadPool as PISCINA
from algosdk.v2client.algod import AlgodClient
import pactsdk

dbdiario = mysql.connector.pooling.MySQLConnectionPool(
	pool_name = "pumba2",
	pool_size = 24,
	host="localhost",
	user="pablo",
	password="test1",
	database="PACTFIprecios_diario"
	)

with open("/scripts/pactfi/lista_pares", "rb") as fp:
	lista_pares = pickle.load(fp)
piscina_size = 16

class conexion(object):
	def __init__(self):
		codigo=""

algod = AlgodClient("api-token", "https://nodo.algocharts.net")  # provide options
pact = pactsdk.PactClient(algod)

def worker(elemento):
	try:
		ASSET_ID = elemento[0]
		ALGO_ID = elemento[1]

		algo = pact.fetch_asset(ALGO_ID)
		jamnik = pact.fetch_asset(ASSET_ID)

		pool = pact.fetch_pool(algo, jamnik)
		precioalgoXasa = '%.16f'%(pool.state.primary_asset_price)
		precioasaXalgo = '%.16f'%(pool.state.secondary_asset_price)
		cantidad_asset2 = pool.state.total_primary
		cantidad_asset1 = pool.state.total_secondary
		lppool = "Null"

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
		print(e)
		pass

piscina = PISCINA(piscina_size)
for elemento in lista_pares:
	piscina.apply_async(worker, (elemento,))

piscina.close()
piscina.join()

