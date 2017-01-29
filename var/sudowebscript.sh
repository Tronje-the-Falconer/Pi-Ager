#!/bin/bash
#
# sudo web script allowing user www-data to run commands with root privilegs

case "$1" in
    startrss) #Starten von Rss.py
        python /opt/RSS/Rss.py > /dev/null 2>/dev/null &
    ;;
    stoprss) #Stoppen von Rss.py
        pkill -f Rss.py
    ;;
    grrss) #Überprüfen von Rss.py
        ps ax | grep -v grep | grep Rss.py
    ;;
    startreifetab) #Starten von Reifetab.py
        sudo python /home/pi/RSS/Reifetab.py > /dev/null 2>/dev/null &
    ;;
    stopstopreifetab) #Stoppen von Reifetab.py
        pkill -f Reifetab.py
    ;;
    grreifetab) #Überprüfen von Reifetab.py
        ps ax | grep -v grep | grep Reifetab.py
    ;;
    r22) #Ansteuern von GPIO22 Kühlschrankkompressor
        /usr/local/bin/gpio -g read 22
    ;;
    r27)#Ansteuern von GPIO27 Heizkabel
        /usr/local/bin/gpio -g read 27
    ;;
    r18)#Ansteuern von GPIO18 Umluftventilator
        /usr/local/bin/gpio -g read 18
    ;;
    r23)#Ansteuern von GPIO23 Austauschlüfter
        /usr/local/bin/gpio -g read 23
    ;;
    r24)#Ansteuern von GPIO24 Luftbefeuchter
        /usr/local/bin/gpio -g read 24
    ;;
    reb) # reboot
        reboot
    ;;
    shut) #Shutdown 
        shutdown -h now
    ;;
    *) echo "ERROR: invalid parameter: $1 (for $0)"; exit 1 #Fehlerbehandlung
    ;;
esac

exit 0
