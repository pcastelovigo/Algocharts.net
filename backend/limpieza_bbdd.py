import pickle
import mysql.connector

dbdiario = mysql.connector.connect(
	host="localhost",
	user="pablo",
	password="test1",
	database="precios_diario"
	)

with open("/scripts/lista_pares", "rb") as fp:
	lista_pares = pickle.load(fp)

for elemento in lista_pares:
	ASSET_ID = elemento[0]
	ALGO_ID = elemento[1]
	nombre_fichero1 = str(ALGO_ID) + "_" + str(ASSET_ID)
	nombre_fichero2 = str(ASSET_ID) + "_" + str(ALGO_ID)
		
	try:
		cursor_diario = dbdiario.cursor()
		sql = "select max(id) from %s" % nombre_fichero2
		cursor_diario.execute(sql)
		result_set = cursor_diario.fetchone()
		if result_set[0] > 350:
			borrar = result_set[0] - 250
			sql = ("delete from " + nombre_fichero2 + " where id < " + str(borrar) + " AND id % 14 > 0")
			cursor_diario.execute(sql)
			dbdiario.commit()
	except Exception as e: print(e)

for elemento in lista_pares:
	ASSET_ID = elemento[0]
	ALGO_ID = elemento[1]
	nombre_fichero1 = str(ALGO_ID) + "_" + str(ASSET_ID)
	nombre_fichero2 = str(ASSET_ID) + "_" + str(ALGO_ID)
	try:
		cursor_diario = dbdiario.cursor(buffered=True)
		sql = ("OPTIMIZE TABLE ") + nombre_fichero2
		cursor_diario.execute(sql)
		dbdiario.commit()
	except Exception as e: print(e)

