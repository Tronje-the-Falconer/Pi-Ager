#!/bin/bash
# File: setup_pi-ager.sh
# START
#crontab richtigstellen 
# sed -n '/reboot/!p' /etc/crontab >/etc/crontabtemp ; mv -f /etc/crontabtemp /etc/crontab # sed sucht | -n abstellen von ausgabe auf Konsole 

INTERFACE="wlan0"
CONNECTION_NAME="PI_AGER_STA"

MAX_RETRIES=5
RETRY_DELAY=5               # Sekunden zwischen den Versuchen
# ─────────────────────────────────────────────────────────────

# Farbausgabe
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

log()     { echo -e "[$(date '+%H:%M:%S')] $*"; }
log_ok()  { echo -e "${GREEN}[$(date '+%H:%M:%S')] $*${NC}"; }
log_warn(){ echo -e "${YELLOW}[$(date '+%H:%M:%S')] $*${NC}"; }
log_err() { echo -e "${RED}[$(date '+%H:%M:%S')] $*${NC}"; }

log
log "------------------------------------------------------"
log "Config Start"
log "------------------------------------------------------"

if [ ! -e /boot/firmware/setup.txt ]; then           #wenn setup.txt nicht existiert
    log "Setup.txt does not exist, exit"
    exit 1
fi

log "Setup.txt exists, getting configuration parameter"
eval $(grep -i "^piname=" /boot/firmware/setup.txt| tr -d "\n\r")
eval $(grep -i "^pipass=" /boot/firmware/setup.txt| tr -d "\n\r")
eval $(grep -i "^rootpass=" /boot/firmware/setup.txt| tr -d "\n\r")
eval $(grep -i "^webguipw=" /boot/firmware/setup.txt| tr -d "\n\r")
eval $(grep -i "^dbpw=" /boot/firmware/setup.txt| tr -d "\n\r")
eval $(grep -i "^wlanssid=" /boot/firmware/setup.txt| tr -d "\n\r")
eval $(grep -i "^wlankey=" /boot/firmware/setup.txt| tr -d "\n\r")
eval $(grep -i "^country=" /boot/firmware/setup.txt| tr -d "\n\r")
eval $(grep -i "^keepconf=" /boot/firmware/setup.txt| tr -d "\n\r")
eval $(grep -i "^sensor=" /boot/firmware/setup.txt | tr -d "\n\r")    
eval $(grep -i "^hmidisplay=" /boot/firmware/setup.txt | tr -d "\n\r")
eval $(grep -i "^sensor=" /boot/firmware/setup.txt | tr -d "\n\r")  
log "configuration parameter:"
log "Hostname = $piname"
#log $pipass
#log $rootpass
#log $webguipw
log "WLAN SSID = $wlanssid"
log "Country = $country"
#log $wlankey
log "Config behalten = $keepconf"
log "sensor = $sensor"    
log "hmidisplay = $hmidisplay"

#  log "SSH Host Key generieren"
# SSH Host Key generieren
#  /bin/rm -fv /etc/ssh_host_*
#/usr/sbin/dpkg-reconfigure openssh-server
#  systemctl restart ssh.service
# pi Rechnername setzen
if [ -n "$piname" ]            #wenn nicht ""
then
    # CURRENT_HOSTNAME=`cat /etc/hostname | tr -d " \t\n\r"`
    # echo -n $piname > /etc/hostname
    # sed -i "s/127.0.1.1.*$CURRENT_HOSTNAME/127.0.1.1\t$piname/g" /etc/hosts
    raspi-config nonint do_hostname $piname
    log "Hostname gesetzt"
fi

# pi pass setzen
if [ -n "$pipass" ]           #wenn nicht ""
then
    echo "pi:$pipass" | chpasswd 
    log "Passwort pi gesetzt"
fi

# root pass setzen
if [ -n "$rootpass" ]         #wenn nicht ""
then
    echo "root:$rootpass" | chpasswd 
    log "Passwort root gesetzt"
fi

# phpliteadmin pass setzen
if [ -n "$dbpw" ]         #wenn nicht ""
then
    sed -i "s/raspberry/$dbpw/g" /var/www/phpliteadmin.config.php
    log "phpliteadmin.config.php gesetzt"
fi

# settings pass setzen oder direkt mit https://websistent.com/tools/htdigest-generator-tool/ 
if [ -n "$webguipw" ]         #wenn nicht ""
then
    user="pi-ager"
    realm="Pi-Ager"
    digest="$( printf "%s:%s:%s" "$user" "$realm" "$webguipw" | md5sum | awk '{print $1}' )"
    
	rm /var/.htcredentials
	echo "$user:$realm:$digest" > /var/.htcredentials
    log "Passwort webgui gesetzt"
fi


# check sensor
sensorbus=0
sensornum=5
case $sensor in
  "DHT11") sensorbus=1; sensornum=1;;
  "DHT22") sensorbus=1; sensornum=2;;
  "SHT75") sensorbus=1; sensornum=3;;
  "SHT85") sensorbus=0; sensornum=4;;
  "SHT3x") sensorbus=0; sensornum=5;;
  "SHT3x-mod") sensorbus=0; sensornum=6;;
  "AHT1x") sensorbus=0; sensornum=7;; 
  "AHT1x-mod") sensorbus=0; sensornum=8;;       
  "AHT2x") sensorbus=0; sensornum=9;;
  "AHT30") sensorbus=0; sensornum=10;;      
  "AHT4x-A") sensorbus=0; sensornum=11;;
  "AHT4x-B") sensorbus=0; sensornum=12;;
  "AHT4x-C") sensorbus=0; sensornum=13;;      
esac
log "Bus = $sensorbus  sensor = $sensornum"

if [ -n "$sensornum" ]    # wenn nicht ""
then
    sqlite3 /var/www/config/pi-ager.sqlite3 "PRAGMA journalMode = wal; UPDATE config SET value=$sensorbus WHERE key='sensorbus';"
    sqlite3 /var/www/config/pi-ager.sqlite3 "PRAGMA journalMode = wal; UPDATE config SET value=$sensornum WHERE key='sensortype';"
    log "sensorbus und sensornum in DB gesetzt"
fi

if [ $sensorbus -eq 0 ]; then
# hier muss alles hin was vor dem shutdown gemacht werden soll, um auf i2c zu wechseln
#        rm -f /etc/modprobe.d/Pi-Ager_i2c_off.conf
    log "i2c is active"
elif [ $sensorbus -eq 1 ]; then
# hier muss alles hin was vor dem shutdown gemacht werden soll, um auf 1wire zu wechseln
#        cp /etc/modprobe.d/Pi-Ager_i2c_off.conf.on /etc/modprobe.d/Pi-Ager_i2c_off.conf
    log "1-wire is active"
fi

log "WLAN Verbindung aufbauen"
# ════════════════════════════════════════════════════════════
# ── Eingabe-Validierung ──────────────────────────────────────
# ════════════════════════════════════════════════════════════

validation_failed=0

# 1) SSID: darf nicht leer sein
if [[ -z "$wlanssid" ]]; then
    log_err "Validierung fehlgeschlagen: SSID darf nicht leer sein."
    validation_failed=1
fi

# 2) WLAN_KEY: mindestens 8 Zeichen
if [[ ${#wlankey} -lt 8 ]]; then
    log_err "Validierung fehlgeschlagen: WLAN_KEY muss mindestens 8 Zeichen lang sein (aktuell: ${#wlankey})."
    validation_failed=1
fi

# 3) country: genau 2 Zeichen, ausschließlich A-Z
if [[ ! "$country" =~ ^[A-Z]{2}$ ]]; then
    log_err "Validierung fehlgeschlagen: country muss genau 2 Großbuchstaben enthalten (z.B. 'DE'). Aktueller Wert: '$country'."
    validation_failed=1
fi

if [[ $validation_failed -ne 0 ]]; then
    log_err "Abbruch wegen Konfigurationsfehlern. Bitte obige Meldungen korrigieren."
    exit 1
fi

log_ok "Eingabe-Validierung bestanden (SSID, WLAN_KEY, Country-Code: $country)."

# ════════════════════════════════════════════════════════════
# ── Voraussetzungen prüfen ───────────────────────────────────
# ════════════════════════════════════════════════════════════

if ! command -v nmcli &>/dev/null; then
    log_err "nmcli nicht gefunden. Bitte NetworkManager installieren."
    exit 1
fi

if ! command -v raspi-config &>/dev/null; then
    log_err "raspi-config nicht gefunden. Läuft das Script auf einem Raspberry Pi mit Pi-OS?"
    exit 1
fi

if ! ip link show "$INTERFACE" &>/dev/null; then
    log_err "Interface '$INTERFACE' nicht gefunden."
    exit 1
fi

# ════════════════════════════════════════════════════════════
# ── WLAN-Ländercode setzen ───────────────────────────────────
# ════════════════════════════════════════════════════════════

log "Setze WLAN-Ländercode auf '$country' via raspi-config ..."

raspi-config nonint do_wifi_country "$country"

if [[ $? -ne 0 ]]; then
    log_err "Fehler beim Setzen des WLAN-Ländercodes. Abbruch."
    exit 1
fi

log_ok "WLAN-Ländercode '$country' erfolgreich gesetzt."

# ════════════════════════════════════════════════════════════
# ── Altes Verbindungsprofil entfernen (falls vorhanden) ──────
# ════════════════════════════════════════════════════════════

if nmcli connection show "$CONNECTION_NAME" &>/dev/null; then
    log_warn "Altes Profil '$CONNECTION_NAME' wird gelöscht..."
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
    ssid "$wlanssid" \
    wifi-sec.key-mgmt wpa-psk \
    wifi-sec.psk "$wlankey" \
    connection.autoconnect yes \
    connection.autoconnect-retries 0 \
    connection.auth-retries 5

if [[ $? -ne 0 ]]; then
    log_err "Verbindungsprofil konnte nicht erstellt werden. Abbruch."
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
            log_ok "Verbindung erfolgreich! Interface: $INTERFACE | IP: $IP"
            break
        else
            log_warn "nmcli meldete Erfolg, aber keine IP erhalten – Versuch gilt als fehlgeschlagen."
        fi
    else
        log_warn "Verbindungsversuch $attempt fehlgeschlagen."
    fi

    if [[ $attempt -lt $MAX_RETRIES ]]; then
        log "Warte $RETRY_DELAY Sekunden bis zum nächsten Versuch..."
        sleep "$RETRY_DELAY"
        (( attempt++ ))
    else
        # ════════════════════════════════════════════════════════════
        # ── Alle Versuche erschöpft ──────────────────────────────────
        # ════════════════════════════════════════════════════════════

        log_err "Verbindung zu '$wlanssid' nach $MAX_RETRIES Versuchen nicht möglich. Abbruch."

        # Profil aufräumen
        nmcli connection delete "$CONNECTION_NAME" &>/dev/null

        exit 1
    fi
done

# load firmware into HMI display if hmidisplay != "none"
case $hmidisplay in
    "NX3224K028") log "start firmware upload for HMI display device $hmidisplay"
                  nextion-fw-upload /dev/serial0 /var/www/nextion/NX3224K028/pi-ager.tft
                  log "firmware upload for HMI device finished";;
    "NX3224T028") log "start firmware upload for HMI display device $hmidisplay"
                  nextion-fw-upload /dev/serial0 /var/www/nextion/NX3224T028/pi-ager.tft
                  log "firmware upload for HMI device finished";;
    "NX3224F028") log "start firmware upload for HMI display device $hmidisplay"
                  nextion-fw-upload /dev/serial0 /var/www/nextion/NX3224F028/pi-ager.tft
                  log "firmware upload for HMI device finished";;
    "NX3224K024") log "start firmware upload for HMI display device $hmidisplay"
                  nextion-fw-upload /dev/serial0 /var/www/nextion/NX3224K024/pi-ager.tft
                  log "firmware upload for HMI device finished";;
    "NX3224T024") log "start firmware upload for HMI display device $hmidisplay"
                  nextion-fw-upload /dev/serial0 /var/www/nextion/NX3224T024/pi-ager.tft
                  log "firmware upload for HMI device finished";;
    "NX3224F024") log "start firmware upload for HMI display device $hmidisplay"
                  nextion-fw-upload /dev/serial0 /var/www/nextion/NX3224F024/pi-ager.tft
                  log "firmware upload for HMI device finished";;
esac

# Configfile löschen
if [ -z "$keepconf" ]         #wenn ""
then
    rm /boot/firmware/setup.txt
    log "Config gelöscht"
fi

log "disable setup_pi-ager.service now"
systemctl disable setup_pi-ager.service # Setupscript in Startroutine deaktivieren, da es nur beim ersten Start benötigt wird. 

# enable and start pi-ager_main.service
systemctl enable --now pi-ager_main.service 

# enable and start nodogsplash.service
# systemctl enable nodogsplash.service

exit 0
