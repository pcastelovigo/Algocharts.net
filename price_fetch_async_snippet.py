from tinyman.v1.client import *
from algosdk import mnemonic
from datetime import datetime
import requests
import json
import pickle
import asyncio


ADDRESS="BX4EKQO7URGCGBXYQ56CXMPX2LFNVUXH5REOHQ7DW7HLPH3A2PAVGP2EHQ"
MNEMONIC="riot short artefact mammal similar daughter visual cute name hat arrive slim general review promote utility hollow squeeze level autumn manual better foil absorb doll"

account = {
	'address': ADDRESS,
	'private_key': mnemonic.to_private_key(MNEMONIC)
	}
with open("lista_pares", "rb") as fp:
	lista_pares = pickle.load(fp)
with open("decimales", "rb") as fp:
	decimales = pickle.load(fp)
decimales.update({0:6})
i = 0
client = TinymanMainnetClient(user_address=account['address'])


async def fetch_asset_in(ASSET_ID):
	ASA = client.fetch_asset(ASSET_ID)
	return ASA
async def fetch_asset_out(ALGO_ID):
        ALGO = client.fetch_asset(ALGO_ID)
        return ALGO
async def fetch_piscina(ALGO, ASA):
        piscina = client.fetch_pool(ALGO, ASA)
        return piscina
async def precioalgoasa(piscina, ALGO, unidades2):
	precioalgoXasa = piscina.fetch_fixed_input_swap_quote(ALGO(unidades2), slippage=0.01)
	return precioalgoXasa
async def precioasaalgo(piscina, ALGO, unidades2):
	precioasaXalgo = piscina.fetch_fixed_output_swap_quote(ALGO(unidades2), slippage=0.01)
	return precioasaXalgo
#async def infopiscina(piscina):
#	informacion = piscina.info()
#	return informacion



async def main():
	try:
		ASSET_ID = elemento[0]
		ALGO_ID = elemento[1]
		task1 = asyncio.create_task(fetch_asset_in(ASSET_ID))
		task2 = asyncio.create_task(fetch_asset_out(ALGO_ID))

		try:
			unidades1 = 1 * pow(10,decimales[ASSET_ID])
			unidades2 = 1 * pow(10,decimales[ALGO_ID])
		except KeyError:
			unidades1 = 6
			unidades2 = 6
			pass
		ASA = await task1 # fetch_asset_in(ASSET_ID)
		print(ASA)
		ALGO = await task2 # fetch_asset_out(ALGO_ID)
		print(ALGO)
		pool = await fetch_piscina(ALGO, ASA)
#		informacion = pool.info()
#		print(informacion)
		try:
			quote_algoXasa = await precioalgoasa(pool, ALGO, unidades2)
#			print(quote_algoXasa)
			quote_asaXalgo = await precioasaalgo(pool, ALGO, unidades2)
#			print(quote_asaXalgo)
		except Exception as excepcion:
			pass
#		print(informacion)
		if informacion['asset1_id'] == ASSET_ID:
			cantidad_asset1 = informacion['asset1_reserves']
			cantidad_asset2 = informacion['asset2_reserves']
		else:
			cantidad_asset2 = informacion['asset1_reserves']
			cantidad_asset1 = informacion['asset2_reserves']
		try:
			precioalgoXasa = float(quote_asaXalgo.price*(unidades2/unidades1))
#			print(precioalgoXasa)
		except ZeroDivisionError:
			precioalgoXasa = float(1*unidades2)
		try:
			precioasaXalgo = float(quote_asaXalgo.price*(unidades1/unidades2))
#			print(precioasaXalgo)
		except ZeroDivisionError:
			precioasaXalgo = float(1*unidades1)
		if precioalgoXasa < 0:
			precioalgoXasa = (1 / unidades1)
		if precioasaXalgo < 0:
			precioasaXalgo = (1 / unidades2)
		nombre_fichero1 = str(ALGO_ID) + "_" + str(ASSET_ID)
		nombre_fichero2 = str(ASSET_ID) + "_" + str(ALGO_ID)
		ahora = datetime.now()
		fecha = ahora.strftime("%d/%m/%Y %H:%M")
#		print(informacion)
		
	except:
		pass

for elemento in lista_pares:
	asyncio.run(main())

#print(informacion)
