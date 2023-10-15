----
-- phpLiteAdmin database dump (https://www.phpliteadmin.org/)
-- phpLiteAdmin version: 1.9.8.2
-- Exported: 8:46pm on August 27, 2023 (CEST)
-- database file: ./config/pi-ager.sqlite3
----
BEGIN TRANSACTION;

----
-- Drop table for atc_device_name
----
DROP TABLE IF EXISTS "atc_device_name";

----
-- Table structure for atc_device_name
----
CREATE TABLE 'atc_device_name' (id INTEGER PRIMARY KEY,'name' TEXT DEFAULT '');

----
-- Data dump for atc_device_name, a total of 1 rows
----
INSERT INTO "atc_device_name" ("id","name") VALUES ('1','ATC_C4C134');
COMMIT;
