#!/bin/bash

# Script-Name: pi-ager_image
# Version    : 0.0.1
# Autor      : DerBurgermeister
# Datum      : 11.01.2020
# Dieses Script erstellt aus einem Backup ein Image. Nur für internen Gebrauch
#####################################################################
#Variablen
#####################################################################
#

#NFSVOL=192.168.2.142:/backup						# Pfad zur NFS Freigabe (Muss im NAS angelegt werden)
NFSVOL=$(sqlite3 /var/www/config/pi-ager.sqlite3 "select nfsvol from nfs_backup where active = 1")

#SUBDIR=pi-ager										# dieses Verzeichniss muss im NAS angelegt sein
SUBDIR=$(sqlite3 /var/www/config/pi-ager.sqlite3 "select subdir from nfs_backup where active = 1")

#NFSMOUNT=/home/pi/backup							# Pfad auf dem Pi indem das Backup gespeichert wird
NFSMOUNT=$(sqlite3 /var/www/config/pi-ager.sqlite3 "select nfsmount from nfs_backup where active = 1")

#BACKUP_PFAD="/home/pi/backup/pi-ager"				# setzt sich zusammen aus dem Dateipfad auf dem Pi und dem Verzeichnis im NAS
BACKUP_PFAD=$(sqlite3 /var/www/config/pi-ager.sqlite3 "select backup_path from nfs_backup where active = 1")

#BACKUP_ANZAHL="5"									# behält die letzten "n" Backups
BACKUP_ANZAHL=$(sqlite3 /var/www/config/pi-ager.sqlite3 "select number_of_backups from nfs_backup where active = 1")

#BACKUP_NAME="PiAgerBackup"							# Name des Backup
BACKUP_NAME=$(sqlite3 /var/www/config/pi-ager.sqlite3 "select backup_name from nfs_backup where active = 1")


DIENSTE_START_STOP="/etc/init.d/pi-ager-main.sh"	# Dienst die vor Backup gestoppt und nach Backup wieder gestartet werden sollen 
# ENDE VARIABLEN
 
#####################################################################
# Skript (hier sollten nur erfahrene User anpassungen machen!)
#####################################################################


img_old="$1"
img = "PiAger_image.img"
cp $img_old $img
parted_output=$(parted -ms "$img" unit B print | tail -n 1)
partnum=$(echo "$parted_output" | cut -d ':' -f 1)
partstart=$(echo "$parted_output" | cut -d ':' -f 2 | tr -d 'B')
loopback=$(losetup -f --show -o "$partstart" "$img")

mountdir=$(mktemp -d)

mount "$loopback" "$mountdir"
read -p "Press enter to continue after image mount"

#read -p "Press enter to continue after copy chroot script"

for i in dev proc sys dev/pts
do
    mount -o bind /$i $mountdir/$i
done
read -p "Press enter to continue after mount dev sys ..."
echo $mountdir
regex='(\/.*\/)(.*)'
[[ $mountdir =~ $regex ]]
chrootdir=${BASH_REMATCH[2]}
echo $chrootdir
cd ${BASH_REMATCH[1]} 
pwd
chroot $chrootdir /bin/bash <<EOF
# This commands are called inside of the chroot environment 
# The aim is to make an new image for the Pi-Ager Communtiy

######################################################
# System update and cleanup
######################################################

apt -y update 
apt -y upgrade 
apt -y install linux-image
#update-grub
apt -y autoremove 
apt -y clean 
apt -y autoclean 

pip3 list --outdated --format=freeze | grep -v '^\-e' | cut -d = -f 1  | xargs -n1 pip3 install 

######################################################
# delete not needed packages
######################################################

apt purge -y timidity lxmusic gnome-disk-utility deluge-gtk evince wicd wicd-gtk clipit usermode gucharmap gnome-system-tools pavucontrol
apt purge -y influxdb grafana-server

######################################################
# delete not needed files
######################################################

find /var/log/ -type f -exec rm "{}" \;
find /var/mail/ -type f -exec rm "{}" \;
find /var/tmp/ -type f -exec rm "{}" \;
find /var/cache/ -type f -exec rm "{}" \;
find /var/www/logs/ -type f -exec rm "{}" \;
find /tmp/ -type f -exec rm "{}" \;
find /root/.cache/ -type f -exec rm "{}" \;

######################################################
# Delete personal files (ssh keys ...)
######################################################
# root user
#rm -f /root/.ssh/authorized_keys
#rm -f /root/.ssh/known_hosts

# pi user
rm -f /home/pi/.ssh/authorized_keys
rm -f /home/pi/.bash_history

######################################################
# Change some settings
######################################################
# change ssh port:
sed -e "s/Port 57673/Port 22/g" < /etc/ssh/sshd_config > /etc/ssh/sshd_config

# change hostname
hostname -b {rpi-Pi-Ager}
#rm -f /etc/hostname
#echo "rpi-Pi-Ager" >> /etc/hostname
sed -e "s/rpi-Pi-AgerTest/rpi-Pi-Ager/g" < /etc/hosts > /etc/hosts

# remove git repository
rm /opt/git -rf

EOF

#read -p "Press enter to continue after executin script"

for i in dev/pts proc sys dev
do
    umount $mountdir/$i
done

#read -p "Press enter to continue after umount dev sys ..."

umount "$mountdir"
rm -rf $mountdir
# Shrink image
pishrink.sh -r ${BACKUP_PFAD}/$img ${BACKUP_PFAD}/PiAger_image.img
# Backup umbenennen in image
mv ${BACKUP_PFAD}/PiAger_image.img ${BACKUP_PFAD}/PiAger_image_$(date +%Y-%m-%d-%H:%M:%S).img
# tmp image lösche
rm $img