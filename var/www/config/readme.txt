Database can be changed with sqliteman
The you can export the database schema to an sql file 
This command changes the database according to the exported schema

Copying the database is prevented during git transfer, otherwise all data will be overwritten. For this reason, you change the database tables and then maintain the data locally on the Pi-Ager.
 
sqlite3 pi-ager.sqlite3 < 2019-12-07_sqlite_changes.sql
