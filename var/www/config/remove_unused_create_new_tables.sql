----
-- phpLiteAdmin database dump (https://www.phpliteadmin.org/)
-- phpLiteAdmin version: 1.9.8.2
-- Exported: 9:13pm on March 7, 2021 (CET)
-- database file: ./config/pi-ager.sqlite3
----
BEGIN TRANSACTION;

----
-- Drop index for humidity
----
DROP INDEX IF EXISTS "humidity";
----
-- Drop index for temperature
----
DROP INDEX IF EXISTS "temperature";
----
-- Drop index for ntc1
----
DROP INDEX IF EXISTS "ntc1";
----
-- Drop index for ntc2
----
DROP INDEX IF EXISTS "ntc2";
----
-- Drop index for ntc3
----
DROP INDEX IF EXISTS "ntc3";
----
-- Drop index for ntc4
----
DROP INDEX IF EXISTS "ntc4";
----
-- Drop index for scale1
----
DROP INDEX IF EXISTS "scale1";
----
-- Drop index for scale2
----
DROP INDEX IF EXISTS "scale2";
----
-- Drop index for temperature_extern
----
DROP INDEX IF EXISTS "temperature_extern";
----
-- Drop index for dewpoint
----
DROP INDEX IF EXISTS "dewpoint";
----
-- Drop index for dewpoint_extern
----
DROP INDEX IF EXISTS "dewpoint_extern";
----
-- Drop index for humidity_extern
----
DROP INDEX IF EXISTS "humidity_extern";
----
-- Drop index for all_sensors_index
----
DROP INDEX IF EXISTS "all_sensors_index";
----
-- Drop index for all_scales_index
----
DROP INDEX IF EXISTS "all_scales_index";


----
-- Drop table for scale1_data
----
DROP TABLE IF EXISTS "scale1_data";
----
-- Drop table for scale2_data
----
DROP TABLE IF EXISTS "scale2_data";
----
-- Drop table for sensor_temperature_meat1_data
----
DROP TABLE IF EXISTS "sensor_temperature_meat1_data";
----
-- Drop table for sensor_temperature_meat2_data
----
DROP TABLE IF EXISTS "sensor_temperature_meat2_data";
----
-- Drop table for sensor_temperature_meat3_data
----
DROP TABLE IF EXISTS "sensor_temperature_meat3_data";
----
-- Drop table for sensor_temperature_meat4_data
----
DROP TABLE IF EXISTS "sensor_temperature_meat4_data";
----
-- Drop table for sensor_temperature_data
----
DROP TABLE IF EXISTS "sensor_temperature_data";
----
-- Drop table for sensor_humidity_data
----
DROP TABLE IF EXISTS "sensor_humidity_data";
----
-- Drop table for sensor_dewpoint_data
----
DROP TABLE IF EXISTS "sensor_dewpoint_data";
----
-- Drop table for sensor_extern_temperature_data
----
DROP TABLE IF EXISTS "sensor_extern_temperature_data";
----
-- Drop table for sensor_extern_dewpoint_data
----
DROP TABLE IF EXISTS "sensor_extern_dewpoint_data";
----
-- Drop table for sensor_extern_humidity_data
----
DROP TABLE IF EXISTS "sensor_extern_humidity_data";

----
-- Drop table for all_sensors
----
DROP TABLE IF EXISTS "all_sensors";
----
-- Table structure for all_sensors
----
CREATE TABLE 'all_sensors' ('id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 'tempint' REAL DEFAULT NULL, 'tempext' REAL DEFAULT NULL, 'humint' REAL DEFAULT NULL, 'humext' REAL DEFAULT NULL, 'dewint' REAL DEFAULT NULL, 'dewext' REAL DEFAULT NULL, 'humintabs' REAL DEFAULT NULL, 'humextabs' REAL DEFAULT NULL, 'ntc1' REAL DEFAULT NULL, 'ntc2' REAL DEFAULT NULL, 'ntc3' REAL DEFAULT NULL, 'ntc4' REAL DEFAULT NULL, 'last_change' INTEGER NOT NULL);
----
-- structure for index all_sensors_index on table all_sensors
----
CREATE INDEX 'all_sensors_index' ON "all_sensors" ("last_change" ASC);


----
-- Drop table for all_scales
----
DROP TABLE IF EXISTS "all_scales";
----
-- Table structure for all_scales
----
CREATE TABLE 'all_scales' ('id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 'scale1' REAL DEFAULT NULL, 'scale2' REAL DEFAULT NULL, 'last_change' INTEGER NOT NULL);
----
-- structure for index all_scales_index on table all_scales
----
CREATE INDEX 'all_scales_index' ON "all_scales" ("last_change" ASC);


----
-- Drop table for current_values
----
DROP TABLE IF EXISTS "current_values";

----
-- Table structure for current_values
----
CREATE TABLE "current_values" ('id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 'key' TEXT NOT NULL, 'value' REAL, 'last_change' INTEGER NOT NULL);

----
-- Data dump for current_values, a total of 37 rows
----
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('1','sensor_temperature','20.6','1615147942');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('2','sensor_humidity','36.77','1615147942');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('3','status_circulating_air','0.0','1615147942');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('4','status_cooling_compressor','0.0','1615147942');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('5','status_exhaust_air','0.0','1615147942');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('6','status_heater','0.0','1615147942');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('7','status_light','0.0','1615147942');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('8','status_uv','0.0','1615147942');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('9','status_humidifier','0.0','1615147942');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('10','status_dehumidifier','0.0','1615147942');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('11','scale1','-522.17','1615147913');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('12','scale2','-499.15','1615147917');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('13','status_piager','0.0','1615147925');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('14','status_agingtable','0.0','1615147925');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('15','status_scale1','0.0','1615147916');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('16','status_scale2','0.0','1615147921');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('17','status_tara_scale1','0.0','1615114317');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('18','status_tara_scale2','0.0','1615114317');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('19','agingtable_period','0.0','1614454803');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('20','agingtable_period_starttime','1614454563.0','1614454564');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('21','status_light_manual','0.0','1608641257');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('22','calibrate_scale1','0.0','1615114316');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('23','calibrate_scale2','0.0','1615114316');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('24','calibrate_weight','0.0','1615114045');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('25','status_uv_manual','0.0','1608641257');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('26','temperature_meat1','21.16','1615147942');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('27','temperature_meat2',NULL,'1615147942');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('28','temperature_meat3',NULL,'1615147942');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('29','temperature_meat4','21.084','1615147942');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('30','sensor_dewpoint','5.32','1615147942');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('31','sensor_extern_temperature','23.31','1611420926');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('32','sensor_extern_humidity','38.79','1611420926');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('33','sensor_extern_dewpoint','8.5','1611420926');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('34','agingtable_period_day','1.0','1614454803');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('35','scale1_thread_alive','0.0','1615114316');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('36','scale2_thread_alive','0.0','1615114316');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('37','aging_thread_alive','0.0','1615114316');
----
-- Drop table for scale1_settings
----
DROP TABLE IF EXISTS "scale1_settings";

----
-- Table structure for scale1_settings
----
CREATE TABLE 'scale1_settings' ('id' INTEGER DEFAULT 0 PRIMARY KEY AUTOINCREMENT NOT NULL, 'key' TEXT DEFAULT 0 NOT NULL, 'value' REAL DEFAULT 0 NOT NULL, 'last_change' INTEGER DEFAULT 0 NOT NULL);

----
-- Data dump for scale1_settings, a total of 10 rows
----
INSERT INTO "scale1_settings" ("id","key","value","last_change") VALUES ('1','samples','20.0','0');
INSERT INTO "scale1_settings" ("id","key","value","last_change") VALUES ('2','spikes','0.0','0');
INSERT INTO "scale1_settings" ("id","key","value","last_change") VALUES ('3','sleep','0.1','0');
INSERT INTO "scale1_settings" ("id","key","value","last_change") VALUES ('4','gain','128.0','0');
INSERT INTO "scale1_settings" ("id","key","value","last_change") VALUES ('5','bits_to_read','24.0','0');
INSERT INTO "scale1_settings" ("id","key","value","last_change") VALUES ('6','referenceunit','210.0','1611829959');
INSERT INTO "scale1_settings" ("id","key","value","last_change") VALUES ('7','measuring_interval','3.0','0');
INSERT INTO "scale1_settings" ("id","key","value","last_change") VALUES ('8','measuring_duration','15.0','0');
INSERT INTO "scale1_settings" ("id","key","value","last_change") VALUES ('9','saving_period','120.0','0');
INSERT INTO "scale1_settings" ("id","key","value","last_change") VALUES ('10','offset','531.3','1611829981');

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
INSERT INTO "scale2_settings" ("id","key","value","last_change") VALUES ('6','referenceunit','210.7','1606926195');
INSERT INTO "scale2_settings" ("id","key","value","last_change") VALUES ('7','measuring_interval','3.0','0');
INSERT INTO "scale2_settings" ("id","key","value","last_change") VALUES ('8','measuring_duration','15.0','0');
INSERT INTO "scale2_settings" ("id","key","value","last_change") VALUES ('9','saving_period','120.0','0');
INSERT INTO "scale2_settings" ("id","key","value","last_change") VALUES ('10','offset','507.0','1606926216');

COMMIT;




