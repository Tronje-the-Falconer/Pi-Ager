#!/bin/bash
# Creates and activates PI_AGER_AP on wlan1
# wlan1 is pre-created by wifi-init.service before NM starts

AP_CON="PI_AGER_AP"
AP_SSID="pi-ager"
AP_PASSWORD="1234567890"
STA_IFACE="wlan0"
AP_IFACE="wlan1"
LOGFILE="/var/log/setup-wifi-ap.log"

log() {
    echo "$(date '+%Y-%m-%d %H:%M:%S') $1" | tee -a "$LOGFILE"
    logger -t setup-ap "$1"
}

log "=== PI_AGER_AP setup started ==="
log "create wlan1"

if ip link show wlan1 &>/dev/null; then
    log "wlan1 already exists — skipping"
else
    iw dev wlan0 interface add wlan1 type __ap && log "wlan1 created" ||  { log "ERROR: wlan1 creation failed"; exit 1; } 

    WLAN0_MAC=$(cat /sys/class/net/wlan0/address)
    FIRST=$(echo "$WLAN0_MAC" | cut -d: -f1)
    NEW_FIRST=$(printf "%02x" $(( (0x${FIRST} | 0x02) ^ 0x08 )))
    AP_MAC="${NEW_FIRST}:$(echo "$WLAN0_MAC" | cut -d: -f2-)"
    ip link set wlan1 address "$AP_MAC" && log "wlan1 MAC set to $AP_MAC" || log "WARNING: could not set wlan1 MAC"

# Bring wlan1 up
    /sbin/ip link set wlan1 up

# Disable power save on wlan0 and wlan1
    /sbin/iw dev wlan0 set power_save off
    /sbin/iw dev wlan1 set power_save off
    log "wifi-init complete: wlan1 created, power save disabled"
fi   
   
# --- Confirm wlan1 exists ---
if ! ip link show "$AP_IFACE" &>/dev/null; then
    log "ERROR: ${AP_IFACE} does not exist — should not happen here"
    exit 1
fi
log "${AP_IFACE} confirmed present"

# --- Read AP MAC from wlan1 ---
AP_MAC=$(cat /sys/class/net/${AP_IFACE}/address)
log "AP MAC    : ${AP_MAC}"

# --- Wait for STA to connect ---
TRIES=0
while true; do
    STA_STATE=$(nmcli -g GENERAL.STATE dev show "$STA_IFACE" 2>/dev/null)
    if echo "$STA_STATE" | grep -q "100"; then
        log "STA connected — proceeding"
        break
    fi
    if [ "$TRIES" -ge 30 ]; then
        log "WARNING: STA not connected after 30s — using default channel"
        break
    fi
    log "Waiting for STA ... (${TRIES}s)"
    sleep 1; TRIES=$((TRIES+1))
done

BAND="bg"; CHANNEL=6
log " using default bg/6"


# --- Delete stale profile ---
EXISTING=$(nmcli -g connection.id con show "$AP_CON" 2>/dev/null)
if [ "$EXISTING" = "$AP_CON" ]; then
    log "Removing existing profile '${AP_CON}' ..."
    nmcli con delete "$AP_CON"
    sleep 1
fi

# --- Create AP profile bound to wlan1 ---
log "Creating NM profile '${AP_CON}' on ${AP_IFACE} ..."
nmcli con add \
    type wifi \
    ifname "$AP_IFACE" \
    con-name "$AP_CON" \
    autoconnect yes \
    ssid "$AP_SSID" \
    -- \
    wifi.mode ap \
    wifi.band "$BAND" \
    wifi.channel "$CHANNEL" \
    wifi.cloned-mac-address "$AP_MAC" \
    wifi-sec.key-mgmt wpa-psk \
    wifi-sec.psk "$AP_PASSWORD" \
    ipv4.method shared \
    ipv4.addresses 10.0.0.1/24 \
    ipv6.method disabled

if [ $? -ne 0 ]; then
    log "ERROR: nmcli con add failed"
    exit 1
fi
log "Profile '${AP_CON}' created"

# --- Activate ---
log "Activating '${AP_CON}' on ${AP_IFACE} ..."
nmcli con up "$AP_CON" ifname "$AP_IFACE"

if [ $? -ne 0 ]; then
    log "ERROR: Activation failed"
    journalctl -u NetworkManager -n 30 --no-pager >> "$LOGFILE" 2>&1
    exit 1
fi

sleep 3

# --- Verify ---
ACTIVE=$(nmcli con show --active | grep "$AP_CON")
AP_STATE=$(ip link show "$AP_IFACE" 2>/dev/null | grep -o "state [A-Z]*")
if [ -n "$ACTIVE" ]; then
    log "SUCCESS: '${AP_CON}' is active — ${AP_STATE}"
    iw dev "$AP_IFACE" info >> "$LOGFILE" 2>&1
else
    log "ERROR: '${AP_CON}' not in active connections"
    nmcli con show --active >> "$LOGFILE"
    exit 1
fi

log "=== PI_AGER_AP setup complete ==="
