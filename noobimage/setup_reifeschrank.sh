###########################################################################
##
## V4 SSH Host Key neu generieren
##    5.9.2016
##
## V3 Resize mit Prozentangabe
##    automatisches Update des Displays
##    WLAN starten
##    18.3.2015
##
## V2 Passwoerter von pi, root und web aus setup.txt setzen
##    WLAN einstellen
##    Resize root partition                                                 
##
###########################################################################

#crontab richtigstellen 
sed -n '/reboot/!p' /etc/crontab >/etc/crontabtemp ; mv -f /etc/crontabtemp /etc/crontab # sed sucht -n abstellen von ausgabe auf Konsole 
echo
echo "------------------------------------------------------"
echo "Config Start"
echo "------------------------------------------------------"

if [ -e /boot/setup.txt ]; then

  #service WLANThermoNEXTION stop

  # stty -F /dev/ttyAMA0 115200
  # echo -en page boot"\xff\xff\xff" > /dev/ttyAMA0  # -en = e: Von einem Backslash („\“) eingeleitete Sequenzen werden als Befehle erkannt & n: Keine Ausgabe des am Zeilenende stehenden Zeilentrenners
  # echo -en tm0.en=0"\xff\xff\xff" > /dev/ttyAMA0 
  # echo -en t0.txt=\"\""\xff\xff\xff" > /dev/ttyAMA0 
  # echo -en b0.txt=\"Setup startet...\""\xff\xff\xff" > /dev/ttyAMA0 
  # echo -en t0.txt=\"\""\xff\xff\xff" > /dev/ttyAMA0 
  # echo -en b0.txt=\"Setup startet...\""\xff\xff\xff" > /dev/ttyAMA0 
  # sleep 1

  eval $(grep -i "^piname=" /boot/setup.txt| tr -d "\n\r")
  eval $(grep -i "^pipass=" /boot/setup.txt| tr -d "\n\r")
  eval $(grep -i "^rootpass=" /boot/setup.txt| tr -d "\n\r")
  eval $(grep -i "^webguipw=" /boot/setup.txt| tr -d "\n\r")
  eval $(grep -i "^wlanssid=" /boot/setup.txt| tr -d "\n\r")
  eval $(grep -i "^wlankey=" /boot/setup.txt| tr -d "\n\r")
  eval $(grep -i "^keepconf=" /boot/setup.txt| tr -d "\n\r")
  eval $(grep -i "^partsize=" /boot/setup.txt| tr -d "\n\r")
  eval $(grep -i "^reboot=" /boot/setup.txt| tr -d "\n\r")


  echo "Variablen"
  echo "Hostname:"
  echo $piname
  #echo $pipass
  #echo $rootpass
  #echo $webguipw
  echo "WLAN SSID:"
  echo $wlanssid
  #echo $wlankey
  echo "Config behalten:"
  echo $keepconf
  echo "Partitionsgroesse:"
  echo $partsize

  # SSH Host Key generieren
  /bin/rm -fv /etc/ssh_host_*
  /usr/sbin/dpkg-reconfigure openssh-server

  # pi Rechnername setzen
  if [ -n "$piname" ]; then           #wenn nicht ""
    # echo -en b0.txt=\"Hostname: $piname\""\xff\xff\xff" > /dev/ttyAMA0
    # sleep 1
    CURRENT_HOSTNAME=`cat /etc/hostname | tr -d " \t\n\r"`
    echo -n $piname > /etc/hostname
    sed -i "s/127.0.1.1.*$CURRENT_HOSTNAME/127.0.1.1\t$piname/g" /etc/hosts
    echo "Hostname gesetzt"
  fi

  # pi pass setzen
  if [ -n "$pipass" ]; then           #wenn nicht ""
    # echo -en b0.txt=\"pi Passwort setzen\""\xff\xff\xff" > /dev/ttyAMA0
    # sleep 1
    echo "pi:$pipass" | chpasswd 
    echo "Passwort pi gesetzt"
  fi

  # root pass setzen
  if [ -n "$rootpass" ]; then         #wenn nicht ""
    # echo -en b0.txt=\"root Passwort setzen\""\xff\xff\xff" > /dev/ttyAMA0
    # sleep 1
    echo "root:$rootpass" | chpasswd 
    echo "Passwort root gesetzt"
  fi

  # settings pass setzen
  if [ -n "$webguipw" ]; then         #wenn nicht ""
    # echo -en b0.txt=\"web Passwort setzen\""\xff\xff\xff" > /dev/ttyAMA0
    # sleep 1
    htpasswd -b /var/htpasswd reifeschrank $webguipw
    echo "Passwort webgui gesetzt"
  fi

  # wlan Netz und Key eintragen
  if [ -n "$wlanssid" ]; then         #wenn nicht ""
    if [  ${#wlankey} -ge 8 ]; then   # 8 Zeichen oder mehr
      # echo -en b0.txt=\"WLAN einrichten\""\xff\xff\xff" > /dev/ttyAMA0
      # sleep 1
      echo "ctrl_interface=DIR=/var/run/wpa_supplicant GROUP=netdev" > /etc/wpa_supplicant/wpa_supplicant.conf
      echo "update_config=1" >> /etc/wpa_supplicant/wpa_supplicant.conf
      
      wpa_passphrase $wlanssid $wlankey >> /etc/wpa_supplicant/wpa_supplicant.conf
      ifdown wlan0
      echo "WLAN SSID und Passphrase gesetzt"

    fi
  fi

  # Configfile löschen
  if [ -z "$keepconf" ]; then         #wenn ""

    # echo -en b0.txt=\"Config loeschen\""\xff\xff\xff" > /dev/ttyAMA0
    # sleep 1

    rm /boot/setup.txt

    echo "Config gelöscht"
  fi
fi

#echo -en b0.txt=\"Partition resize.\""\xff\xff\xff" > /dev/ttyAMA0
ROOT_PART=$(mount | sed -n 's|^/dev/\(.*\) on / .*|\1|p')
PART_NUM=${ROOT_PART#mmcblk0p}
PART_START=$(parted /dev/mmcblk0 -ms unit s p | egrep "^${PART_NUM}" | cut -f 2 -d: | sed 's/[^0-9]//g')
#PART_END=$(parted /dev/mmcblk0 -ms unit s p | egrep "^${PART_NUM}" | cut -d: -f3| sed 's/[^0-9]//g') # aktuelles Partiton Ende

CARD_END=$(parted /dev/mmcblk0 -ms unit s p | egrep "/dev/mmcblk0" | cut -d: -f2| sed 's/[^0-9]//g')  # Anzahl der Sektoren gesamt
PART_END=$(expr $CARD_END - 1)        # Ende der Karte setzen, Sektor 0 entfernen

if [ -n "$partsize"  ]; then         #wenn nicht ""
  if [ "$partsize" != *[!0-9]* ]; then         # und eine Zahl
    # echo -en b0.txt=\"Partition resize $partsize%\""\xff\xff\xff" > /dev/ttyAMA0
    # sleep 1
    PARTTEMP=$(expr $PART_END \* 100)          # Shift für Ganzzahlen
    PARTTEMP=$(expr $PARTTEMP \* $partsize)    # Prozent der Größe angeben
    PART_END=$(expr $PARTTEMP / 10000)         # Auf den richtigen Wert
echo Debug PART_END $PART_END
echo Debug partsize $partsize
echo Debug PARTTEMP $PARTTEMP
  fi
fi

#[ "${PART_START}" ] || exit 1
#[ "${PART_END}" ] || exit 1

#if [ $(echo ${PART_END} | sed 's/s//g') -eq 3788799 -o \
#     $(echo ${PART_END} | sed 's/s//g') -eq 5785599 -o \
#     $(echo ${PART_END} | sed 's/s//g') -eq 6399999 \
#   ]; then
  echo
  echo "------------------------------------------------------"
  echo "Resize root partition"
  echo "------------------------------------------------------"

  fdisk /dev/mmcblk0 <<EOF
p
d
$PART_NUM
n
p
$PART_NUM
$PART_START
$PART_END
p
w
EOF
# echo -en b0.txt=\"Partition resize..\""\xff\xff\xff" > /dev/ttyAMA0
# service WLANThermoNEXTION start

  echo
  echo "Done"
#fi

if [ ! -f /etc/init.d/resize2fs_once ]; then
  echo
  echo "------------------------------------------------------"
  echo "Create init script for filesystem resize"
  echo "------------------------------------------------------"

  cat <<EOF > /etc/init.d/resize2fs_once &&
#!/bin/sh
### BEGIN INIT INFO
# Provides:          resize2fs_once
# Required-Start:
# Required-Stop:
# Default-Start: 3
# Default-Stop:
# Short-Description: Resize the root filesystem to fill partition
# Description:
### END INIT INFO

. /lib/lsb/init-functions

case "\$1" in
  start)
    log_daemon_msg "Starting resize2fs_once" &&
    resize2fs /dev/$ROOT_PART &&
    update-rc.d resize2fs_once remove &&
    /sbin/dphys-swapfile setup &&
    rm /etc/init.d/resize2fs_once &&
    rm /root/resize.sh &&
    log_end_msg \$?
    ;;
  *)
    echo "Usage: \$0 start" >&2
    exit 3
    ;;
esac
EOF
# service WLANThermoNEXTION stop
# echo -en b0.txt=\"Partition resize...\""\xff\xff\xff" > /dev/ttyAMA0

  chmod +x /etc/init.d/resize2fs_once &&
  update-rc.d resize2fs_once defaults &&

  echo
  echo "Done"
fi
ifup wlan0
## Display Update Check
# if [ -e /var/www/tmp/nextionupdate ]; then           #wenn nicht ""
   # echo "Display Update startet $(date +"%R %x")"
   # /usr/sbin/wlt_2_updatenextion.sh /usr/share/WLANThermo/nextion/ > /var/www/tmp/error.txt
   # echo "Display Update fertig $(date +"%R %x")"
# fi


# reboot wenn 
if [ -z "$reboot" ]; then         #wenn fehlt oder ""
  shutdown -r now
  #echo -en b0.txt=\"Reboot.....\""\xff\xff\xff" > /dev/ttyAMA0
fi
