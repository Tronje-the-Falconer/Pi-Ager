#!/bin/bash
# Watchdog: detects STA dropout and forces NM to reconnect
# Works even when background scanning is suppressed by AP

PING_TARGET="8.8.8.8"
STA_IFACE="wlan0"
AP_IFACE="wlan1"
AP_CON="PI_AGER_AP"
LOGFILE="/var/log/wifi-watchdog.log"
MAX_FAILS=3
FAIL_COUNT=0

log() {
    echo "$(date '+%Y-%m-%d %H:%M:%S') $1" | tee -a "$LOGFILE"
    logger -t wifi-watchdog "$1"
}

log "Watchdog started"

while true; do
    if ! ping -I "$STA_IFACE" -c 2 -W 3 "$PING_TARGET" &>/dev/null; then
        FAIL_COUNT=$((FAIL_COUNT + 1))
        log "Ping fail #${FAIL_COUNT}"

        if [ "$FAIL_COUNT" -ge "$MAX_FAILS" ]; then
            log "STA down — forcing NM to scan and reconnect"

            # Get current STA connection name dynamically
            STA_CON=$(nmcli -g GENERAL.CONNECTION dev show "$STA_IFACE" 2>/dev/null)
            nmcli con mod "$AP_CON" wifi.band "bg" wifi.channel 6
            systemctl restart NetworkManager
            nmcli con up "$STA_CON" ifname"$STA_IFACE"
            # nmcli con down "$AP_CON"
            # sleep 3
            nmcli con up "$AP_CON" ifname "$AP_IFACE"
            
#            # Force NM to rescan
#            nmcli dev wifi rescan ifname "$STA_IFACE" 2>/dev/null
#            sleep 5

            # If still not connected, force reconnect
            if ! ping -I "$STA_IFACE" -c 1 -W 3 "$PING_TARGET" &>/dev/null; then
                log "Rescan did not help — forcing reconnect of '${STA_CON}'"
 #               nmcli dev disconnect "$STA_IFACE" 2>/dev/null
 #               sleep 3
 #               nmcli con up "$STA_CON" ifname "$STA_IFACE" 2>/dev/null
 #               log "Reconnect triggered"
            fi

            FAIL_COUNT=0
        fi
    else
        if [ "$FAIL_COUNT" -gt 0 ]; then
            log "Connection restored"
        fi
        FAIL_COUNT=0
    fi

    sleep 30
done