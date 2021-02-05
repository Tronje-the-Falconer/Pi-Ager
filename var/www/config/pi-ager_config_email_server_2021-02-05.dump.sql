----
-- phpLiteAdmin database dump (https://www.phpliteadmin.org/)
-- phpLiteAdmin version: 1.9.8.2
-- Exported: 6:41pm on February 5, 2021 (CET)
-- database file: ./config/pi-ager.sqlite3
----
BEGIN TRANSACTION;

----
-- Drop table for config_email_server
----
DROP TABLE IF EXISTS "config_email_server";

----
-- Table structure for config_email_server
----
CREATE TABLE "config_email_server" ('id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 'server' TEXT NOT NULL, 'user' TEXT NOT NULL, 'password' TEXT NOT NULL, 'port' INTEGER NOT NULL);

----
-- Data dump for config_email_server, a total of 1 rows
----
INSERT INTO "config_email_server" ("id","server","user","password","port") VALUES ('1','smtp.yourEmaiProvider.de','max.mustermann@yourEmailProvider.de','yourEmailPassword','465');
COMMIT;
