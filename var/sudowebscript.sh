#!/bin/bash
#
# sudo web script allowing user www-data to run commands with root privilegs
# shell_exec('sudo /var/sudowebscript.sh PARAMETER')
jq '.employees[0].name'

# gpio_cooling_compressor = cat /var/www/config.json | jq '.gpio_cooling_compressor'
# gpio_heater = cat /var/www/config.json | jq '.gpio_heater'
# gpio_humidifier = cat /var/www/config.json | jq '.gpio_humidifier'
# gpio_circulating_air = cat /var/www/config.json | jq '.gpio_circulating_air'
# gpio_exhausting_air = cat /var/www/config.json | jq '.gpio_exhausting_air'
# gpio_uv_light = cat /var/www/config.json | jq '.gpio_uv_light'
# gpio_reserved1 = cat /var/www/config.json | jq '.gpio_reserved1'
# gpio_reserved2 = cat /var/www/config.json | jq '.gpio_reserved2'

gpio_cooling_compressor = 23
gpio_heater = 22
gpio_humidifier = 27
gpio_circulating_air = 18
gpio_exhausting_air = 17
gpio_uv_light = 4
gpio_reserved1 = 3
gpio_reserved2 = 2

case "$1" in
    startmain) #Starten von main.py
        python /opt/pi-ager/main.py > /dev/null 2>/dev/null &
    ;;
    pkillmain) #Stoppen von Rss.py
        pkill -f main.py
    ;;
    grepmain) #Überprüfen von Rss.py | ps ax gibt Prozessliste zurück, wird nach grep übergeben und Versionsnummer von Grep wird hinzugefügt, wird dann nach grep nochmals übergeben und nach RSS.py gesucht
        ps ax | grep -v grep | grep main.py
    ;;
    starthangingtable) #Starten von hangingtable.py
        python /opt/pi-ager/hangingtable.py > /dev/null 2>/dev/null &
    ;;
    pkillhangingtable) #Stoppen von hangingtable.py
        pkill -f hangingtable.py
    ;;
    grephangingtable) #Überprüfen von hangingtable.py  | ps ax gibt Prozessliste zurück, wird nach grep übergeben und Versionsnummer von Grep wird hinzugefügt, wird dann nach grep nochmals übergeben und nach Reifetab.py gesucht
        ps ax | grep -v grep | grep hangingtable.py
    ;;
    read_gpio_cooling_compressor) # Ansteuern von GPIO Kühlschrankkompressor
        /usr/local/bin/gpio -g read $gpio_cooling_compressor
    ;;
    write_gpio_cooling_compressor) # Ansteuern von GPIO Kühlschrankkompressor
        /usr/local/bin/gpio -g write $gpio_cooling_compressor 1
    ;;
    read_gpio_heater)# Ansteuern von GPIO Heizkabel
        /usr/local/bin/gpio -g read $gpio_heater
    ;;
    write_gpio_heater)# Ansteuern von GPIO Heizkabel
        /usr/local/bin/gpio -g write $gpio_heater 1
    ;;
    read_gpio_humidifier)# Ansteuern von GPIO Luftbefeuchter
        /usr/local/bin/gpio -g read $gpio_humidifier
    ;;
    write_gpio_humidifier)# Ansteuern von GPIO Luftbefeuchter
        /usr/local/bin/gpio -g write $gpio_humidifier 1
    ;;
    read_gpio_circulating_air)# Ansteuern von GPIO Umluftventilator
        /usr/local/bin/gpio -g read $gpio_circulating_air
    ;;
    write_gpio_circulating_air)# Ansteuern von GPIO Umluftventilator
        /usr/local/bin/gpio -g write $gpio_circulating_air 1
    ;;
    read_gpio_exhausting_air)# Ansteuern von GPIO Austauschlüfter
        /usr/local/bin/gpio -g read $gpio_exhausting_air
    ;;
    write_gpio_exhausting_air)# Ansteuern von GPIO Austauschlüfter
        /usr/local/bin/gpio -g write $gpio_exhausting_air 1
    ;;
    read_gpio_uv_light)# Ansteuern von GPIO UV-Licht
        /usr/local/bin/gpio -g read $gpio_uv_light
    ;;
    write_gpio_uv_light)# Ansteuern von GPIO UV-Licht
        /usr/local/bin/gpio -g write $gpio_uv_light 1
    ;;
    read_gpio_reserved1)# Ansteuern von GPIO reserved1
        /usr/local/bin/gpio -g read $gpio_reserved1
    ;;
    write_gpio_reserved1)# Ansteuern von GPIO reserved1
        /usr/local/bin/gpio -g write $gpio_reserved1 1
    ;;
    read_gpio_reserved2)# Ansteuern von GPIO reserved2
        /usr/local/bin/gpio -g read $gpio_reserved2
    ;;
    write_gpio_reserved2)# Ansteuern von GPIO reserved1
        /usr/local/bin/gpio -g write $gpio_reserved2 1
    ;;
    reboot) # reboot
        reboot
    ;;
    shutdown) #Shutdown 
        shutdown -h now
    ;;
    getpirevision) # auslesen der Revision vom pi um auf Model zu kommen
        cat /proc/cpuinfo | grep 'Revision' | awk '{print $3}' | sed 's/^1000//'
    ;;
    *) echo "ERROR: invalid parameter: $1 (for $0)"; exit 1 #Fehlerbehandlung
    ;;
esac

exit 0
