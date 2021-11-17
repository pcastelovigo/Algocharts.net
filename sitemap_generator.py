import pickle

with open("/scripts/lista_pares", "rb") as fp:
	lista_pares = pickle.load(fp)


for elemento in lista_pares:
	ASSET_ID = elemento[0]
	ALGO_ID = elemento[1]
	with open('output.txt', 'a') as f:
		f.write("https://algocharts.net/chart.php?asset_in=" + str(elemento[0]) + "&asset_out=" + str(elemento[1]))
		f.write('\n')
		f.write("https://algocharts.net/chart.php?asset_in=" + str(elemento[1]) + "&asset_out=" + str(elemento[0]))
		f.write('\n')

