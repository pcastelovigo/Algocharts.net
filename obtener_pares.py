import requests
import json
import pickle

hay_next_token = True
lista_pool_creator = []
lista_pools = []

def obtenerpool1():
    global next_token, hay_next_token
    obtener_pools = requests.get('https://algoexplorerapi.io/idx2/v2/assets?name=Tinyman%20Pool')
    obtener_pools_p2 = json.loads(obtener_pools.text)
    next_token = obtener_pools_p2["next-token"]
    tamaño_dict = len(obtener_pools_p2['assets'])
    for i in range(tamaño_dict):
        lista_pool_creator.append(obtener_pools_p2['assets'][i]['params']['creator'])
        lista_pools.append(obtener_pools_p2['assets'][i]['index'])

def obtenerpool2():
    global next_token, hay_next_token
    obtener_pools = requests.get('https://algoexplorerapi.io/idx2/v2/assets?name=Tinyman%20Pool&next=' + next_token )
    obtener_pools_p2 = json.loads(obtener_pools.text)
    if 'next-token' in obtener_pools_p2.keys():
        next_token = obtener_pools_p2["next-token"]
    else:
        hay_next_token = False
    tamaño_dict = len(obtener_pools_p2['assets'])
    for i in range(tamaño_dict):
        lista_pool_creator.append(obtener_pools_p2['assets'][i]['params']['creator'])
        lista_pools.append(obtener_pools_p2['assets'][i]['index'])


obtenerpool1()
while hay_next_token == True:
    obtenerpool2()

pares_assets = []
tamaño_lista = len(lista_pools)
for i in range(tamaño_lista):
    par_assets = []
    par_assets.clear()
    obtener_assets = requests.get('https://algoexplorerapi.io/v2/accounts/' + lista_pool_creator[i])
    obtener_assets_p2 = json.loads(obtener_assets.text)
    tamaño_dict = len(obtener_assets_p2['assets'])
    for j in range(tamaño_dict):
        par_assets.append(obtener_assets_p2['assets'][j]['asset-id'])
    par_assets.remove(lista_pools[i])
    if len(par_assets) == 1:
        par_assets.append(0)
    pares_assets.append(par_assets)

with open("/scripts/lista_pares", "wb") as fichero:
    pickle.dump(pares_assets, fichero)
    fichero.close()





    


    

