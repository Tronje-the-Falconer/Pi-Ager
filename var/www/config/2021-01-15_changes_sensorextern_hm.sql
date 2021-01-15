----
-- phpLiteAdmin database dump (https://www.phpliteadmin.org/)
-- phpLiteAdmin version: 1.9.8.2
-- Exported: 9:12pm on January 15, 2021 (CET)
-- database file: ./config/pi-ager.sqlite3
----
BEGIN TRANSACTION;

----
-- Table structure for config
----
CREATE TABLE 'config' ('id' INTEGER DEFAULT 0 PRIMARY KEY AUTOINCREMENT NOT NULL, 'key' TEXT DEFAULT 0 NOT NULL, 'value' REAL DEFAULT 0 NOT NULL, 'last_change' INTEGER DEFAULT 0 NOT NULL);

----
-- Data dump for config, a total of 40 rows
----
INSERT INTO "config" ("id","key","value","last_change") VALUES ('1','switch_on_cooling_compressor','1.0','1604862198');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('2','switch_off_cooling_compressor','0.0','1604862198');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('3','switch_on_humidifier','20.0','1604862198');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('4','switch_off_humidifier','0.0','1604862198');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('5','delay_humidify','5.0','1604862198');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('6','sensortype','5.0','1582013839');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('7','language','1.0','1610706559');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('8','switch_on_light_hour','12.0','1604862198');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('9','switch_on_light_minute','30.0','1604862198');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('10','light_duration','60.0','1604862198');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('11','light_period','21600.0','1604862198');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('12','light_modus','0.0','1604862198');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('13','switch_on_uv_hour','11.0','1604862198');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('14','switch_on_uv_minute','30.0','1604862198');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('15','uv_duration','60.0','1604862198');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('16','uv_period','21600.0','1604862198');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('17','uv_modus','0.0','1604862198');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('18','dehumidifier_modus','1.0','1604862198');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('19','circulation_air_period','3600.0','1610724479');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('20','setpoint_temperature','21.0','1610724479');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('21','exhaust_air_duration','900.0','1610724479');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('22','modus','4.0','1610724479');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('23','setpoint_humidity','93.0','1610724479');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('24','exhaust_air_period','21600.0','1610724479');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('25','circulation_air_duration','900.0','1610724479');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('26','agingtable','1.0','1610707601');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('27','failure_humidity_delta','10.0','0');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('28','failure_temperature_delta','4.0','0');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('29','samples_refunit_tara','20.0','0');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('30','spikes_refunit_tara','4.0','0');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('31','save_temperature_humidity_loops','27.0','0');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('32','sensorbus','0.0','0');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('33','meat1_sensortype','1.0','1610706559');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('34','meat2_sensortype','3.0','1610706559');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('35','meat3_sensortype','0.0','1610706559');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('36','meat4_sensortype','18.0','1610706559');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('37','customtime_for_diagrams','230400.0','0');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('38','secondsensortype','5.0','0');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('39','agingtable_startperiod','0.0','1610391207');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('40','agingtable_startday','1.0','1610391207');

----
-- Table structure for current_values
----
CREATE TABLE "current_values" ('id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 'key' TEXT NOT NULL, 'value' REAL, 'last_change' INTEGER NOT NULL);

----
-- Data dump for current_values, a total of 33 rows
----
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('1','sensor_temperature','16.52','1610741566');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('2','sensor_humidity','36.9','1610741566');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('3','status_circulating_air','1.0','1610741566');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('4','status_cooling_compressor','0.0','1610741566');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('5','status_exhaust_air','0.0','1610741566');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('6','status_heater','1.0','1610741566');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('7','status_light','0.0','1610741566');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('8','status_uv','0.0','1610741566');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('9','status_humidifier','1.0','1610741566');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('10','status_dehumidifier','0.0','1610741566');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('11','scale1','400.620238095238','1610741540');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('12','scale2','0.0','1610650477');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('13','status_piager','1.0','1610727742');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('14','status_agingtable','0.0','1610724548');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('15','status_scale1','0.0','1610724560');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('16','status_scale2','0.0','1610650477');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('17','status_tara_scale1','0.0','1608641257');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('18','status_tara_scale2','0.0','1608641257');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('19','agingtable_period','0.0','1610724489');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('20','agingtable_period_starttime','1610724479.0','1610724479');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('21','status_light_manual','0.0','1608641257');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('22','calibrate_scale1','2.0','1610215277');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('23','calibrate_scale2','0.0','1608641257');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('24','calibrate_weight','0.0','1608641257');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('25','status_uv_manual','0.0','1608641257');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('26','temperature_meat1','17.538','1610741566');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('27','temperature_meat2','17.006','1610741566');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('28','temperature_meat3',NULL,'1610741566');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('29','temperature_meat4','0.043','1610741566');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('30','sensor_dewpoint','0.0','1610724567');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('31','sensor_extern_temperature','0.0','1610724567');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('32','sensor_extern_humidity','55.0','1610724567');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('33','sensor_extern_dewpoint','0.0','1610724567');

----
-- Table structure for sensor_dewpoint_data
----
CREATE TABLE 'sensor_dewpoint_data' ('id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL DEFAULT 0 , 'value' REAL NOT NULL DEFAULT 0 , 'last_change' INTEGER NOT NULL DEFAULT 0 , 'key' TEXT NOT NULL DEFAULT '0');

----
-- Data dump for sensor_dewpoint_data, a total of 1 rows
----
INSERT INTO "sensor_dewpoint_data" ("id","value","last_change","key") VALUES ('1','22.0','1610724498','0');

----
-- Table structure for sensor_extern_dewpiont_data
----
CREATE TABLE 'sensor_extern_dewpiont_data' ('id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL DEFAULT 0 , 'value' REAL NOT NULL DEFAULT 0 , 'last_change' INTEGER NOT NULL DEFAULT 0 , 'key' TEXT NOT NULL DEFAULT '0');

----
-- Data dump for sensor_extern_dewpiont_data, a total of 1 rows
----
INSERT INTO "sensor_extern_dewpiont_data" ("id","value","last_change","key") VALUES ('1','2.0','1610724498','0');

----
-- Table structure for sensor_extern_temperature_data
----
CREATE TABLE 'sensor_extern_temperature_data' ('id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL DEFAULT 0 , 'value' REAL NOT NULL DEFAULT 0 , 'last_change' INTEGER NOT NULL DEFAULT 0 , 'key' TEXT NOT NULL DEFAULT '0');

----
-- Data dump for sensor_extern_temperature_data, a total of 1 rows
----
INSERT INTO "sensor_extern_temperature_data" ("id","value","last_change","key") VALUES ('1','11.0','1610724498','0');

----
-- Table structure for sensor_extern_humidity_data
----
CREATE TABLE 'sensor_extern_humidity_data' ('id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL DEFAULT 0 , 'value' REAL NOT NULL DEFAULT 0 , 'last_change' INTEGER NOT NULL DEFAULT 0 , 'key' TEXT NOT NULL DEFAULT '0');

----
-- Data dump for sensor_extern_humidity_data, a total of 1 rows
----
INSERT INTO "sensor_extern_humidity_data" ("id","value","last_change","key") VALUES ('1','55.0','1610724498','0');

----
-- structure for index humidity	 on table sensor_extern_humidity_data
----
CREATE INDEX 'humidity	' ON "sensor_extern_humidity_data" ("last_change" ASC);

----
-- structure for index temperature_extern on table sensor_extern_temperature_data
----
CREATE INDEX 'temperature_extern' ON "sensor_extern_temperature_data" ("last_change" ASC);

----
-- structure for index dewpoint_extern on table sensor_extern_dewpiont_data
----
CREATE INDEX 'dewpoint_extern' ON "sensor_extern_dewpiont_data" ("last_change" ASC);

----
-- structure for index dewpoint on table sensor_dewpoint_data
----
CREATE INDEX 'dewpoint' ON "sensor_dewpoint_data" ("last_change" ASC);
COMMIT;
