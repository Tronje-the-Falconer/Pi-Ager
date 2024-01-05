#!/bin/bash
# File: setup_pi-ager.sh
# START
#crontab richtigstellen 
sed -n '/reboot/!p' /etc/crontab >/etc/crontabtemp ; mv -f /etc/crontabtemp /etc/crontab # sed sucht | -n abstellen von ausgabe auf Konsole 
echo
echo "------------------------------------------------------"
echo "Config Start"
echo "------------------------------------------------------"

if [ -e /boot/firmware/setup.txt ]           #wenn setup.txt existiert
then
    echo "Setup.txt exists, getting variables"
    eval $(grep -i "^piname=" /boot/firmware/setup.txt| tr -d "\n\r")
    eval $(grep -i "^pipass=" /boot/firmware/setup.txt| tr -d "\n\r")
    eval $(grep -i "^rootpass=" /boot/firmware/setup.txt| tr -d "\n\r")
    eval $(grep -i "^webguipw=" /boot/firmware/setup.txt| tr -d "\n\r")
    eval $(grep -i "^dbpw=" /boot/firmware/setup.txt| tr -d "\n\r")
    eval $(grep -i "^wlanssid=" /boot/firmware/setup.txt| tr -d "\n\r")
    eval $(grep -i "^wlankey=" /boot/firmware/setup.txt| tr -d "\n\r")
    eval $(grep -i "^country=" /boot/firmware/setup.txt| tr -d "\n\r")
    eval $(grep -i "^keepconf=" /boot/firmware/setup.txt| tr -d "\n\r")
    eval $(grep -i "^sensor=" /boot/firmware/setup.txt | tr -d "\n\r")    
    eval $(grep -i "^hmidisplay=" /boot/firmware/setup.txt | tr -d "\n\r")
    eval $(grep -i "^sensor=" /boot/firmware/setup.txt | tr -d "\n\r")  
    echo "Variablen"
    echo "Hostname = $piname"
    #echo $pipass
    #echo $rootpass
    #echo $webguipw
    echo "WLAN SSID = $wlanssid"
    echo "Country = $country"
    #echo $wlankey
    echo "Config behalten = $keepconf"
	echo "sensor = $sensor"    
    echo "hmidisplay = $hmidisplay"
    
    echo "SSH Host Key generieren"
    # SSH Host Key generieren
    /bin/rm -fv /etc/ssh_host_*
    #/usr/sbin/dpkg-reconfigure openssh-server
    systemctl restart ssh.service
    # pi Rechnername setzen
    if [ -n "$piname" ]            #wenn nicht ""
    then
        # CURRENT_HOSTNAME=`cat /etc/hostname | tr -d " \t\n\r"`
        # echo -n $piname > /etc/hostname
        # sed -i "s/127.0.1.1.*$CURRENT_HOSTNAME/127.0.1.1\t$piname/g" /etc/hosts
        raspi-config nonint do_hostname $piname
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
    # remove old profiles but not PI_AGER_ap.nmconnection
    # rfkill unblock wifi
    mv /etc/NetworkManager/system-connections/PI_AGER_AP.nmconnection /etc/NetworkManager/system-connections/PI_AGER_AP.nmconnection.org
    rm /etc/NetworkManager/system-connections/*.nmconnection
    mv /etc/NetworkManager/system-connections/PI_AGER_AP.nmconnection.org /etc/NetworkManager/system-connections/PI_AGER_AP.nmconnection
    # systemctl restart NetworkManager
    if [ -n "$wlanssid" ]         #wenn nicht ""
    then
        if [  ${#wlankey} -ge 8 ]   # 8 Zeichen oder mehr
        then
            # echo "ctrl_interface=DIR=/var/run/wpa_supplicant GROUP=netdev" > /etc/wpa_supplicant/wpa_supplicant.conf
            # echo "update_config=1" >> /etc/wpa_supplicant/wpa_supplicant.conf
            # echo "country=$country" >> /etc/wpa_supplicant/wpa_supplicant.conf
            # wpa_passphrase "$wlanssid" "$wlankey" >> /etc/wpa_supplicant/wpa_supplicant.conf
            # echo -e "\nnetwork={\n\tssid=\x22${wlanssid}\x22\n\tpsk=\x22${wlankey}\x22\n\tkey_mgmt=WPA-PSK\n}" >> /etc/wpa_supplicant/wpa_supplicant.conf
            raspi-config nonint do_wifi_country $country
            echo "raspi-config do_wifi_country setup finished"
            # raspi-config nonint do_wifi_ssid_passphrase "$wlanssid" "$wlankey"
            # nmcli device wifi connect "$wlanssid" password "$wlankey" ifname wlan0
            nmFilename='/etc/NetworkManager/system-connections/pi-ager-wlan.nmconnection'
            cat <<EOF > "$nmFilename"
[connection]
id=pi-ager-wlan
uuid=
type=wifi
interface-name=wlan0
timestamp=
autoconnect=true

[wifi]
mode=infrastructure
ssid=

[wifi-security]
auth-alg=open
key-mgmt=wpa-psk
psk=

[ipv4]
method=auto

[ipv6]
addr-gen-mode=default
method=auto

[proxy]
EOF
            chmod -R 600 "$nmFilename"
            chown -R root:root "$nmFilename"
            uuid=$(uuidgen)
            timestamp=$(date +%s)
            modify_nmconnection "$nmFilename" "$wlanssid" "$wlankey" "$uuid" "$timestamp"
            if [ $? -eq 0 ]
            then
                echo "WLAN SSID und Passphrase gesetzt"
            else
                echo "Fehler $? : WLAN SSID und Passphrase konnten nicht gesetzt werden"
            fi
        fi
    fi

    # Configfile löschen
    if [ -z "$keepconf" ]         #wenn ""
    then
        rm /boot/firmware/setup.txt
        echo "Config gelöscht"
    fi
    
# check sensor
    sensorbus=0
    sensornum=5
    case $sensor in
      "DHT11") sensorbus=1; sensornum=1;;
      "DHT22") sensorbus=1; sensornum=2;;
      "SHT75") sensorbus=1; sensornum=3;;
      "SHT85") sensorbus=0; sensornum=4;;
      "SHT3x") sensorbus=0; sensornum=5;;
      "SHT3x-mod") sensorbus=0; sensornum=6;;
      "AHT1x") sensorbus=0; sensornum=7;; 
      "AHT1x-mod") sensorbus=0; sensornum=8;;       
      "AHT2x") sensorbus=0; sensornum=9;;
      "AHT30") sensorbus=0; sensornum=10;;      
      "AHT4x-A") sensorbus=0; sensornum=11;;
      "AHT4x-B") sensorbus=0; sensornum=12;;
      "AHT4x-C") sensorbus=0; sensornum=13;;      
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
        rm -f /etc/modprobe.d/Pi-Ager_i2c_off.conf
        echo "i2c is active"
    elif [ $sensorbus -eq 1 ]; then
    # hier muss alles hin was vor dem shutdown gemacht werden soll, um auf 1wire zu wechseln
        cp /etc/modprobe.d/Pi-Ager_i2c_off.conf.on /etc/modprobe.d/Pi-Ager_i2c_off.conf
        echo "1-wire is active"

    fi

# load firmware into HMI display if hmidisplay != "none"
    case $hmidisplay in
        "NX3224K028") echo "start firmware upload for HMI display device $hmidisplay"
                      nextion-fw-upload /dev/serial0 /var/www/nextion/NX3224K028/pi-ager.tft
                      echo "firmware upload for HMI device finished";;
        "NX3224T028") echo "start firmware upload for HMI display device $hmidisplay"
                      nextion-fw-upload /dev/serial0 /var/www/nextion/NX3224T028/pi-ager.tft
                      echo "firmware upload for HMI device finished";;
        "NX3224F028") echo "start firmware upload for HMI display device $hmidisplay"
                      nextion-fw-upload /dev/serial0 /var/www/nextion/NX3224F028/pi-ager.tft
                      echo "firmware upload for HMI device finished";;
    esac
fi

echo "disable setup_pi-ager.service now"
systemctl disable setup_pi-ager.service # Setupscript in Startroutine deaktivieren, da es nur beim ersten Start benötigt wird. 

# now its time to enable pi-ager_main.service to start at next boot. Reboot is initiated by rc.local after expanding file system on root partition
systemctl enable pi-ager_main.service 

exit 0
