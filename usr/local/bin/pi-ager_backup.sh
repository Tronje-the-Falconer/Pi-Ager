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

echo "--------------------------------------------------------------------------------------"
#sleep 5
# Server und Pfad zur NFS Freigabe (Muss im NAS angelegt werden)
NFSVOL=$(sqlite3 -cmd ".timeout 5000" /var/www/config/pi-ager.sqlite3 "select nfsvol from config_nfs_backup where active = 1")

# dieses Verzeichniss muss im NAS angelegt sein
SUBDIR=$(sqlite3 -cmd ".timeout 5000" /var/www/config/pi-ager.sqlite3 "select subdir from config_nfs_backup where active = 1")

# Pfad auf dem Pi indem das Backup gespeichert wird, hierhin wird gemoundet
NFSMOUNT=$(sqlite3 -cmd ".timeout 5000" /var/www/config/pi-ager.sqlite3 "select nfsmount from config_nfs_backup where active = 1")

#z.B. NFSOPT="nosuid,nodev,rsize=65536,wsize=65536,intr,noatime"
NFSOPT=$(sqlite3 -cmd ".timeout 5000" /var/www/config/pi-ager.sqlite3 "select nfsopt from config_nfs_backup where active = 1")


# setzt sich zusammen aus dem Dateipfad auf dem Pi und dem Verzeichnis im NAS
BACKUP_PFAD=$(sqlite3 -cmd ".timeout 5000" /var/www/config/pi-ager.sqlite3 "select backup_path from config_nfs_backup where active = 1")

# behält die letzten "n" Backups
BACKUP_ANZAHL=$(sqlite3 -cmd ".timeout 5000" /var/www/config/pi-ager.sqlite3 "select number_of_backups from config_nfs_backup where active = 1")

# Name des Backup
BACKUP_NAME=$(sqlite3 -cmd ".timeout 5000" /var/www/config/pi-ager.sqlite3 "select backup_name from config_nfs_backup where active = 1")

# get status aging_table from current_values
AGINGTABLE_STATUS=$(sqlite3 -cmd ".timeout 5000" /var/www/config/pi-ager.sqlite3 "select value from current_values where key = 'status_agingtable'")
AGINGTABLE_STATUS=${AGINGTABLE_STATUS%.*}

# systemctl daemon-reload

# Prüfen, ob Backup schon läuft
currsh=$0
currpid=$$
runpid=$(lsof -t $currsh| paste -s -d " ")
echo "current PID:  $currpid"
echo "Running PID : $runpid"
if [[ $runpid != $currpid ]]
	then
	  echo "Zweiter Backup Prozess vorhanden!"
	  exit 1
fi	 

# Lese Status der Pi-Ager Prozesse

MAIN_STATUS=$(/var/sudowebscript.sh grepmain) 
echo "Main Status ist $MAIN_STATUS"

# ENDE VARIABLEN
 
#####################################################################
# Skript (hier sollten nur erfahrene User anpassungen machen!)
#####################################################################

# read -p "weiter mit Enter mit Ctrl + c beenden" -t 3
echo "ok los gehts lehne dich zurück $(date +%T)"
anfang=$(date +%s)

echo "Starte mit dem Backup, dies kann einige Zeit dauern"

# Überprüfen ob Agingtable aktiv ist
echo "überprüfe ob aging_table aktiv ist"
echo "agingtable status is ${AGINGTABLE_STATUS}"
if [ $AGINGTABLE_STATUS -ne "1" ]
    then
	  echo "Agingtable ist nicht aktiv. Backup wird gestartet!"
	else
  	  echo "Agingtable ist aktiv. Backup wird nicht gestartet!"
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
 
#Überprüfen ob PiShrink aktuell ist sonst herunterladen
echo "überprüfe ob PiShrink vorhanden ist"
echo "Checking..."
online_md5="$(curl -sL https://raw.githubusercontent.com/Drewsif/PiShrink/master/pishrink.sh | md5sum | cut -d ' ' -f 1)"
local_md5="$(md5sum "/usr/local/bin/pishrink.sh" | cut -d ' ' -f 1)"
if [ "$online_md5" == "$local_md5" ]; 
	then
    	echo "PiShrink is the latest version!"
    else
    	echo "Installing PiShrink!"
		wget -N https://raw.githubusercontent.com/Drewsif/PiShrink/master/pishrink.sh
		chmod +x pishrink.sh
		sudo mv pishrink.sh /usr/local/bin
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

DIR=$NFSMOUNT/$SUBDIR
 
echo "Starte $BACKUP_NAME $(date +%T)"
echo "NFSVOL=$NFSVOL"
echo "NFSMOUNT=$NFSMOUNT"
echo "Backup Verzeichnis=$DIR"
echo "NFSOPT=$NFSOPT"
echo "Backup file ${BACKUP_PFAD}/${BACKUP_NAME}.img"

# Vorsichtshalber einmal unmounten
umount $NFSMOUNT

# NFS-Volume mounten
echo "hänge NFS-Volume ein"

if [ -z $NFSOPT ]
	then
		sudo mount -t nfs4 $NFSVOL $NFSMOUNT -o $NFSOPT
 	else
 		sudo mount -t nfs4 $NFSVOL $NFSMOUNT
fi

# Prüfen, ob das Zielverzeichnis existiert
echo "Prüfen, ob das Zielverzeichnis $DIR existiert"
sleep 2
if [ ! -d "$DIR" ];
	then
        echo "Backupverzeichnis $DIR existiert nicht. Abbruch! Bitte anlegen"
        umount $NFSMOUNT
        exit 1
    else
        echo "Backupverzeichnis $DIR existiert"
fi

# prüfe, ob current user in das Verzeichnis schreiben kann

u="$USER"
echo "Prüfen, ob $u in das Backup Verzeichnis schreiben kann"

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

if [ "$ACCESS" == "no" ]; then
      echo "$u hat keine Schreibrechte. Abbruch"
      echo "Entweder für das Backupverzeichnis mit chmod Schreibrechte einräumen oder"
      echo "mit chown Besitzer und Gruppe anpassen"
      echo "OWNER = $OWNER"
      echo "GROUP = $GROUP"
      echo "Rechte = $PERM"
      umount $NFSMOUNT
      exit 1
    else
      echo "$u hat Schreibrechte für $DIR"
fi

# Stoppe Dienste vor Backup
echo "Stoppe schreibende Dienste!"
#${DIENSTE_START_STOP} stop

if [ -z "$MAIN_STATUS" ]
	then
      PI_AGER_MAIN_ACTIVE=0
      echo "Pi-Ager Main ist nicht gestartet"
    else
      PI_AGER_MAIN_ACTIVE=1
      echo "Stoppe Pi-Ager Main"
      systemctl stop pi-ager_main
      sleep 10
fi	

# Backup mit Hilfe von dd erstellen und im angegebenen Pfad speichern
# write buffer and clear caches
sync
echo 1 > /proc/sys/vm/drop_caches

echo "erstelle Backup ${BACKUP_PFAD}/${BACKUP_NAME}.img $(date +%T)"
dd if=/dev/mmcblk0 of=${BACKUP_PFAD}/${BACKUP_NAME}.img bs=1M status=progress 2>&1
status=$?
if [ $status -ne 0 ]; then
  echo "Fehler $status beim Erstellen des Backup. Abbruch"
  echo "Starte schreibende Dienste wieder!"
  if [ $PI_AGER_MAIN_ACTIVE == 1 ]; then
    echo  "Starte Pi-Ager Main"
    systemctl start pi-ager_main &
  fi
  umount $NFSMOUNT
  exit 1
fi

# Starte Dienste nach Backup
echo "Starte schreibende Dienste wieder!"
#${DIENSTE_START_STOP} start
if [ $PI_AGER_MAIN_ACTIVE == 1 ]; then
    echo  "Starte Pi-Ager Main"
    systemctl start pi-ager_main &
fi

sync
# Starte Shrink
echo "starte mit PiShrink $(date +%T) pishrink.sh $OPTARG ${BACKUP_PFAD}/${BACKUP_NAME}.img"
#read -p "Press enter to continue before pishrink call"
# -d write debug file
#sudo /usr/local/bin/pishrink.sh -d $OPTARG ${BACKUP_PFAD}/${BACKUP_NAME}.img
sudo /usr/local/bin/pishrink.sh ${BACKUP_PFAD}/${BACKUP_NAME}.img

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
    echo -e $(date +%c)": "'Backup und verkleinern erfolgreich abgeschlossen nach '$diff' Sekunden'

# Wenn kleiner 3600 Sekunden, in Minuten und Sekunden umrechnen
#################################################################
    elif [ $diff -lt  3599 ]; then
        echo -e $(date +%c)": "'Backup und verkleinern erfolgreich abgeschlossen nach '$[$diff / 60] 'Minuten(s) '$[$diff % 60] 'Sekunden'

# Wenn gleich oder groeßer 3600 Sekunden, in Stunden Minuten und Sekunden umrechnen
#################################################################
    elif [ $diff -ge 3600 ]; then
        echo -e $(date +%c)": "'Backup und verkleinern erfolgreich abgeschlossen nach '$[$diff / 3600] 'Stunden '$[$diff % 3600 / 60] 'Minuten '$[$diff % 60] 'Sekunden'
fi

# unmounten
sync
umount $NFSMOUNT

