----
-- phpLiteAdmin database dump (https://www.phpliteadmin.org/)
-- phpLiteAdmin version: 1.9.8.2
-- Exported: 2:51pm on January 6, 2021 (CET)
-- database file: ./config/pi-ager.sqlite3
----
BEGIN TRANSACTION;

----
-- Drop table for config_messenger_exception
----
DROP TABLE IF EXISTS "config_messenger_exception";
DROP TABLE IF EXISTS "config_messenger";
DROP TABLE IF EXISTS "config_messenger_event";

----
-- Table structure for config_messenger_exception
----
CREATE TABLE "config_messenger_exception" (
    "exception" TEXT NOT NULL,
    "e-mail" INTEGER,
    "pushover" INTEGER,
    "telegram" INTEGER,
    "alarm" TEXT,
    "raise_exception" INTEGER
, "active" INTEGER);

----
-- Data dump for config_messenger_exception, a total of 5 rows
----
INSERT INTO "config_messenger_exception" ("exception","e-mail","pushover","telegram","alarm","raise_exception","active") VALUES ('cx_Sensor_not_defined','1','1','1','short','0','1');
INSERT INTO "config_messenger_exception" ("exception","e-mail","pushover","telegram","alarm","raise_exception","active") VALUES ('OperationalError','1','1','1','short','1','1');
INSERT INTO "config_messenger_exception" ("exception","e-mail","pushover","telegram","alarm","raise_exception","active") VALUES ('FileNotFoundError','1','1','1','short','1','1');
INSERT INTO "config_messenger_exception" ("exception","e-mail","pushover","telegram","alarm","raise_exception","active") VALUES ('OSError','1','0','0','short','0','1');
INSERT INTO "config_messenger_exception" ("exception","e-mail","pushover","telegram","alarm","raise_exception","active") VALUES ('cx_measurement_error','1','0','0','short','0','1');

----
-- Table structure for config_messenger_event
----
CREATE TABLE "config_messenger_event" (
    "event" TEXT NOT NULL,
    "e-mail" INTEGER,
    "pushover" INTEGER,
    "telegram" INTEGER,
    "alarm" TEXT,
    "event_text" TEXT,
    "active" INTEGER);

----
-- Data dump for config_messenger_event, a total of 1 rows
----
INSERT INTO "config_messenger_event" ("event","e-mail","pushover","telegram","alarm","event_text","active") VALUES ('Pi-Ager_started','1','1','1','short','Pi-Ager wurde gestarted','1');

COMMIT;
