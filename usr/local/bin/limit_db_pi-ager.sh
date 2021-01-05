#!/bin/bash

mkdir -p /var/log/pi-ager
exec &>> /var/log/pi-ager/db-cleanup.log

# File: limit_db_pi-ager.sh
# Put this script into /usr/local/bin
# Then setup crontab to execute this script e.g. weekly or monthly
# Define the time window for the data that should remain in the 
# database starting from (EPOCHSECONDS - range_seconds).
# last_change in the database holds the time of adding data in 
# seconds since 1970
#
# Month seconds = 2629700
# Week seconds = 604800
# Day seconds = 86400
# Hour seconds = 3600
# Half hour = 1800

range_seconds=2629700
# echo "range_seconds: $range_seconds"

seconds_now=$EPOCHSECONDS
# echo "seconds_now: $seconds_now"

limit=$((seconds_now - range_seconds))
# echo $limit

sqlite3 /var/www/config/pi-ager.sqlite3 "PRAGMA journalMode = wal; DELETE FROM sensor_temperature_data WHERE last_change < $limit;"
sqlite3 /var/www/config/pi-ager.sqlite3 "PRAGMA journalMode = wal; DELETE FROM sensor_humidity_data WHERE last_change < $limit;"
sqlite3 /var/www/config/pi-ager.sqlite3 "PRAGMA journalMode = wal; DELETE FROM scale1_data WHERE last_change < $limit;"
sqlite3 /var/www/config/pi-ager.sqlite3 "PRAGMA journalMode = wal; DELETE FROM scale2_data WHERE last_change < $limit;"
sqlite3 /var/www/config/pi-ager.sqlite3 "PRAGMA journalMode = wal; DELETE FROM sensor_temperature_meat1_data WHERE last_change < $limit;"
sqlite3 /var/www/config/pi-ager.sqlite3 "PRAGMA journalMode = wal; DELETE FROM sensor_temperature_meat2_data WHERE last_change < $limit;"
sqlite3 /var/www/config/pi-ager.sqlite3 "PRAGMA journalMode = wal; DELETE FROM sensor_temperature_meat3_data WHERE last_change < $limit;"
sqlite3 /var/www/config/pi-ager.sqlite3 "PRAGMA journalMode = wal; DELETE FROM sensor_temperature_meat4_data WHERE last_change < $limit;"

echo $(date '+%d-%m-%Y %H:%M:%S') "Pi-Ager database shrinked"
