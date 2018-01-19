---
layout: installation
title: "Standalone installation"
---
# standalone installation (for experienced users)
# Content

* [RASPBIAN LITE operating system](#operating-system-raspian-lite)
* [WiFi connection](#wifi-connection)
* [Software packages](#software-packages)
  * [lighttpd](#lighttpd)
  * [PHP 7](#php-7)
  * [pip](#pip)
  * [git](# git)
  * [sqlite3](#sqlite3)
  * [Sht sensor](#sht-sensors)
  * [DHT sensor](#dht-sensors)
  * [Wiring Pi](#wiringpi)
  * [ZIP](#zip)
  * [webcam](#webcam)
  * [fswebcam](# fswebcam)
* [Program maturity control](# program-racks control)


This guide is based on Windows. For other operating systems, you may need to choose a different approach (for example, describe the SD card.

# Operating system RASPBIAN LITE

Download [RASPBIAN LITE](https://www.raspberrypi.org/downloads/raspbian/)

We unzip this and have to write it on the SD card.

For this we download the program [Win 32 Disk Imager from Sourceforge](http://sourceforge.net/projects/win32diskimager/) and unpack it. (On a Mac, for example, another program called "Etcher" must be used instead of Win 32 Disk Imager)

In order to access the console of the Raspberry Pi later on we need another program, e.g. Putty. We can download this here: [putty](https://putty.de.softonic.com/) (In case of Mac we need the console)

Also this we unpack after the download.

Now we put at least 8GB SD card in a card reader and wait until Windows has recognized it.

Then we start the »Win32DiskImager.exe«, select the image [_Image File_] and the drive letter [_Device_] of the memory card

![Win32DiskManager GUI](https://a.fsdn.com/con/app/proj/win32diskimager/screenshots/Win32DiskImager-1.0.png/1)

and start the writing process by clicking on [_Write_] -> ATTENTION not the wrong drive!

When the process is complete and we have a keyboard, monitor and / or USB-Ethernet adapter, we can eject the memory card in Windows.


In case we do not have keyboard, monitor and / or USB-Ethernet adapter or we want to access directly via putty, two files have to be created in the root folder on the SD card:

An empty file named ssh without any file extension and

a file named wpa_supplicant.conf with the content (ESSID and PASSPHRASE should be replaced with the access data for our WLAN):

{% highlight plaintext%}
# Wpa_supplicant.conf file in the boot partition (Raspbian Stretch)
country = DE # with if US
ctrl_interface = DIR = / var / run / wpa_supplicant GROUP = netdev
update_config = 1
network = {
       ssid = "ESSID"
       psk = "PASSPHRASE"
       key_mgmt = WPA-PSK
}
{% endhighlight%}

If this is done we can also eject the memory card in Windows.

We put the memory card in the Raspberry PI.

If desired and available, we now connect a USB keyboard, an HDMI monitor (TV) and a min. 1A strong Micro USB power adapter. If the Raspberry PI has an Ethernet connection, we also connect a network cable.

Then the USB power supply is connected to the power.

The Raspberry Pi starts now and the boot process should be visible on the monitor.

If the two files were created in the root directory, we can access via Putty. See the instructions later.

Log in with (Attention! Keyboard still wrongly configured [z = y]):

{% highlight plaintext%}
Username: pi
Password: raspberry
{% endhighlight%}

Then we run the wizard

{% highlight shell%}
sudo raspi-config
{% endhighlight%}

(Attention! Keyboard still wrongly configured [- = ß])

and set the following settings (the individual points may be under different numbers):

{% highlight plaintext%}
2 Network Options (with raspberry pi zero)
    N2 Wi-fi
8 Update (if we have no Ethernet connection and no WLAN available, this point will be omitted or can be made up later)
7 Advanced Options
    A1 Expand file system
    A3 Memory Split to 8 for more RAM to run services
1 Change User Password for User pi
2 change hostname to e.g. "Rpi-Pi-Ager"
4 Internationalization Options
    Add I1 Standard Locale »de_DE.UTF-8 UTF-8« and select as default
    I2 Select the time zone »Europe / Berlin«
    I3 Confirm the "Generic 105-key (Intel) PC" keyboard and select "other" / "German" as the language, leaving all other options at their default values
    Select I4 DE as WiFi
5 interface options
    P2 SSH »enable«
    P6 Serial »disable« (So click on NO two times !!)
finish
{% endhighlight%}

After that we should be asked if we want to restart. We answer this with Yes.

If the question is not asked, we have to start the Raspberry manually.

{% highlight shell%}
sudo sync
sudo reboot
{% endhighlight%}

If there is no Ethernet connection, but a WLAN stick is available, point [WiFi connection](# wifi connection) should now be preferred.

From now it is also possible by means of PC and additional program such. [Putty](http://www.putty.org/) to access the Raspberry PI. For this we have to look in our router, which IP was assigned. By means of this we can then connect via Putty. The port is 22

Once we have logged in again, we will make an update (if no LAN cable is connected, or can (eg Rapberry PI Zero first follow the instructions [WiFi connection](# wifi connection) below!)

{% highlight shell%}
sudo apt-get update && sudo apt-get upgrade -y && sudo apt-get dist-upgrade
{% endhighlight%}

Now we activate the "root" user by assigning a password for the user:

{% highlight shell%}
sudo passwd
{% endhighlight%}

And if we want to log in as root using SSH, we need to adjust the config:

{% highlight shell%}
sudo nano / etc / ssh / sshd_config
{% endhighlight%}

Here we search for the following line:

{% highlight plaintext%}
#authentication:
#LoginGraceTime 2m
#PermitRootLogin prohibit-password
#StrictModes yes
#MaxAuthTries 6
#MaxSessions 10
{% endhighlight%}

and change them as follows

{% highlight plaintext%}
#authentication:
LoginGraceTime 2m
PermitRootLogin yes
StrictModes yes
MaxAuthTries 6
Max sessions 10
{% endhighlight%}

Now we save with "_STRG + o_", "_RETURN_" and close with "_STRG + x_"

Restart once

{% highlight shell%}
sudo sync
sudo reboot
{% endhighlight%}

[to the top](# content)

# WiFi connection (if not already set up via raspi-config)

Only plug in the USB WIFI stick if the PI is switched off or if we use an active USB HUB -> otherwise the Raspberry PI will start by itself because of the voltage dip ...

If the USB WIFI stick is plugged in and the PI is powered up, or if it is a Raspberry PI Zero W, we enter the following to see if it has been detected as a USB device:

{% highlight shell%}
lsusb
{% endhighlight%}

It should then be displayed in something like this:

{% highlight plaintext%}
Bus 001 Device 002: ID 0424: 9512 Standard Microsystems Corp.
Bus 001 Device 001: ID 1d6b: 0002 Linux Foundation 2.0 root hub
Bus 001 Device 003: ID 0424: ec00 Standard Microsystems Corp.
Bus 001 Device 004: ID 046a: 0023 Cherry GmbH CyMotion Master Linux Keyboard
Bus 001 Device 005: ID 0bda: 8176 Realtek Semiconductor Corp. RTL8188CUS 802.11n WLAN adapter (or similar)
{% endhighlight%}

Then we test if the stick was recognized as a USB WIFI stick:

{% highlight shell%}
iwconfig wlan0
{% endhighlight%}

It should look something like this:

{% highlight plaintext%}
wlan0 unassociated nickname: "<WIFI @ REALTEK>"
          Mode: Managed Frequency = 2.412 GHz Access Point: Not-Associated
          Sensitivity: 0/0
          Retry: off RTS thr: off Fragment thr: off
          Power Management: on
          Link Quality: 0 Signal level: 0 Noise level: 0
          Rx invalid: 0 Rx invalid: 0 Rx invalid: 0
          Tx excessive retries: 0 Invalid misc: 0 Missed beacon: 0
{% endhighlight%}
          
A Note on Power Management: If on, this should be set to off and then checked:

{% highlight shell%}
Sudo iw wlan0 set power_save off
iw wlan0 get power_save
{% endhighlight%}

With the following command we can list the available networks:

{% highlight shell%}
iwlist wlan0 scanning
{% endhighlight%}

To write the WLAN key higher rights are necessary (root)

{% highlight shell%}
sudo su -
{% endhighlight%}

Now we enter the following and adjust the ESSID and the PASSPHRASE for our WLAN. In order for spaces in the ESSID or password to be recognized as well, they must be masked with "(the password and the ESSID must be set in" Gänsefüßchen "eg" MY ESSID WITH SPACE "" MY PASSPHRASE WITH SPACE ")

{% highlight shell%}
wpa_passphrase "ESSID" "PASSPHRASE" >> /etc/wpa_supplicant/wpa_supplicant.conf
{% endhighlight%}

with [_STRG_] + [_D_] we return to the user PI. Now we can look at whether the WLan was also registered:

{% highlight shell%}
sudo nano /etc/wpa_supplicant/wpa_supplicant.conf
{% endhighlight%}

Result in something like this:

{% highlight plaintext%}
ctrl_interface = DIR = / var / run / wpa_supplicant GROUP = netdev
update_config = 1
network = {
        ssid = "DEIN_WLAN_NAME"
        # psk = "BlaBla-Real Key"
        psk = lksdfj09o4pokpofdgkpß0jppkspdfkpsß09i4popok
}
{% endhighlight%}

If there are configurations in the file that are not needed, we can delete them and use "_STRG + o_", "_RETURN_" and close with "_STRG + x_"

Now we activate the WLAN configuration and see if it worked:

{% highlight shell%}
sudo ifdown wlan0
sudo ifup wlan0
{% endhighlight%}

Possibly. can also help a reboot:

{% highlight shell%}
sudo reboot
{% endhighlight%}

input

{% highlight shell%}
iwconfig wlan0
{% endhighlight%}

Result in about:

{% highlight shell%}
wlan0 IEEE 802.11bgn ESSID: "PK-NEW" nickname: "<WIFI @ REALTEK>"
          Mode: Managed Frequency: 2.412GHz Access Point: DC: 9F: DB: FD: E7: A0
          Bit Rate: 150 Mbps Sensitivity: 0/0
          Retry: off RTS thr: off Fragment thr: off
          Power Management: on
          Link Quality = 83/100 Signal level = 50/100 Noise level = 0/100
          Rx invalid: 0 Rx invalid: 0 Rx invalid: 0
          Tx excessive retries: 0 Invalid misc: 0 Missed beacon: 0
{% endhighlight%}
          
Input:

{% highlight shell%}
ifconfig wlan0
{% endhighlight%}

Result in about:

{% highlight plaintext%}
wlan0 Link encap: Ethernet Hardware Address 64: 70: 02: 23: ef: 11
          inet Address: 192.168.0.52 Bcast: 192.168.200.255 Mask: 255.255.255.0
          UP BROADCAST RUNNING MULTICAST MTU: 1500 Metric: 1
          RX packet: 10 errors: 0 dropped: 17 overruns: 0 frame: 0
          TX packets: 4 errors: 0 dropped: 0 overruns: 0 carrier: 0
          Collisions: 0 Send Queue Length: 1000
          RX bytes: 2001 (1.9 KiB) TX bytes: 1036 (1.0 KiB)
{% endhighlight%}

If we see an IP from your DHCP area, we have made it and can now connect via Wi-Fi.

[to the top](# content)

# Software packages

## lighttpd
First we update the packages:

{% highlight shell%}
sudo apt-get update
sudo apt-get upgrade
{% endhighlight%}
    
Now we install the webserver lighttpd

{% highlight shell%}
sudo apt-get install lighttpd
{% endhighlight%}

The question of whether we really want that, we answer with "yes".

After installation, the service starts automatically. We can check this:

{% highlight shell%}
sudo systemctl status lighttpd
{% endhighlight%}

So that the functionality is given also with other operating versions, we must change the DocumentRoot directory for the Web server. By default, the new path is / var / www / html /, change this to / var / www / (it used to be like this). For this we edit the configuration file 000-default.conf in / etc / apache2 / sites-available /

{% highlight shell%}
sudo nano /etc/lighttpd/lighttpd.conf
{% endhighlight%}

and change the parameter

{% highlight plaintext%}
server.document-root = "/ var / www / html"
{% endhighlight%}

after

{% highlight plaintext%}
server.document-root = "/ var / www"
{% endhighlight%}

and save this with "_STRG + o_", "_RETURN_" and close with "_STRG + x_"

In order to be able to store files in the web server directory, we still have to set some rights.

{% highlight shell%}
sudo groupadd www-data
sudo usermod -G www-data -a pi
sudo chown -R www-data: www-data / var / www
sudo chmod -R 775 / var / www
{% endhighlight%}

For testing, we create an html page in the web directory:

{% highlight shell%}
sudo nano / var / www / test.html
{% endhighlight%}

with the content

{% highlight plaintext%}
<Html>
<Head> <title> Test Page </ title> </ head>
<Body>
<h1> This is a test page. </ h1>
</ Body>
</ Html>
{% endhighlight%}

save this with "_STRG + o_", "_RETURN_" and close with "_STRG + x_" and test the installation now.

Possibly. can also help a reboot:

{% highlight shell%}
    sudo reboot
{% endhighlight%}

For this we give in our browser to the IP address of our Raspberry PI followed by /test.html (http: // {IP} Adresse_des_Raspberry_Pi /test.html) and should see the website.

Now we have to set up password authentication for the settings page. For this we activate the necessary module

{% highlight shell%}
sudo lighty-enable-mod auth
{% endhighlight%}

We still have to configure this and open it in the editor

{% highlight shell%}
sudo nano /etc/lighttpd/conf-enabled/05-auth.conf
{% endhighlight%}

The following lines are added under server.modules + = ("mod_auth"):

{% highlight plaintext%}
auth.backend = "htdigest"
auth.backend.htdigest.userfile = "/var/.htcredentials"

auth.require = ("/settings.php" =>
                                   (
                                    "method" => "digest",
                                    "realm" => "Pi-Ager",
                                    "require" => "user = pi-ager"
                                   )
                                    "/admin.php" =>
                                   (
                                    "method" => "digest",
                                    "realm" => "Pi-Ager",
                                    "require" => "valid-user"
                                    )
                                    "/webcam.php" =>
                                   (
                                    "method" => "digest",
                                    "realm" => "Pi-Ager",
                                    "require" => "valid-user"
                                    )
                                  )
{% endhighlight%}

save this with "_STRG + o_", "_RETURN_" and close with "_STRG + x_"
and restart the web server

{% highlight shell%}
sudo service lighttpd force-reload
{% endhighlight%}

[to the top](# content)

## PHP 7

Now we install PHP7 support for lighttpd

{% highlight shell%}
sudo apt-get update
sudo apt-get install php7.0-common php7.0-cgi php7.0 php7.0-sqlite3
{% endhighlight%}

The question of whether we really want to answer with "Yes".

After installing PHP7, we need to enable the FastCGI module for PHP and reload the ighttpd configuration.

{% highlight shell%}
sudo lighty-enable-mod fastcgi
sudo lighty-enable-mod fastcgi-php
sudo service lighttpd force-reload
{% endhighlight%}

Now we create a test phpinfo.php in the web directory

{% highlight shell%}
sudo nano /var/www/phpinfo.php
{% endhighlight%}

Content:

{% highlight php%}
<? php phpinfo (); ?>
{% endhighlight%}

save this with "_STRG + o_", "_RETURN_" and close with "_STRG + x_" and test the installation now.

To do this we enter in our browser the IP address of our Raspberry PI followed by /test.html (http: // {IP_address_of_Raspberry_Pi} /phpinfo.php) and should see an info website about PHP.

Now let's speed up the whole system a bit by enabling caching.

{% highlight shell%}
sudo apt-get update
sudo apt-get install php7.0-apcu
{% endhighlight%}

The question of whether we really want to answer with "Yes".

Then we have to make some settings on the APC cache. To do this, open the APC configuration file

{% highlight shell%}
sudo nano /etc/php/7.0/mods-available/apcu_bc.ini
{% endhighlight%}

and make the following changes:

{% highlight lighttpd%}
extension = apc.so
apc.enabled = 1
apc.file_update_protection = 2
apc.optimization = 0
apc.shm_size = 32M
apc.include_once_override = 0
apc.shm_segments = 1
apc.gc_ttl = 7200
apc.ttl = 7200
apc.num_files_hint = 1024
apc.enable_cli = 0
{% endhighlight%}

save this with "_STRG + o_", "_RETURN_" and close with "_STRG + x_".

Furthermore, we authorize files to be downloaded via the website via x-send-file. For this we edit the fcgi-configuration:

{% highlight shell%}
sudo nano /etc/lighttpd/conf-enabled/15-fastcgi-php.conf
{% endhighlight%}

and complete at the end of the line

{% highlight lighttpd%}
"broken-scriptfilename" => "enable"
{% endhighlight%}

a "," and in a new line

{% highlight lighttpd%}
"allow-x-send-file" => "enable"
{% endhighlight%}


save this with "_STRG + o_", "_RETURN_" and close with "_STRG + x_" and test the installation now.

To apply the changes, we need to reload the configuration of the web server.

{% highlight shell%}
sudo service lighttpd force-reload
{% endhighlight%}

[to the top](# content)

## pip

This is followed by pip, with which Python modules can be installed

{% highlight shell%}
sudo apt-get install python3-pip
{% endhighlight%}

The question of whether we really want that, we answer with "yes".

[to the top](# content)

## git

We need GIT to access the repository.

{% highlight shell%}
sudo apt-get install git
{% endhighlight%}

The question of whether we really want that, we answer yes.

[to the top](# content)

## sqlite3

Now we install sqlite3 support

{% highlight shell%}
sudo apt install sqlite3
{% endhighlight%}

[to the top](# content)

## sht sensors

Support for SHT sensor

{% highlight shell%}
sudo pip3 install pi-sht1x
{% endhighlight%}

[to the top](# content)

## DHT sensors

Now we install the support for the DHT sensors

{% highlight shell%}
git clone https://github.com/bob60/DHT-sensors-python3
sudo apt-get install build-essential python3-dev
cd DHT-sensors-python3
sudo python3 setup.py install
{% endhighlight%}

and go back to the home directory

{% highlight shell%}
CD
{% endhighlight%}

[to the top](# content)

## wiringpi

Now we install Wiring Pi. This is a useful framework for switching the GPIO inputs and outputs on the Raspberry Pi.

For this we clone wiringPi

{% highlight shell%}
sudo git clone git: //git.drogon.net/wiringPi
cd wiringPi
sudo ./build
CD
{% endhighlight%}

[to the top](# content)

## ZIP

So that we e.g. Zipping logfiles, we now install ZIP support

{% highlight shell%}
sudo apt-get install zip
{% endhighlight%}

[to the top](# content)

## webcam

Now we install mjpegstreamer to stream the webcam image.

{% highlight shell%}
sudo apt-get install subversion libjpeg8-dev imagemagick -y
sudo svn co https://svn.code.sf.net/p/mjpg-streamer/code/mjpg-streamer/mjpg-streamer
cd mjpg streamer
sudo make
{% endhighlight%}

move the program to / opt

{% highlight shell%}
CD
sudo mv mjpg streamer / / opt /
{% endhighlight%}

create a startup script
{% highlight shell%}
sudo nano /opt/mjpg-streamer/webcam.sh
{% endhighlight%}

with the content (without line break)

{% highlight plaintext%}
#! / Bin / bash
/ opt / mjpg-streamer / mjpg_streamer -i "/opt/mjpg-streamer/input_uvc.so -d / dev / video0 -y -n -f 2" -o "/opt/mjpg-streamer/output_http.so -n -w / opt / mjpg-streamer / www "&
{% endhighlight%}

save this with "_STRG + o_", "_RETURN_" and close with "_STRG + x_"

Then we allow the execution of the script

{% highlight shell%}
sudo chmod + x /opt/mjpg-streamer/webcam.sh
{% endhighlight%}

[to the top](# content)

## fswebcam

To occasionally save a picture from the webcam we install fswebcam

{% highlight shell%}
sudo apt-get install fswebcam
{% endhighlight%}

The question of whether we really want that, we answer with "yes".

This completes our installation preparations and allows us to focus on the maturity cabinet control

[to the top](# content)

# Program maturity cabinet control

Now create a folder (pi-ager) in the directory / opt:

{% highlight shell%}
sudo mkdir / opt / pi-ager
{% endhighlight%}

We now download the files provided in the GitHub from the branch master and unzip them.

[Download](https://github.com/Tronje-the-Falconer/Pi-Ager/archive/master.zip)

On our PC we install an FTP software (for example [FileZilla](https://filezilla-project.org/)), with which we establish a connection to our Raspberry Pi.

We connect with sftp: // IP-DES_RASPBERRY-PI and the user root with the associated password

and then copy the files from the download directory RSS into the directory of the same name on the Raspberry Pi under / opt / pi-ager.

then we copy from the download directory var the files sudowebscript.sh and .htcredentials into the directory of the same name on the Raspberry Pi under / var.

We start now putty and log in with the user pi and the assigned password for pi.

About putty we have to enter this shell script in / etc / sudoers so that the www-data user (user of the website) can do this. Since I find nano easier to edit, we set this first as the default editor

{% highlight shell%}
export EDITOR = nano
{% endhighlight%}

then open etc / sudoers with

{% highlight shell%}
EDITOR = nano sudo -E visudo
{% endhighlight%}
    
and then follow up in sudoers following

{% highlight plaintext%}
...
#User privilege specification
root ALL = (ALL: ALL) ALL
...
{% endhighlight%}

on:

{% highlight plaintext%}
www-data ALL = NOPASSWD: /var/sudowebscript.sh
{% endhighlight%}

Save with _STRG + O_ and finish with _STRG + X_

Next, let's take care of the autostart of the cabinet. This is used e.g. allows the cabinet to restart automatically after a power failure. For this we create a file:

{% highlight shell%}
sudo nano /etc/init.d/pi-ager-main.sh
{% endhighlight%}

Note: The following content can be copied here and then inserted into putty.

{% highlight plaintext%}
#! / Bin / sh
### BEGIN INIT INFO
# Provides: pi-ager-main.sh
# Required-Start: $ syslog
# Required-Stop: $ syslog
# Default start: 2 3 4 5
# Default-Stop: 0 1 6
# Short-Description: pi-ager main.py
# Description:
### END INIT INFO

case "$ 1" in
    begin)
        echo "pi-ager main.py will start"
        # Start program
        / usr / bin / python3 /opt/pi-ager/main.py> / dev / null 2> / dev / null &
        echo "start process completed"
        ;;
    Stop)
        echo "pi-ager main.py is closing"
        # Exit program
        pkill -f main.py
        ;;
    *)
        echo "Uses: /etc/init.d/pi-ager-main.sh {start | stop}"
        exit 1
        ;;
esac

exit 0
{% endhighlight%}

save this with "_STRG + o_", "_RETURN_" and close with "_STRG + x_" and then give the file the right to be executable

{% highlight shell%}
sudo chmod 755 /etc/init.d/pi-ager-main.sh
{% endhighlight%}

We test the whole function by entering the following command on the console:

{% highlight shell%}
sudo /etc/init.d/pi-ager-main.sh start
{% endhighlight%}

The following issue should appear:

{% highlight plaintext%}
pi-ager main.py is started
startup process completed
{% endhighlight%}

after that we land again in the so-called prompt and can issue another command, which stops the service again:

{% highlight shell%}
sudo /etc/init.d/pi-ager-main.sh stop
{% endhighlight%}

If all this was successful, enter this file as a startup routine

{% highlight shell%}
sudo update-rc.d pi-ager-main.sh defaults
{% endhighlight%}

Now we need a .htcredentials file for the settings page that contains our user and password.

For this we use the online tool [https://websistent.com/tools/htdigest-generator-tool/](https://websistent.com/tools/htdigest-generator-tool/)

{% highlight plaintext%}
Username: pi-ager
REALM: Pi-Ager
Password: your password
{% endhighlight%}

Attention! Case sensitive!

We open the file with

{% highlight shell%}
sudo nano / var /.htcredentials
{% endhighlight%}

and paste the generated string into the file (preferably by copying / pasting).

save this with "_STRG + o_", "_RETURN_" and close with "_STRG + x_"

The files from the download directory www we copy to / var / www /

Now we need to allocate a few write permissions via Putty or FileZilla on certain files:

Here are the commands for Putty:

{% highlight shell%}
sudo chmod 666 /var/www/logs/logfile.txt
sudo chmod 775 / var / www / logs /
sudo chmod 664 /var/www/config/pi-ager.sqlite3
sudo chown -R www-data: www-data / var / www / config /
sudo chmod 555 /var/sudowebscript.sh
sudo chmod 777 / var / www / csv /
{% endhighlight%}

The user 'pi' is a member of the group 'gpio' by default and therefore has access to the virtual files / sys / class / gpio / ... The webserver runs as a user 'www-data' and is not a member of this special Group. To change that, you have to add the 'www-data' user to the group 'gpio' and restart the web server:

{% highlight shell%}
sudo usermod -G gpio -a www-data
sudo service lighttpd force-reload
{% endhighlight%}

Now we drive the Raspberry Pi via

{% highlight shell%}
sudo stop
{% endhighlight%}

down and unplug the power supply.

Now the sensor is connected to the Raspberry Pi. See the [Construction and Connection Manual](https://github.com/Tronje-the-Falconer/Pi-Ager/wiki/3.i-Building-and-connection- of the Humidity and Temperature Sensor)

Once this is done, we can restart the Raspberry by reconnecting the power plug.

If we now call the webiste http: //IPADRESSE/index.php everything should work fine.

[to the top](# content)
