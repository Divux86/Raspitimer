# Raspitimer

## Descripcion.

Proyecto creado por David Prado Mejuto para el trabajo de fin de grado de ASIR 2020-2022.

## Paquetes y versiones.
- *apache2* (2.4)
- *php* (7.3)
- *php-mysql*
- *mariadb-server* (10.3)
- *libapache2-mod-php*
- *Python 3*
- *python3-mysqldb* (con conexion a internet no es necesario instalarlo)

## Estructura.
- La carpeta laptimer2/* debe ir en /home/pi.
- El archivo install.sh debe ser ejecutado, esto instala los drivers de la pantalla LCD.
- La carpeta html con todas las paginas debe ir en /var/www.
- Necesario crear un usuario llamado "admin" con password "rasberry", en caso de querer usar otro usuario modificar todos los archivos .PHP de la carpeta /var/www/html y modificar el codigo de Python.

## Componentes necesarios.
- RaspberryPI zero.
- Sensor IR.
- 3 botones. 
- Buzzer o altavoz.
- Pantalla LCD 20x4 con modulo I2C.


## Raspberry GPIO
- Pantalla LCD : 
 -> Pin SDA a GPIO2
 -> Pin SCL a GPIO3
 -> Pin GND a tierra
 -> Pin VCC a 5V
 
- Sensor IR: 
 -> Pin de datos número 16
 -> Pin VCC a corriente de 5V
 
- Buzzer : 
 -> Pin de datos número 18
 
- Boton  de reset: 
 -> Pin de datos número 36
 
- Boton  de vueltas: 
 -> Pin de datos número 21
 
- Boton de jugador : 
 -> Pin de datos número 10
