import requests
import json
import pickle

hay_next_token = True
lista_pool_creator = []
lista_pools = []
pares_assets = []

sesion = requests.Session()

try:
    with open("/scripts/pactfi/next-token", "rb") as fichero:
        next_token = pickle.load(fichero)
        fichero.close()
        hay_next_token_almacenado = True
except:
    hay_next_token_almacenado = False
    pass

try:
   with open("/scripts/pactfi/lista_pares", "rb") as fichero:
       pares_assets = pickle.load(fichero)
       fichero.close()
except:
    pass

def obtenerpool1():
    global next_token, hay_next_token
    obtener_pools = sesion.get('https://algoindexer.algoexplorerapi.io/v2/assets?name=PACT%20LP%20Token')
    obtener_pools_p2 = json.loads(obtener_pools.text)
    next_token = obtener_pools_p2["next-token"]
    tamaño_dict = len(obtener_pools_p2['assets'])
    for i in range(tamaño_dict):
        lista_pool_creator.append(obtener_pools_p2['assets'][i]['params']['creator'])
        lista_pools.append(obtener_pools_p2['assets'][i]['index'])

def obtenerpool2():
    global next_token, hay_next_token
    obtener_pools = sesion.get('https://algoindexer.algoexplorerapi.io/v2/assets?name=PACT%20LP%20Token&next=' + next_token )
    obtener_pools_p2 = json.loads(obtener_pools.text)
    if 'next-token' in obtener_pools_p2.keys():
        next_token = obtener_pools_p2["next-token"]
        with open("/scripts/pactfi/next-token", "wb") as fichero:
            pickle.dump(next_token, fichero)
            fichero.close()
    else:
        hay_next_token = False
    tamaño_dict = len(obtener_pools_p2['assets'])
    for i in range(tamaño_dict):
        lista_pool_creator.append(obtener_pools_p2['assets'][i]['params']['creator'])
        lista_pools.append(obtener_pools_p2['assets'][i]['index'])

if hay_next_token_almacenado == False:
    obtenerpool1()
while hay_next_token == True:
    obtenerpool2()

tamaño_lista = len(lista_pools)
sesion2 = requests.Session()
sesion2.headers.update({'X-Algo-API-Token':'api-token'})

for i in range(tamaño_lista):
    par_assets = []
    par_assets.clear()
    obtener_assets = sesion2.get('https://nodo.algocharts.net/v2/accounts/' + lista_pool_creator[i])
    obtener_assets_p2 = json.loads(obtener_assets.text)
    tamaño_dict = len(obtener_assets_p2['assets'])
    for j in range(tamaño_dict):
        par_assets.append(obtener_assets_p2['assets'][j]['asset-id'])
    par_assets.remove(lista_pools[i])
    if len(par_assets) == 1:
        par_assets.append(0)
    if par_assets not in pares_assets:
        pares_assets.append(par_assets)

with open("/scripts/pactfi/lista_pares", "wb") as fichero:
    pickle.dump(pares_assets, fichero)
    fichero.close()

