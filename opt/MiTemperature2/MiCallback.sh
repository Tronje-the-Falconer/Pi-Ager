#!/bin/bash
sqlite3 /var/www/config/pi-ager.sqlite3 <<END_SQL
.timeout 2000
PRAGMA journalMode = wal;
UPDATE atc_mi_thermometer_data SET mi_data="$@" WHERE id="1";
END_SQL
exit 0
