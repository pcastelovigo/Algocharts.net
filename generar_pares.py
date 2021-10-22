import pickle
import mysql.connector

dbpares = mysql.connector.connect(
	host="localhost",
	user="test",
	password="test1",
	database="pairs"
	)

with open("lista_pares", "rb") as fp:
	lista_pares = pickle.load(fp)

cursor_pares = dbpares.cursor()
for elemento in lista_pares:
	cursor_pares.execute("CREATE TABLE IF NOT EXISTS " + "_" + str(elemento[0]) + " (id INT PRIMARY KEY NOT NULL)")
	cursor_pares.execute("CREATE TABLE IF NOT EXISTS " + "_" + str(elemento[1]) + " (id INT PRIMARY KEY NOT NULL)")
for elemento in lista_pares:
	insercion = "INSERT IGNORE INTO "  + "_" + str(elemento[1]) + " (id) VALUES (" + str(elemento[0]) + ")"
	cursor_pares.execute(insercion)
	dbpares.commit()
	insercion = "INSERT IGNORE INTO "  + "_" + str(elemento[1]) + " (id) VALUES (" + str(elemento[0]) + ")"
	cursor_pares.execute(insercion)
	dbpares.commit()
cursor_pares.close()
dbpares.close()
