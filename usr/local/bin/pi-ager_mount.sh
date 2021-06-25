#!/bin/bash

# Script-Name: pi-ager_mount
# Version    : 0.0.1
# Autor      : DerBurgermeister
# Datum      : 19.05.2021
# Coauthor   : 
# Datum      : 
# Dieses Script mounted das Backup
#####################################################################
#Variablen
#####################################################################

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

# ENDE VARIABLEN
 
#####################################################################
# Skript (hier sollten nur erfahrene User anpassungen machen!)
#####################################################################

read -p "weiter mit Enter mit Ctrl + c beenden" -t 3
echo "ok los gehts lehne dich zurück $(date +%H:%M:%S)"
anfang=$(date +%s)

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
 

DIR=$NFSMOUNT/$SUBDIR
 
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
