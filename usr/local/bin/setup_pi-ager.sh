#!/bin/sh
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
#    eval $(grep -i "^partsize=" /boot/setup.txt| tr -d "\n\r")
    eval $(grep -i "^reboot=" /boot/setup.txt| tr -d "\n\r")
    eval $(grep -i "^bus_type=" /boot/setup.txt| tr -d "\n\r")
    eval $(grep -i "^sensor_type=" /boot/setup.txt| tr -d "\n\r")
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
	echo "Bus Typ:"
	echo $bus_type
	echo "Sensor Typ:"
	echo $sensor_type
	
#    echo "Partitionsgroesse:"
#    echo $partsize
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
        sed -i "s/raspberry/$dbpw/g" /var/www/phpliteadmin.php
        echo "phpliteadmin gesetzt"
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
            wpa_passphrase "$wlanssid" $wlankey >> /etc/wpa_supplicant/wpa_supplicant.conf
            #ifdown wlan0
            echo "WLAN SSID und Passphrase gesetzt"
        fi
    fi

    # Configfile löschen
    if [ -z "$keepconf" ]         #wenn ""
    then
        rm /boot/setup.txt
        echo "Config gelöscht"
    fi
    
    # Bus type setzen
    if [ -z "$bus_type" ]
    then
    	sqlite3 /var/www/config/pi-ager.sqlite3 "UPDATE config SET value = $bus_type WHERE key = 'sensorbus'"
    	echo "Sensor Bus auf "$bus_type" gesetzt"
    	if [ "$bus_type" eq 0 ]
	    	then
				# hier muss alles hin was vor dem shutdown gemacht werden soll, um auf i2c zu wechseln
        		rm -r /etc/modprobe.d/Pi-Ager_i2c_off.conf
				echo "I2C is active"
			elif [ "$bus_type" eq 1 ]
		then
				# hier muss alles hin was vor dem shutdown gemacht werden soll, um auf 1wire zu wechseln
        		cp /etc/modprobe.d/Pi-Ager_i2c_off.conf.on /etc/modprobe.d/Pi-Ager_i2c_off.conf
        		echo "1-wire is active"
    	fi
    fi
    
    # Sensor type setzen
    if [ -z "$sensor_type" ]
    then
    	sqlite3 /var/www/config/pi-ager.sqlite3 "UPDATE config SET value = $sensor_type WHERE key = 'sensortype'"
		echo "Sensor Bus auf "$sensor_type" gesetzt"
    fi
fi

systemctl disable setup_pi-ager.service # Setupscript in Startroutine deaktivieren, da es nur beim ersten Start benötigt wird. 
systemctl disable pi-ager_scale.service pi-ager_agingtable.service # Werden manuell gestartet
systemctl enable pi-ager_main.service 
systemctl start pi-ager_main.service
# reboot wenn 
#if [ -z "$reboot" ]         #wenn fehlt oder ""
#then
#    shutdown -r now
#fi
