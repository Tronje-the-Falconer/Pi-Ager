#!/bin/bash

# Script-Name: pi-ager_backup
# Version    : 0.9.8
# Autor      : denni_m
# Datum      : 10.02.2019
# Coauthor   : DerBurgermeister
# Datum      : 03.01.2021
# Dieses Script erstellt im laufenden Betrieb ein Backup und löscht ungenutzen Speicher aus dem Image welches später einfach auf eine SD-Karte geschrieben werden kann.
# Hier wird der Pi-Ager gestoppt, das Backup gemacht und der Pi-Ager wird wieder gestartet. Während dieser Zeit werden keine Pi-Ager Funktionen ausgeführt.
# Nachdem die Felder in der Tabelle nfs_backup eingetragen wurden, kann das Script mit einem einfachen "pi-ager_backup.sh" gestartet werden.
# Das Script liegt in /usr/local/bin und wurde mit "chmod +x backup.sh" ausführbar gemacht. 
# Es kann natürlich auch über einen Cron Job laufen.
#####################################################################
#Variablen
#####################################################################
# VARIABLEN - HIER EDITIEREN
# warten bis Admin Seite wieder aufgebaut ist und damit versuchen zu verhindern, dass es mit sqlite3 Konflikte gibt 
# die nachfolgenden Zugriffe auf die DB sind nicht immer fehlerfrei
# 
# remove output from pushd and popd
pushd () {
    command pushd "$@" > /dev/null
}

popd () {
    command popd "$@" > /dev/null
}

start_piager () {
    $(sqlite3 -cmd ".timeout 5000" /var/www/config/pi-ager.sqlite3 "UPDATE current_values SET value = '1' WHERE key = 'status_piager'")
}

start_scale1 () {
    $(sqlite3 -cmd ".timeout 5000" /var/www/config/pi-ager.sqlite3 "UPDATE current_values SET value = '1' WHERE key = 'status_scale1'")
}

start_scale2 () {
    $(sqlite3 -cmd ".timeout 5000" /var/www/config/pi-ager.sqlite3 "UPDATE current_values SET value = '1' WHERE key = 'status_scale2'")
}

set_piager_status () {
    if [ $PIAGER_STATUS == 1 ]; then
        echo "Set pi-ager active in DB"
        start_piager
    fi
    if [ $SCALE1_STATUS == 1 ]; then
        echo "Set scale1 active in DB"
        start_scale1
    fi
    if [ $SCALE2_STATUS == 1 ]; then
        echo "Set scale2 active in DB"
        start_scale2
    fi
}

echo "--------------------------------------------------------------------------------------"
#sleep 5
# Server und Pfad zur NFS Freigabe (Muss im NAS angelegt werden)
NFSVOL=$(sqlite3 -cmd ".timeout 5000" /var/www/config/pi-ager.sqlite3 "select nfsvol from config_nfs_backup where active = 1")

# dieses Verzeichniss muss im NAS angelegt sein
# SUBDIR=$(sqlite3 -cmd ".timeout 5000" /var/www/config/pi-ager.sqlite3 "select subdir from config_nfs_backup where active = 1")

# Pfad auf dem Pi indem das Backup gespeichert wird, hierhin wird gemoundet
# NFSMOUNT=$(sqlite3 -cmd ".timeout 5000" /var/www/config/pi-ager.sqlite3 "select nfsmount from config_nfs_backup where active = 1")
NFSMOUNT="/home/nfs/public"

#z.B. NFSOPT="nosuid,nodev,rsize=65536,wsize=65536,intr,noatime"
NFSOPT=$(sqlite3 -cmd ".timeout 5000" /var/www/config/pi-ager.sqlite3 "select nfsopt from config_nfs_backup where active = 1")

# setzt sich zusammen aus dem Dateipfad auf dem Pi und dem Verzeichnis im NAS
# BACKUP_PFAD=$(sqlite3 -cmd ".timeout 5000" /var/www/config/pi-ager.sqlite3 "select backup_path from config_nfs_backup where active = 1")
BACKUP_PFAD="$NFSMOUNT"

# behält die letzten "n" Backups
BACKUP_ANZAHL=$(sqlite3 -cmd ".timeout 5000" /var/www/config/pi-ager.sqlite3 "select number_of_backups from config_nfs_backup where active = 1")

# Name des Backup
BACKUP_NAME=$(sqlite3 -cmd ".timeout 5000" /var/www/config/pi-ager.sqlite3 "select backup_name from config_nfs_backup where active = 1")

# get status aging_table from current_values
AGINGTABLE_STATUS=$(sqlite3 -cmd ".timeout 5000" /var/www/config/pi-ager.sqlite3 "select value from current_values where key = 'status_agingtable'")
AGINGTABLE_STATUS=${AGINGTABLE_STATUS%.*}

# get status_piager from current_values
PIAGER_STATUS=$(sqlite3 -cmd ".timeout 5000" /var/www/config/pi-ager.sqlite3 "select value from current_values where key = 'status_piager'")
PIAGER_STATUS=${PIAGER_STATUS%.*}

# get status_scale1 and status_cale2 from current_values
SCALE1_STATUS=$(sqlite3 -cmd ".timeout 5000" /var/www/config/pi-ager.sqlite3 "select value from current_values where key = 'status_scale1'")
SCALE1_STATUS=${SCALE1_STATUS%.*}
SCALE2_STATUS=$(sqlite3 -cmd ".timeout 5000" /var/www/config/pi-ager.sqlite3 "select value from current_values where key = 'status_scale2'")
SCALE2_STATUS=${SCALE2_STATUS%.*}

# systemctl daemon-reload

# Prüfen, ob Backup schon läuft
currsh=$0
currpid=$$
runpid=$(lsof -t $currsh| paste -s -d " ")
echo "current PID:  $currpid"
echo "Running PID : $runpid"
if [[ $runpid != $currpid ]]
	then
	  echo "Another backup process is already running! Current backup process stopped."
	  exit 1
fi	 

# Lese Status der Pi-Ager Prozesse

MAIN_STATUS=$(/var/sudowebscript.sh grepmain) 
echo "Main Status is $MAIN_STATUS"

# ENDE VARIABLEN
 
#####################################################################
# Skript (hier sollten nur erfahrene User anpassungen machen!)
#####################################################################

# read -p "weiter mit Enter mit Ctrl + c beenden" -t 3
echo "ok let's go $(date +%T)"
anfang=$(date +%s)

echo "Backup is starting, it will need some time to complete ..."

# Überprüfen ob Agingtable aktiv ist
echo "check if aging_table is active"
echo "agingtable status is ${AGINGTABLE_STATUS}"
if [ $AGINGTABLE_STATUS -ne "1" ]
    then
	  echo "Agingtable is not active. Backup continues!"
	else
  	  echo "Agingtable is active. Backup stopped!"
	  exit 1
fi	


#Überprüfen ob der NFS-Server vorhanden ist
echo "check if nfs directory exists in the backup table."
if [ -z "$NFSVOL" ]
	then
        echo "backup not setup correctly. Please enter nfs directory in the backup table! Format example is <ip address>:/folder/subfolder"
        exit 1
    else
        echo "$NFSVOL exists. Backup continues!"
fi

echo "check if format of nfs directory is correct, e.g.: 192.168.0.111:/srv/backup"

array=(${NFSVOL//:/ })
serverIP=${array[0]}
echo "NFS IP address = $serverIP"
localIP=$(hostname -I)
#remove last space character
localIP=${localIP// /}
echo "Local IP address = $localIP"

echo "check if nfs Server IP address has correct format"
if [[ "$serverIP" =~ ^(([1-9]?[0-9]|1[0-9][0-9]|2([0-4][0-9]|5[0-5]))\.){3}([1-9]?[0-9]|1[0-9][0-9]|2([0-4][0-9]|5[0-5]))$ ]]; then
  echo "nfs Server address $serverIP has correct format"
else
  echo "nfs Server address $serverIP is not defined or has wrong format. Backup stopped."
  exit 1
fi

echo "check if nfs Server IP and local IP are different."
if [[ "$localIP" == "$serverIP" ]]; then
  echo "nfs Server IP and local IP are equal. Backup stopped."
  exit 1
else
  echo "nfs Server IP and local IP are different. Backup continues."
fi

# Überprüfen ob der Backup Pfad in der Tabelle vorhanden ist
# echo "Überprüfen, ob Backup Pfad in der Tabelle vorhanden ist."	
# if [ -z "$BACKUP_PFAD" ]
#	then
#        echo "Backup Pfad nicht korrekt eingestellt. Bitte Tabelle nfs_backup prüfen. Abbruch"
#        exit 1
#    else
#        echo "$BACKUP_PFAD ist vorhanden"
# fi

#Überprüfen ob NFS Mount point in der Tabelle existiert
# echo "Überprüfen, ob NFS Mount point definiert ist."
# if [ -z "$NFSMOUNT" ]
#	then
#        echo "Kein NFS Mount point definiert. Bitte Tabelle nfs_backup prüfen. Abbruch"
#        exit 1
#    else
#        echo "$NFSMOUNT ist definiert"
# fi

echo "check if default NFS mount point $NFSMOUNT exists in local filesystem."
if [ -d "$NFSMOUNT" ]
	then
		echo "$NFSMOUNT exists. Backup continues."
	else
		echo "$NFSMOUNT missing, will be be created."
		mkdir -p $NFSMOUNT
		chmod -R u=rwx,g+rw-x,o+rwx $NFSMOUNT
		echo "$NFSMOUNT is created."								 
fi
 
#Überprüfen ob PiShrink aktuell ist sonst herunterladen
echo "check if PiShrink exists."
echo "Checking..."
online_md5="$(curl -sL https://raw.githubusercontent.com/Drewsif/PiShrink/master/pishrink.sh | md5sum | cut -d ' ' -f 1)"
local_md5="$(md5sum "/usr/local/bin/pishrink.sh" | cut -d ' ' -f 1)"
if [[ "$online_md5" == "$local_md5" ]]; 
	then
    	echo "PiShrink is the latest version!"
    else
    	echo "Installing PiShrink!"
		wget -N https://raw.githubusercontent.com/Drewsif/PiShrink/master/pishrink.sh
		chmod +x pishrink.sh
		mv pishrink.sh /usr/local/bin
        echo "PiShrink installed."
fi

#if [ -x /usr/local/bin/pishrink.sh ]
#	then
#		echo "PiShrink ist vorhanden"	
#	else
#		echo "PiShrink wird geladen!"
#		wget -N https://raw.githubusercontent.com/Drewsif/PiShrink/master/pishrink.sh
#		chmod +x pishrink.sh
#		sudo mv pishrink.sh /usr/local/bin
#fi

DIR=$NFSMOUNT
 
echo "Start $BACKUP_NAME $(date +%T)"
echo "NFSVOL=$NFSVOL"
echo "NFSMOUNT=$NFSMOUNT"
# echo "Backup directory=$DIR"
echo "NFSOPT=$NFSOPT"
echo "Backup file ${BACKUP_PFAD}/${BACKUP_NAME}.img"

# Vorsichtshalber einmal unmounten
umount $NFSMOUNT

# NFS-Volume mounten
echo "mount NFS-Volume. Map $NFSVOL to $NFSMOUNT"

if [ -z $NFSOPT ]
	then
		mount -t nfs4 $NFSVOL $NFSMOUNT -o $NFSOPT
        mountstatus=$?
 	else
 		mount -t nfs4 $NFSVOL $NFSMOUNT
        mountstatus=$?
fi

if [ $mountstatus -ne 0 ]; then
  echo "Error $mountstatus during mount NFS Volume $NFSVOL. Backup stopped."
  exit 1
else
  echo "mount NFS-Volume $NFSVOL successfull. Backup continues."
fi

# Prüfen, ob das Zielverzeichnis existiert
# echo "check if target directory $DIR exists on the nfs Server."
# sleep 2
# if [ ! -d "$DIR" ];
#	then
#        echo "target directory $DIR does not exist. Please create this directory. Backup stopped."
#        umount $NFSMOUNT
#        exit 1
#    else
#        echo "target directory $DIR exists. Backup continues."
# fi

# prüfe, ob current user in das Verzeichnis schreiben kann

u="$USER"
echo "check if $u can write into $DIR mapped to $NFSVOL"

INFO=( $(stat -L -c "0%a %G %U" "$DIR") )
PERM=${INFO[0]}
GROUP=${INFO[1]}
OWNER=${INFO[2]}

ACCESS=no
if (( ($PERM & 0x02) != 0 )); then
    # Everyone has write access
    ACCESS=yes
elif (( ($PERM & 0x10) != 0 )); then
    # Some group has write access.
    # Is user in that group?
    gs=( $(groups $u) )
    for g in "${gs[@]}"; do
        if [[ $GROUP == $g ]]; then
            ACCESS=yes
            break
        fi
    done
elif (( ($PERM & 0x80) != 0 )); then
    # The owner has write access.
    # Does the user own the file?
    [[ $u == $OWNER ]] && ACCESS=yes
fi

if [[ "$ACCESS" == "no" ]]; then
      echo "$u has no WRITE permission. Backup stopped."
      echo "Can be fixed by using command chmod, or"
      echo "adjust OWNER and GROUP by using command chown."
      echo "OWNER = $OWNER"
      echo "GROUP = $GROUP"
      echo "PERMISSION = $PERM"
      umount $NFSMOUNT
      exit 1
    else
      echo "$u can write into $DIR mapped to $NFSVOL, Backup continues."
fi

# Stoppe Dienste vor Backup
# echo "Stop Pi-Ager Main service!"
#${DIENSTE_START_STOP} stop

if [ -z "$MAIN_STATUS" ]
	then
      PI_AGER_MAIN_ACTIVE=0
      echo "Pi-Ager Main is already stopped"
    else
      PI_AGER_MAIN_ACTIVE=1
      echo "Stop Pi-Ager Main service, wait 10s to continue."
      systemctl stop pi-ager_main &
      sleep 10
fi	

# Backup mit Hilfe von dd erstellen und im angegebenen Pfad speichern
# write buffer and clear caches
sync
echo 1 > /proc/sys/vm/drop_caches

echo "create now Backup ${BACKUP_PFAD}/${BACKUP_NAME}.img at $(date +%T) with command dd. This needs some time to complete ..."
dd if=/dev/mmcblk0 of=${BACKUP_PFAD}/${BACKUP_NAME}.img bs=1M 2>&1

ddstatus=$?
if [ $ddstatus -ne 0 ]; then
  echo "error $ddstatus during dd command. Backup stopped."
#  echo "Start Pi-Ager Main service again!"
  if [ $PI_AGER_MAIN_ACTIVE == 1 ]; then
    set_piager_status
    echo  "Start Pi-Ager Main service again."
    systemctl start pi-ager_main &
  fi
  umount $NFSMOUNT
  exit 1
fi

sync
# Starte Shrink
echo "start PiShrink $(date +%T) pishrink.sh $OPTARG ${BACKUP_PFAD}/${BACKUP_NAME}.img"
#read -p "Press enter to continue before pishrink call"
# -d write debug file
#sudo /usr/local/bin/pishrink.sh -d $OPTARG ${BACKUP_PFAD}/${BACKUP_NAME}.img
/usr/local/bin/pishrink.sh ${BACKUP_PFAD}/${BACKUP_NAME}.img

# Backup umbenennen
mv ${BACKUP_PFAD}/${BACKUP_NAME}.img ${BACKUP_PFAD}/${BACKUP_NAME}_$(date +%Y-%m-%d-%H%M%S).img

# Backup beendet
#sqlite3 /var/www/config/pi-ager.sqlite3 "BEGIN TRANSACTION;UPDATE config SET value = '0.0' where key = 'backup_status'; COMMIT;"

# Alte Sicherungen die nach X neuen Sicherungen entfernen
NUMBER_OF_BACKUPS=$(find ${BACKUP_PFAD}/${BACKUP_NAME}* -maxdepth 1 -type f | wc -l)
echo "Number of backups that are kept. ${BACKUP_ANZAHL}"
echo "Actual number of backups. ${NUMBER_OF_BACKUPS}"

if [ ${NUMBER_OF_BACKUPS} -gt ${BACKUP_ANZAHL} ]
    then
        echo "more than ${BACKUP_ANZAHL} backup files, some old backups will be removed"
        ls -tr ${BACKUP_PFAD}/${BACKUP_NAME}* | head -n -${BACKUP_ANZAHL}
        pushd ${BACKUP_PFAD}; ls -tr ${BACKUP_PFAD}/${BACKUP_NAME}* | head -n -${BACKUP_ANZAHL} | xargs rm; popd
    else
        echo "less or equal than ${BACKUP_ANZAHL} backup files, no backups will be removed"
fi
# Sekundenzähler stoppen ########################################
ende=$(date +%s)
 
# benötigte Zeit in Sekunden berechnen ##########################
diff=$[ende-anfang]
echo -e "\n"

# Prüfen, ob benoetigte Zeit kleiner als 60 sec ##################
if [ $diff -lt 60 ]; then
    echo -e $(date +%c)": "'Backup and shrinking successful after '$diff' seconds'

# Wenn kleiner 3600 Sekunden, in Minuten und Sekunden umrechnen
#################################################################
elif [ $diff -lt  3599 ]; then
   echo -e $(date +%c)": "'Backup and shrinking successful after '$[$diff / 60] 'minute(s) '$[$diff % 60] 'seconds'

# Wenn gleich oder groeßer 3600 Sekunden, in Stunden Minuten und Sekunden umrechnen
#################################################################
elif [ $diff -ge 3600 ]; then
   echo -e $(date +%c)": "'Backup and shrinking successful after '$[$diff / 3600] 'hour(s) '$[$diff % 3600 / 60] 'minutes '$[$diff % 60] 'seconds'
fi

# unmounten
sync
umount $NFSMOUNT


# Starte pi-ager service nach Backup

if [ $PI_AGER_MAIN_ACTIVE == 1 ]; then
    set_piager_status
    echo  "Start Pi-Ager Main service again."
    systemctl start pi-ager_main &
fi
