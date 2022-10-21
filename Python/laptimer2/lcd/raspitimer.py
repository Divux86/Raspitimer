
#! /usr/bin/env python
#programa hecho por David Prado Mejuto para el proyecto de fin de curso de ASIR 2
# Importando las librerias que se van a usar en el Programa
import drivers
import RPi.GPIO as GPIO
import MySQLdb
from gpiozero import Button
import time
import os

#genero las variable de tiempo de inicio 
starttime=time.time()
lasttime=starttime
totaltime=starttime
#esta variable indica si es la primera vuelta o no, por defecto es la primera vuelta
firsttrigered=True
#genero las variables que voy a usar 
jugador=0
besttime=99999
newbesttime=0
newbest=False
#contador de vueltas actuales tanto para el modo libre como para el modo carrera
lapnum=1
#contador de vueltas totales del modo libre y modo carrera
lapsracemode=0
lapsfreemode=0

# la variable mode sirve para saber que modo se esta jugando, mode = 1 es modo libre, mode =2 es modo carrera
#mode seleccted me permite saber si ya se ha seleccionado el modo o no
mode=0
modeselected = False
#pins GPIO
sensor = 16
buzzer = 18
botonvueltas = 21 #Boton 1 azul
botonjugador = 10 #Boton 2 rojo
botonreiniciar = 36 #boton amarillo
#GPIO.setwarnings(False)
GPIO.setmode(GPIO.BOARD)
GPIO.setup(botonvueltas, GPIO.IN, pull_up_down=GPIO.PUD_UP)
GPIO.setup(botonjugador, GPIO.IN, pull_up_down=GPIO.PUD_UP)
GPIO.setup(botonreiniciar, GPIO.IN, pull_up_down=GPIO.PUD_UP)
GPIO.setup(sensor,GPIO.IN)
GPIO.setup(buzzer,GPIO.OUT)

GPIO.output(buzzer,False)

print ("script corriendo..........")
# Load the driver and set it to "display"
display = drivers.Lcd()
display.lcd_clear()
display.lcd_display_string("introduce el modo",1)
display.lcd_display_string("boton 1: free mode",2)
display.lcd_display_string("boton 2: race mode",3)

#conecto con la base de datos
db = MySQLdb.connect("localhost", "admin", "raspberry", "modo2")
try:
    while True:
#boton  para reiniciar el script, 
        if GPIO.input(botonreiniciar) == GPIO.LOW:
            os.system("python /home/pi/laptimer2/lcd/raspitimer.py")
            exit()    

#Boton para seleccionar el modo libre
        if modeselected==False and GPIO.input(botonvueltas) == GPIO.LOW:  
            mode=1
            modeselected=True
            #limpio la pantalla
            display.lcd_clear()
            #hago que aparezca el mensaje de modo libre por un segundo
            display.lcd_display_string("Modo Libre",1)
            time.sleep(1)
            #una vez esperado un segundo, limpio otra vez la pantalla y muestro el texto 
            #para cambiar las vuetlas del modo libre
            display.lcd_clear()
            display.lcd_display_string("introduce las vueltas",1)
            display.lcd_display_string("Vueltas: ",3)

#Boton para seleccionar el modo carrera
        if modeselected==False and GPIO.input(botonjugador) == GPIO.LOW:
            mode=2
            modeselected=True
            #limpio la pantalla LCD
            display.lcd_clear()
            display.lcd_display_string("Modo Carrera",1)

          #abro el cursos para sacar la ID de la carrera seleccionada para correr
          # y la introduzco en al variable "currace"
            cursor = db.cursor()
            cursor.execute("SELECT * FROM currace")
            for row in cursor.fetchall():
                currace=row[0]
            
            #ahora con el id de carrera, saco las vuelta que tiene esa carrera
            vueltaslcd = "SELECT vueltas FROM races where id_race = '" + str(currace) + "'"
            cursor.execute(vueltaslcd)
            for row in cursor.fetchall():
            #e introduzco las vultas que he sacado de la tabla races en la variable
            #lapsracemode que es la variable de las vueltas totales que se van a hacer
                lapsracemode=row[0]
            cursor.close()

            time.sleep(1)
            display.lcd_clear()
            display.lcd_display_string("Introduce el jugador",1)
            display.lcd_display_string("Jugador: ",2)
            vueltaslcd = "Vueltas: " + str(lapsracemode)
            display.lcd_display_string(vueltaslcd,3)


#------------BOTONES para cambiar los jugadores y las vueltas-----------
# boton de cambiar las vueltas para el modo libre  
        if GPIO.input(botonvueltas) == GPIO.LOW and modeselected==True and mode==1:
        #time.sleep (0.3) sirve para tener un siempo de espera entre pulsacion y
        #pulsacion del boton, impidiendo falsas pulsaciones
                  time.sleep(0.3)
        #se suma en 1 la varible laspfreemode
                  lapsfreemode = lapsfreemode+1
        #se muestra por pantalla el cambio de las vueltas
                  vueltaslcd = "Vueltas: " + str(lapsfreemode)
                  display.lcd_display_string(vueltaslcd,3)



# boton de cambiar el jugador para el modo carrera        
        if GPIO.input(botonjugador) == GPIO.LOW and modeselected==True and mode==2:
          #time.sleep (0.3) sirve para tener un siempo de espera entre pulsacion y
        #pulsacion del boton, impidiendo falsas pulsaciones
                  time.sleep(0.3)
                  #se suma en 1 la varible laspfreemode
                  jugador=jugador+1
                   #se muestra por pantalla el cambio del jugador
                  jugadorlcd = "Jugador: " + str(jugador)
                  display.lcd_display_string(jugadorlcd,2)

#inicio de los timers
#si se detecta el sensor ,es la primera vez que se detecta algo se ejecuta este codigo
        if GPIO.input(sensor) != True and firsttrigered == True and lapnum<=lapsfreemode and mode==1:
                    #limpio el LCD
                    display.lcd_clear()
                    display.lcd_display_string("Go!Go!Go!",1)
                    #reinicio el tiempo de inicio y cambio el firsttrigered a false
                    starttime=time.time()
                    lasttime=starttime
                    #cambio la variable de primer inicio a falso para que cuente la segunda vuelta
                    #en el siguiente pulso del sensor
                    firsttrigered=False

                    #hago pitar el buzzer para mostrar que se ha empezado a contar la primera vuelta
                    GPIO.output(buzzer, GPIO.HIGH)
                    time.sleep(0.4)
                    GPIO.output(buzzer, GPIO.LOW)
                    time.sleep(0.1)

#inicio del script principal
      #if mode = libre
        if GPIO.input(sensor) != True and firsttrigered == False and lapnum<=lapsfreemode and mode==1:
            #limpio el LCD
            display.lcd_clear()
            #genero las variables de los tiempos tiempo total y tiempo de vuelta
            laptime=round((time.time() - lasttime), 3)
            #genero el tiempo total, si la vuelta es  la primera el tiempo total es el tiempo de la primera vuelta
            #si no lo es, se suma el tiempo de la primera vuelta al siguiente.  
            if lapnum == 1:
                totaltime=laptime
            else:
                totaltime=totaltime+laptime

            #genero los datos del mejor tiempo, se compara el el laptime con el best time, si el tiempo de la ultima vuelta ha sido mejor que el 
            #de la mejor vuelta, la variable besttime se actualiza
            if laptime < besttime:
                besttime = laptime
                tmejor = "Best: " + str(besttime)
                GPIO.output(buzzer, GPIO.HIGH)
                time.sleep(0.1)
                GPIO.output(buzzer, GPIO.LOW)
                    
            #genero las strings para mostrar las vueltas
            vuelta = "Lap: " + str(lapnum)
            total = "Total: " + str(round(totaltime,4))
            tultima = "Last: " + str(laptime)

            #muestro los tiempos por el LCD
            display.lcd_display_string(vuelta,1)
            display.lcd_display_string(total,2)
            display.lcd_display_extended_string(tultima,3)
            display.lcd_display_string(tmejor,4)

            #actualizo la variable lasttime para hacer la diferencia con laptime en la siguiente vuelta
            lasttime=time.time()

            if lapnum == lapsfreemode :
                GPIO.output(buzzer, GPIO.HIGH)
                time.sleep(1)
                GPIO.output(buzzer, GPIO.LOW)
                        
            lapnum=lapnum+1

#si se detecta el sensor y es la primera vez que se detecta se ejecuta este codigo
        if GPIO.input(sensor) != True and firsttrigered == True and lapnum<=lapsracemode and mode==2 and jugador!=0:
                    #limpio el LCD
                    display.lcd_clear()
                    display.lcd_display_string("Go!Go!Go!",1)
                    #reinicio el tiempo de inicio y cambio el firsttrigered a false
                    starttime=time.time()
                    lasttime=starttime
                    #cambio la variable de primer inicio a falso para que cuente la segunda vuelta
                    #en el siguiente pulso del sensor
                    firsttrigered=False

                    #suena el altavoz
                    GPIO.output(buzzer, GPIO.HIGH)
                    time.sleep(0.4)
                    GPIO.output(buzzer, GPIO.LOW)
                    time.sleep(0.1) 
                    
      #if mode = carrera
        if GPIO.input(sensor) != True and firsttrigered == False and lapnum<=lapsracemode and mode==2 and jugador!=0:
                #limpio el LCD
                display.lcd_clear()
                #genero las variables de los tiempos tiempo total y tiempo de vuelta
                laptime=round((time.time() - lasttime), 3)
                #genero el tiempo total, si la vuelta es  la primera el tiempo total es el tiempo de la primera vuelta
                #si no lo es, se suma el tiempo de la primera vuelta al siguiente.
                if lapnum == 1:
                    totaltime=laptime
                else:
                    totaltime=totaltime+laptime

                #genero los datos dekl mejor tiempo, se compara el el laptime con el best time, si el tiempo de la ultima vuelta ha sido mejor que el 
                #de la mejor vuelta, la variable besttime se actualiza
                if laptime < besttime:
                    besttime = laptime
                    tmejor = "Best: " + str(besttime)
                    GPIO.output(buzzer, GPIO.HIGH)
                    time.sleep(0.1)
                    GPIO.output(buzzer, GPIO.LOW)
                    
                #genero las strings para mostrar las vueltas
                vuelta = "Lap: " + str(lapnum)+ "  " + "Player: " + str (jugador)
                total = "Total: " + str(round(totaltime,4))
                tultima = "Last: " + str(laptime)
                #muestro los tiempos por el LCD
                display.lcd_display_string(vuelta,1)
                display.lcd_display_string(total,2)
                display.lcd_display_extended_string(tultima,3)
                display.lcd_display_string(tmejor,4)

                #genero el curso mysql
                cursor = db.cursor()
                #hago el insert del tiempo de vuelta a la tabla resultados
                insert = "insert ignore into resultados values (" + str(currace) + " , " + str (jugador) + " , " + str(lapnum) + " , " + str(laptime) + ")"
                #ejecuto el insert
                cursor.execute(insert)
                #hago el commit de mysql
                db.commit()

                #actualizo la variable lasttime para hacer la diferencia con laptime en el siguiente ciclo del bucle
                lasttime=time.time()

                # si la vuelta actual es igual que la ultima vuelta se cierra el cursor y la conexion con la base de datos
                #ademas se hace pitar el altavoz
                if lapnum == lapsracemode:
                  #cierro el cursor 
                    cursor.close()
                    db.close()
                  #hago sonar el altavoz
                    GPIO.output(buzzer, GPIO.HIGH)
                    time.sleep(1)
                    GPIO.output(buzzer, GPIO.LOW)

                #se suma en +1 la variable laptnum
                lapnum=lapnum+1


except KeyboardInterrupt:
    # (si presionas ctrl+c), el programa de para y se limpia todo
    GPIO.cleanup()
    print ("programa interrumpido, limpiando LCD")



