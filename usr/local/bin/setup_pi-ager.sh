#!/bin/bash
# File: setup_pi-ager.sh
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
    eval $(grep -i "^country=" /boot/setup.txt| tr -d "\n\r")
    eval $(grep -i "^keepconf=" /boot/setup.txt| tr -d "\n\r")
    eval $(grep -i "^sensor=" /boot/setup.txt | tr -d "\n\r")    
#    eval $(grep -i "^reboot=" /boot/setup.txt| tr -d "\n\r")
    eval $(grep -i "^sensor=" /boot/setup.txt | tr -d "\n\r")  
    echo "Variablen"
    echo "Hostname:"
    echo $piname
    #echo $pipass
    #echo $rootpass
    #echo $webguipw
    echo "WLAN SSID:"
    echo $wlanssid
    echo "Country:"
    echo $country
    #echo $wlankey
    echo "Config behalten:"
    echo $keepconf

	echo "Sensor=$sensor"    

    echo "SSH Host Key generieren"
    # SSH Host Key generieren
    /bin/rm -fv /etc/ssh_host_*
    #/usr/sbin/dpkg-reconfigure openssh-server
    systemctl restart ssh.service
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
        sed -i "s/raspberry/$dbpw/g" /var/www/phpliteadmin.config.php
        echo "phpliteadmin.config.php gesetzt"
    fi

    # settings pass setzen oder direkt mit https://websistent.com/tools/htdigest-generator-tool/ 
    if [ -n "$webguipw" ]         #wenn nicht ""
    then
        user="pi-ager"
        realm="Pi-Ager"
        digest="$( printf "%s:%s:%s" "$user" "$realm" "$webguipw" | md5sum | awk '{print $1}' )"
        
		rm /var/.htcredentials
		echo "$user:$realm:$digest" > /var/.htcredentials
        echo "Passwort webgui gesetzt"
    fi

    # wlan Netz und Key eintragen
    if [ -n "$wlanssid" ]         #wenn nicht ""
    then
        if [  ${#wlankey} -ge 8 ]   # 8 Zeichen oder mehr
        then
            echo "ctrl_interface=DIR=/var/run/wpa_supplicant GROUP=netdev" > /etc/wpa_supplicant/wpa_supplicant.conf
            echo "update_config=1" >> /etc/wpa_supplicant/wpa_supplicant.conf
            echo "country=$country" >> /etc/wpa_supplicant/wpa_supplicant.conf
#            wpa_passphrase "$wlanssid" "$wlankey" >> /etc/wpa_supplicant/wpa_supplicant.conf
            echo -e "\nnetwork={\n\tssid=\x22${wlanssid}\x22\n\tpsk=\x22${wlankey}\x22\n\tkey_mgmt=WPA-PSK\n}" >> /etc/wpa_supplicant/wpa_supplicant.conf
            echo "WLAN SSID und Passphrase gesetzt"
        fi
    fi

    # Configfile löschen
    if [ -z "$keepconf" ]         #wenn ""
    then
        rm /boot/setup.txt
        echo "Config gelöscht"
    fi
    
# check sensor
    sensorbus=
    sensornum=
    case $sensor in
      "DHT11") sensorbus=1; sensornum=1;;
      "DHT22") sensorbus=1; sensornum=2;;
      "SHT75") sensorbus=1; sensornum=3;;
      "SHT85") sensorbus=0; sensornum=4;;
      "SHT3x") sensorbus=0; sensornum=5;;
    esac
    echo "Bus = $sensorbus  sensor = $sensornum"

    if [ -n "$sensornum" ]    # wenn nicht ""
    then
        sqlite3 /var/www/config/pi-ager.sqlite3 "PRAGMA journalMode = wal; UPDATE config SET value=$sensorbus WHERE key='sensorbus';"
        sqlite3 /var/www/config/pi-ager.sqlite3 "PRAGMA journalMode = wal; UPDATE config SET value=$sensornum WHERE key='sensortype';"
        echo "sensorbus und sensornum in DB gesetzt"
    fi
    
    if [ $sensorbus -eq 0 ]; then
    # hier muss alles hin was vor dem shutdown gemacht werden soll, um auf i2c zu wechseln
        rm -r /etc/modprobe.d/Pi-Ager_i2c_off.conf
        echo "i2c is active"
    elif [ $sensorbus -eq 1 ]; then
    # hier muss alles hin was vor dem shutdown gemacht werden soll, um auf 1wire zu wechseln
        cp /etc/modprobe.d/Pi-Ager_i2c_off.conf.on /etc/modprobe.d/Pi-Ager_i2c_off.conf
        echo "1-wire is active"

    fi  
fi

systemctl disable setup_pi-ager.service # Setupscript in Startroutine deaktivieren, da es nur beim ersten Start benötigt wird. 
systemctl disable man-db.timer          # save cpu load, not needed wth pi-ager
systemctl disable man-db                # save cpu load, not needed wth pi-ager

systemctl enable pi-ager_main.service 
# systemctl start pi-ager_main.service
# ifup wlan0

# reboot
# shutdown -r now
exit 0
