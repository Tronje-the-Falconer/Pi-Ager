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
        /usr/local/bin/pi-ager_backup.sh &
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
    nm_set_pw_ssid)  # set wlan country and add a new connection with pwd and ssid for wlan0 
        COUNTRY=$4
        SSID=$3
        WLAN_KEY=$2 
        INTERFACE="wlan0"
        CONNECTION_NAME="PI_AGER_STA"
        MAX_RETRIES=5
        RETRY_DELAY=5               # Sekunden zwischen den Versuchen
        # ─────────────────────────────────────────────────────────────
        validation_failed=0

        # 1) SSID: darf nicht leer sein
        if [[ -z "$SSID" ]]; then
            log "Validierung fehlgeschlagen: SSID darf nicht leer sein."
            validation_failed=1
        fi

        # 2) WLAN_KEY: mindestens 8 Zeichen
        if [[ ${#WLAN_KEY} -lt 8 ]]; then
            log "Validierung fehlgeschlagen: WLAN_KEY muss mindestens 8 Zeichen lang sein (aktuell: ${#WLAN_KEY})."
            validation_failed=1
        fi

        # 3) COUNTRY: genau 2 Zeichen, ausschließlich A-Z
        if [[ ! "$COUNTRY" =~ ^[A-Z]{2}$ ]]; then
            log "Validierung fehlgeschlagen: COUNTRY muss genau 2 Großbuchstaben enthalten (z.B. 'DE'). Aktueller Wert: '$COUNTRY'."
            validation_failed=1
        fi

        if [[ $validation_failed -ne 0 ]]; then
            log "Abbruch wegen Konfigurationsfehlern. Bitte obige Meldungen korrigieren."
            exit 1
        fi

        log "Eingabe-Validierung bestanden (SSID, WLAN_KEY, COUNTRY)."

        # ════════════════════════════════════════════════════════════
        # ── WLAN-Ländercode setzen ───────────────────────────────────
        # ════════════════════════════════════════════════════════════

        log "Setze WLAN-Ländercode auf '$COUNTRY' via raspi-config ..."

        raspi-config nonint do_wifi_country "$COUNTRY"

        if [[ $? -ne 0 ]]; then
            log "Fehler beim Setzen des WLAN-Ländercodes. Abbruch."
            exit 1
        fi

        log "WLAN-Ländercode '$COUNTRY' erfolgreich gesetzt."

        # ════════════════════════════════════════════════════════════
        # ── Altes Verbindungsprofil entfernen (falls vorhanden) ──────
        # ════════════════════════════════════════════════════════════

        if nmcli connection show "$CONNECTION_NAME" &>/dev/null; then
            log "Altes Profil '$CONNECTION_NAME' wird gelöscht..."
            nmcli connection delete "$CONNECTION_NAME" &>/dev/null
        fi

        # ════════════════════════════════════════════════════════════
        # ── Verbindungsprofil anlegen ────────────────────────────────
        # ════════════════════════════════════════════════════════════

        log "Erstelle Verbindungsprofil '$CONNECTION_NAME' für SSID: $SSID"

        nmcli connection add \
            type wifi \
            ifname "$INTERFACE" \
            con-name "$CONNECTION_NAME" \
            ssid "$SSID" \
            wifi-sec.key-mgmt wpa-psk \
            wifi-sec.psk "$WLAN_KEY" \
            connection.autoconnect yes \
            connection.autoconnect-retries 0 \
            connection.auth-retries 5

        if [[ $? -ne 0 ]]; then
            log "Verbindungsprofil konnte nicht erstellt werden. Abbruch."
            exit 1
        fi

        # ════════════════════════════════════════════════════════════
        # ── Verbindungsversuche ──────────────────────────────────────
        # ════════════════════════════════════════════════════════════

        attempt=1

        while [[ $attempt -le $MAX_RETRIES ]]; do
            log "Verbindungsversuch $attempt / $MAX_RETRIES ..."

            nmcli connection up "$CONNECTION_NAME" ifname "$INTERFACE"

            if [[ $? -eq 0 ]]; then
                # Zusätzliche Verifikation: IP-Adresse vorhanden?
                sleep 2
                IP=$(ip -4 addr show "$INTERFACE" | awk '/inet / {print $2}' | head -1)

                if [[ -n "$IP" ]]; then
                    log "Verbindung erfolgreich! Interface: $INTERFACE | IP: $IP"
                    exit 0
                else
                    log "nmcli meldete Erfolg, aber keine IP erhalten – Versuch gilt als fehlgeschlagen."
                fi
            else
                log "Verbindungsversuch $attempt fehlgeschlagen."
            fi

            if [[ $attempt -lt $MAX_RETRIES ]]; then
                log "Warte $RETRY_DELAY Sekunden bis zum nächsten Versuch..."
                sleep "$RETRY_DELAY"
            fi

            (( attempt++ ))
        done

        # ════════════════════════════════════════════════════════════
        # ── Alle Versuche erschöpft ──────────────────────────────────
        # ════════════════════════════════════════════════════════════

        log "Verbindung zu '$SSID' nach $MAX_RETRIES Versuchen nicht möglich. Abbruch."

        # Profil aufräumen
        nmcli connection delete "$CONNECTION_NAME" &>/dev/null
        exit 1
    ;;    
    *) echo "ERROR: invalid parameter: $1 (for $0)"; exit 1 #Fehlerbehandlung
    ;;
esac

exit 0
