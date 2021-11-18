import pickle
import requests
import json

with open("lista_pares", "rb") as fp:
    lista_pares = pickle.load(fp)


diccionario_decimales = {}
for elemento in lista_pares:
    for par in elemento:
        if par != 0:
            respuesta_algoexplorer = requests.get('https://algoexplorerapi.io/idx2/v2/assets?asset-id=' + str(par))
            respuesta_algoexplorer_p1 = respuesta_algoexplorer.text
            respuesta_algoexplorer_p2 = json.loads(respuesta_algoexplorer_p1)
            try:
                decimales = respuesta_algoexplorer_p2['assets'][0]['params']['decimals']
                diccionario_decimales.update({par:decimales})
            except:
                pass

with open("decimales", "wb") as fichero:
    pickle.dump(diccionario_decimales, fichero)
    fichero.close()
    



