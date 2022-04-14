import os
from dotenv import dotenv_values
from algosdk import mnemonic
from algofi_amm.v0.asset import Asset
from algofi_amm.v0.client import AlgofiAMMTestnetClient, AlgofiAMMMainnetClient
from algofi_amm.v0.config import PoolType, PoolStatus
from algofi_amm.utils import get_payment_txn, get_params, send_and_wait

my_path = os.path.abspath(os.path.dirname(__file__))
ENV_PATH = os.path.join(my_path, ".env")
user = dotenv_values(ENV_PATH)
user['mnemonic']="riot short artefact mammal similar daughter visual cute name hat arrive slim general review promote utility hollow squeeze level autumn manual better foil absorb doll"

sender = mnemonic.to_public_key(user['mnemonic'])
key =  mnemonic.to_private_key(user['mnemonic'])

IS_MAINNET = True
if IS_MAINNET:
    amm_client = AlgofiAMMMainnetClient(user_address=sender)
else:
    amm_client = AlgofiAMMTestnetClient(user_address=sender)

asset1_id = 1
asset2_id = 31566704
swap_input_asset = Asset(amm_client, asset2_id)
swap_asset_amount = 1
min_amount_to_receive = 0.92
asset1 = Asset(amm_client, asset1_id)
asset2 = Asset(amm_client, asset2_id)
#swap_asset_scaled_amount = asset1.get_scaled_amount(swap_asset_amount)
pool = amm_client.get_pool(PoolType.CONSTANT_PRODUCT_25BP_FEE,asset1_id, asset2_id)
if pool.pool_status == PoolStatus.UNINITIALIZED:
	pool = amm_client.get_pool(PoolType.CONSTANT_PRODUCT_75BP_FEE,asset1_id, asset2_id)
print(pool.get_pool_price(asset2_id))
#print(vars(pool))
#print(pool.get_pool_price(asset1_id))
#lp_asset_id = pool.lp_asset_id
#lp_asset = Asset(amm_client, lp_asset_id)
#print(pool.get_pool_price(asset1_id))

