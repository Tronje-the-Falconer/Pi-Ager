#!/bin/bash
#
# sudo web script allowing user www-data to run commands with root privilegs
# shell_exec('sudo /var/sudowebscript.sh PARAMETER')

case "$1" in
    startrss) #Starten von Rss.py
        python /opt/RSS/Rss.py > /dev/null 2>/dev/null &
    ;;
    pkillrss) #Stoppen von Rss.py
        pkill -f Rss.py
    ;;
    greprss) #Überprüfen von Rss.py
        ps ax | grep -v grep | grep Rss.py
    ;;
    startreifetab) #Starten von Reifetab.py
        python /opt/RSS/Reifetab.py > /dev/null 2>/dev/null &
    ;;
    pkillreifetab) #Stoppen von Reifetab.py
        pkill -f Reifetab.py
    ;;
    grepreifetab) #Überprüfen von Reifetab.py
        ps ax | grep -v grep | grep Reifetab.py
    ;;
    read18)#Ansteuern von GPIO18 Umluftventilator
        /usr/local/bin/gpio -g read 18
    ;;
    read22) #Ansteuern von GPIO22 Kühlschrankkompressor
        /usr/local/bin/gpio -g read 22
    ;;
    read23)#Ansteuern von GPIO23 Austauschlüfter
        /usr/local/bin/gpio -g read 23
    ;;
    read24)#Ansteuern von GPIO24 Luftbefeuchter
        /usr/local/bin/gpio -g read 24
    ;;
    read27)#Ansteuern von GPIO27 Heizkabel
        /usr/local/bin/gpio -g read 27
    ;;
    write18)#Ansteuern von GPIO18 Umluftventilator
        /usr/local/bin/gpio -g write 18 1
    ;;
    write22) #Ansteuern von GPIO22 Kühlschrankkompressor
        /usr/local/bin/gpio -g write 22 1
    ;;
    write23)#Ansteuern von GPIO23 Austauschlüfter
        /usr/local/bin/gpio -g write 23 1
    ;;
    write24)#Ansteuern von GPIO24 Luftbefeuchter
        /usr/local/bin/gpio -g write 24 1
    ;;
    write27)#Ansteuern von GPIO27 Heizkabel
        /usr/local/bin/gpio -g write 27 1
    ;;
    reboot) # reboot
        reboot
    ;;
    shutdown) #Shutdown 
        shutdown -h now
    ;;
    getpirevision) # auslesen der Revision vom pi um auf Model zu kommen
        cat /proc/cpuinfo | grep 'Revision' | awk '{print $3}' | sed 's/^1000//''
    ;;
    *) echo "ERROR: invalid parameter: $1 (for $0)"; exit 1 #Fehlerbehandlung
    ;;
esac

exit 0
