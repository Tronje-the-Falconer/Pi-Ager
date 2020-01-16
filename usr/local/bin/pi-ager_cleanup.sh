img="$1"
parted_output=$(parted -ms "$img" unit B print | tail -n 1)
partnum=$(echo "$parted_output" | cut -d ':' -f 1)
partstart=$(echo "$parted_output" | cut -d ':' -f 2 | tr -d 'B')
loopback=$(losetup -f --show -o "$partstart" "$img")

mountdir=$(mktemp -d)

mount "$loopback" "$mountdir"

find $mountdir/var/log/ -type f -exec rm "{}" \;
find $mountdir/var/mail/ -type f -exec rm "{}" \;
find $mountdir/var/tmp/ -type f -exec rm "{}" \;
find $mountdir/var/cache/ -type f -exec rm "{}" \;
find $mountdir/var/www/logs/ -type f -exec rm "{}" \;
find $mountdir/tmp/ -type f -exec rm "{}" \;
find $mountdir/root/.cache/ -type f -exec rm "{}" \;
umount "$mountdir"
rm -R $mountdir

