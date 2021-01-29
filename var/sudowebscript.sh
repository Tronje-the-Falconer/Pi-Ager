#!/bin/bash
#
# web script allowing user www-data to run commands with root privilegs
# shell_exec('/var/sudowebscript.sh PARAMETER snapshot-filename')

# GPIO's aus config.json auslesen
gpio_cooling_compressor=4
gpio_heater=3
gpio_humidifier=18
gpio_circulating_air=24
gpio_exhausting_air=23
gpio_uv_light=25
gpio_light=8
gpio_dehumidifier=7
gpio_voltage=26
gpio_battery=11
gpio_digital_switch=22


# IP-Adresse
MYIP=$(hostname -I | cut -d' ' -f1)

# Zeitstempel
DATE=$(date +"%Y-%m-%d_%H%M%S")

case "$1" in
    startmain) #Starten von main.py
        #python3 /opt/pi-ager/main.py > /dev/null 2>/dev/null &
    	systemctl start pi-ager_main &
    ;;
    pkillmain) #Stoppen von Rss.py
        systemctl stop pi-ager_main &
    ;;
    grepmain) #Überprüfen von Rss.py | ps ax gibt Prozessliste zurück, wird nach grep übergeben und Versionsnummer von Grep wird hinzugefügt, wird dann nach grep nochmals übergeben und nach RSS.py gesucht
        ps ax | grep -v grep | grep main.py
    ;;
    startagingtable) #Starten von agingtable.py
        #python3 /opt/pi-ager/agingtable.py > /dev/null 2>/dev/null &
    	systemctl start pi-ager_agingtable &
    ;;
    pkillagingtable) #Stoppen von agingtable.py
        #pkill -f agingtable.py
    	systemctl stop pi-ager_agingtable &
    ;;
    grepagingtable) #Überprüfen von agintable.py  | ps ax gibt Prozessliste zurück, wird nach grep übergeben und Versionsnummer von Grep wird hinzugefügt, wird dann nach grep nochmals übergeben und nach Reifetab.py gesucht
        ps ax | grep -v grep | grep agingtable.py
    ;;
    startscale) #Starten von scale1.py
        #python3 /opt/pi-ager/scale.py > /dev/null 2>/dev/null &
    	systemctl start pi-ager_scale &
    ;;
    pkillscale) #Stoppen von scale1.py
        #pkill -f scale.py
        systemctl stop pi-ager_scale &
    ;;
    grepscale) #Überprüfen von scale1.py | ps ax gibt Prozessliste zurück, wird nach grep übergeben und Versionsnummer von Grep wird hinzugefügt, wird dann nach grep nochmals übergeben und nach RSS.py gesucht
        ps ax | grep -v grep | grep scale.py
    ;;
    grepbackup) #Überprüfen von pi-ager_backup.sh | ps ax gibt Prozessliste zurück, wird nach grep übergeben und Versionsnummer von Grep wird hinzugefügt, wird dann nach grep nochmals übergeben und nach RSS.py gesucht
        ps ax | grep -v grep | grep pi-ager_backup.sh
    ;;
    
    startwebcam)
        /opt/mjpg-streamer/webcam.sh > /dev/null 2>/dev/null
    ;;
    grepwebcam)
        ps ax | grep -v grep | grep mjpg-streamer
    ;;
    pkillwebcam)
        pkill -f mjpg-streamer
    ;;
    read_gpio_cooling_compressor) # Ansteuern von GPIO Kühlschrankkompressor
        /usr/local/bin/gpio -g read $gpio_cooling_compressor
    ;;
    write_gpio_cooling_compressor_value_to_1) # Ansteuern von GPIO Kühlschrankkompressor
        /usr/local/bin/gpio -g write $gpio_cooling_compressor 1
    ;;
    read_gpio_heater)# Ansteuern von GPIO Heizkabel
        /usr/local/bin/gpio -g read $gpio_heater
    ;;
    write_gpio_heater_value_to_1)# Ansteuern von GPIO Heizkabel
        /usr/local/bin/gpio -g write $gpio_heater 1
    ;;
    read_gpio_humidifier)# Ansteuern von GPIO Luftbefeuchter
        /usr/local/bin/gpio -g read $gpio_humidifier
    ;;
    write_gpio_humidifier_value_to_1)# Ansteuern von GPIO Luftbefeuchter
        /usr/local/bin/gpio -g write $gpio_humidifier 1
    ;;
    read_gpio_circulating_air)# Ansteuern von GPIO Umluftventilator
        /usr/local/bin/gpio -g read $gpio_circulating_air
    ;;
    write_gpio_circulating_air_value_to_1)# Ansteuern von GPIO Umluftventilator
        /usr/local/bin/gpio -g write $gpio_circulating_air 1
    ;;
    read_gpio_exhausting_air)# Ansteuern von GPIO Austauschlüfter
        /usr/local/bin/gpio -g read $gpio_exhausting_air
    ;;
    write_gpio_exhausting_air_value_to_1)# Ansteuern von GPIO Austauschlüfter
        /usr/local/bin/gpio -g write $gpio_exhausting_air 1
    ;;
    read_gpio_uv)# Ansteuern von GPIO UV-Licht
        /usr/local/bin/gpio -g read $gpio_uv_light
    ;;
    write_gpio_uv_value_to_1)# Ansteuern von GPIO UV-Licht
        /usr/local/bin/gpio -g write $gpio_uv_light 1
    ;;
    read_gpio_light)# Ansteuern von GPIO Licht
        /usr/local/bin/gpio -g read $gpio_light
    ;;
    write_gpio_light_value_to_1)# Ansteuern von GPIO Licht
        /usr/local/bin/gpio -g write $gpio_light 1
    ;;
    read_gpio_dehumidifier)# Ansteuern von GPIO Entfeuchter
        /usr/local/bin/gpio -g read $gpio_dehumidifier
    ;;
    write_gpio_dehumidifier_value_to_1)# Ansteuern von GPIO Entfeuchter
        /usr/local/bin/gpio -g write $gpio_dehumidifier 1
    ;;
    read_gpio_voltage)# Ansteuern von GPIO Stromspannung
        /usr/local/bin/gpio -g read $gpio_voltage
    ;;
    read_gpio_battery)# Ansteuern von GPIO Batterie
        /usr/local/bin/gpio -g read $gpio_battery
    ;;
    read_gpio_digital_switch)# Ansteuern von GPIO Schalter
        /usr/local/bin/gpio -g read $gpio_digital_switch
    ;;
    reboot) # reboot
        sleep 3
        reboot
    ;;
    shutdown) #Shutdown 
        sleep 3
        shutdown -h now
    ;;
    savewebcampicture) # macht ein Bild mit der Webcam
        #curl -s -m 5 -o /var/www/images/webcam/snap_$DATE.jpg http://$MYIP:8080/?action=snapshot
        fswebcam -r 640X480 -S 10 $2
    ;;
    ziplogfiles) # Zippt alle logfiles
        pushd /var/www/ && zip -r /var/www/logs/pi-ager_logfiles.zip ./logs/ && popd
        #zip -r -j /var/www/logs/pi-ager_logfiles.zip /var/www/logs/
    ;;
    delete_snapshot_files) # delete all .jpg files from folder /var/www/images/webcam/ 
        rm /var/www/images/webcam/*.jpg
    ;;
    backup) # Backupscript ausfuehren
        /usr/local/bin/pi-ager_backup.sh >> /var/log/pi-ager_backup.log  
    ;;
    test_mailserver) 
            python3 /opt/pi-ager/pi_ager_test_mail.py
    ;;
    test_pushover) 
            python3 /opt/pi-ager/pi_ager_test_pushover.py
    ;;
    test_telegram) 
            python3 /opt/pi-ager/pi_ager_test_telegram.py
    ;;
    encrypt_password) # $2 ist unverschluesseltes passwort
            python3 /opt/pi-ager/pi_ager_encrypt_password.py $2 #'base64'
    ;;
    sensorbusi2c) #Sensorbus wurde geaendert auf i2c
		# hier muss alles hin was vor dem shutdown gemacht werden soll, um auf i2c zu wechseln (SHT3x und SHT85)
        rm -r /etc/modprobe.d/Pi-Ager_i2c_off.conf
        sleep 3
        shutdown -h now
    ;;
    sensorbus1wire) #Sensorbus wurde geaendert auf 1wire
		# hier muss alles hin was vor dem shutdown gemacht werden soll, um auf 1wire zu wechseln (DHT* und SHT75)
        cp /etc/modprobe.d/Pi-Ager_i2c_off.conf.on /etc/modprobe.d/Pi-Ager_i2c_off.conf
        sleep 3
        shutdown -h now
    ;;
    *) echo "ERROR: invalid parameter: $1 (for $0)"; exit 1 #Fehlerbehandlung
    ;;
esac

exit 0
