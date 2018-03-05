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
    eval $(grep -i "^dbpw=" /boot/setup.txt| tr -d "\n\r")
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

    # phpliteadmin pass setzen
    if [ -n "$dbpw" ]         #wenn nicht ""
    then
        sed -i 's/raspberry/$dbpw' /var/www/phpliteadmin.php
        echo "phpliteadmin gesetzt"
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
systemctl disable setup_reifeschrank # Setupscript in Startroutine deaktivieren (eigentlich nicht nötig, da One-Shot)
ifup wlan0

# reboot wenn 
if [ -z "$reboot" ]         #wenn fehlt oder ""
then
    shutdown -r now
fi
