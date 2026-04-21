#!/bin/bash
# Bereitet geshrinktes Pi-OS Trixie Image für Auto-Expand vor
# Verwendet den offiziellen 'resize' cmdline Parameter

IMAGE="$1"
LOGFILE="/var/www/logs/pi-ager_backup.log"

log() {
    echo "$(date '+%Y-%m-%d %H:%M:%S') $1" | tee -a "$LOGFILE"
}

[ -z "$IMAGE" ] || [ ! -f "$IMAGE" ] && {
    echo "Usage: $0 <image>"
    exit 1
}

log "=== image-prepare-expand.sh ==="
log "Image: ${IMAGE}"

# ── Loop-Device ──────────────────────────────────────────────────
LOOP=$(losetup --find --partscan --show "$IMAGE")
[ -z "$LOOP" ] && { log "ERROR: Loop-Device failed"; exit 1; }
log "Loop: ${LOOP}"

MOUNT_ROOT=$(mktemp -d)
MOUNT_BOOT=$(mktemp -d)

cleanup() {
    umount "$MOUNT_BOOT" 2>/dev/null
    umount "$MOUNT_ROOT" 2>/dev/null
    rmdir  "$MOUNT_BOOT" "$MOUNT_ROOT" 2>/dev/null
    losetup -d "$LOOP"   2>/dev/null
}
trap cleanup EXIT

mount "${LOOP}p2" "$MOUNT_ROOT" || { log "ERROR: Root mount"; exit 1; }
mount "${LOOP}p1" "$MOUNT_BOOT" || { log "ERROR: Boot mount"; exit 1; }

# ── Wer verarbeitet 'resize'? ────────────────────────────────────
log "Suche resize Handler im Image ..."
find "${MOUNT_ROOT}/usr/lib/raspberrypi-sys-mods/" \
     "${MOUNT_ROOT}/usr/share/initramfs-tools/" \
     "${MOUNT_ROOT}/etc/initramfs-tools/" \
     -name "*resize*" 2>/dev/null | tee -a "$LOGFILE"

# ── cmdline.txt: 'resize' eintragen ─────────────────────────────
CMDLINE="${MOUNT_BOOT}/cmdline.txt"
cp "$CMDLINE" "${CMDLINE}.bak"
CURRENT=$(cat "$CMDLINE")
log "cmdline.txt vorher: ${CURRENT}"

# ds=nocloud entfernen
sed -i 's| ds=nocloud[^ ]*||g' "$CMDLINE"

# 'resize' eintragen falls nicht vorhanden
if grep -qw "resize" "$CMDLINE"; then
    log "'resize' bereits vorhanden ✅"
else
    sed -i 's|rootwait|rootwait resize|' "$CMDLINE"
    log "'resize' eingetragen ✅"
fi

log "cmdline.txt nachher: $(cat $CMDLINE)"

# ── machine-id auf uninitialized ─────────────────────────────────
log "machine-id vorher: '$(cat ${MOUNT_ROOT}/etc/machine-id)'"
echo "uninitialized" > "${MOUNT_ROOT}/etc/machine-id"
chmod 444 "${MOUNT_ROOT}/etc/machine-id"
log "machine-id = 'uninitialized' ✅"

# ── rpi-resize.service Symlink prüfen ────────────────────────────
WANTS="${MOUNT_ROOT}/etc/systemd/system/sysinit.target.wants"
RPI_SVC="${MOUNT_ROOT}/usr/lib/systemd/system/rpi-resize.service"
RPI_LNK="${WANTS}/rpi-resize.service"

if [ -f "$RPI_SVC" ] && [ ! -L "$RPI_LNK" ]; then
    mkdir -p "$WANTS"
    ln -sf /usr/lib/systemd/system/rpi-resize.service "$RPI_LNK"
    log "rpi-resize.service Symlink erstellt ✅"
else
    log "rpi-resize.service: $(ls -la $RPI_LNK 2>/dev/null)"
fi

# ── Verify ───────────────────────────────────────────────────────
log "--- Verify ---"
log "cmdline.txt : $(cat $CMDLINE)"
log "machine-id  : $(cat ${MOUNT_ROOT}/etc/machine-id)"
log "PARTUUID p2 : $(blkid ${LOOP}p2 | grep -o 'PARTUUID=\"[^\"]*\"')"

log "=== Fertig ==="