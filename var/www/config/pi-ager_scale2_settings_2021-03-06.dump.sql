----
-- phpLiteAdmin database dump (https://www.phpliteadmin.org/)
-- phpLiteAdmin version: 1.9.8.2
-- Exported: 6:58pm on March 6, 2021 (CET)
-- database file: ./config/pi-ager.sqlite3
----
BEGIN TRANSACTION;

----
-- Drop table for scale2_settings
----
DROP TABLE IF EXISTS "scale2_settings";

----
-- Table structure for scale2_settings
----
CREATE TABLE 'scale2_settings' ('id' INTEGER DEFAULT 0 PRIMARY KEY AUTOINCREMENT NOT NULL, 'key' TEXT DEFAULT 0 NOT NULL, 'value' REAL DEFAULT 0 NOT NULL, 'last_change' INTEGER DEFAULT 0 NOT NULL);

----
-- Data dump for scale2_settings, a total of 10 rows
----
INSERT INTO "scale2_settings" ("id","key","value","last_change") VALUES ('1','samples','20.0','0');
INSERT INTO "scale2_settings" ("id","key","value","last_change") VALUES ('2','spikes','0.0','0');
INSERT INTO "scale2_settings" ("id","key","value","last_change") VALUES ('3','sleep','0.1','0');
INSERT INTO "scale2_settings" ("id","key","value","last_change") VALUES ('4','gain','128.0','0');
INSERT INTO "scale2_settings" ("id","key","value","last_change") VALUES ('5','bits_to_read','24.0','0');
INSERT INTO "scale2_settings" ("id","key","value","last_change") VALUES ('6','referenceunit','1.0','1613243596');
INSERT INTO "scale2_settings" ("id","key","value","last_change") VALUES ('7','measuring_interval','2.0','0');
INSERT INTO "scale2_settings" ("id","key","value","last_change") VALUES ('8','measuring_duration','15.0','0');
INSERT INTO "scale2_settings" ("id","key","value","last_change") VALUES ('9','saving_period','120.0','0');
INSERT INTO "scale2_settings" ("id","key","value","last_change") VALUES ('10','offset','0.0','1613243410');
COMMIT;
