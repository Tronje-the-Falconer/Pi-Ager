#!/bin/bash
# WiFi STA Watchdog — reconnects STA without restarting NetworkManager
# Resets AP to bg/6 before reconnect, then restores AP after STA is up

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

wait_for_sta() {
    local TRIES=0
    while true; do
        STATE=$(nmcli -g GENERAL.STATE dev show "$STA_IFACE" 2>/dev/null)
        if echo "$STATE" | grep -q "100"; then
            log "STA connected (state=${STATE})"
            return 0
        fi
        if [ "$TRIES" -ge 30 ]; then
            log "WARNING: STA did not connect after 30s"
            return 1
        fi
        sleep 1; TRIES=$((TRIES+1))
    done
}

wait_for_ping() {
    local TRIES=0
    while ! ping -I "$STA_IFACE" -c 1 -W 3 "$PING_TARGET" &>/dev/null; do
        if [ "$TRIES" -ge 20 ]; then
            log "WARNING: Ping still failing after 20s"
            return 1
        fi
        sleep 1; TRIES=$((TRIES+1))
    done
    return 0
}

ensure_wlan1() {
    if ip link show "$AP_IFACE" &>/dev/null; then
        return 0
    fi
    log "${AP_IFACE} missing — recreating ..."
    WLAN0_MAC=$(cat /sys/class/net/${STA_IFACE}/address)
    FIRST_BYTE=$(echo "$WLAN0_MAC" | cut -d: -f1)
    NEW_FIRST=$(printf "%02x" $(( (0x${FIRST_BYTE} | 0x02) ^ 0x08 )))
    AP_MAC="${NEW_FIRST}:$(echo "$WLAN0_MAC" | cut -d: -f2-)"
    iw dev "$STA_IFACE" interface add "$AP_IFACE" type __ap
    if ! ip link show "$AP_IFACE" &>/dev/null; then
        log "ERROR: Could not recreate ${AP_IFACE}"
        return 1
    fi
    ip link set "$AP_IFACE" address "$AP_MAC"
    ip link set "$AP_IFACE" up
    iw dev "$AP_IFACE" set power_save off
    log "${AP_IFACE} recreated with MAC ${AP_MAC}"
    return 0
}

reconnect() {
# Get STA connection name dynamically
    STA_CON=$(nmcli -g GENERAL.CONNECTION dev show "$STA_IFACE" 2>/dev/null)
    if [ -z "$STA_CON" ] || [ "$STA_CON" = "--" ]; then
        # STA fully disconnected — find profile from NM connection list
        STA_CON=$(nmcli -g NAME,TYPE con show \
            | grep ":802-11-wireless" \
            | grep -v "$AP_CON" \
            | cut -d: -f1 \
            | head -1)
        log "STA not active — using profile '${STA_CON}'"
    fi

    if [ -z "$STA_CON" ]; then
        log "ERROR: Could not determine STA connection name — aborting"
        return 1
    fi

    log "--- Reconnect procedure start ---"

    # Step 1: Bring down AP cleanly
    log "Step 1: Bringing down AP ..."
    nmcli con down "$AP_CON" 2>/dev/null
    sleep 5

    # Step 2: Reset AP to safe default band/channel
    # This is required — driver cannot bring up STA while AP holds a channel lock
    log "Step 2: Resetting AP to band=bg channel=6 ..."
    nmcli con mod "$AP_CON" wifi.band bg wifi.channel 6
    sleep 5
    
    # Step 3: not needed ! Force NM rescan so it sees the router SSID
    # log "Step 3: Forcing WiFi rescan ..."
    # nmcli dev wifi rescan ifname "$STA_IFACE" 2>/dev/null

    # Step 4: Bring up STA
    log "Step 4: Bringing up STA '${STA_CON}' ..."
    # not needed:   nmcli dev disconnect "$STA_IFACE" 2>/dev/null
    #    sleep 2
    nmcli con up "$STA_CON" ifname "$STA_IFACE"

    # Step 5: Wait for STA to connect
    if ! wait_for_sta; then
        log "ERROR: STA reconnect failed — will retry next cycle"
    #  not good, have to bring up AP_CON again      return 1
    fi

    # Step 6: Wait for ping to confirm internet
    if ! wait_for_ping; then
        log "WARNING: STA connected but no internet yet"
    fi

    # Step 7: Ensure wlan1 exists (may have been lost)
    ensure_wlan1
    
    # Step 8: Bring AP back up
    # 98-sync-ap-channel dispatcher will have already updated band/channel
    # from the STA up event — so AP will use correct settings
    log "Step 8: Bringing up AP '${AP_CON}' ..."
    sleep 2
    nmcli con up "$AP_CON" ifname "$AP_IFACE"

    if [ $? -eq 0 ]; then
        log "SUCCESS: AP restored"
    else
        log "ERROR: AP restore failed — check journalctl -u NetworkManager"
    fi

    log "--- Reconnect procedure complete ---"
    return 0
}

log "Watchdog started (ping target: ${PING_TARGET})"

while true; do
    sleep 30
    if ! ping -I "$STA_IFACE" -c 2 -W 3 "$PING_TARGET" &>/dev/null; then
        FAIL_COUNT=$((FAIL_COUNT + 1))
        log "Ping fail #${FAIL_COUNT}/${MAX_FAILS}"

        if [ "$FAIL_COUNT" -ge "$MAX_FAILS" ]; then
            log "STA down — starting reconnect procedure"
            reconnect
            FAIL_COUNT=0
        fi
    else
        if [ "$FAIL_COUNT" -gt 0 ]; then
            log "Connection restored — resetting fail counter"
            FAIL_COUNT=0
        fi
    fi

#    sleep 30
done