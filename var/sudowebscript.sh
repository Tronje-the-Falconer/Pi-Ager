#!/bin/bash
#
# sudo web script allowing user www-data to run commands with root privilegs
# shell_exec('sudo /var/sudowebscript.sh PARAMETER')

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
    read18)#Ansteuern von GPIO18 Umluftventilator
        /usr/local/bin/gpio -g read 27
    ;;
    read22) #Ansteuern von GPIO22 Kühlschrankkompressor
        /usr/local/bin/gpio -g read 24
    ;;
    read23)#Ansteuern von GPIO23 Austauschlüfter
        /usr/local/bin/gpio -g read 17
    ;;
    read24)#Ansteuern von GPIO24 Luftbefeuchter
        /usr/local/bin/gpio -g read 23
    ;;
    read27)#Ansteuern von GPIO27 Heizkabel
        /usr/local/bin/gpio -g read 22
    ;;
    write18)#Ansteuern von GPIO18 Umluftventilator
        /usr/local/bin/gpio -g write 27 1
    ;;
    write22) #Ansteuern von GPIO22 Kühlschrankkompressor
        /usr/local/bin/gpio -g write 24 1
    ;;
    write23)#Ansteuern von GPIO23 Austauschlüfter
        /usr/local/bin/gpio -g write 17 1
    ;;
    write24)#Ansteuern von GPIO24 Luftbefeuchter
        /usr/local/bin/gpio -g write 23 1
    ;;
    write27)#Ansteuern von GPIO27 Heizkabel
        /usr/local/bin/gpio -g write 22 1
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
