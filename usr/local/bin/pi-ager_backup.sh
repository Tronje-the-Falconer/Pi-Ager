#!/bin/bash

# Script-Name: pi-ager_backup
# Version    : 0.9.7
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

# Server und Pfad zur NFS Freigabe (Muss im NAS angelegt werden)
NFSVOL=$(sqlite3 /var/www/config/pi-ager.sqlite3 "select nfsvol from config_nfs_backup where active = 1")

# dieses Verzeichniss muss im NAS angelegt sein
SUBDIR=$(sqlite3 /var/www/config/pi-ager.sqlite3 "select subdir from config_nfs_backup where active = 1")

# Pfad auf dem Pi indem das Backup gespeichert wird, hierhin wird gemoundet
NFSMOUNT=$(sqlite3 /var/www/config/pi-ager.sqlite3 "select nfsmount from config_nfs_backup where active = 1")

#z.B. NFSOPT="nosuid,nodev,rsize=65536,wsize=65536,intr,noatime"
NFSOPT=$(sqlite3 /var/www/config/pi-ager.sqlite3 "select nfsopt from config_nfs_backup where active = 1")


# setzt sich zusammen aus dem Dateipfad auf dem Pi und dem Verzeichnis im NAS
BACKUP_PFAD=$(sqlite3 /var/www/config/pi-ager.sqlite3 "select backup_path from config_nfs_backup where active = 1")

# behält die letzten "n" Backups
BACKUP_ANZAHL=$(sqlite3 /var/www/config/pi-ager.sqlite3 "select number_of_backups from config_nfs_backup where active = 1")

# Name des Backup
BACKUP_NAME=$(sqlite3 /var/www/config/pi-ager.sqlite3 "select backup_name from config_nfs_backup where active = 1")

systemctl daemon-reload

# Prüfen, ob Backup schon läuft
currsh=$0
currpid=$$
runpid=$(lsof -t $currsh| paste -s -d " ")
#echo $currpid
#echo $runpid
if [[ $runpid != $currpid ]]
	then
	  echo "Zweiter Backup Prozess vorhanden!"
	  exit 1
fi	 


# Lese Status der Pi-Ager Prozesse

MAIN_STATUS=$(/var/sudowebscript.sh grepmain) 
echo "Main Status ist $MAIN_STATUS"

# AGINGTABLE_STATUS=$(/var/sudowebscript.sh grepagingtable)
# echo "Agingtable Status ist $AGINGTABLE_STATUS"

# SCALE_STATUS=$(/var/sudowebscript.sh grepscale)
# echo "Scale Status ist $SCALE_STATUS"



# ENDE VARIABLEN
 
#####################################################################
# Skript (hier sollten nur erfahrene User anpassungen machen!)
#####################################################################

read -p "weiter mit Enter mit Ctrl + c beenden" -t 3
echo "ok los gehts lehne dich zurück $(date +%H:%M:%S)"
anfang=$(date +%s)

echo "Starte mit dem Backup, dies kann einige Zeit dauern"
#Überprüfen ob Agingtable aktiv ist
# if [ -z "$AGINGTABLE_STATUS" ]
#    then
#	  echo "Agingtable ist nicht aktiv. Backup wird gestartet!"
#	else
#  	  echo "Agingtable ist aktiv. Backup wird nicht gestartet!"
#	  exit 1
#fi	
#Überprüfen ob Backup aktiv ist
if [ -z "$BACKUP_STATUS" ]
	then
	  echo "Backup ist inaktiv. Backup wird gestartet!"
	else
	  echo "Backup ist aktiv. Backup wird nicht gestartet!"
	  exit 1
fi	



#Überprüfen ob der NFS-Server vorhanden ist
echo "überprüfe ob der NFS-Server vorhanden ist."
echo "Checking..."
if [ -z "$NFSVOL" ]
	then
	echo "Backup nicht korrekt eingestellt. Bitte Tabelle nfs_backup prüfen!"
	exit 1
fi
 		
#Überprüfen ob Backupordner vorhanden ist sonst erstellen
echo "überprüfe ob der Backuppfad vorhanden ist."
echo "Checking..."
if [ -d "$NFSMOUNT" ]
	then
		echo "$NFSMOUNT ist vorhanden"
	else
		echo "$NFSMOUNT wird angelegt"
		sudo mkdir $NFSMOUNT
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
 
echo "Starte $BACKUP_NAME! $(date +%H:%M:%S)"
echo "NFSVOL=$NFSVOL"
echo "DIR=$DIR"
 
# Vorsichtshalber einmal unmounten
umount $NFSMOUNT

# NFS-Volume mounten
echo "hänge NFS-Volume ein"
echo $NFSOPT
if [ -z $NFSOPT ]
	then
		sudo mount -t nfs4 $NFSVOL $NFSMOUNT -o $NFSOPT
 	else
 		sudo mount -t nfs4 $NFSVOL $NFSMOUNT
 fi
# Prüfen, ob das Zielverzeichnis existiert
echo "Prüfe ob das Zielverzeichnis existiert"
sleep 2
if [ ! -d "$DIR" ];
	then
	echo "Backupverzeichnis existiert nicht. Abbruch! Bitte anlegen"
	umount $NFSMOUNT
	exit 1
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

#if [ -z "$AGINGTABLE_STATUS" ]
#	then
#      PI_AGER_AGINGTABLE_ACTIVE=0
#      echo "Pi-Ager Agingtable ist nicht gestartet"
#    else
#	  PI_AGER_AGINGTABLE_ACTIVE=1
#      echo "Stoppe Pi-Ager Agingtable"
#      systemctl stop pi-ager_agingtable
#fi

#if [ -z "$SCALE_STATUS" ]
#	then
#      PI_AGER_SCALE_ACTIVE=0
#      echo "Pi-Ager Scale ist nicht gestartet"
#    else
#	  PI_AGER_SCALE_ACTIVE=1
#      echo "Stoppe Pi-Ager Scale"
#      systemctl stop pi-ager_scale
#fi

# Backup mit Hilfe von dd erstellen und im angegebenen Pfad speichern
sync
echo "erstelle Backup $(date +%H:%M:%S)"
dd if=/dev/mmcblk0 of=${BACKUP_PFAD}/${BACKUP_NAME}.img bs=1M status=progress 2>&1

# Starte Dienste nach Backup
echo "Starte schreibende Dienste wieder!"
#${DIENSTE_START_STOP} start
if [ $PI_AGER_MAIN_ACTIVE == 1 ]; then
echo  "Starte Pi-Ager Main"
  systemctl start pi-ager_main &
fi

#if [ $PI_AGER_SCALE_ACTIVE == 1 ]; then
#echo  "Starte Pi-Ager Scale"
#  systemctl start pi-ager_scale &
#fi

#if [ $PI_AGER_AGINGTABLE_ACTIVE == 1 ]; then
#echo  "Starte Pi-Ager Agingtable"
#  systemctl start pi-ager_agingtable &
#fi

sync
# Starte Shrink
echo "starte mit PiShrink $(date +%H:%M:%S) pishrink.sh $OPTARAG ${BACKUP_PFAD}/${BACKUP_NAME}.img"
#read -p "Press enter to continue before pishrink call"
# -d write debug file
#sudo /usr/local/bin/pishrink.sh -d $OPTARG ${BACKUP_PFAD}/${BACKUP_NAME}.img
sudo /usr/local/bin/pishrink.sh $OPTARG ${BACKUP_PFAD}/${BACKUP_NAME}.img

# Backup umbenennen
mv ${BACKUP_PFAD}/${BACKUP_NAME}.img ${BACKUP_PFAD}/${BACKUP_NAME}_$(date +%Y-%m-%d-%H:%M:%S).img

# Backup beendet
#sqlite3 /var/www/config/pi-ager.sqlite3 "BEGIN TRANSACTION;UPDATE config SET value = '0.0' where key = 'backup_status'; COMMIT;"

# Alte Sicherungen die nach X neuen Sicherungen entfernen
NUMBER_OF_BACKUPS=$(find ${BACKUP_PFAD}/${BACKUP_NAME}* -type f | wc -l)
echo "Number of backups that are kept. ${BACKUP_ANZAHL}"
echo "Actual number of backups. ${NUMBER_OF_BACKUPS}"

if [ ${NUMBER_OF_BACKUPS} -gt ${BACKUP_ANZAHL} ]; then
	pushd ${BACKUP_PFAD}; ls -tr ${BACKUP_PFAD}/${BACKUP_NAME}* | head -n -${BACKUP_ANZAHL} | xargs rm; popd
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
umount $NFSMOUNT

