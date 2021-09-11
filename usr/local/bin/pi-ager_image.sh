#!/bin/bash

# Script-Name: pi-ager_image
# Version    : 0.1.0
# Autor      : DerBurgermeister, überarbeitet von phylax
# Datum      : 11.06.2021
# Dieses Script erstellt aus einem Backup ein Image. Nur für internen Gebrauch
# Vorher auf dem Source System noch folgende Befehle ausführen:
# apt -y update && apt -y upgrade && apt -y install linux-image && apt --fix-broken install
# Grund: Bei einem Kernel Upgrade gibt es Probleme
#####################################################################
#Variablen
#####################################################################
#

# Pfad zur NFS Freigabe (Muss im NAS angelegt werden)
NFSVOL=$(sqlite3 -cmd ".timeout 5000" /var/www/config/pi-ager.sqlite3 "select nfsvol from config_nfs_backup where active = 1")

# dieses Verzeichniss muss im NAS angelegt sein
SUBDIR=$(sqlite3 -cmd ".timeout 5000" /var/www/config/pi-ager.sqlite3 "select subdir from config_nfs_backup where active = 1")

#NFSMOUNT=/home/pi/backup							# Pfad auf dem Pi indem das Backup gespeichert wird
NFSMOUNT=$(sqlite3 -cmd ".timeout 5000" /var/www/config/pi-ager.sqlite3 "select nfsmount from config_nfs_backup where active = 1")

# setzt sich zusammen aus dem Dateipfad auf dem Pi und dem Verzeichnis im NAS
BACKUP_PFAD=$(sqlite3 -cmd ".timeout 5000" /var/www/config/pi-ager.sqlite3 "select backup_path from config_nfs_backup where active = 1")

#z.B. NFSOPT="nosuid,nodev,rsize=65536,wsize=65536,intr,noatime"
NFSOPT=$(sqlite3 -cmd ".timeout 5000" /var/www/config/pi-ager.sqlite3 "select nfsopt from config_nfs_backup where active = 1")

# Name des Backup
BACKUP_NAME=$(sqlite3 -cmd ".timeout 5000" /var/www/config/pi-ager.sqlite3 "select backup_name from config_nfs_backup where active = 1")

# ENDE VARIABLEN
 
#####################################################################
# Skript (hier sollten nur erfahrene User anpassungen machen!)
#####################################################################
COMMAND_LINE_OPTIONS_HELP='
Command line options:
    -f			Source Filename or
    -l			Take the last Backup file
    -c          Make a Copy of the input File. Otherwise the input file will be changed
    -m			my Image - Do not delete all 
    -h          Print this help menu

Examples:

    Copy last backup file to a new image file
         '`basename $0`' -c -l 

    Copy backup file to a new image file
        '`basename $0`' -c -f PiAgerBackup_2020-01-22-07:02:17.img

    Use backup file as a new image file - Backup is then the image
        '`basename $0`' -f PiAgerBackup_2020-01-22-07:02:17.img
	
    Create and copy my Image - do not delete all test packages
        '`basename $0`' -c -m -f PiAgerBackup_2020-01-22-07:02:17.img
'

VALID_COMMAND_LINE_OPTIONS="clmhf:"
do_copy=false;
my_image=false;
last_backup=false;

while getopts $VALID_COMMAND_LINE_OPTIONS options; do
    #echo "option is " $options
    case $options in
    	f)
            source_file=${OPTARG}
        ;;
        l)
            last_backup=true;
        ;;
        c)
            do_copy=true;
        ;;
        m)
            my_image=true;
        ;;
        h)
            echo "$COMMAND_LINE_OPTIONS_HELP"
            exit $E_OPTERROR;
        ;;
        \?)
            echo "Usage: `basename $0` -h for help"
            echo "$COMMAND_LINE_OPTIONS_HELP"
            exit $E_OPTERROR;
        ;;
        *) 
	        echo "$COMMAND_LINE_OPTIONS_HELP"
            exit $E_OPTERROR; 
        ;;
    esac
done
if [[ "$last_backup" = true ]] && [[ "$my_image" = true ]]; then
	echo "Use Source_file with -f or Lastname -l for filename. Not the same!"
	exit;
fi

BACKUP_STATUS=$(ps ax | grep -v grep | grep pi-ager_backup.sh)
if [ -z "$BACKUP_STATUS" ]
	then
	  echo "Backup ist inaktiv. Pi-Ager_image wird gestartet!"
	else
	  echo "Backup ist aktiv. Pi-Ager_image wird nicht gestartet!"
	  exit 1
fi	

#Überprüfen ob der NFS-Server vorhanden ist
echo "überprüfe ob der NFS-Server vorhanden ist."
echo "Checking..."
if [ -z "$NFSVOL" ]
	then
        echo "Backup nicht korrekt eingestellt. Bitte Tabelle nfs_backup prüfen!"
        exit 1
    else
        echo "$NFSVOL ist vorhanden"
fi

# Überprüfen ob der Backup Pfad vorhanden ist
echo "überprüfe ob Backup Pfad vorhanden ist."	
echo "Checking ..."
if [ -z "$BACKUP_PFAD" ]
	then
        echo "Backup Pfad nicht korrekt eingestellt. Bitte Tabelle nfs_backup prüfen!"
        exit 1
    else
        echo "$BACKUP_PFAD ist vorhanden"
fi

#Überprüfen ob NFS Mount point in der Tabelle existiert
echo "überprüfe ob NFS Mount point definiert ist."
echo "Checking ..."
if [ -z "$NFSMOUNT" ]
	then
        echo "Kein NFS Mount point definiert. Bitte Tabelle nfs_backup prüfen!"
        exit 1
    else
        echo "$NFSMOUNT ist definiert"
fi

echo "überprüfe ob NFS Mount point im file system angelegt ist."
echo "Checking..."
if [ -d "$NFSMOUNT" ]
	then
		echo "$NFSMOUNT ist angelegt"
	else
		echo "$NFSMOUNT wird angelegt"
		sudo mkdir -p $NFSMOUNT
		sudo chmod -R u=rwx,g+rw-x,o+rwx $NFSMOUNT
		echo "$NFSMOUNT wurde angelegt"								 
fi

# DIR=$NFSMOUNT/$SUBDIR

echo "NFSVOL=$NFSVOL"
echo "NFSPATH=$BACKUP_PFAD"
echo "NFSMOUNT=$NFSMOUNT"

# Vorsichtshalber einmal unmounten
umount $NFSMOUNT

# NFS-Volume mounten
echo "hänge NFS-Volume ein"
echo $NFSOPT
if [ -z $NFSOPT ]
	then
		mount -t nfs4 $NFSVOL $NFSMOUNT -o $NFSOPT
 	else
 		mount -t nfs4 $NFSVOL $NFSMOUNT
 fi

# Prüfen, ob das Zielverzeichnis existiert
echo "Prüfe ob das Zielverzeichnis existiert"
sleep 2
if [ ! -d "$BACKUP_PFAD" ];
	then
        echo "Backupverzeichnis existiert nicht. Abbruch! Bitte anlegen"
        umount $NFSMOUNT
        exit 1
    else
        echo "Backupverzeichnis existiert = ${BACKUP_PFAD}"
fi
    
if [ "$last_backup" = true ]; 
	then
	echo "Load last backup file."
	echo "Backup path is  ${BACKUP_PFAD}"
	Pi_Ager_backup=$(ls -ar $(find ${BACKUP_PFAD} -maxdepth 1 -type f) | grep "Backup" | head -n1)
	
	echo "Backup file is" $Pi_Ager_backup
	source_file=$Pi_Ager_backup
	echo "Backup path with file is" $source_file
fi

if [ -z "${source_file}" ]; then
    echo "$COMMAND_LINE_OPTIONS_HELP"
fi
if [[ ! -f "$source_file" ]]; then
	echo "Source File $source_file not found!"
    umount $NFSMOUNT
	exit 1;
fi
echo "Source File = $source_file"
echo "do_copy     = $do_copy"
echo "my_image    = $my_image"

if [ "$do_copy" = true ]; 
	then
		img_old="$source_file"
        new_image="PiAger_image.img"
		img=$BACKUP_PFAD/$new_image
		echo "Coping $img_old to $img"
		rsync -a --info=progress2 "$img_old" "$img"
 	else
 		img="$source_file"
 		echo "Using $img as source and target"
fi

#read -p "Press enter to continue after copy image"
echo "####################################################################################"
parted_output=$(parted -ms "$img" unit B print | tail -n 1)
partnum=$(echo "$parted_output" | cut -d ':' -f 1)
partstart=$(echo "$parted_output" | cut -d ':' -f 2 | tr -d 'B')
loopback=$(losetup -f --show -o "$partstart" "$img")
echo "parted_output = $parted_output"
echo "partnum = $partnum"
echo "partstart = $partstart"
echo "loopback = $loopback"
echo "####################################################################################"
parted_output_boot=$(parted -ms "$img" unit B print | head -n3 | tail -n1)
partnum_boot=$(echo "$parted_output_boot" | cut -d ':' -f 1)
partstart_boot=$(echo "$parted_output_boot" | cut -d ':' -f 2 | tr -d 'B')
loopback_boot=$(losetup -f --show -o "$partstart_boot" "$img")
echo "parted_output_boot = $parted_output_boot"
echo "partnum_boot = $partnum_boot"
echo "partstart_boot = $partstart_boot"
echo "loopback_boot = $loopback_boot"
echo "####################################################################################"

#read -p "Press enter to continue before image mount"
mountdir=$(mktemp -d)
echo "mount directory is ${mountdir}"

mount ${loopback} ${mountdir}
#read -p "Press enter to continue after mounting $loopback to $mountdir"

mount -t msdos "$loopback_boot" "$mountdir/boot"
#read -p "Press enter to continue after mounting $loopback_boot $mountdir/boot"
#echo "Copy $mountdir/boot.bak/ to $mountdir/boot/"
#rsync -a --info=progress2 "$mountdir/boot.bak/" "$mountdir/boot/"
#read -p "Press enter to continue after copy boot.bak to boot"

for i in dev proc sys dev/pts
do
    mount -o bind /$i $mountdir/$i
done
#read -p "Press enter to continue after mount dev sys ..."

regex='(\/.*\/)(.*)'
[[ $mountdir =~ $regex ]]

echo "rematch1 ${BASH_REMATCH[1]}"
echo "rematch2 ${BASH_REMATCH[2]}"

chrootdir=${BASH_REMATCH[2]}
echo "change rootdir is ${chrootdir}"
cd ${BASH_REMATCH[1]} 
pwd
chroot $chrootdir /bin/bash <<EOF
# This commands are called inside of the chroot environment 
# The aim is to make an new image for the Pi-Ager Communtiy

######################################################
# System delete not needed packages and cleanup
# apt update and upgrade don't work. Error loding module and hciuart.service
######################################################

# apt -y update && apt -y upgrade && apt -y install linux-image && apt --fix-broken install
# apt remove -y timidity lxmusic gnome-disk-utility deluge-gtk evince wicd wicd-gtk clipit usermode gucharmap gnome-system-tools pavucontrol pi-bluetooth subversion


# C++
#apt remove -y g++-8/stable g++ gcc-4.6-base gcc-4.7-base gcc-4.8-base gcc-4.9-base gcc-5-base gcc-6-base gcc-6 gcc-7-base gcc-8-base gcc-8 gcc gdb 
# Fortran
# apt remove -y gfortran-6 gfortran-8 gfortran
# Old python version
# apt remove -y python2-minimal python2.7-minimal python2.7 python2
# Pango
# apt remove -y libpango-1.0-0 libpangocairo-1.0-0 libpangoft2-1.0-0

# apt -y autoremove && apt -y clean &&  apt -y autoclean 

######################################################
# Pip upgrade and update packages
######################################################

# pip install --upgrade pip
# pip3 list --outdated --format=freeze | grep -v '^\-e' | cut -d = -f 1  | xargs -n1 pip3 install 




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

# delete webcam files
rm /var/www/images/webcam/*.jpg

touch /var/www/logs/logfile.txt
chmod 666 /var/www/logs/logfile.txt
touch /var/www/logs/pi-ager.log
chmod 666 /var/www/logs/pi-ager.log

# remove history
cat /dev/null > /root/.bash_history
cat /dev/null > /home/pi/.bash_history 


# remove obsolete direcectories after upgrade
# rm -f /boot.bak
# rm -f /lib/modules.bak
# PRUNE_MODULES=1 sudo rpi-update

systemctl enable pi-ager_main.service setup_pi-ager.service
# systemctl disable pi-ager_scale.service pi-ager_agingtable.service

#systemctl daemon-reload
#systemctl reset-failed






#******************************************************
# Change some settings
#******************************************************

######################################################
# Remove System key for encrypt/decrypt
######################################################
rm /home/pi/system_key.bin

######################################################
# change hostname
######################################################
raspi-config nonint do_hostname rpi-Pi-Ager

# rewrite /var/.htcredentials
# mv /var/.htcredentials.org  /var/.htcredentials

######################################################
# rewrite /etc/wpa_supplicant/wpa_supplicant.conf
######################################################
# mv /etc/wpa_supplicant/wpa_supplicant.conf.org /etc/wpa_supplicant/wpa_supplicant.conf


######################################################
# rewrite /boot/setup.txt
######################################################
#mv /root/setup.txt /boot/setup.txt

######################################################
#Force password change for user root
######################################################
#change -d 0 root

######################################################
# SQLite3 changes
######################################################

sqlite3 /var/www/config/pi-ager.sqlite3 <<END_SQL
UPDATE debug SET value = '20' WHERE key = 'loglevel_file';
UPDATE debug SET value = '20' WHERE key = 'loglevel_console';
UPDATE debug SET value = '86400' WHERE key = 'agingtable_days_in_seconds_debug';
UPDATE debug SET value = '30' WHERE key = 'measuring_interval_debug';
UPDATE scale1_settings SET value='0.1' WHERE key='referenceunit';
UPDATE scale1_settings SET value='300' WHERE key='measuring_interval';
UPDATE scale1_settings SET value='15' WHERE key='measuring_duration';
UPDATE scale1_settings SET value='150' WHERE key='saving_period';
UPDATE scale1_settings SET value='20' WHERE key='samples';
UPDATE scale2_settings SET value='0.1' WHERE key='referenceunit';
UPDATE scale2_settings SET value='300' WHERE key='measuring_interval';
UPDATE scale2_settings SET value='15' WHERE key='measuring_duration';
UPDATE scale2_settings SET value='150' WHERE key='saving_period';
UPDATE scale2_settings SET value='20' WHERE key='samples';
UPDATE config SET value='1' WHERE key='switch_on_cooling_compressor';
UPDATE config SET value='-1' WHERE key='switch_off_cooling_compressor';
UPDATE config SET value='20' WHERE key='switch_on_humidifier';
UPDATE config SET value='0' WHERE key='switch_off_humidifier';
UPDATE config SET value='5' WHERE key='delay_humidify';
UPDATE config SET value='12' WHERE key='switch_on_light_hour';
UPDATE config SET value='30' WHERE key='switch_on_light_minute';
UPDATE config SET value='240' WHERE key='light_duration';
UPDATE config SET value='21600' WHERE key='light_period';
UPDATE config SET value='0' WHERE key='light_modus';
UPDATE config SET value='11' WHERE key='switch_on_uv_hour';
UPDATE config SET value='30' WHERE key='switch_on_uv_minute';
UPDATE config SET value='300' WHERE key='uv_duration';
UPDATE config SET value='21600' WHERE key='uv_period';
UPDATE config SET value='0' WHERE key='uv_modus';
UPDATE config SET value='3' WHERE key='modus';
UPDATE config SET value='60' WHERE key = 'light_duration';
UPDATE config SET value='180' WHERE key = 'light_perod';
UPDATE config SET value='0' WHERE key = 'light_modus';
UPDATE config SET value='60' WHERE key = 'uv_duration';
UPDATE config SET value='180' WHERE key = 'uv_perod';
UPDATE config SET value='0' WHERE key = 'uv_modus';
UPDATE config SET value='15' WHERE key = 'save_temperature_humidity_loops';	
UPDATE config SET value='0.0' WHERE key = 'meat1_sensortype';
UPDATE config SET value='0.0' WHERE key = 'meat2_sensortype';
UPDATE config SET value='0.0' WHERE key = 'meat3_sensortype';
UPDATE config SET value='0.0' WHERE key = 'meat4_sensortype';
UPDATE config SET value='0.0' WHERE key = 'secondsensortype';

DELETE FROM config_nfs_backup;
delete FROM config_email_server;
delete FROM config_email_recipient;
#delete FROM config_messenger;
delete FROM all_scales;
delete FROM all_sensors;
delete FROM uv_status;
delete FROM circulating_air_status;	
delete FROM cooling_compressor_status;	
# delete FROM current_values;
delete FROM dehumidifier_status;
delete FROM exhaust_air_status;
delete FROM heater_status;
delete FROM humidifier_status;
delete FROM light_status;
delete FROM config_pushover;
delete FROM config_telegram;

UPDATE config_messenger_exception SET "e-mail" = 0;
UPDATE config_messenger_exception SET "pushover" = 0;
UPDATE config_messenger_exception SET "telegram" = 0;
	
UPDATE config_messenger_event SET "e-mail" = 0;
UPDATE config_messenger_event SET "pushover" = 0;
UPDATE config_messenger_event SET "telegram" = 0;

END_SQL
# Rebuild DB to reduce the size of the DB
sqlite3 /var/www/config/pi-ager.sqlite3 'VACUUM;'

EOF
sync
if [ "$my_image" = false ]; then
	chroot $chrootdir /bin/bash <<EOF
	# This commands are called inside of the chroot environment 
	# The aim is to make an new image for the Pi-Ager Communtiy
	# apt remove -y influxdb grafana-rpi sysstat stress bareos-common bareos-filedaemon check-mk-agent 
	# apt -y autoremove && apt -y clean &&  apt -y autoclean 
	# systemctl stop check_mk@.service check_mk.socket haveget.service smartd.service
	# systemctl disable chronograf.service bacula-fd.service bareos-filedaemon.service display-manager.service grafana-server.service influxdb.service 
	# systemctl disable check_mk@.service check_mk.socket haveget.service smartd.service
	######################################################
	# Delete personal files (ssh keys ...)
	######################################################
	# systems
	rm -f /etc/wpa_supplicant/wpa_supplicant.conf
	# root user
	rm -f /root/.ssh/authorized_keys
	rm -f /root/.ssh/known_hosts
	
	# pi user
	rm -f /home/pi/.ssh/authorized_keys
	rm -f /home/pi/.bash_history
	rm -f /home/pi/NOAA_data.txt
	rm -f /home/pi/pishrink.log
	rm -f /home/pi/.influx_history
	rm -f /home/pi/.gitconfig
	rm -rf /home/pi/.gnupg/
	rm -f /home/pi/.lesshst
	rm -f /home/pi/.selected_editor
	rm -f /home/pi/setup.txt
	rm -f /home/pi/.sqlite_history
	rm -f /home/pi/.wget-hsts
	rm -f /home/pi/subversion
	
	# delete obsolete /etc direcories
	# rm -rf /etc/bacula
	# rm -rf /etc/bareos
	# rm -rf /etc/grafana
	# rm -rf /etc/ingluxdb
	# rm -rf /etc/telegraf
	
	# rm -rf /etc/python2.7
	# rm -rf /etc/python3.5
	
	# userdel bareos
	# userdel telegraf
	# userdel influxdb
	# userdel grafana
	# userdel chronograf
	# userdel kapacitor
	
	
	# delete obsolete /var direcories
	rm /var/www/logs/*
	# rm /var/logs
	
	# delete obsolete /tmp direcory
	# rm -rf /tmp
	
	# delete obsolete /opt direcories
	# rm -rf /opt/git
	# rm -rf /opt/GPIO-Test
	# rm -rf /opt/MCP3204
	# rm -rf /opt/vc
	######################################################
	# Change some settings
	######################################################
	# change ssh port:
	# sed -i "s/Port 57673/Port 22/g" /etc/ssh/sshd_config
EOF
fi

sync

for i in dev/pts proc sys dev
do
    umount $mountdir/$i
done

#read -p "Press enter to continue after umount dev sys ..."
echo "unmount ${mountdir}/boot"
umount ${mountdir}/boot
echo "unmount ${mountdir}"
umount ${mountdir}

if [ $? -ne 0 ]
then
  	echo "Error unmounting $mountdir. Maybe $mountdir is open. Image is then corrupt."
  	lsof $mountdir
  	exit 1
fi

#detach loop devices
echo "Detaching loop devices from ${img}"
losetup -d ${loopback_boot}
losetup -d ${loopback}

rm -rf $mountdir/boot
rm -rf $mountdir
if [[ ! -f "$img" ]];
    then
        echo "cannot shrink $img"
    else
        # Shrink image
        pishrink.sh -r $img 
        # Backup umbenennen mit Datum
        mv $img ${BACKUP_PFAD}/PiAger_image_$(date +%Y-%m-%d-%H%M%S).img
        echo "The image ${BACKUP_PFAD}/PiAger_image_$(date +%Y-%m-%d-%H%M%S).img was successfully created."
fi
sync

umount $NFSMOUNT

exit