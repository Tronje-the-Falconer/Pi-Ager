#!/bin/bash
export PIP_BREAK_SYSTEM_PACKAGES=1

set -x
# trap read debug

if [ ! -f  /usr/share/this_script.progress ] ; then
    echo 1 >/usr/share/this_script.progress

    echo 2 >/usr/share/this_script.progress
fi

progress=$(</usr/share/this_script.progress)
echo $progress

case $progress in
 1)
    echo "This should not happen!"
    ;;
 2)
    echo 3 >/usr/share/this_script.progress
    printf "\nPerform apt update / full-upgrade, wait 10s to start. Reboot when done\n"
    sleep 10
    apt update
    apt -y full-upgrade

    printf "\n Setup WiFi AP \n"
    ./setup-wifi-ap.sh
    
    printf "\n install git "
    apt -y install git
    
    printf "\nclone Pi-Ager software from github repository\n"
    git clone --depth=1 -b entwicklung https://github.com/Tronje-the-Falconer/Pi-Ager /home/pi/Pi-Ager/    
    printf "\n install rc.local service \n"
    cp /home/pi/Pi-Ager/etc/rc.local /etc/rc.local
    chmod 755 /etc/rc.local
    cp /home/pi/Pi-Ager/etc/systemd/system/rc-local.service /etc/systemd/system/
    systemctl daemon-reload
    systemctl enable rc-local.service
    
    printf "\nreboot, after reboot start this script with sudo again to continue system setup\n"
    sleep 3
    sync
    reboot
    ;;
 3)
    echo 4 >/usr/share/this_script.progress
    printf "\nSetup system for Pi-Ager, wait 10s to start. Reboot when done\n"
    sleep 10
    
    printf "\n SSH allow root login\n"
    sed -i 's/^#PermitRootLogin.*/PermitRootLogin yes/' /etc/ssh/sshd_config 
    service ssh restart
    
    printf "\nsetup serial port and locale\n"
    echo "root:raspberry" | chpasswd 
#    raspi-config nonint do_serial_hw 0
#    raspi-config nonint do_serial_cons 1
#    raspi-config nonint do_change_locale de_DE.UTF-8 UTF-8
#    raspi-config nonint do_change_locale en_GB.UTF-8 UTF-8
    
    printf "\nsetup /boot/firmware/config.txt and cmdline.txt\n"
sed -i '/^#dtparam=spi=on$/a \
\
# --------------------- pi-ager settings --------- \
# Use Pi-Ager Pins 11/13 GPIO 17/27 for I2C \
dtoverlay=i2c-gpio,bus=3,i2c_gpio_sda=17,i2c_gpio_scl=27 \
# Use Pi-Ager Pin 16 for MCP3204 \
dtoverlay=spi1-1cs,cs0_pin=16 \
# -----------------------------------------------' /boot/firmware/config.txt

    echo "enable_uart=1" >> /boot/firmware/config.txt
    echo "dtoverlay=miniuart-bt" >>/boot/firmware/config.txt
#    echo "dtparam=uart0=on" >> /boot/firmware/config.txt
#    echo "#  force_turbo=1" >> /boot/firmware/config.txt

    CMDLINE="/boot/firmware/cmdline.txt"
    sed -i 's/fsck.repair=yes/fsck.mode=force fsck.repair=yes/' /boot/firmware/cmdline.txt
    sed -i 's/rootwait/rootwait dwc_otg.fiq_fsm_mask=0x3/' /boot/firmware/cmdline.txt

    sed -i $CMDLINE -e "s/console=ttyAMA0,[0-9]\+ //"
    sed -i $CMDLINE -e "s/console=serial0,[0-9]\+ //"
    
    printf "\nConfigure Locales: enable 'de_DE.UTF-8 UTF-8' and 'en_GB.UTF-8 UTF-8'\n"
    printf "Other Locales can also be enabled but only en_GB and de_DE are supported by Pi-Ager.\n"
    printf "Reboots automatically when finished\n"
    printf "After reboot start this script with sudo again to continue system setup\n"
#    read -p "Press enter to continue"

    sed -i -e 's/# de_DE.UTF-8 UTF-8/de_DE.UTF-8 UTF-8/' /etc/locale.gen
    dpkg-reconfigure -f noninteractive locales
    update-locale LANG=de_DE.UTF-8
#    sync
#    reboot
#    ;;
# 4)
#    echo 5 >/usr/share/this_script.progress
#    printf "\nSetup system for Pi-Ager part 2, wait 10s to start. Reboot when done\n"
#    sleep 10
    echo "i2c-dev" >> /etc/modules
    touch /etc/modprobe.d/raspi-blacklist.conf
    
    cd /home/pi/Pi-Ager
    cp /home/pi/Pi-Ager/boot/firmware/setup.txt /boot/firmware/
    
    printf "\ninstall php\n"
    apt -y install php-fpm php-cli
    apt -y install php
    apt -y install php-common php-sqlite3
    apt -y install php-mbstring php-zip php-curl
    
    printf "\ninstall lighttpd\n"
    apt -y install lighttpd
    sed -i 's%/var/www/html%/var/www%' /etc/lighttpd/lighttpd.conf
    sed -i '/server.modules += (/a \
"mod_auth", \
"mod_authn_file",' /etc/lighttpd/lighttpd.conf

    printf "\ncopy Pi-Ager from local folder to final destinations\n"
    cp -r /home/pi/Pi-Ager/opt/* /opt/
    cp -r /home/pi/Pi-Ager/var/* /var/
    usermod -G www-data -a pi
#    usermod –G gpio –a www-data
    chown -R www-data:www-data /var/www
    chmod -R 755 /var/www
    chmod +x /opt/ATC_MiThermometer/ATC_xxxxxx.py
    
    printf "\ncreate .htcredentials\n"
    user="pi-ager"
    realm="Pi-Ager"
    password="raspberry"
    digest="$( printf "%s:%s:%s" "$user" "$realm" "$password" | md5sum | awk '{print $1}' )"
        
	rm /var/.htcredentials
	echo "$user:$realm:$digest" > /var/.htcredentials
    printf "\nPasswort webgui gesetzt\n"
    
    printf "\nconfigure lighttpd\n"
    cp -rf /home/pi/Pi-Ager/etc/lighttpd/conf-available/05-auth.conf /etc/lighttpd/conf-available/
    cp -rf /home/pi/Pi-Ager/etc/lighttpd/conf-available/15-fastcgi-php-fpm.conf /etc/lighttpd/conf-available/
    lighty-enable-mod auth
    lighty-enable-mod fastcgi-php-fpm
    service lighttpd force-reload
    
    printf "\ninstall necessary modules\n"
    apt -y install python3-smbus2
    apt -y install sqlite3
    apt -y install python3-pip
    pip3 install pi-sht1x
    apt -y install libgd-dev
    apt -y install libssl-dev
    apt -y install fswebcam
    apt -y install python3-cryptography
    apt -y install uuid-runtime
    apt -y install nfs-common
    
    printf "\ninstall wiringPi and RPi.GPIO\n"
    cd /tmp
    apt -y purge wiringpi
    if [ `dpkg --print-architecture` = "arm64" ]
    then
        wget  https://github.com/WiringPi/WiringPi/releases/download/3.18/wiringpi_3.18_arm64.deb
        apt -y install ./wiringpi_3.18_arm64.deb    
    else
        wget  https://github.com/WiringPi/WiringPi/releases/download/3.18/wiringpi_3.18_armhf.deb
        apt -y install ./wiringpi_3.18_armhf.deb
    fi
    
    apt -y purge python3-RPi.GPIO
    pip3 uninstall --yes RPi.GPIO
    
    # Codename auslesen
    CODENAME=$(grep VERSION_CODENAME /etc/os-release | cut -d'=' -f2)
    if [ "$CODENAME" = "trixie" ]; then
        if [ `dpkg --print-architecture` = "arm64" ]
        then
            wget https://github.com/phylax2020/RPi.GPIO/releases/download/v0.8.8/python3-rpi.gpio_0.8.8-1_arm64.deb
            apt -y install ./python3-rpi.gpio_0.8.8-1_arm64.deb    
        else
            wget https://github.com/phylax2020/RPi.GPIO/releases/download/v0.8.8/python3-rpi.gpio_0.8.8-1_armhf.deb
            apt -y install ./python3-rpi.gpio_0.8.8-1_armhf.deb
        fi
    elif [ "$CODENAME" = "bookworm" ]; then
        if [ `dpkg --print-architecture` = "arm64" ]
        then
            wget https://github.com/phylax2020/RPi.GPIO/releases/download/v0.8.7/python3-rpi.gpio_0.8.7-1_arm64.deb
            apt -y install ./python3-rpi.gpio_0.8.7-1_arm64.deb    
        else
            wget https://github.com/phylax2020/RPi.GPIO/releases/download/v0.8.7/python3-rpi.gpio_0.8.7-1_armhf.deb
            apt -y install ./python3-rpi.gpio_0.8.7-1_armhf.deb
        fi    
    else
        echo "Unknown version: $CODENAME"
    fi
    
    printf "\ninstall additional modules\n"
    apt -y install lsof
    pip3 install nextion
    pip3 install pyserial-asyncio

    apt -y install zip unzip
    apt -y install python3-schedule
    apt -y install python3-paho-mqtt
    
#    cp -rf /home/pi/Pi-Ager/etc/rc.local /etc/
#    chmod +x /etc/rc.local
    
#    printf "\ndisable start of pi-ager service in rc.local for next reboot\n"
#    sed -i 's/systemctl/#systemctl/' /etc/rc.local
    
    cp /home/pi/Pi-Ager/usr/local/bin/* /usr/local/bin/
    chmod +x /usr/local/bin/*
    chmod +x /var/*.sh
    
    cd /home/pi
    cp /home/pi/Pi-Ager/etc/sudoers.d/99_pi-ager /etc/sudoers.d/
    chmod 440 /etc/sudoers.d/99_pi-ager

    STA_CON=$(nmcli -g GENERAL.CONNECTION dev show wlan0)

    if [ -z "$STA_CON" ] || [ "$STA_CON" = "--" ]; then
        printf "\nERROR: No active connection on wlan0"
        exit 1
    fi
    printf "\n Found STA connection: ${STA_CON} \n"
    
    nmcli con mod "$STA_CON" connection.autoconnect yes connection.autoconnect-retries 0 connection.auth-retries 5
    nmcli con mod "$STA_CON" 802-11-wireless.wake-on-wlan default
    
    printf "\nSync AP-Channel with channel of preconfigured connection\n"
    cp /home/pi/Pi-Ager/etc/NetworkManager/dispatcher.d/98-sync-ap-channel /etc/NetworkManager/dispatcher.d/
    chmod 755 /etc/NetworkManager/dispatcher.d/98-sync-ap-channel
    
    printf "\nInstall nodogsplash captive portal\n"
    apt -y install libjson-c-dev
    apt -y install iptables
    apt -y install libmicrohttpd-dev
    git clone https://github.com/nodogsplash/nodogsplash.git /home/pi/nodogsplash/
    cd /home/pi/nodogsplash
    make
    make install
    cp -rf /home/pi/Pi-Ager/etc/nodogsplash/* /etc/nodogsplash/
    chmod +x /usr/bin/nodogsplash
    chmod +x /usr/bin/ndsctl
    
    printf "\nCopy nodogsplash.service to /etc/systems/system/ \n"
    cp /home/pi/Pi-Ager/etc/systemd/system/nodogsplash.service /etc/systemd/system/
 
    printf "\nInstall bluetooth fo Xiaomi temp/hum sensor\n"
    apt -y install libglib2.0-dev
    pip3 install bluepy requests
    apt -y install bluetooth libbluetooth-dev
    
    printf "\nPatch btle.py to stop intermittend errors\n"
    sed -i '/self._helper.wait()/a \
            time.sleep(0.1)' /usr/local/lib/python3.13/dist-packages/bluepy/btle.py

    printf "\nchange some owner and rw rights\n"
    
    chmod 666 /var/www/logs/logfile.txt
    chmod 755 /var/www/logs/
    chmod 664 /var/www/config/pi-ager.sqlite3
    chown -R www-data:www-data /var/www/config/
    chmod 777 /var/www/config/
    
    printf "\ncopy pi-ager service files to /etc/systemd/system/ \n"
#    cp /home/pi/Pi-Ager/lib/systemd/system/* /etc/systemd/system/
    cp /home/pi/Pi-Ager/etc/systemd/system/pi-ager_main.service /etc/systemd/system/
    cp /home/pi/Pi-Ager/etc/systemd/system/setup_pi-ager.service /etc/systemd/system/
    
    printf "\nreload .service \n"
    systemctl daemon-reload
    
    printf "\ncopy fswebcam\n"
    cp /home/pi/Pi-Ager/usr/bin/fswebcam /usr/bin/
    chmod +x /usr/bin/fswebcam
    cp /home/pi/Pi-Ager/usr/share/man/man1/fswebcam.1.gz /usr/share/man/man1/
    
    printf "\nclosing_actions\n"

    rm -rf /home/pi/Pi-Ager
    
    echo "Edit now /boot/firmware/setup.txt file !"
    echo "After editing and saving setup.txt start script pi-ager-finalize-build.sh with sudo to activate system configuration from data in setup.txt "
    echo "rebooting now"

    sync
    reboot
    ;;
    
 4)
    printf "\nDid you edit /boot/firmware/setup.txt file ?\n"
    printf "After editing and saving setup.txt start script pi-ager-finalize-build.sh with sudo to activate system configuration from data in setup.txt \n"
 
esac
