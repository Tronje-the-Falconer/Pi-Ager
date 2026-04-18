#!/bin/bash
# Bereitet geshrinktes Pi-OS Trixie Image für Auto-Expand vor
# Fügt ein Partition-Expand Script ein das VOR systemd-growfs läuft

IMAGE="$1"
LOGFILE="/var/www/logs/pi-ager_backup.log"

log() {
    echo "$(date '+%Y-%m-%d %H:%M:%S') $*" | tee -a "$LOGFILE"
}

if [ -z "$IMAGE" ] || [ ! -f "$IMAGE" ]; then
    echo "Usage: $0 <image-file>"
    exit 1
fi

log "=== image-prepare-expand.sh gestartet ==="
log "Image: ${IMAGE}"

for TOOL in losetup mount; do
    if ! command -v "$TOOL" &>/dev/null; then
        log "ERROR: '${TOOL}' nicht gefunden"
        exit 1
    fi
done

# ── Loop-Device einrichten ───────────────────────────────────────
LOOP=$(losetup --find --partscan --show "$IMAGE")
[ -z "$LOOP" ] && { log "ERROR: Loop-Device failed"; exit 1; }
log "Loop-Device: ${LOOP}"

BOOT_PART="${LOOP}p1"
ROOT_PART="${LOOP}p2"
MOUNT_ROOT=$(mktemp -d)
MOUNT_BOOT=$(mktemp -d)

cleanup() {
    log "Cleanup ..."
    umount "$MOUNT_BOOT" 2>/dev/null
    umount "$MOUNT_ROOT" 2>/dev/null
    rmdir  "$MOUNT_BOOT" 2>/dev/null
    rmdir  "$MOUNT_ROOT" 2>/dev/null
    losetup -d "$LOOP"   2>/dev/null
}
trap cleanup EXIT

mount "$ROOT_PART" "$MOUNT_ROOT" || { log "ERROR: Root mount failed"; exit 1; }
mount "$BOOT_PART" "$MOUNT_BOOT" || { log "ERROR: Boot mount failed"; exit 1; }

# ── machine-id auf uninitialized setzen ─────────────────────────
log "Setze machine-id auf 'uninitialized' ..."
echo "uninitialized" > "${MOUNT_ROOT}/etc/machine-id"
chmod 444 "${MOUNT_ROOT}/etc/machine-id"

# ── Partition-Expand Script ins Image schreiben ──────────────────
log "Schreibe expand-partition.sh ..."
cat > "${MOUNT_ROOT}/usr/local/bin/expand-partition.sh" << 'SCRIPT'
#!/bin/bash
# Expandiert die Root-Partition auf volle SD-Card Größe
# Verwendet growpart — funktioniert auch auf gemounteter Partition

LOGFILE="/var/log/expand-partition.log"

log() {
    echo "$(date '+%Y-%m-%d %H:%M:%S') $*" | tee -a "$LOGFILE"
    logger -t expand-partition "$1"
}

log "=== Partition expandieren ==="

# Root-Device ermitteln
ROOT_DEV=$(findmnt -n -o SOURCE /)
log "Root-Device: ${ROOT_DEV}"

# Disk und Partitionsnummer ermitteln
if [[ "$ROOT_DEV" =~ (mmcblk[0-9]+)p([0-9]+) ]]; then
    DISK="/dev/${BASH_REMATCH[1]}"
    PART_NUM="${BASH_REMATCH[2]}"
elif [[ "$ROOT_DEV" =~ (sd[a-z]+)([0-9]+) ]]; then
    DISK="/dev/${BASH_REMATCH[1]}"
    PART_NUM="${BASH_REMATCH[2]}"
else
    log "ERROR: Konnte Block-Device nicht ermitteln aus ${ROOT_DEV}"
    exit 1
fi

log "Disk: ${DISK}  Partition: ${PART_NUM}"

# growpart verfügbar?
if ! command -v growpart &>/dev/null; then
    log "ERROR: growpart nicht gefunden"
    log "       apt-get install -y cloud-guest-utils"
    exit 1
fi

# Aktuelle und maximale Größe vergleichen
DISK_SECTORS=$(blockdev --getsz "$DISK")
PART_END=$(sfdisk -l "$DISK" 2>/dev/null \
    | awk -v search="$ROOT_DEV" '$0 ~ search {print $3}')
log "Disk Sektoren: ${DISK_SECTORS}"
log "Partition End: ${PART_END}"

if [ -n "$PART_END" ] && \
   [ "$PART_END" -ge $(( DISK_SECTORS - 2048 )) ]; then
    log "Partition bereits maximal — kein Expand nötig"
    exit 0
fi

# Partition expandieren mit growpart
log "Expandiere Partition ${PART_NUM} auf ${DISK} mit growpart ..."
growpart "$DISK" "$PART_NUM"
RC=$?

if [ $RC -eq 0 ]; then
    log "SUCCESS: Partition expandiert"
elif [ $RC -eq 1 ]; then
    log "ERROR: growpart fehlgeschlagen (RC=1)"
    exit 1
elif [ $RC -eq 2 ]; then
    log "INFO: Partition bereits maximal (RC=2) — nichts zu tun"
    exit 0
fi

# Neue Größe loggen
PART_END_NEW=$(sfdisk -l "$DISK" 2>/dev/null \
    | awk "/^${ROOT_DEV}/ {print $3}")
log "Partition End vorher : ${PART_END}"
log "Partition End nachher: ${PART_END_NEW}"
log "=== Partition erfolgreich expandiert ==="
SCRIPT

chmod 755 "${MOUNT_ROOT}/usr/local/bin/expand-partition.sh"
chown root:root "${MOUNT_ROOT}/usr/local/bin/expand-partition.sh"
log "expand-partition.sh geschrieben"

# ── expand-partition.service ins Image schreiben ─────────────────
log "Schreibe expand-partition.service ..."
cat > "${MOUNT_ROOT}/etc/systemd/system/expand-partition.service" << 'SERVICE'
[Unit]
Description=Expand root partition to fill SD card (first boot only)
DefaultDependencies=no
After=systemd-remount-fs.service
Before=systemd-growfs-root.service rpi-resize.service
ConditionFirstBoot=yes
Conflicts=shutdown.target
Before=shutdown.target

[Service]
Type=oneshot
RemainAfterExit=yes
ExecStart=/usr/local/bin/expand-partition.sh
StandardOutput=journal
StandardError=journal

[Install]
WantedBy=sysinit.target
SERVICE

chown root:root "${MOUNT_ROOT}/etc/systemd/system/expand-partition.service"
log "expand-partition.service geschrieben"

# ── Service aktivieren ───────────────────────────────────────────
log "Aktiviere expand-partition.service ..."
WANTS_DIR="${MOUNT_ROOT}/etc/systemd/system/sysinit.target.wants"
mkdir -p "$WANTS_DIR"
ln -sf /etc/systemd/system/expand-partition.service \
       "${WANTS_DIR}/expand-partition.service"
log "Symlink erstellt"

# ── rpi-resize.service Symlink sicherstellen ─────────────────────
log "Prüfe rpi-resize.service Symlink ..."
RPI_SERVICE="${MOUNT_ROOT}/usr/lib/systemd/system/rpi-resize.service"
RPI_SYMLINK="${WANTS_DIR}/rpi-resize.service"

if [ -f "$RPI_SERVICE" ] && [ ! -L "$RPI_SYMLINK" ]; then
    ln -sf /usr/lib/systemd/system/rpi-resize.service "$RPI_SYMLINK"
    log "rpi-resize.service Symlink erstellt"
else
    log "rpi-resize.service Symlink bereits vorhanden"
fi

# ── cmdline.txt bereinigen ───────────────────────────────────────
CMDLINE="${MOUNT_BOOT}/cmdline.txt"
if [ -f "$CMDLINE" ]; then
    cp "$CMDLINE" "${CMDLINE}.bak"
    log "Original cmdline.txt: $(cat $CMDLINE)"
    sed -i 's| ds=nocloud[^ ]*||g' "$CMDLINE"
    log "Bereinigt cmdline.txt: $(cat $CMDLINE)"
fi

# ── cloud-init Netzwerkverwaltung deaktivieren ───────────────────
mkdir -p "${MOUNT_ROOT}/etc/cloud/cloud.cfg.d"
cat > "${MOUNT_ROOT}/etc/cloud/cloud.cfg.d/99-disable-network.cfg" << 'EOF'
network:
  config: disabled
EOF
chmod 644 "${MOUNT_ROOT}/etc/cloud/cloud.cfg.d/99-disable-network.cfg"
[ -f "${MOUNT_BOOT}/network-config" ] && \
    mv "${MOUNT_BOOT}/network-config" \
       "${MOUNT_BOOT}/network-config.disabled"
log "cloud-init Netzwerkverwaltung deaktiviert"

# ── Verify ──────────────────────────────────────────────────────
log "--- Verify ---"
log "machine-id           : $(cat ${MOUNT_ROOT}/etc/machine-id)"
log "expand-partition Link: $(ls -la ${WANTS_DIR}/expand-partition.service)"
log "rpi-resize Link      : $(ls -la ${WANTS_DIR}/rpi-resize.service)"
log "cmdline.txt          : $(cat ${MOUNT_BOOT}/cmdline.txt)"

log "=== Image erfolgreich modifiziert ==="
