import pickle
import mysql.connector


dbpares = mysql.connector.connect(
        host="localhost",
        user="pablo",
        password="test1",
        database="ALGOFIprecios_diario"
        )

cursor_diario = dbpares.cursor()

with open("/scripts/lista_pares", "rb") as fp:
        lista_pares = pickle.load(fp)
for elemento in lista_pares:
	try:
		ASSET_ID = elemento[0]
		ALGO_ID = elemento[1]
		nombre_fichero1 = str(ALGO_ID) + "_" + str(ASSET_ID)
		print(nombre_fichero1)
		sql = "DROP TABLE %s" % nombre_fichero1
		cursor_diario.execute(sql)
		dbpares.commit()
		sql = "DELETE FROM liquidez WHERE pool_id = %s" % ("'" + nombre_fichero1 + "'", )
		print(sql)
		cursor_diario.execute(sql)
		dbpares.commit()
	except Exception as excepcion:
		print(excepcion)
