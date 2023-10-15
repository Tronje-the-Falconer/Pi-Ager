----
-- phpLiteAdmin database dump (https://www.phpliteadmin.org/)
-- phpLiteAdmin version: 1.9.8.2
-- Exported: 8:47pm on August 27, 2023 (CEST)
-- database file: ./config/pi-ager.sqlite3
----
BEGIN TRANSACTION;

----
-- Drop table for atc_data
----
DROP TABLE IF EXISTS "atc_data";

----
-- Table structure for atc_data
----
CREATE TABLE 'atc_data' ('id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 'temperature' REAL, 'humidity' REAL, 'battvolt' REAL, 'battpercent' REAL, 'last_change' INTEGER);

----
-- Data dump for atc_data, a total of 1 rows
----
INSERT INTO "atc_data" ("id","temperature","humidity","battvolt","battpercent","last_change") VALUES ('1','25.66','55.9','2.883','75.0','1693162020');
COMMIT;
