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
    startfirmwareprog) # start tft display firmware programming
        python3 /opt/pi-ager/piager_upload_firmware.py /dev/serial0 $2 >/dev/null 2>/dev/null &
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
        fswebcam --fps 30 -r 640x480 -S 20 $2
    ;;
    ziplogfiles) # Zippt alle logfiles
        pushd /var/www/ && zip -r /var/www/logs/pi-ager_logfiles.zip ./logs/ && popd
        #zip -r -j /var/www/logs/pi-ager_logfiles.zip /var/www/logs/
    ;;
    delete_snapshot_files) # delete all .jpg files from folder /var/www/images/webcam/ 
        rm /var/www/images/webcam/*.jpg
    ;;
    backup) # Backupscript ausfuehren
        /usr/local/bin/pi-ager_backup.sh >> /var/www/logs/pi-ager_backup.log &
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
#        sleep 3
#        shutdown -h now
    ;;
    sensorbus1wire) #Sensorbus wurde geaendert auf 1wire
		# hier muss alles hin was vor dem shutdown gemacht werden soll, um auf 1wire zu wechseln (DHT* und SHT75)
        cp /etc/modprobe.d/Pi-Ager_i2c_off.conf.on /etc/modprobe.d/Pi-Ager_i2c_off.conf
#        sleep 3
#        shutdown -h now
    ;;
    set_time_date)  # set system time and date when pi-ager is in hotspot mode
        systemctl stop systemd-timesyncd.service
        timedatectl set-time "$2"
        systemctl start systemd-timesyncd.service
        systemctl daemon-reload
    ;;
    *) echo "ERROR: invalid parameter: $1 (for $0)"; exit 1 #Fehlerbehandlung
    ;;
esac

exit 0
