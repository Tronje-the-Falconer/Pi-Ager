----
-- phpLiteAdmin database dump (https://www.phpliteadmin.org/)
-- phpLiteAdmin version: 1.9.8.2
-- Exported: 11:17am on January 17, 2021 (CET)
-- database file: ./config/pi-ager.sqlite3
----
BEGIN TRANSACTION;

----
-- Drop table for current_values
----
DROP TABLE IF EXISTS "current_values";

----
-- Table structure for current_values
----
CREATE TABLE "current_values" ('id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 'key' TEXT NOT NULL, 'value' REAL, 'last_change' INTEGER NOT NULL);

----
-- Data dump for current_values, a total of 33 rows
----
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('1','sensor_temperature','5.68','1610878647');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('2','sensor_humidity','71.36','1610878647');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('3','status_circulating_air','1.0','1610878647');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('4','status_cooling_compressor','0.0','1610878647');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('5','status_exhaust_air','0.0','1610878647');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('6','status_heater','1.0','1610878647');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('7','status_light','0.0','1610878647');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('8','status_uv','0.0','1610878647');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('9','status_humidifier','1.0','1610878647');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('10','status_dehumidifier','0.0','1610878647');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('11','scale1','2.06202380952379','1610878628');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('12','scale2','0.215757575757578','1610878644');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('13','status_piager','1.0','1610815992');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('14','status_agingtable','0.0','1610874689');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('15','status_scale1','1.0','1610818239');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('16','status_scale2','1.0','1610818239');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('17','status_tara_scale1','0.0','1610819616');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('18','status_tara_scale2','0.0','1610819641');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('19','agingtable_period','0.0','1610874689');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('21','status_light_manual','0.0','1608641257');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('22','calibrate_scale1','0.0','1610818076');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('23','calibrate_scale2','0.0','1610818222');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('24','calibrate_weight','0.0','1610818222');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('25','status_uv_manual','0.0','1608641257');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('26','temperature_meat1','7.066','1610878647');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('27','temperature_meat2','6.577','1610878647');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('28','temperature_meat3',NULL,'1610878647');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('29','temperature_meat4','0.043','1610878647');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('30','sensor_dewpoint','1.4','1610724567');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('31','sensor_extern_temperature','8.4','1610724567');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('32','sensor_extern_humidity','55.0','1610724567');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('33','sensor_extern_dewpoint','0.4','1610724567');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('34','agingtable_period_day','1.0','1610724567');
COMMIT;
