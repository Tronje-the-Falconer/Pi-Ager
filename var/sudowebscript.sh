#!/bin/bash
#
# sudo web script allowing user www-data to run commands with root privilegs

case "$1" in
    startrss)
        python /opt/RSS1.0/Rss.py > /dev/null 2>/dev/null &
    ;;
    stoprss)
        pkill -f Rss.py
    ;;
    startsalami)
        python /opt/RSS1.0/salami.py > /dev/null 2>/dev/null &
    ;;
    stopsalami)
        pkill -f salami.py
    ;;
    r22)
        /usr/local/bin/gpio -g read 22
    ;;
    r27)
        /usr/local/bin/gpio -g read 27
    ;;
    r18)
        /usr/local/bin/gpio -g read 18
    ;;
    r23)
        /usr/local/bin/gpio -g read 23
    ;;
    r24)
        /usr/local/bin/gpio -g read 24
    ;;
    grsalami)
        ps ax | grep -v grep | grep salami.py
    ;;
    grrss)
        ps ax | grep -v grep | grep Rss.py
    ;;
    reb)
        reboot
    ;;
    shut)
        shutdown -h now
    ;;
    *) echo "ERROR: invalid parameter: $1 (for $0)"; exit 1
    ;;
esac

exit 0
