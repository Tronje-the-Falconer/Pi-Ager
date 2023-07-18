BEGIN TRANSACTION;
DROP TABLE IF EXISTS "cooling_compressor_status";
CREATE TABLE IF NOT EXISTS "cooling_compressor_status" (
	"id"	INTEGER NOT NULL DEFAULT 0,
	"value"	REAL NOT NULL DEFAULT 0,
	"last_change"	INTEGER NOT NULL DEFAULT 0,
	"key"	TEXT NOT NULL DEFAULT 0,
	PRIMARY KEY("id" AUTOINCREMENT)
);
DROP TABLE IF EXISTS "heater_status";
CREATE TABLE IF NOT EXISTS "heater_status" (
	"id"	INTEGER NOT NULL DEFAULT 0,
	"value"	REAL NOT NULL DEFAULT 0,
	"last_change"	INTEGER NOT NULL DEFAULT 0,
	"key"	TEXT NOT NULL DEFAULT 0,
	PRIMARY KEY("id" AUTOINCREMENT)
);
DROP TABLE IF EXISTS "circulating_air_status";
CREATE TABLE IF NOT EXISTS "circulating_air_status" (
	"id"	INTEGER NOT NULL DEFAULT 0,
	"value"	REAL NOT NULL DEFAULT 0,
	"last_change"	INTEGER NOT NULL DEFAULT 0,
	"key"	TEXT NOT NULL DEFAULT 0,
	PRIMARY KEY("id" AUTOINCREMENT)
);
DROP TABLE IF EXISTS "exhaust_air_status";
CREATE TABLE IF NOT EXISTS "exhaust_air_status" (
	"id"	INTEGER NOT NULL DEFAULT 0,
	"value"	REAL NOT NULL DEFAULT 0,
	"last_change"	INTEGER NOT NULL DEFAULT 0,
	"key"	TEXT NOT NULL DEFAULT 0,
	PRIMARY KEY("id" AUTOINCREMENT)
);
DROP TABLE IF EXISTS "light_status";
CREATE TABLE IF NOT EXISTS "light_status" (
	"id"	INTEGER NOT NULL DEFAULT 0,
	"value"	REAL NOT NULL DEFAULT 0,
	"last_change"	INTEGER NOT NULL DEFAULT 0,
	"key"	TEXT NOT NULL DEFAULT 0,
	PRIMARY KEY("id" AUTOINCREMENT)
);
DROP TABLE IF EXISTS "uv_status";
CREATE TABLE IF NOT EXISTS "uv_status" (
	"id"	INTEGER NOT NULL DEFAULT 0,
	"value"	REAL NOT NULL DEFAULT 0,
	"last_change"	INTEGER NOT NULL DEFAULT 0,
	"key"	TEXT NOT NULL DEFAULT 0,
	PRIMARY KEY("id" AUTOINCREMENT)
);
DROP TABLE IF EXISTS "humidifier_status";
CREATE TABLE IF NOT EXISTS "humidifier_status" (
	"id"	INTEGER NOT NULL DEFAULT 0,
	"value"	REAL NOT NULL DEFAULT 0,
	"last_change"	INTEGER NOT NULL DEFAULT 0,
	"key"	TEXT NOT NULL DEFAULT 0,
	PRIMARY KEY("id" AUTOINCREMENT)
);
DROP TABLE IF EXISTS "dehumidifier_status";
CREATE TABLE IF NOT EXISTS "dehumidifier_status" (
	"id"	INTEGER NOT NULL DEFAULT 0,
	"value"	REAL NOT NULL DEFAULT 0,
	"last_change"	INTEGER NOT NULL DEFAULT 0,
	"key"	TEXT NOT NULL DEFAULT 0,
	PRIMARY KEY("id" AUTOINCREMENT)
);
DROP TABLE IF EXISTS "debug";
CREATE TABLE IF NOT EXISTS "debug" (
	"id"	INTEGER NOT NULL DEFAULT 0,
	"key"	TEXT NOT NULL DEFAULT 0,
	"value"	REAL NOT NULL DEFAULT 0,
	"last_change"	INTEGER NOT NULL DEFAULT 0,
	PRIMARY KEY("id" AUTOINCREMENT)
);
DROP TABLE IF EXISTS "agingtables";
CREATE TABLE IF NOT EXISTS "agingtables" (
	"id"	INTEGER NOT NULL DEFAULT 0,
	"name"	TEXT NOT NULL DEFAULT 0,
	PRIMARY KEY("id" AUTOINCREMENT)
);
DROP TABLE IF EXISTS "system";
CREATE TABLE IF NOT EXISTS "system" (
	"id"	INTEGER NOT NULL DEFAULT 0,
	"key"	TEXT NOT NULL DEFAULT 0,
	"value"	TEXT NOT NULL DEFAULT 0,
	"last_change"	INTEGER NOT NULL DEFAULT 0,
	PRIMARY KEY("id" AUTOINCREMENT)
);
DROP TABLE IF EXISTS "config_email_recipient";
CREATE TABLE IF NOT EXISTS "config_email_recipient" (
	"id"	INTEGER NOT NULL,
	"to_mail"	TEXT NOT NULL,
	"active"	INTEGER,
	PRIMARY KEY("id")
);
DROP TABLE IF EXISTS "meat_sensortypes";
CREATE TABLE IF NOT EXISTS "meat_sensortypes" (
	"id"	INTEGER NOT NULL,
	"name"	TEXT DEFAULT NULL,
	"a"	REAL DEFAULT NULL,
	"b"	REAL DEFAULT NULL,
	"c"	REAL DEFAULT NULL,
	"Rn"	REAL DEFAULT NULL,
	"Mode"	TEXT DEFAULT NULL,
	"RefVoltage"	REAL DEFAULT NULL,
	"Sensitivity"	REAL DEFAULT NULL,
	"Turns"	INTEGER DEFAULT NULL,
	"nAverage"	INTEGER DEFAULT NULL,
	"key"	TEXT NOT NULL DEFAULT 0,
	"value"	REAL NOT NULL DEFAULT 0,
	"last_change"	INTEGER NOT NULL DEFAULT 0,
	PRIMARY KEY("id" AUTOINCREMENT)
);
DROP TABLE IF EXISTS "config_telegram";
CREATE TABLE IF NOT EXISTS "config_telegram" (
	"id"	INTEGER,
	"bot_token"	TEXT NOT NULL,
	"bot_chatID"	TEXT NOT NULL,
	"active"	INTEGER
);
DROP TABLE IF EXISTS "config_pushover";
CREATE TABLE IF NOT EXISTS "config_pushover" (
	"id"	INTEGER,
	"user_key"	TEXT NOT NULL,
	"api_token"	TEXT NOT NULL,
	"active"	INTEGER
);
DROP TABLE IF EXISTS "config_alarm";
CREATE TABLE IF NOT EXISTS "config_alarm" (
	"id"	INTEGER NOT NULL,
	"alarm"	TEXT NOT NULL DEFAULT ('short'),
	"replication"	INTEGER DEFAULT (3),
	"sleep"	REAL DEFAULT (0.5),
	"high_time"	REAL DEFAULT (0.5),
	"low_time"	REAL DEFAULT (0.5),
	"waveform"	TEXT,
	"frequency"	REAL,
	PRIMARY KEY("id")
);
DROP TABLE IF EXISTS "all_sensors";
CREATE TABLE IF NOT EXISTS "all_sensors" (
	"id"	INTEGER NOT NULL,
	"tempint"	REAL DEFAULT NULL,
	"tempext"	REAL DEFAULT NULL,
	"humint"	REAL DEFAULT NULL,
	"humext"	REAL DEFAULT NULL,
	"dewint"	REAL DEFAULT NULL,
	"dewext"	REAL DEFAULT NULL,
	"humintabs"	REAL DEFAULT NULL,
	"humextabs"	REAL DEFAULT NULL,
	"ntc1"	REAL DEFAULT NULL,
	"ntc2"	REAL DEFAULT NULL,
	"ntc3"	REAL DEFAULT NULL,
	"ntc4"	REAL DEFAULT NULL,
	"last_change"	INTEGER NOT NULL,
	PRIMARY KEY("id" AUTOINCREMENT)
);
DROP TABLE IF EXISTS "all_scales";
CREATE TABLE IF NOT EXISTS "all_scales" (
	"id"	INTEGER NOT NULL,
	"scale1"	REAL DEFAULT NULL,
	"scale2"	REAL DEFAULT NULL,
	"last_change"	INTEGER NOT NULL,
	PRIMARY KEY("id" AUTOINCREMENT)
);
DROP TABLE IF EXISTS "config_email_server";
CREATE TABLE IF NOT EXISTS "config_email_server" (
	"id"	INTEGER NOT NULL,
	"server"	TEXT NOT NULL,
	"user"	TEXT NOT NULL,
	"password"	TEXT NOT NULL,
	"port"	INTEGER NOT NULL
);
DROP TABLE IF EXISTS "current_values";
CREATE TABLE IF NOT EXISTS "current_values" (
	"id"	INTEGER NOT NULL,
	"key"	TEXT NOT NULL,
	"value"	REAL,
	"last_change"	INTEGER NOT NULL,
	PRIMARY KEY("id" AUTOINCREMENT)
);
DROP TABLE IF EXISTS "config_messenger_exception";
CREATE TABLE IF NOT EXISTS "config_messenger_exception" (
	"exception"	TEXT NOT NULL,
	"e-mail"	INTEGER,
	"pushover"	INTEGER,
	"telegram"	INTEGER,
	"alarm"	TEXT,
	"raise_exception"	INTEGER,
	"active"	INTEGER,
	"id"	INTEGER NOT NULL,
	PRIMARY KEY("id")
);
DROP TABLE IF EXISTS "nextion";
CREATE TABLE IF NOT EXISTS "nextion" (
	"id"	INTEGER NOT NULL,
	"progress"	INTEGER NOT NULL DEFAULT 0,
	"status"	TEXT NOT NULL DEFAULT '''idle''',
	PRIMARY KEY("id" AUTOINCREMENT)
);
DROP TABLE IF EXISTS "config_nfs_backup";
CREATE TABLE IF NOT EXISTS "config_nfs_backup" (
	"id"	INTEGER,
	"nfsvol"	TEXT,
	"number_of_backups"	INTEGER,
	"backup_name"	TEXT,
	"nfsopt"	TEXT,
	"active"	INTEGER,
	PRIMARY KEY("id")
);
DROP TABLE IF EXISTS "config";
CREATE TABLE IF NOT EXISTS "config" (
	"id"	INTEGER NOT NULL DEFAULT 0,
	"key"	TEXT NOT NULL DEFAULT 0,
	"value"	REAL NOT NULL DEFAULT 0,
	"last_change"	INTEGER NOT NULL DEFAULT 0,
	PRIMARY KEY("id" AUTOINCREMENT)
);
DROP TABLE IF EXISTS "atc_mi_thermometer_data";
CREATE TABLE IF NOT EXISTS "atc_mi_thermometer_data" (
	"id"	INTEGER NOT NULL,
	"mi_data"	TEXT DEFAULT NULL,
	PRIMARY KEY("id" AUTOINCREMENT)
);
DROP TABLE IF EXISTS "atc_mi_thermometer_mac";
CREATE TABLE IF NOT EXISTS "atc_mi_thermometer_mac" (
	"id"	INTEGER NOT NULL,
	"mi_mac_last3bytes"	TEXT DEFAULT NULL,
	PRIMARY KEY("id" AUTOINCREMENT)
);
DROP TABLE IF EXISTS "config_defrost";
CREATE TABLE IF NOT EXISTS "config_defrost" (
	"id"	INTEGER NOT NULL,
	"active"	INTEGER,
	"temperature"	REAL,
	"cycle_hours"	INTEGER,
	"temp_limit"	REAL,
	"circulate_air"	INTEGER,
	PRIMARY KEY("id" AUTOINCREMENT)
);
DROP TABLE IF EXISTS "config_current_check";
CREATE TABLE IF NOT EXISTS "config_current_check" (
	"id"	INTEGER NOT NULL,
	"current_check_active"	INTEGER NOT NULL,
	"current_threshold"	REAL NOT NULL,
	"repeat_event_cycle"	INTEGER NOT NULL,
	PRIMARY KEY("id" AUTOINCREMENT)
);
DROP TABLE IF EXISTS "config_messenger_event";
CREATE TABLE IF NOT EXISTS "config_messenger_event" (
	"event"	TEXT NOT NULL,
	"e-mail"	INTEGER,
	"pushover"	INTEGER,
	"telegram"	INTEGER,
	"alarm"	TEXT,
	"event_text"	TEXT,
	"active"	INTEGER,
	"id"	INTEGER NOT NULL,
	PRIMARY KEY("id")
);
DROP TABLE IF EXISTS "time_meter";
CREATE TABLE IF NOT EXISTS "time_meter" (
	"id"	INTEGER NOT NULL,
	"uv_light_seconds"	INTEGER NOT NULL DEFAULT 0,
	"pi_ager_seconds"	INTEGER NOT NULL DEFAULT 0,
	PRIMARY KEY("id" AUTOINCREMENT)
);
DROP TABLE IF EXISTS "agingtable_dryaging1";
CREATE TABLE IF NOT EXISTS "agingtable_dryaging1" (
	"id"	INTEGER NOT NULL DEFAULT 0,
	"modus"	INTEGER,
	"setpoint_humidity"	INTEGER,
	"setpoint_temperature"	INTEGER,
	"circulation_air_duration"	INTEGER,
	"circulation_air_period"	INTEGER,
	"exhaust_air_duration"	INTEGER,
	"exhaust_air_period"	INTEGER,
	"hours"	INTEGER NOT NULL DEFAULT 1,
	"comment"	TEXT,
	PRIMARY KEY("id")
);
DROP TABLE IF EXISTS "agingtable_Salami";
CREATE TABLE IF NOT EXISTS "agingtable_Salami" (
	"id"	INTEGER NOT NULL,
	"modus"	INTEGER,
	"setpoint_humidity"	INTEGER,
	"setpoint_temperature"	INTEGER,
	"circulation_air_duration"	INTEGER,
	"circulation_air_period"	INTEGER,
	"exhaust_air_duration"	INTEGER,
	"exhaust_air_period"	INTEGER,
	"hours"	INTEGER,
	"comment"	TEXT,
	PRIMARY KEY("id" AUTOINCREMENT)
);
DROP TABLE IF EXISTS "agingtable_dryaging2";
CREATE TABLE IF NOT EXISTS "agingtable_dryaging2" (
	"id"	INTEGER NOT NULL,
	"modus"	INTEGER,
	"setpoint_humidity"	INTEGER,
	"setpoint_temperature"	INTEGER,
	"circulation_air_duration"	INTEGER,
	"circulation_air_period"	INTEGER,
	"exhaust_air_duration"	INTEGER,
	"exhaust_air_period"	INTEGER,
	"hours"	INTEGER NOT NULL DEFAULT 1,
	"comment"	TEXT,
	PRIMARY KEY("id" AUTOINCREMENT)
);
DROP TABLE IF EXISTS "scale1_settings";
CREATE TABLE IF NOT EXISTS "scale1_settings" (
	"id"	INTEGER NOT NULL DEFAULT 0,
	"key"	TEXT NOT NULL DEFAULT 0,
	"value"	REAL NOT NULL DEFAULT 0,
	"last_change"	INTEGER NOT NULL DEFAULT 0,
	PRIMARY KEY("id" AUTOINCREMENT)
);
DROP TABLE IF EXISTS "scale2_settings";
CREATE TABLE IF NOT EXISTS "scale2_settings" (
	"id"	INTEGER NOT NULL DEFAULT 0,
	"key"	TEXT NOT NULL DEFAULT 0,
	"value"	REAL NOT NULL DEFAULT 0,
	"last_change"	INTEGER NOT NULL DEFAULT 0,
	PRIMARY KEY("id" AUTOINCREMENT)
);
INSERT INTO "debug" ("id","key","value","last_change") VALUES (1,'measuring_interval_debug',30.0,0);
INSERT INTO "debug" ("id","key","value","last_change") VALUES (2,'agingtable_hours_in_seconds_debug',3600.0,0);
INSERT INTO "debug" ("id","key","value","last_change") VALUES (3,'loglevel_file',20.0,0);
INSERT INTO "debug" ("id","key","value","last_change") VALUES (4,'loglevel_console',20.0,0);
INSERT INTO "agingtables" ("id","name") VALUES (2,'dryaging1');
INSERT INTO "agingtables" ("id","name") VALUES (7,'Salami');
INSERT INTO "agingtables" ("id","name") VALUES (8,'dryaging2');
INSERT INTO "system" ("id","key","value","last_change") VALUES (1,'pi_revision','9000c1',1689664950);
INSERT INTO "system" ("id","key","value","last_change") VALUES (3,'pi_ager_version','3.3.4 build 0718 bullseye',0);
INSERT INTO "meat_sensortypes" ("id","name","a","b","c","Rn","Mode","RefVoltage","Sensitivity","Turns","nAverage","key","value","last_change") VALUES (0,'------',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0',0.0,0);
INSERT INTO "meat_sensortypes" ("id","name","a","b","c","Rn","Mode","RefVoltage","Sensitivity","Turns","nAverage","key","value","last_change") VALUES (1,'Fantast',0.003355834,0.00025698192,1.6391056e-06,50.08,NULL,NULL,NULL,NULL,NULL,'0',0.0,0);
INSERT INTO "meat_sensortypes" ("id","name","a","b","c","Rn","Mode","RefVoltage","Sensitivity","Turns","nAverage","key","value","last_change") VALUES (2,'MAVERICK',0.003356158,0.00022237925,2.652016e-06,1004.0,NULL,NULL,NULL,NULL,NULL,'0',0.0,0);
INSERT INTO "meat_sensortypes" ("id","name","a","b","c","Rn","Mode","RefVoltage","Sensitivity","Turns","nAverage","key","value","last_change") VALUES (3,'iGrill2',0.0033562424,0.00025319218,2.7988397e-06,99.61,NULL,NULL,NULL,NULL,NULL,'0',0.0,0);
INSERT INTO "meat_sensortypes" ("id","name","a","b","c","Rn","Mode","RefVoltage","Sensitivity","Turns","nAverage","key","value","last_change") VALUES (4,'ThermoWorks',0.0033556417,0.0002519145,2.360696e-06,97.31,NULL,NULL,NULL,NULL,NULL,'0',0.0,0);
INSERT INTO "meat_sensortypes" ("id","name","a","b","c","Rn","Mode","RefVoltage","Sensitivity","Turns","nAverage","key","value","last_change") VALUES (5,'ET-73',0.00335672,0.000291888,4.39054e-06,200.0,NULL,NULL,NULL,NULL,NULL,'0',0.0,0);
INSERT INTO "meat_sensortypes" ("id","name","a","b","c","Rn","Mode","RefVoltage","Sensitivity","Turns","nAverage","key","value","last_change") VALUES (6,'PERFEKTION',0.003356199,0.00024352911,3.4519389e-06,200.1,NULL,NULL,NULL,NULL,NULL,'0',0.0,0);
INSERT INTO "meat_sensortypes" ("id","name","a","b","c","Rn","Mode","RefVoltage","Sensitivity","Turns","nAverage","key","value","last_change") VALUES (7,'100K6A1B',0.00335639,0.000241116,2.43362e-06,100.0,NULL,NULL,NULL,NULL,NULL,'0',0.0,0);
INSERT INTO "meat_sensortypes" ("id","name","a","b","c","Rn","Mode","RefVoltage","Sensitivity","Turns","nAverage","key","value","last_change") VALUES (8,'FANTAST_NEU',0.00334519,0.000243825,2.61726e-06,220.0,NULL,NULL,NULL,NULL,NULL,'0',0.0,0);
INSERT INTO "meat_sensortypes" ("id","name","a","b","c","Rn","Mode","RefVoltage","Sensitivity","Turns","nAverage","key","value","last_change") VALUES (9,'100K3950',0.0033527432,0.00025977101,3.693425e-06,100.0,NULL,NULL,NULL,NULL,NULL,'0',0.0,0);
INSERT INTO "meat_sensortypes" ("id","name","a","b","c","Rn","Mode","RefVoltage","Sensitivity","Turns","nAverage","key","value","last_change") VALUES (10,'Inkbird',0.0033552456,0.00025608666,1.9317204e-06,48.59,NULL,NULL,NULL,NULL,NULL,'0',0.0,0);
INSERT INTO "meat_sensortypes" ("id","name","a","b","c","Rn","Mode","RefVoltage","Sensitivity","Turns","nAverage","key","value","last_change") VALUES (11,'5K3A1B',0.0033555,0.000257,2.43e-06,5.0,NULL,NULL,NULL,NULL,NULL,'0',0.0,0);
INSERT INTO "meat_sensortypes" ("id","name","a","b","c","Rn","Mode","RefVoltage","Sensitivity","Turns","nAverage","key","value","last_change") VALUES (12,'ET-735',0.0033566373,0.00019664428,9.6248678e-07,998.9,NULL,NULL,NULL,NULL,NULL,'0',0.0,0);
INSERT INTO "meat_sensortypes" ("id","name","a","b","c","Rn","Mode","RefVoltage","Sensitivity","Turns","nAverage","key","value","last_change") VALUES (13,'Acurite',0.0033555291,0.00025249073,2.5667292e-06,50.21,NULL,NULL,NULL,NULL,NULL,'0',0.0,0);
INSERT INTO "meat_sensortypes" ("id","name","a","b","c","Rn","Mode","RefVoltage","Sensitivity","Turns","nAverage","key","value","last_change") VALUES (14,'ODC',0.0033538614,0.00022352517,2.4382337e-06,1000.0,NULL,NULL,NULL,NULL,NULL,'0',0.0,0);
INSERT INTO "meat_sensortypes" ("id","name","a","b","c","Rn","Mode","RefVoltage","Sensitivity","Turns","nAverage","key","value","last_change") VALUES (15,'Santos',0.0033561093,0.00023552814,2.1375541e-06,200.82,NULL,NULL,NULL,NULL,NULL,'0',0.0,0);
INSERT INTO "meat_sensortypes" ("id","name","a","b","c","Rn","Mode","RefVoltage","Sensitivity","Turns","nAverage","key","value","last_change") VALUES (16,'SmokeMAX',0.0033561946,0.0002550005,2.5976258e-06,99.645,NULL,NULL,NULL,NULL,NULL,'0',0.0,0);
INSERT INTO "meat_sensortypes" ("id","name","a","b","c","Rn","Mode","RefVoltage","Sensitivity","Turns","nAverage","key","value","last_change") VALUES (17,'Weber_6743',0.0033558796,0.00027111149,3.1838428e-06,102.315,NULL,NULL,NULL,NULL,NULL,'0',0.0,0);
INSERT INTO "meat_sensortypes" ("id","name","a","b","c","Rn","Mode","RefVoltage","Sensitivity","Turns","nAverage","key","value","last_change") VALUES (18,'LEM-HO6P-AC',NULL,NULL,NULL,NULL,'AC',1.6672,76.67,3,100,'0',0.0,0);
INSERT INTO "meat_sensortypes" ("id","name","a","b","c","Rn","Mode","RefVoltage","Sensitivity","Turns","nAverage","key","value","last_change") VALUES (19,'LEM-HO6P-DC',NULL,NULL,NULL,NULL,'DC',1.6672,76.67,3,50,'0',0.0,0);
INSERT INTO "config_alarm" ("id","alarm","replication","sleep","high_time","low_time","waveform","frequency") VALUES (1,'short',3,0.5,0.5,0.5,'','');
INSERT INTO "config_alarm" ("id","alarm","replication","sleep","high_time","low_time","waveform","frequency") VALUES (2,'long',3,2.0,3.0,3.0,'','');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES (1,'sensor_temperature',26.43,1689666069);
INSERT INTO "current_values" ("id","key","value","last_change") VALUES (2,'sensor_humidity',46.74,1689666069);
INSERT INTO "current_values" ("id","key","value","last_change") VALUES (3,'status_circulating_air',0.0,1689666069);
INSERT INTO "current_values" ("id","key","value","last_change") VALUES (4,'status_cooling_compressor',0.0,1689666069);
INSERT INTO "current_values" ("id","key","value","last_change") VALUES (5,'status_exhaust_air',0.0,1689666069);
INSERT INTO "current_values" ("id","key","value","last_change") VALUES (6,'status_heater',0.0,1689666069);
INSERT INTO "current_values" ("id","key","value","last_change") VALUES (7,'status_light',0.0,1689666069);
INSERT INTO "current_values" ("id","key","value","last_change") VALUES (8,'status_uv',0.0,1689666069);
INSERT INTO "current_values" ("id","key","value","last_change") VALUES (9,'status_humidifier',0.0,1689666069);
INSERT INTO "current_values" ("id","key","value","last_change") VALUES (10,'status_dehumidifier',0.0,1689666069);
INSERT INTO "current_values" ("id","key","value","last_change") VALUES (11,'scale1',-524.417,1687511510);
INSERT INTO "current_values" ("id","key","value","last_change") VALUES (12,'scale2',1005.589,1687511510);
INSERT INTO "current_values" ("id","key","value","last_change") VALUES (13,'status_piager',0.0,1689628715);
INSERT INTO "current_values" ("id","key","value","last_change") VALUES (14,'status_agingtable',0.0,1687511470);
INSERT INTO "current_values" ("id","key","value","last_change") VALUES (15,'status_scale1',0.0,1687507889);
INSERT INTO "current_values" ("id","key","value","last_change") VALUES (16,'status_scale2',0.0,1687507897);
INSERT INTO "current_values" ("id","key","value","last_change") VALUES (17,'status_tara_scale1',0.0,1689672966);
INSERT INTO "current_values" ("id","key","value","last_change") VALUES (18,'status_tara_scale2',0.0,1689672966);
INSERT INTO "current_values" ("id","key","value","last_change") VALUES (19,'agingtable_period',0.0,1687511471);
INSERT INTO "current_values" ("id","key","value","last_change") VALUES (20,'agingtable_period_starttime',1687508013.0,1687508013);
INSERT INTO "current_values" ("id","key","value","last_change") VALUES (21,'status_light_manual',1.0,1675595657);
INSERT INTO "current_values" ("id","key","value","last_change") VALUES (22,'calibrate_scale1',0.0,1689672966);
INSERT INTO "current_values" ("id","key","value","last_change") VALUES (23,'calibrate_scale2',0.0,1689672966);
INSERT INTO "current_values" ("id","key","value","last_change") VALUES (24,'calibrate_weight',0.0,1689672966);
INSERT INTO "current_values" ("id","key","value","last_change") VALUES (25,'status_uv_manual',1.0,1645642355);
INSERT INTO "current_values" ("id","key","value","last_change") VALUES (26,'temperature_meat1',25.776,1689666069);
INSERT INTO "current_values" ("id","key","value","last_change") VALUES (27,'temperature_meat2',25.95,1689666069);
INSERT INTO "current_values" ("id","key","value","last_change") VALUES (28,'temperature_meat3',26.033,1689666069);
INSERT INTO "current_values" ("id","key","value","last_change") VALUES (29,'temperature_meat4',25.648,1689666069);
INSERT INTO "current_values" ("id","key","value","last_change") VALUES (30,'sensor_dewpoint',14.12,1689666069);
INSERT INTO "current_values" ("id","key","value","last_change") VALUES (31,'sensor_extern_temperature',28.02,1689666069);
INSERT INTO "current_values" ("id","key","value","last_change") VALUES (32,'sensor_extern_humidity',40.08,1689666069);
INSERT INTO "current_values" ("id","key","value","last_change") VALUES (33,'sensor_extern_dewpoint',13.19,1689666069);
INSERT INTO "current_values" ("id","key","value","last_change") VALUES (34,'agingtable_period_hour',1.0,1687511471);
INSERT INTO "current_values" ("id","key","value","last_change") VALUES (35,'scale1_thread_alive',0.0,1689672966);
INSERT INTO "current_values" ("id","key","value","last_change") VALUES (36,'scale2_thread_alive',0.0,1689672966);
INSERT INTO "current_values" ("id","key","value","last_change") VALUES (37,'aging_thread_alive',0.0,1689672967);
INSERT INTO "current_values" ("id","key","value","last_change") VALUES (38,'sensor_humidity_abs',11.65,1689666069);
INSERT INTO "current_values" ("id","key","value","last_change") VALUES (39,'sensor_extern_humidity_abs',10.91,1689666069);
INSERT INTO "current_values" ("id","key","value","last_change") VALUES (40,'status_humidity_check',0.0,1689666064);
INSERT INTO "current_values" ("id","key","value","last_change") VALUES (41,'MiSensor_battery',2.538,1689666064);
INSERT INTO "current_values" ("id","key","value","last_change") VALUES (42,'status_defrost',0.0,1689672961);
INSERT INTO "current_values" ("id","key","value","last_change") VALUES (43,'agingtable_period_hour',1.0,1687511471);
INSERT INTO "config_messenger_exception" ("exception","e-mail","pushover","telegram","alarm","raise_exception","active","id") VALUES ('unknown',0,0,0,'short',1,1,1);
INSERT INTO "config_messenger_exception" ("exception","e-mail","pushover","telegram","alarm","raise_exception","active","id") VALUES ('OperationalError',0,0,0,'short',1,1,2);
INSERT INTO "config_messenger_exception" ("exception","e-mail","pushover","telegram","alarm","raise_exception","active","id") VALUES ('FileNotFoundError',0,0,0,'short',1,1,3);
INSERT INTO "config_messenger_exception" ("exception","e-mail","pushover","telegram","alarm","raise_exception","active","id") VALUES ('OSError',0,0,0,'short',1,1,4);
INSERT INTO "config_messenger_exception" ("exception","e-mail","pushover","telegram","alarm","raise_exception","active","id") VALUES ('cx_measurement_error',0,0,0,'short',1,1,5);
INSERT INTO "config_messenger_exception" ("exception","e-mail","pushover","telegram","alarm","raise_exception","active","id") VALUES ('cx_i2c_sht_temperature_crc_error',0,0,0,'short',1,1,6);
INSERT INTO "config_messenger_exception" ("exception","e-mail","pushover","telegram","alarm","raise_exception","active","id") VALUES ('cx_i2c_sht_humidity_crc_error',0,0,0,'short',1,1,7);
INSERT INTO "config_messenger_exception" ("exception","e-mail","pushover","telegram","alarm","raise_exception","active","id") VALUES ('cx_i2c_bus_error',0,0,0,'short',1,1,8);
INSERT INTO "config_messenger_exception" ("exception","e-mail","pushover","telegram","alarm","raise_exception","active","id") VALUES ('cx_sensor_not_defined',0,0,0,'short',1,1,9);
INSERT INTO "nextion" ("id","progress","status") VALUES (1,500,'success');
INSERT INTO "config_nfs_backup" ("id","nfsvol","number_of_backups","backup_name","nfsopt","active") VALUES (1,'',3,'PiAgerBackup','nosuid,nodev',1);
INSERT INTO "config" ("id","key","value","last_change") VALUES (1,'cooling_hysteresis',2.0,1669821889);
INSERT INTO "config" ("id","key","value","last_change") VALUES (2,'heating_hysteresis',3.0,1669821889);
INSERT INTO "config" ("id","key","value","last_change") VALUES (3,'switch_on_humidifier',25.0,1669821889);
INSERT INTO "config" ("id","key","value","last_change") VALUES (4,'switch_off_humidifier',0.0,1669821889);
INSERT INTO "config" ("id","key","value","last_change") VALUES (5,'delay_humidify',5.0,1669821889);
INSERT INTO "config" ("id","key","value","last_change") VALUES (6,'sensortype',5.0,1582013839);
INSERT INTO "config" ("id","key","value","last_change") VALUES (7,'language',1.0,1687520357);
INSERT INTO "config" ("id","key","value","last_change") VALUES (8,'switch_on_light_hour',12.0,1669821889);
INSERT INTO "config" ("id","key","value","last_change") VALUES (9,'switch_on_light_minute',30.0,1669821889);
INSERT INTO "config" ("id","key","value","last_change") VALUES (10,'light_duration',0.0,1669821889);
INSERT INTO "config" ("id","key","value","last_change") VALUES (11,'light_period',21600.0,1669821889);
INSERT INTO "config" ("id","key","value","last_change") VALUES (12,'light_modus',0.0,1669821889);
INSERT INTO "config" ("id","key","value","last_change") VALUES (13,'switch_on_uv_hour',11.0,1669821889);
INSERT INTO "config" ("id","key","value","last_change") VALUES (14,'switch_on_uv_minute',30.0,1669821889);
INSERT INTO "config" ("id","key","value","last_change") VALUES (15,'uv_duration',300.0,1669821889);
INSERT INTO "config" ("id","key","value","last_change") VALUES (16,'uv_period',21600.0,1669821889);
INSERT INTO "config" ("id","key","value","last_change") VALUES (17,'uv_modus',0.0,1669821889);
INSERT INTO "config" ("id","key","value","last_change") VALUES (18,'dehumidifier_modus',2.0,1669821889);
INSERT INTO "config" ("id","key","value","last_change") VALUES (19,'circulation_air_period',3600.0,1689665909);
INSERT INTO "config" ("id","key","value","last_change") VALUES (20,'setpoint_temperature',20.0,1689665909);
INSERT INTO "config" ("id","key","value","last_change") VALUES (21,'exhaust_air_duration',600.0,1689665909);
INSERT INTO "config" ("id","key","value","last_change") VALUES (22,'modus',3.0,1689665909);
INSERT INTO "config" ("id","key","value","last_change") VALUES (23,'setpoint_humidity',93.0,1689665909);
INSERT INTO "config" ("id","key","value","last_change") VALUES (24,'exhaust_air_period',28800.0,1689665909);
INSERT INTO "config" ("id","key","value","last_change") VALUES (25,'circulation_air_duration',600.0,1689665909);
INSERT INTO "config" ("id","key","value","last_change") VALUES (26,'agingtable',7.0,1687507987);
INSERT INTO "config" ("id","key","value","last_change") VALUES (27,'failure_humidity_delta',4.0,0);
INSERT INTO "config" ("id","key","value","last_change") VALUES (28,'failure_temperature_delta',10.0,0);
INSERT INTO "config" ("id","key","value","last_change") VALUES (29,'samples_refunit_tara',20.0,0);
INSERT INTO "config" ("id","key","value","last_change") VALUES (30,'spikes_refunit_tara',4.0,0);
INSERT INTO "config" ("id","key","value","last_change") VALUES (31,'save_temperature_humidity_loops',6.0,0);
INSERT INTO "config" ("id","key","value","last_change") VALUES (32,'sensorbus',0.0,0);
INSERT INTO "config" ("id","key","value","last_change") VALUES (33,'meat1_sensortype',0.0,1687520357);
INSERT INTO "config" ("id","key","value","last_change") VALUES (34,'meat2_sensortype',0.0,1687520357);
INSERT INTO "config" ("id","key","value","last_change") VALUES (35,'meat3_sensortype',0.0,1687520357);
INSERT INTO "config" ("id","key","value","last_change") VALUES (36,'meat4_sensortype',0.0,1687520357);
INSERT INTO "config" ("id","key","value","last_change") VALUES (37,'customtime_for_diagrams',3600.0,0);
INSERT INTO "config" ("id","key","value","last_change") VALUES (38,'secondsensortype',0.0,0);
INSERT INTO "config" ("id","key","value","last_change") VALUES (39,'agingtable_startperiod',1.0,1687508010);
INSERT INTO "config" ("id","key","value","last_change") VALUES (40,'agingtable_starthour',1.0,1687508010);
INSERT INTO "config" ("id","key","value","last_change") VALUES (41,'tft_display_type',1.0,0);
INSERT INTO "config" ("id","key","value","last_change") VALUES (42,'internal_temperature_low_limit',0.0,0);
INSERT INTO "config" ("id","key","value","last_change") VALUES (43,'internal_temperature_high_limit',25.0,0);
INSERT INTO "config" ("id","key","value","last_change") VALUES (44,'internal_temperature_hysteresis',3.0,0);
INSERT INTO "config" ("id","key","value","last_change") VALUES (45,'shutdown_on_batlow',0.0,0);
INSERT INTO "config" ("id","key","value","last_change") VALUES (46,'diagram_modus',0.0,1645089748);
INSERT INTO "config" ("id","key","value","last_change") VALUES (47,'delay_cooler',30.0,0);
INSERT INTO "config" ("id","key","value","last_change") VALUES (48,'dewpoint_check',1.0,0);
INSERT INTO "config" ("id","key","value","last_change") VALUES (49,'humidity_check_hysteresis',0.2,0);
INSERT INTO "config" ("id","key","value","last_change") VALUES (50,'switch_control_uv_light',0.0,1687520357);
INSERT INTO "config" ("id","key","value","last_change") VALUES (51,'switch_control_light',0.0,1687520357);
INSERT INTO "config" ("id","key","value","last_change") VALUES (52,'uv_check',0.0,0);
INSERT INTO "config" ("id","key","value","last_change") VALUES (53,'agingtable_starthour',1.0,1687508010);
INSERT INTO "atc_mi_thermometer_data" ("id","mi_data") VALUES (1,'');
INSERT INTO "atc_mi_thermometer_mac" ("id","mi_mac_last3bytes") VALUES (1,'');
INSERT INTO "config_defrost" ("id","active","temperature","cycle_hours","temp_limit","circulate_air") VALUES (1,1,3.0,6,8.0,1);
INSERT INTO "config_current_check" ("id","current_check_active","current_threshold","repeat_event_cycle") VALUES (1,0,1.0,15);
INSERT INTO "config_messenger_event" ("event","e-mail","pushover","telegram","alarm","event_text","active","id") VALUES ('Pi-Ager_started',0,0,0,' ','Pi-Ager gestartet',1,1);
INSERT INTO "config_messenger_event" ("event","e-mail","pushover","telegram","alarm","event_text","active","id") VALUES ('Int_Temp_Low_Limit',0,0,0,' ','Temp. tief',1,2);
INSERT INTO "config_messenger_event" ("event","e-mail","pushover","telegram","alarm","event_text","active","id") VALUES ('Int_Temp_High_Limit',0,0,0,' ','Temp. hoch',1,3);
INSERT INTO "config_messenger_event" ("event","e-mail","pushover","telegram","alarm","event_text","active","id") VALUES ('ups_bat_low',0,0,0,' ','',0,4);
INSERT INTO "config_messenger_event" ("event","e-mail","pushover","telegram","alarm","event_text","active","id") VALUES ('ups_bat_ok',0,0,0,' ','',0,5);
INSERT INTO "config_messenger_event" ("event","e-mail","pushover","telegram","alarm","event_text","active","id") VALUES ('powerfail',0,0,0,' ','',0,6);
INSERT INTO "config_messenger_event" ("event","e-mail","pushover","telegram","alarm","event_text","active","id") VALUES ('powergood',0,0,0,' ','',0,7);
INSERT INTO "config_messenger_event" ("event","e-mail","pushover","telegram","alarm","event_text","active","id") VALUES ('switch_on',0,0,0,' ','Schalter Ein',1,8);
INSERT INTO "config_messenger_event" ("event","e-mail","pushover","telegram","alarm","event_text","active","id") VALUES ('switch_off',0,0,0,' ','Schalter Aus',1,9);
INSERT INTO "config_messenger_event" ("event","e-mail","pushover","telegram","alarm","event_text","active","id") VALUES ('Pi-Ager_offline',0,0,0,' ','',0,10);
INSERT INTO "config_messenger_event" ("event","e-mail","pushover","telegram","alarm","event_text","active","id") VALUES ('Mi_Sensor_failed',0,0,0,' ','Bluetooth sensor out of range',0,11);
INSERT INTO "config_messenger_event" ("event","e-mail","pushover","telegram","alarm","event_text","active","id") VALUES ('Mi_Sensor_ok',0,0,0,' ','Bluetooth sensor within range',0,12);
INSERT INTO "config_messenger_event" ("event","e-mail","pushover","telegram","alarm","event_text","active","id") VALUES ('GPIO_status_changed',0,0,0,' ','one or more GPIO states changed',1,13);
INSERT INTO "config_messenger_event" ("event","e-mail","pushover","telegram","alarm","event_text","active","id") VALUES ('Defrost_started',0,0,0,' ','Defrost process started',0,14);
INSERT INTO "config_messenger_event" ("event","e-mail","pushover","telegram","alarm","event_text","active","id") VALUES ('Defrost_stopped',0,0,0,' ','Defrost process stopped',0,15);
INSERT INTO "config_messenger_event" ("event","e-mail","pushover","telegram","alarm","event_text","active","id") VALUES ('cooler_turned_on_failure',0,0,0,' ','cooler should turn on if requested',1,16);
INSERT INTO "config_messenger_event" ("event","e-mail","pushover","telegram","alarm","event_text","active","id") VALUES ('cooler_turned_off_failure',0,0,0,' ','cooler should turn off if requested',1,17);
INSERT INTO "time_meter" ("id","uv_light_seconds","pi_ager_seconds") VALUES (1,0,0);
INSERT INTO "agingtable_dryaging1" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","hours","comment") VALUES (1,4,85,2,1440,2160,900,12960,240,'Testtabelle');
INSERT INTO "agingtable_dryaging1" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","hours","comment") VALUES (2,NULL,30,4,2520,1080,NULL,6480,192,NULL);
INSERT INTO "agingtable_dryaging1" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","hours","comment") VALUES (3,NULL,70,NULL,1080,2520,NULL,15120,192,NULL);
INSERT INTO "agingtable_dryaging1" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","hours","comment") VALUES (4,NULL,60,NULL,720,2880,NULL,17280,192,NULL);
INSERT INTO "agingtable_dryaging1" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","hours","comment") VALUES (5,NULL,45,7,NULL,NULL,NULL,NULL,240,NULL);
INSERT INTO "agingtable_dryaging1" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","hours","comment") VALUES (6,NULL,33,NULL,540,3060,NULL,18360,192,NULL);
INSERT INTO "agingtable_dryaging1" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","hours","comment") VALUES (7,NULL,NULL,NULL,NULL,NULL,NULL,NULL,24,'');
INSERT INTO "agingtable_dryaging1" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","hours","comment") VALUES (8,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'');
INSERT INTO "agingtable_Salami" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","hours","comment") VALUES (1,4,93,20,600,3600,600,28800,24,NULL);
INSERT INTO "agingtable_Salami" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","hours","comment") VALUES (2,4,90,19,600,3600,600,28800,24,NULL);
INSERT INTO "agingtable_Salami" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","hours","comment") VALUES (3,4,90,19,600,3600,600,28800,24,NULL);
INSERT INTO "agingtable_Salami" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","hours","comment") VALUES (4,4,90,18,600,3600,600,28800,24,NULL);
INSERT INTO "agingtable_Salami" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","hours","comment") VALUES (5,4,90,18,600,5400,600,28800,24,NULL);
INSERT INTO "agingtable_Salami" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","hours","comment") VALUES (6,4,90,17,600,5400,600,28800,24,NULL);
INSERT INTO "agingtable_Salami" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","hours","comment") VALUES (7,4,90,17,600,5400,600,28800,24,NULL);
INSERT INTO "agingtable_Salami" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","hours","comment") VALUES (8,4,89,16,600,5400,600,28800,24,NULL);
INSERT INTO "agingtable_Salami" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","hours","comment") VALUES (9,4,89,16,600,5400,600,28800,24,NULL);
INSERT INTO "agingtable_Salami" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","hours","comment") VALUES (10,4,88,15,600,5400,600,28800,24,NULL);
INSERT INTO "agingtable_Salami" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","hours","comment") VALUES (11,4,87,15,600,5400,600,28800,24,NULL);
INSERT INTO "agingtable_Salami" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","hours","comment") VALUES (12,4,86,14,600,5400,600,28800,24,NULL);
INSERT INTO "agingtable_Salami" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","hours","comment") VALUES (13,4,85,13,600,5400,600,28800,24,NULL);
INSERT INTO "agingtable_Salami" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","hours","comment") VALUES (14,4,80,13,600,7200,600,28800,24,NULL);
INSERT INTO "agingtable_Salami" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","hours","comment") VALUES (15,4,80,13,600,7200,600,28800,24,NULL);
INSERT INTO "agingtable_Salami" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","hours","comment") VALUES (16,4,80,13,600,7200,600,28800,24,NULL);
INSERT INTO "agingtable_Salami" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","hours","comment") VALUES (17,4,80,13,600,7200,600,28800,24,NULL);
INSERT INTO "agingtable_Salami" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","hours","comment") VALUES (18,4,80,13,600,7200,600,28800,24,NULL);
INSERT INTO "agingtable_Salami" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","hours","comment") VALUES (19,4,80,13,600,7200,600,28800,24,NULL);
INSERT INTO "agingtable_Salami" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","hours","comment") VALUES (20,4,80,12,600,7200,600,28800,24,NULL);
INSERT INTO "agingtable_Salami" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","hours","comment") VALUES (21,4,75,12,600,7200,600,28800,24,NULL);
INSERT INTO "agingtable_Salami" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","hours","comment") VALUES (22,4,75,12,600,10800,600,28800,24,NULL);
INSERT INTO "agingtable_Salami" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","hours","comment") VALUES (23,4,75,12,600,10800,600,28800,24,NULL);
INSERT INTO "agingtable_Salami" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","hours","comment") VALUES (24,4,75,12,600,10800,600,28800,24,NULL);
INSERT INTO "agingtable_Salami" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","hours","comment") VALUES (25,4,75,12,600,10800,600,28800,24,NULL);
INSERT INTO "agingtable_Salami" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","hours","comment") VALUES (26,4,75,12,600,10800,600,28800,24,NULL);
INSERT INTO "agingtable_Salami" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","hours","comment") VALUES (27,4,75,12,600,10800,600,28800,24,NULL);
INSERT INTO "agingtable_Salami" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","hours","comment") VALUES (28,4,70,11,600,10800,600,28800,24,NULL);
INSERT INTO "agingtable_dryaging2" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","hours","comment") VALUES (1,4,85,2,1440,2160,900,12960,12,'Testtabelle');
INSERT INTO "agingtable_dryaging2" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","hours","comment") VALUES (2,NULL,30,4,2520,1080,NULL,6480,8,NULL);
INSERT INTO "agingtable_dryaging2" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","hours","comment") VALUES (3,NULL,70,NULL,1080,2520,NULL,15120,8,NULL);
INSERT INTO "agingtable_dryaging2" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","hours","comment") VALUES (4,NULL,60,NULL,720,2880,NULL,17280,8,NULL);
INSERT INTO "agingtable_dryaging2" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","hours","comment") VALUES (5,NULL,45,7,NULL,NULL,NULL,NULL,12,NULL);
INSERT INTO "agingtable_dryaging2" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","hours","comment") VALUES (6,NULL,33,NULL,540,3060,NULL,18360,8,NULL);
INSERT INTO "scale1_settings" ("id","key","value","last_change") VALUES (1,'samples',20.0,0);
INSERT INTO "scale1_settings" ("id","key","value","last_change") VALUES (2,'spikes',0.0,0);
INSERT INTO "scale1_settings" ("id","key","value","last_change") VALUES (3,'sleep',0.1,0);
INSERT INTO "scale1_settings" ("id","key","value","last_change") VALUES (4,'gain',128.0,0);
INSERT INTO "scale1_settings" ("id","key","value","last_change") VALUES (5,'bits_to_read',24.0,0);
INSERT INTO "scale1_settings" ("id","key","value","last_change") VALUES (6,'referenceunit',0.1,1635761280);
INSERT INTO "scale1_settings" ("id","key","value","last_change") VALUES (7,'measuring_interval',300.0,0);
INSERT INTO "scale1_settings" ("id","key","value","last_change") VALUES (8,'measuring_duration',15.0,0);
INSERT INTO "scale1_settings" ("id","key","value","last_change") VALUES (9,'saving_period',150.0,0);
INSERT INTO "scale1_settings" ("id","key","value","last_change") VALUES (10,'offset',535.1,1635761315);
INSERT INTO "scale2_settings" ("id","key","value","last_change") VALUES (1,'samples',20.0,0);
INSERT INTO "scale2_settings" ("id","key","value","last_change") VALUES (2,'spikes',0.0,0);
INSERT INTO "scale2_settings" ("id","key","value","last_change") VALUES (3,'sleep',0.1,0);
INSERT INTO "scale2_settings" ("id","key","value","last_change") VALUES (4,'gain',128.0,0);
INSERT INTO "scale2_settings" ("id","key","value","last_change") VALUES (5,'bits_to_read',24.0,0);
INSERT INTO "scale2_settings" ("id","key","value","last_change") VALUES (6,'referenceunit',0.1,1670398912);
INSERT INTO "scale2_settings" ("id","key","value","last_change") VALUES (7,'measuring_interval',300.0,0);
INSERT INTO "scale2_settings" ("id","key","value","last_change") VALUES (8,'measuring_duration',15.0,0);
INSERT INTO "scale2_settings" ("id","key","value","last_change") VALUES (9,'saving_period',150.0,0);
INSERT INTO "scale2_settings" ("id","key","value","last_change") VALUES (10,'offset',535.1,1670398945);
DROP INDEX IF EXISTS "all_sensors_index";
CREATE INDEX IF NOT EXISTS "all_sensors_index" ON "all_sensors" (
	"last_change"	ASC
);
DROP INDEX IF EXISTS "all_scales_index";
CREATE INDEX IF NOT EXISTS "all_scales_index" ON "all_scales" (
	"last_change"	ASC
);
COMMIT;
