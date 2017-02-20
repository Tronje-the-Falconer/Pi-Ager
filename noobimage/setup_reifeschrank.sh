#!/bin/sh
# START
#crontab richtigstellen 
sed -n '/reboot/!p' /etc/crontab >/etc/crontabtemp ; mv -f /etc/crontabtemp /etc/crontab # sed sucht | -n abstellen von ausgabe auf Konsole 
echo
echo "------------------------------------------------------"
echo "Config Start"
echo "------------------------------------------------------"

if [ -e /boot/setup.txt ]           #wenn setup.txt existiert
then
    echo "Setup.txt exists, getting variables"
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
    if [ -n "$piname" ]            #wenn nicht ""
    then
        CURRENT_HOSTNAME=`cat /etc/hostname | tr -d " \t\n\r"`
        echo -n $piname > /etc/hostname
        sed -i "s/127.0.1.1.*$CURRENT_HOSTNAME/127.0.1.1\t$piname/g" /etc/hosts
        echo "Hostname gesetzt"
    fi

    # pi pass setzen
    if [ -n "$pipass" ]           #wenn nicht ""
    then
        echo "pi:$pipass" | chpasswd 
        echo "Passwort pi gesetzt"
    fi

    # root pass setzen
    if [ -n "$rootpass" ]         #wenn nicht ""
    then
        echo "root:$rootpass" | chpasswd 
        echo "Passwort root gesetzt"
    fi

    # settings pass setzen
    if [ -n "$webguipw" ]         #wenn nicht ""
    then
        htpasswd -b /var/.htpasswd reifeschrank $webguipw
        echo "Passwort webgui gesetzt"
    fi

    # wlan Netz und Key eintragen
    if [ -n "$wlanssid" ]         #wenn nicht ""
    then
        if [  ${#wlankey} -ge 8 ]   # 8 Zeichen oder mehr
        then
            echo "ctrl_interface=DIR=/var/run/wpa_supplicant GROUP=netdev" > /etc/wpa_supplicant/wpa_supplicant.conf
            echo "update_config=1" >> /etc/wpa_supplicant/wpa_supplicant.conf
            wpa_passphrase $wlanssid $wlankey >> /etc/wpa_supplicant/wpa_supplicant.conf
            ifdown wlan0
            echo "WLAN SSID und Passphrase gesetzt"
        fi
    fi

    # Configfile löschen
    if [ -z "$keepconf" ]         #wenn ""
    then
        rm /boot/setup.txt
        echo "Config gelöscht"
    fi
fi

ROOT_PART=$(mount | sed -n 's|^/dev/\(.*\) on / .*|\1|p')
PART_NUM=${ROOT_PART#mmcblk0p}
PART_START=$(parted /dev/mmcblk0 -ms unit s p | egrep "^${PART_NUM}" | cut -f 2 -d: | sed 's/[^0-9]//g')

CARD_END=$(parted /dev/mmcblk0 -ms unit s p | egrep "/dev/mmcblk0" | cut -d: -f2| sed 's/[^0-9]//g')  # Anzahl der Sektoren gesamt
PART_END=$(expr $CARD_END - 1)        # Ende der Karte setzen, Sektor 0 entfernen

if [ -n "$partsize"  ]         #wenn nicht ""
then
    if [ "$partsize" != *[!0-9]* ]         # und eine Zahl
    then
        PARTTEMP=$(expr $PART_END \* 100)          # Shift für Ganzzahlen
        PARTTEMP=$(expr $PARTTEMP \* $partsize)    # Prozent der Größe angeben
        PART_END=$(expr $PARTTEMP / 10000)         # Auf den richtigen Wert
        echo Debug PART_END $PART_END
        echo Debug partsize $partsize
        echo Debug PARTTEMP $PARTTEMP
    fi
fi

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
echo
echo "Done"

if [ ! -f /etc/systemd/system/resize2fs_once.service ]
then
    echo
    echo "------------------------------------------------------"
    echo "Create service for filesystem resize"
    echo "------------------------------------------------------"
    
    cat <<EOF > /etc/systemd/system/resize2fs_once.service &&
    [Unit]
    Description=Resize the root filesystem to fill partition

    [Service]
    Type=oneshot
    ExecStart=/root/resize2fs_once.sh

    [Install]
    Alias=resize2fs_once
    WantedBy=multi-user.target
EOF
fi


if [ ! -f /root/resize2fs_once.sh ]
then
    echo
    echo "------------------------------------------------------"
    echo "Create init script for filesystem resize"
    echo "------------------------------------------------------"

    cat <<EOF > /root/resize2fs_once.sh &&
    #!/bin/sh
    . /lib/lsb/init-functions

        log_daemon_msg "Starting resize2fs_once" &&
        resize2fs /dev/$ROOT_PART &&
        systemctl disable resize2fs_once &&          # resize2fs_once-Script in Startroutine sicherheitshalber deaktivieren (eigentlich unnötig da one-shot)
        /sbin/dphys-swapfile setup &&
        rm /etc/systemd/system/setup_reifeschrank.service &&
        rm /root/setup_reifeschrank.sh &&          # Setupscript löschen
        rm /etc/systemd/system/resize2fs_once.service &&
        rm /root/resize2fs_once.sh &&              # resize2fs_once-Script löschen
        log_end_msg \$?
EOF

    systemctl disable setup_reifeschrank # Setupscript in Startroutine deaktivieren (eigentlich nicht nötig, da One-Shot)
    chmod +x /root/resize2fs_once.sh &&   # Resize2fs_once ausführbar machen
    systemctl enable resize2fs_once &&   # Resize2fs_once in Startroutine aktivieren

    echo
    echo "Done"
fi
ifup wlan0

# reboot wenn 
if [ -z "$reboot" ]         #wenn fehlt oder ""
then
    shutdown -r now
fi