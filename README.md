This software is live at https://algocharts.net

Python backend explained:

Backend is wrote from bottom-up, in the most straightfoward way. This also means that components could be used as standalone programs and redirect output to stdout, text files, csv or spreadsheets mostly just stripping code.
It has 3 main components:

1. obtener_pares.py ('get pairs')

This script get all Tinyman pairs from Algoexplorer and saves them as a Python list in a binary file. As Tinyman pools always have 2 or 3 assets (Token, pool token and another token OR algorand, it just stripes pool token and add Algorand ("ASA #0") to the pair list if it is a ASA-Algorand pair.
As Tinyman API starts to be documented, thats not the only way of getting this data.

2. obtener_nombres.py ('get names')

This script get all ASA info from Algoexplorer and stripes 'name', 'total', 'unit-name', 'decimals' and 'url'. Any other data in API response could be added. Then it write a Python dictionary in a binary file with how many decimals each asset has. Early versions did this with a standalone script I've re-uploaded if somebody wants to work without fetching asset data (https://github.com/pcastelovigo/freetinycharts/blob/main/obtener_decimales.py)

Then it writes data to a database and a pair list to another database, allowing front-end to show trading pairs in the selector menu.


3. obtener_precios_diario.py ('get daily prices')

This script gets price data from Tinyman SDK (that also uses Algoexplorer in the end).
To allow multithreading, first of all it defines a database connection pool. Uses an empty address mnemonic to generate a private key, and loads pairs Python list and decimals Python dictionary generated in previous scripts to get all prices in a big, multithreaded for loop.
Code is full of try-except catches to workarround the unknown when I wrote the code Tinyman bug ( https://docs.tinyman.org/known-issues/2021-11-12-pool-overflow-errors ) For big-value assets without decimals, it retries with a bigger Algo amount. Another solution is to ask for sell and not buy prices.
Finally it creates new DB tables if they not exists and writes UTC date string, price and last liquidity data to database.
Working speed can be increased or reduced changing "piscina_size" and "pool_size" values, but it can eat computer time very fast so be careful. Also remember to set the database connection pool bigger than the multithread worker pool or script will run out of avaliable sockets.

4. PHP frontend

PHP just read values from database and insert them in existing charting libraries. Charts.js and Apexcharts are tested and working, but probably others will work with this backend. Billboard gets high algo liquidity pairs from database with a > comparator and portfolio calculator PHP just gets address assets from Algoexplorer and takes price data from database.

5. Other files

Live folder scripts do almost the same but inserts 1 minute data onto a self-cleaning database and generates a PHP file to inform frontend about 1 minute tracked ASAs.

limpieza_db.py ('database cleaning') just holds 210 minute data from long term storing if even smaller database wanted.

async_snippet is just a working experiment on using asyncronous load from API requests. Not implemented yet.


6. short backend TO-DO

- Volume tracking could be done getting current round via API, estimating 24 hour before round and summing all done transfers in pool between those rounds. Volume could be calculated backwards!

- Adding multithreading to asset data fetching as it starts to be a long proccess

- Exclude bugged pools from being readed and exclude already readed assets from obtener_nombres.py would be optimal.

- Study switching or allowing using undocumented tinyman api to get pools.

- Translate all variable names into English. I am sorry, dudes.


7. Fast How-to install:

- In a linux box with apache,php & mysql, as root:

- Unpack zip


mkdir /scripts/ && mkdir /scripts/live/

cp *.py /scripts/ && cp live/* /scripts/live

cp -r charts/* /var/www/html/


- Enter mysql console

create user 'pablo'@'localhost' identified by 'test1';  // remember to change this and change them in all PHP and PY files.

create database precios_diario;

create database precios_live;

create database pares;

use pares;

CREATE TABLE nombres (asset_id INT NOT NULL PRIMARY KEY, nombre VARCHAR(32) NOT NULL, unidad VARCHAR(8), url VARCHAR(32), cantidad INT, verify VARCHAR(1), telegram VARCHAR(50));

CREATE TABLE pares (id INT NOT NULL, assetin INT NOT NULL, assetout INT NOT NULL, nombre varchar(32) DEFAULT NULL, verify VARCHAR(1) DEFAULT '');

use precios_diario;

CREATE TABLE liquidez (pool_id VARCHAR(4) NOT NULL, liqa1 BIGINT unsigned DEFAULT NULL, liqa2 BIGINT unsingned DEFAULT NULL)

grant all privileges on precios_diario.* to 'test'@'localhost';

grant all privileges on nombres.* to 'test'@'localhost';

flush privileges;

exit


- Then bootstrap software obtaining pairs, decimals file, populating asset info database and first round of prices

cd /scripts/

python3 obtener_pares.py && python3 obtener_nombres.py && python3 obtener_precios_diario.py


- Finally edit crontab to grab prices and new pools automatically. Cron can be substituted by an infinite loop shell script, and scripts are time-agnostic so find the refresh time that works for you and change frontend accordingly

crontab -e

- and add these lines (my setup):

\ */15 * * * * python3 /scripts/obtener_precios_diario.py

\ * */12 * * * python3 /scripts/obtener_nombres.py

\ * 30 */12 * * *  python3 /scripts/obtener_pares.py

\ */15 * * * * python3 /scripts/live/obtener_pools_vivo.py

\ * * * * * python3 /scripts/live/obtener_precios_vivo.py


Finally, change user and password in your PHP files and hardcoded link on scripts.js

Please let me know if inaccuracies found in these ready-to-go how-to
