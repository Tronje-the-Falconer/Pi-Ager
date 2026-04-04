#!/bin/bash
#
# web script allowing user www-data to run commands with root privilegs
# shell_exec('/var/sudowebscript.sh PARAMETER snapshot-filename')

# set -x
# trap read debug

LOGFILE="/var/log/sudowebscript.log"

log() {
    echo "$(date '+%Y-%m-%d %H:%M:%S') $1" | tee -a "$LOGFILE"
    logger -t sudowebscript "$1"
}

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
        sleep 4
        reboot
    ;;
    shutdown) #Shutdown 
        sleep 4
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
    nm_set_pw_ssid)  # set pwd and ssid for wlan0 for existing connection
        PI_AGER_WLAN0_NAME="pi-ager-wlan0"
        log " Evaluate ST_CON "
        STA_CON=$(nmcli -g GENERAL.CONNECTION dev show wlan0)
        log " connection on wlan0 : $STA_CON "
        if [ -z "$STA_CON" ] || [ "$STA_CON" = "--" ]; then
            log " wlan0 connection not found. Create new connection with name $PI_AGER_WLAN0_NAME"
            nmcli dev wifi connect "$3" password "$2" ifname wlan0 name "$PI_AGER_WLAN0_NAME"
            nmcli con mod "$PI_AGER_WLAN0_NAME" connection.autoconnect yes connection.autoconnect-retries 0 connection.auth-retries 5
            nmcli con mod "$PI_AGER_WLAN0_NAME" 802-11-wireless.wake-on-wlan default
        else
            log " modify existing connection ${STA_CON} with new pwd: $2 and new ssid: $3"
            nmcli connection modify "$STA_CON" wifi-sec.psk "$2" 802-11-wireless.ssid "$3"
        fi
    ;;    
    *) echo "ERROR: invalid parameter: $1 (for $0)"; exit 1 #Fehlerbehandlung
    ;;
esac

exit 0
