How-to install:

In a linux box with apache,php & mysql, as root:

Unpack zip


mkdir /scripts/

cp *.py /scripts/

cp charts/* /var/www/html/


Enter mysql console

create user 'test'@'localhost' identified by 'yourpassword';

create database precios_diario;

create database nombres;

use nombres;

create table nombres (asset_id INT NOT NULL PRIMARY KEY, nombre VARCHAR(32) NOT NULL, unidad VARCHAR(8), url VARCHAR(32), cantidad INT);

grant all privileges on precios_diario.* to 'test'@'localhost';

grant all privileges on nombres.* to 'test'@'localhost';

flush privileges;

exit


Then bootstrap the software

cd /scripts/

python3 obtener_pares.py && python3 obtener_decimales.py && python3 obtener_nombres.py && python3 obtener_precios_diario.py



Finally edit crontab to grab prices and new pools automatically

crontab -e

and add these lines:

*/15 * * * * python3 /scripts/obtener_precios_diario.py

35 11 * * * python3 /scripts/obtener_pares.py

40 11 * * * python3 /scripts/obtener_decimales.py

45 11 * * * python3 /scripts/obtener_nombres.py

Finally, change links in the php scripts to your domain/server
