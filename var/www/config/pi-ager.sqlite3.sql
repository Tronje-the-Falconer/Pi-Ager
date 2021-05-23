BEGIN TRANSACTION;
DROP TABLE IF EXISTS `uv_status`;
CREATE TABLE IF NOT EXISTS `uv_status` (
	`id`	INTEGER NOT NULL DEFAULT 0 PRIMARY KEY AUTOINCREMENT,
	`value`	REAL NOT NULL DEFAULT 0,
	`last_change`	INTEGER NOT NULL DEFAULT 0,
	`key`	TEXT NOT NULL DEFAULT 0
);
DROP TABLE IF EXISTS `system`;
CREATE TABLE IF NOT EXISTS `system` (
	`id`	INTEGER NOT NULL DEFAULT 0 PRIMARY KEY AUTOINCREMENT,
	`key`	TEXT NOT NULL DEFAULT 0,
	`value`	TEXT NOT NULL DEFAULT 0,
	`last_change`	INTEGER NOT NULL DEFAULT 0
);
INSERT INTO `system` VALUES (1,'pi_revision','a020d3',1621407911);
INSERT INTO `system` VALUES (3,'pi_ager_version','3.3.0',0);
DROP TABLE IF EXISTS `scale2_settings`;
CREATE TABLE IF NOT EXISTS `scale2_settings` (
	`id`	INTEGER NOT NULL DEFAULT 0 PRIMARY KEY AUTOINCREMENT,
	`key`	TEXT NOT NULL DEFAULT 0,
	`value`	REAL NOT NULL DEFAULT 0,
	`last_change`	INTEGER NOT NULL DEFAULT 0
);
INSERT INTO `scale2_settings` VALUES (1,'samples',20.0,0);
INSERT INTO `scale2_settings` VALUES (2,'spikes',0.0,0);
INSERT INTO `scale2_settings` VALUES (3,'sleep',0.1,0);
INSERT INTO `scale2_settings` VALUES (4,'gain',128.0,0);
INSERT INTO `scale2_settings` VALUES (5,'bits_to_read',24.0,0);
INSERT INTO `scale2_settings` VALUES (6,'referenceunit',0.1,1613243596);
INSERT INTO `scale2_settings` VALUES (7,'measuring_interval',300.0,0);
INSERT INTO `scale2_settings` VALUES (8,'measuring_duration',15.0,0);
INSERT INTO `scale2_settings` VALUES (9,'saving_period',30.0,0);
INSERT INTO `scale2_settings` VALUES (10,'offset',500.0,1613243410);
DROP TABLE IF EXISTS `scale1_settings`;
CREATE TABLE IF NOT EXISTS `scale1_settings` (
	`id`	INTEGER NOT NULL DEFAULT 0 PRIMARY KEY AUTOINCREMENT,
	`key`	TEXT NOT NULL DEFAULT 0,
	`value`	REAL NOT NULL DEFAULT 0,
	`last_change`	INTEGER NOT NULL DEFAULT 0
);
INSERT INTO `scale1_settings` VALUES (1,'samples',20.0,0);
INSERT INTO `scale1_settings` VALUES (2,'spikes',0.0,0);
INSERT INTO `scale1_settings` VALUES (3,'sleep',0.1,0);
INSERT INTO `scale1_settings` VALUES (4,'gain',128.0,0);
INSERT INTO `scale1_settings` VALUES (5,'bits_to_read',24.0,0);
INSERT INTO `scale1_settings` VALUES (6,'referenceunit',0.1,1613669467);
INSERT INTO `scale1_settings` VALUES (7,'measuring_interval',300.0,0);
INSERT INTO `scale1_settings` VALUES (8,'measuring_duration',15.0,0);
INSERT INTO `scale1_settings` VALUES (9,'saving_period',30.0,0);
INSERT INTO `scale1_settings` VALUES (10,'offset',528.4,1613669506);
DROP TABLE IF EXISTS `meat_sensortypes`;
CREATE TABLE IF NOT EXISTS `meat_sensortypes` (
	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`name`	TEXT DEFAULT NULL,
	`a`	REAL DEFAULT NULL,
	`b`	REAL DEFAULT NULL,
	`c`	REAL DEFAULT NULL,
	`Rn`	REAL DEFAULT NULL,
	`Mode`	TEXT DEFAULT NULL,
	`RefVoltage`	REAL DEFAULT NULL,
	`Sensitivity`	REAL DEFAULT NULL,
	`Turns`	INTEGER DEFAULT NULL,
	`nAverage`	INTEGER DEFAULT NULL,
	`key`	TEXT NOT NULL DEFAULT 0,
	`value`	REAL NOT NULL DEFAULT 0,
	`last_change`	INTEGER NOT NULL DEFAULT 0
);
INSERT INTO `meat_sensortypes` VALUES (0,'------',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0',0.0,0);
INSERT INTO `meat_sensortypes` VALUES (1,'Fantast',0.003355834,0.00025698192,1.6391056e-06,50.08,NULL,NULL,NULL,NULL,NULL,'0',0.0,0);
INSERT INTO `meat_sensortypes` VALUES (2,'MAVERICK',0.003356158,0.00022237925,2.652016e-06,1004.0,NULL,NULL,NULL,NULL,NULL,'0',0.0,0);
INSERT INTO `meat_sensortypes` VALUES (3,'iGrill2',0.0033562424,0.00025319218,2.7988397e-06,99.61,NULL,NULL,NULL,NULL,NULL,'0',0.0,0);
INSERT INTO `meat_sensortypes` VALUES (4,'ThermoWorks',0.0033556417,0.0002519145,2.360696e-06,97.31,NULL,NULL,NULL,NULL,NULL,'0',0.0,0);
INSERT INTO `meat_sensortypes` VALUES (5,'ET-73',0.00335672,0.000291888,4.39054e-06,200.0,NULL,NULL,NULL,NULL,NULL,'0',0.0,0);
INSERT INTO `meat_sensortypes` VALUES (6,'PERFEKTION',0.003356199,0.00024352911,3.4519389e-06,200.1,NULL,NULL,NULL,NULL,NULL,'0',0.0,0);
INSERT INTO `meat_sensortypes` VALUES (7,'100K6A1B',0.00335639,0.000241116,2.43362e-06,100.0,NULL,NULL,NULL,NULL,NULL,'0',0.0,0);
INSERT INTO `meat_sensortypes` VALUES (8,'FANTAST_NEU',0.00334519,0.000243825,2.61726e-06,220.0,NULL,NULL,NULL,NULL,NULL,'0',0.0,0);
INSERT INTO `meat_sensortypes` VALUES (9,'100K3950',0.0033527432,0.00025977101,3.693425e-06,100.0,NULL,NULL,NULL,NULL,NULL,'0',0.0,0);
INSERT INTO `meat_sensortypes` VALUES (10,'Inkbird',0.0033552456,0.00025608666,1.9317204e-06,48.59,NULL,NULL,NULL,NULL,NULL,'0',0.0,0);
INSERT INTO `meat_sensortypes` VALUES (11,'5K3A1B',0.0033555,0.000257,2.43e-06,5.0,NULL,NULL,NULL,NULL,NULL,'0',0.0,0);
INSERT INTO `meat_sensortypes` VALUES (12,'ET-735',0.0033566373,0.00019664428,9.6248678e-07,998.9,NULL,NULL,NULL,NULL,NULL,'0',0.0,0);
INSERT INTO `meat_sensortypes` VALUES (13,'Acurite',0.0033555291,0.00025249073,2.5667292e-06,50.21,NULL,NULL,NULL,NULL,NULL,'0',0.0,0);
INSERT INTO `meat_sensortypes` VALUES (14,'ODC',0.0033538614,0.00022352517,2.4382337e-06,1000.0,NULL,NULL,NULL,NULL,NULL,'0',0.0,0);
INSERT INTO `meat_sensortypes` VALUES (15,'Santos',0.0033561093,0.00023552814,2.1375541e-06,200.82,NULL,NULL,NULL,NULL,NULL,'0',0.0,0);
INSERT INTO `meat_sensortypes` VALUES (16,'SmokeMAX',0.0033561946,0.0002550005,2.5976258e-06,99.645,NULL,NULL,NULL,NULL,NULL,'0',0.0,0);
INSERT INTO `meat_sensortypes` VALUES (17,'Weber_6743',0.0033558796,0.00027111149,3.1838428e-06,102.315,NULL,NULL,NULL,NULL,NULL,'0',0.0,0);
INSERT INTO `meat_sensortypes` VALUES (18,'LEM-HO6P-AC',NULL,NULL,NULL,NULL,'AC',1.6672,76.67,2,100,'0',0.0,0);
INSERT INTO `meat_sensortypes` VALUES (19,'LEM-HO6P-DC',NULL,NULL,NULL,NULL,'DC',1.6672,76.67,2,50,'0',0.0,0);
DROP TABLE IF EXISTS `light_status`;
CREATE TABLE IF NOT EXISTS `light_status` (
	`id`	INTEGER NOT NULL DEFAULT 0 PRIMARY KEY AUTOINCREMENT,
	`value`	REAL NOT NULL DEFAULT 0,
	`last_change`	INTEGER NOT NULL DEFAULT 0,
	`key`	TEXT NOT NULL DEFAULT 0
);
DROP TABLE IF EXISTS `humidifier_status`;
CREATE TABLE IF NOT EXISTS `humidifier_status` (
	`id`	INTEGER NOT NULL DEFAULT 0 PRIMARY KEY AUTOINCREMENT,
	`value`	REAL NOT NULL DEFAULT 0,
	`last_change`	INTEGER NOT NULL DEFAULT 0,
	`key`	TEXT NOT NULL DEFAULT 0
);
DROP TABLE IF EXISTS `heater_status`;
CREATE TABLE IF NOT EXISTS `heater_status` (
	`id`	INTEGER NOT NULL DEFAULT 0 PRIMARY KEY AUTOINCREMENT,
	`value`	REAL NOT NULL DEFAULT 0,
	`last_change`	INTEGER NOT NULL DEFAULT 0,
	`key`	TEXT NOT NULL DEFAULT 0
);
DROP TABLE IF EXISTS `exhaust_air_status`;
CREATE TABLE IF NOT EXISTS `exhaust_air_status` (
	`id`	INTEGER NOT NULL DEFAULT 0 PRIMARY KEY AUTOINCREMENT,
	`value`	REAL NOT NULL DEFAULT 0,
	`last_change`	INTEGER NOT NULL DEFAULT 0,
	`key`	TEXT NOT NULL DEFAULT 0
);
DROP TABLE IF EXISTS `dehumidifier_status`;
CREATE TABLE IF NOT EXISTS `dehumidifier_status` (
	`id`	INTEGER NOT NULL DEFAULT 0 PRIMARY KEY AUTOINCREMENT,
	`value`	REAL NOT NULL DEFAULT 0,
	`last_change`	INTEGER NOT NULL DEFAULT 0,
	`key`	TEXT NOT NULL DEFAULT 0
);
DROP TABLE IF EXISTS `debug`;
CREATE TABLE IF NOT EXISTS `debug` (
	`id`	INTEGER NOT NULL DEFAULT 0 PRIMARY KEY AUTOINCREMENT,
	`key`	TEXT NOT NULL DEFAULT 0,
	`value`	REAL NOT NULL DEFAULT 0,
	`last_change`	INTEGER NOT NULL DEFAULT 0
);
INSERT INTO `debug` VALUES (1,'measuring_interval_debug',30.0,0);
INSERT INTO `debug` VALUES (2,'agingtable_days_in_seconds_debug',86400.0,0);
INSERT INTO `debug` VALUES (3,'loglevel_file',20.0,0);
INSERT INTO `debug` VALUES (4,'loglevel_console',20.0,0);
DROP TABLE IF EXISTS `current_values`;
CREATE TABLE IF NOT EXISTS `current_values` (
	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`key`	TEXT NOT NULL,
	`value`	REAL,
	`last_change`	INTEGER NOT NULL
);
INSERT INTO `current_values` VALUES (1,'sensor_temperature',20.97,1621504043);
INSERT INTO `current_values` VALUES (2,'sensor_humidity',43.68,1621504043);
INSERT INTO `current_values` VALUES (3,'status_circulating_air',0.0,1621504043);
INSERT INTO `current_values` VALUES (4,'status_cooling_compressor',0.0,1621504043);
INSERT INTO `current_values` VALUES (5,'status_exhaust_air',0.0,1621504043);
INSERT INTO `current_values` VALUES (6,'status_heater',0.0,1621504043);
INSERT INTO `current_values` VALUES (7,'status_light',0.0,1621504043);
INSERT INTO `current_values` VALUES (8,'status_uv',0.0,1621504043);
INSERT INTO `current_values` VALUES (9,'status_humidifier',0.0,1621504043);
INSERT INTO `current_values` VALUES (10,'status_dehumidifier',0.0,1621504043);
INSERT INTO `current_values` VALUES (11,'scale1',-518.86,1621504017);
INSERT INTO `current_values` VALUES (12,'scale2',-491.908,1621504021);
INSERT INTO `current_values` VALUES (13,'status_piager',0.0,1621504027);
INSERT INTO `current_values` VALUES (14,'status_agingtable',0.0,1621504027);
INSERT INTO `current_values` VALUES (15,'status_scale1',0.0,1621504017);
INSERT INTO `current_values` VALUES (16,'status_scale2',0.0,1621504022);
INSERT INTO `current_values` VALUES (17,'status_tara_scale1',0.0,1621498334);
INSERT INTO `current_values` VALUES (18,'status_tara_scale2',0.0,1621498334);
INSERT INTO `current_values` VALUES (19,'agingtable_period',0.0,1614454803);
INSERT INTO `current_values` VALUES (20,'agingtable_period_starttime',1614454563.0,1614454564);
INSERT INTO `current_values` VALUES (21,'status_light_manual',0.0,1608641257);
INSERT INTO `current_values` VALUES (22,'calibrate_scale1',0.0,1621498334);
INSERT INTO `current_values` VALUES (23,'calibrate_scale2',0.0,1621498334);
INSERT INTO `current_values` VALUES (24,'calibrate_weight',0.0,1621498253);
INSERT INTO `current_values` VALUES (25,'status_uv_manual',0.0,1608641257);
INSERT INTO `current_values` VALUES (26,'temperature_meat1',NULL,1621504043);
INSERT INTO `current_values` VALUES (27,'temperature_meat2',NULL,1621504043);
INSERT INTO `current_values` VALUES (28,'temperature_meat3',NULL,1621504043);
INSERT INTO `current_values` VALUES (29,'temperature_meat4',NULL,1621504043);
INSERT INTO `current_values` VALUES (30,'sensor_dewpoint',8.15,1621504043);
INSERT INTO `current_values` VALUES (31,'sensor_extern_temperature',23.31,1611420926);
INSERT INTO `current_values` VALUES (32,'sensor_extern_humidity',38.79,1611420926);
INSERT INTO `current_values` VALUES (33,'sensor_extern_dewpoint',8.5,1611420926);
INSERT INTO `current_values` VALUES (34,'agingtable_period_day',1.0,1614454803);
INSERT INTO `current_values` VALUES (35,'scale1_thread_alive',1.0,1621498334);
INSERT INTO `current_values` VALUES (36,'scale2_thread_alive',1.0,1621498334);
INSERT INTO `current_values` VALUES (37,'aging_thread_alive',1.0,1621498334);
DROP TABLE IF EXISTS `cooling_compressor_status`;
CREATE TABLE IF NOT EXISTS `cooling_compressor_status` (
	`id`	INTEGER NOT NULL DEFAULT 0 PRIMARY KEY AUTOINCREMENT,
	`value`	REAL NOT NULL DEFAULT 0,
	`last_change`	INTEGER NOT NULL DEFAULT 0,
	`key`	TEXT NOT NULL DEFAULT 0
);
DROP TABLE IF EXISTS `config_telegram`;
CREATE TABLE IF NOT EXISTS `config_telegram` (
	`id`	INTEGER,
	`bot_token`	TEXT NOT NULL,
	`bot_chatID`	TEXT NOT NULL,
	`active`	INTEGER
);
DROP TABLE IF EXISTS `config_pushover`;
CREATE TABLE IF NOT EXISTS `config_pushover` (
	`id`	INTEGER,
	`user_key`	TEXT NOT NULL,
	`api_token`	TEXT NOT NULL,
	`active`	INTEGER
);
DROP TABLE IF EXISTS `config_nfs_backup`;
CREATE TABLE IF NOT EXISTS `config_nfs_backup` (
	`id`	INTEGER,
	`nfsvol`	TEXT,
	`subdir`	TEXT,
	`nfsmount`	TEXT,
	`backup_path`	TEXT,
	`number_of_backups`	INTEGER,
	`backup_name`	TEXT,
	`nfsopt`	TEXT,
	`active`	INTEGER,
	PRIMARY KEY(`id`)
);
INSERT INTO `config_nfs_backup` VALUES (1,'192.168.144.200:/volume1/backups','pi-ager','/home/pi/backup','/home/pi/backup/pi-ager',2,'pi_ager_backup','soft',1);
DROP TABLE IF EXISTS `config_messenger_exception`;
CREATE TABLE IF NOT EXISTS `config_messenger_exception` (
	`exception`	TEXT NOT NULL,
	`e-mail`	INTEGER,
	`pushover`	INTEGER,
	`telegram`	INTEGER,
	`alarm`	TEXT,
	`raise_exception`	INTEGER,
	`active`	INTEGER,
	`id`	INTEGER NOT NULL,
	PRIMARY KEY(`id`)
);
INSERT INTO `config_messenger_exception` VALUES ('cx_sensor_not_defined',0,0,0,'short',0,1,1);
INSERT INTO `config_messenger_exception` VALUES ('OperationalError',0,0,0,'short',1,1,2);
INSERT INTO `config_messenger_exception` VALUES ('FileNotFoundError',0,0,0,'short',1,1,3);
INSERT INTO `config_messenger_exception` VALUES ('OSError',0,0,0,'short',0,1,4);
INSERT INTO `config_messenger_exception` VALUES ('cx_measurement_error',0,0,0,'short',0,1,5);
DROP TABLE IF EXISTS `config_messenger_event`;
CREATE TABLE IF NOT EXISTS `config_messenger_event` (
	`event`	TEXT NOT NULL,
	`e-mail`	INTEGER,
	`pushover`	INTEGER,
	`telegram`	INTEGER,
	`alarm`	TEXT,
	`event_text`	TEXT,
	`active`	INTEGER,
	`id`	INTEGER NOT NULL,
	PRIMARY KEY(`id`)
);
INSERT INTO `config_messenger_event` VALUES ('Pi-Ager_started',0,0,0,' ','Test  Event Pi-Ager Zero',0,1);
DROP TABLE IF EXISTS `config_email_server`;
CREATE TABLE IF NOT EXISTS `config_email_server` (
	`id`	INTEGER NOT NULL,
	`server`	TEXT NOT NULL,
	`user`	TEXT NOT NULL,
	`password`	TEXT NOT NULL,
	`port`	INTEGER NOT NULL
);
DROP TABLE IF EXISTS `config_email_recipient`;
CREATE TABLE IF NOT EXISTS `config_email_recipient` (
	`id`	INTEGER NOT NULL,
	`to_mail`	TEXT NOT NULL,
	`active`	INTEGER,
	PRIMARY KEY(`id`)
);
DROP TABLE IF EXISTS `config_alarm`;
CREATE TABLE IF NOT EXISTS `config_alarm` (
	`id`	INTEGER NOT NULL,
	`alarm`	TEXT NOT NULL DEFAULT ('short'),
	`replication`	INTEGER DEFAULT (3),
	`sleep`	REAL DEFAULT (0.5),
	`high_time`	REAL DEFAULT (0.5),
	`low_time`	REAL DEFAULT (0.5),
	`waveform`	TEXT,
	`frequency`	REAL,
	PRIMARY KEY(`id`)
);
INSERT INTO `config_alarm` VALUES (1,'short',3,0.5,0.5,0.5,'',NULL);
DROP TABLE IF EXISTS `config`;
CREATE TABLE IF NOT EXISTS `config` (
	`id`	INTEGER NOT NULL DEFAULT 0 PRIMARY KEY AUTOINCREMENT,
	`key`	TEXT NOT NULL DEFAULT 0,
	`value`	REAL NOT NULL DEFAULT 0,
	`last_change`	INTEGER NOT NULL DEFAULT 0
);
INSERT INTO `config` VALUES (1,'switch_on_cooling_compressor',2.0,1604862198);
INSERT INTO `config` VALUES (2,'switch_off_cooling_compressor',0.0,1604862198);
INSERT INTO `config` VALUES (3,'switch_on_humidifier',20.0,1604862198);
INSERT INTO `config` VALUES (4,'switch_off_humidifier',0.0,1604862198);
INSERT INTO `config` VALUES (5,'delay_humidify',5.0,1604862198);
INSERT INTO `config` VALUES (6,'sensortype',5.0,1582013839);
INSERT INTO `config` VALUES (7,'language',2.0,1617699411);
INSERT INTO `config` VALUES (8,'switch_on_light_hour',12.0,1604862198);
INSERT INTO `config` VALUES (9,'switch_on_light_minute',30.0,1604862198);
INSERT INTO `config` VALUES (10,'light_duration',60.0,1604862198);
INSERT INTO `config` VALUES (11,'light_period',21600.0,1604862198);
INSERT INTO `config` VALUES (12,'light_modus',0.0,1604862198);
INSERT INTO `config` VALUES (13,'switch_on_uv_hour',11.0,1604862198);
INSERT INTO `config` VALUES (14,'switch_on_uv_minute',30.0,1604862198);
INSERT INTO `config` VALUES (15,'uv_duration',60.0,1604862198);
INSERT INTO `config` VALUES (16,'uv_period',21600.0,1604862198);
INSERT INTO `config` VALUES (17,'uv_modus',0.0,1604862198);
INSERT INTO `config` VALUES (18,'dehumidifier_modus',1.0,1604862198);
INSERT INTO `config` VALUES (19,'circulation_air_period',3600.0,1617699188);
INSERT INTO `config` VALUES (20,'setpoint_temperature',21.0,1617699188);
INSERT INTO `config` VALUES (21,'exhaust_air_duration',900.0,1617699188);
INSERT INTO `config` VALUES (22,'modus',1.0,1617699188);
INSERT INTO `config` VALUES (23,'setpoint_humidity',93.0,1617699188);
INSERT INTO `config` VALUES (24,'exhaust_air_period',21600.0,1617699188);
INSERT INTO `config` VALUES (25,'circulation_air_duration',900.0,1617699188);
INSERT INTO `config` VALUES (26,'agingtable',1.0,1610707601);
INSERT INTO `config` VALUES (27,'failure_humidity_delta',10.0,0);
INSERT INTO `config` VALUES (28,'failure_temperature_delta',4.0,0);
INSERT INTO `config` VALUES (29,'samples_refunit_tara',20.0,0);
INSERT INTO `config` VALUES (30,'spikes_refunit_tara',4.0,0);
INSERT INTO `config` VALUES (31,'save_temperature_humidity_loops',15.0,0);
INSERT INTO `config` VALUES (32,'sensorbus',0.0,0);
INSERT INTO `config` VALUES (33,'meat1_sensortype',0.0,1617699411);
INSERT INTO `config` VALUES (34,'meat2_sensortype',0.0,1617699411);
INSERT INTO `config` VALUES (35,'meat3_sensortype',0.0,1617699411);
INSERT INTO `config` VALUES (36,'meat4_sensortype',0.0,1617699411);
INSERT INTO `config` VALUES (37,'customtime_for_diagrams',7200.0,0);
INSERT INTO `config` VALUES (38,'secondsensortype',0.0,0);
INSERT INTO `config` VALUES (39,'agingtable_startperiod',1.0,1613305041);
INSERT INTO `config` VALUES (40,'agingtable_startday',1.0,1613305041);
DROP TABLE IF EXISTS `circulating_air_status`;
CREATE TABLE IF NOT EXISTS `circulating_air_status` (
	`id`	INTEGER NOT NULL DEFAULT 0 PRIMARY KEY AUTOINCREMENT,
	`value`	REAL NOT NULL DEFAULT 0,
	`last_change`	INTEGER NOT NULL DEFAULT 0,
	`key`	TEXT NOT NULL DEFAULT 0
);
DROP TABLE IF EXISTS `all_sensors`;
CREATE TABLE IF NOT EXISTS `all_sensors` (
	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`tempint`	REAL DEFAULT NULL,
	`tempext`	REAL DEFAULT NULL,
	`humint`	REAL DEFAULT NULL,
	`humext`	REAL DEFAULT NULL,
	`dewint`	REAL DEFAULT NULL,
	`dewext`	REAL DEFAULT NULL,
	`humintabs`	REAL DEFAULT NULL,
	`humextabs`	REAL DEFAULT NULL,
	`ntc1`	REAL DEFAULT NULL,
	`ntc2`	REAL DEFAULT NULL,
	`ntc3`	REAL DEFAULT NULL,
	`ntc4`	REAL DEFAULT NULL,
	`last_change`	INTEGER NOT NULL
);
DROP TABLE IF EXISTS `all_scales`;
CREATE TABLE IF NOT EXISTS `all_scales` (
	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`scale1`	REAL DEFAULT NULL,
	`scale2`	REAL DEFAULT NULL,
	`last_change`	INTEGER NOT NULL
);
DROP TABLE IF EXISTS `agingtables`;
CREATE TABLE IF NOT EXISTS `agingtables` (
	`id`	INTEGER NOT NULL DEFAULT 0 PRIMARY KEY AUTOINCREMENT,
	`name`	TEXT NOT NULL DEFAULT 0
);
INSERT INTO `agingtables` VALUES (1,'salami');
INSERT INTO `agingtables` VALUES (2,'dryaging1');
INSERT INTO `agingtables` VALUES (3,'dryaging2');
DROP TABLE IF EXISTS `agingtable_salami1`;
CREATE TABLE IF NOT EXISTS `agingtable_salami1` (
	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`modus`	INTEGER,
	`setpoint_humidity`	INTEGER,
	`setpoint_temperature`	INTEGER,
	`circulation_air_duration`	INTEGER,
	`circulation_air_period`	INTEGER,
	`exhaust_air_duration`	INTEGER,
	`exhaust_air_period`	INTEGER,
	`days`	INTEGER,
	`comment`	TEXT
);
INSERT INTO `agingtable_salami1` VALUES (1,4,93,18,900,3600,300,21600,1,'Erstversuch Salami');
INSERT INTO `agingtable_salami1` VALUES (2,NULL,NULL,23,NULL,NULL,NULL,NULL,1,NULL);
INSERT INTO `agingtable_salami1` VALUES (3,NULL,92,22,NULL,NULL,NULL,NULL,2,NULL);
INSERT INTO `agingtable_salami1` VALUES (4,NULL,91,20,NULL,NULL,NULL,NULL,2,NULL);
INSERT INTO `agingtable_salami1` VALUES (5,NULL,90,18,NULL,5400,NULL,NULL,2,NULL);
INSERT INTO `agingtable_salami1` VALUES (6,NULL,89,16,NULL,NULL,NULL,NULL,2,NULL);
INSERT INTO `agingtable_salami1` VALUES (7,NULL,88,15,NULL,NULL,NULL,NULL,1,NULL);
INSERT INTO `agingtable_salami1` VALUES (8,NULL,87,NULL,NULL,NULL,NULL,NULL,1,NULL);
INSERT INTO `agingtable_salami1` VALUES (9,NULL,86,14,NULL,NULL,NULL,NULL,1,NULL);
INSERT INTO `agingtable_salami1` VALUES (10,NULL,85,13,NULL,NULL,NULL,NULL,1,NULL);
INSERT INTO `agingtable_salami1` VALUES (11,NULL,80,12,NULL,7200,NULL,NULL,7,NULL);
INSERT INTO `agingtable_salami1` VALUES (12,NULL,75,NULL,NULL,10800,NULL,NULL,7,NULL);
DROP TABLE IF EXISTS `agingtable_salami`;
CREATE TABLE IF NOT EXISTS `agingtable_salami` (
	`id`	INTEGER NOT NULL DEFAULT 0,
	`modus`	INTEGER,
	`setpoint_humidity`	INTEGER,
	`setpoint_temperature`	INTEGER,
	`circulation_air_duration`	INTEGER,
	`circulation_air_period`	INTEGER,
	`exhaust_air_duration`	INTEGER,
	`exhaust_air_period`	INTEGER,
	`days`	INTEGER NOT NULL DEFAULT 0,
	`comment`	TEXT,
	PRIMARY KEY(`id`)
);
INSERT INTO `agingtable_salami` VALUES (1,4,93,21,900,3600,900,21600,1,NULL);
INSERT INTO `agingtable_salami` VALUES (2,NULL,NULL,20,NULL,NULL,NULL,NULL,1,NULL);
INSERT INTO `agingtable_salami` VALUES (3,NULL,92,19,NULL,NULL,NULL,NULL,2,NULL);
INSERT INTO `agingtable_salami` VALUES (4,NULL,91,18,NULL,NULL,NULL,NULL,2,NULL);
INSERT INTO `agingtable_salami` VALUES (5,NULL,90,17,NULL,5400,NULL,NULL,2,NULL);
INSERT INTO `agingtable_salami` VALUES (6,NULL,89,16,NULL,NULL,NULL,NULL,2,NULL);
INSERT INTO `agingtable_salami` VALUES (7,NULL,88,15,NULL,NULL,NULL,NULL,1,NULL);
INSERT INTO `agingtable_salami` VALUES (8,NULL,87,NULL,NULL,NULL,NULL,NULL,1,NULL);
INSERT INTO `agingtable_salami` VALUES (9,NULL,86,14,NULL,NULL,NULL,NULL,1,NULL);
INSERT INTO `agingtable_salami` VALUES (10,NULL,85,13,NULL,NULL,NULL,NULL,1,NULL);
INSERT INTO `agingtable_salami` VALUES (11,NULL,80,12,NULL,7200,NULL,NULL,7,NULL);
INSERT INTO `agingtable_salami` VALUES (12,NULL,75,NULL,NULL,10800,NULL,NULL,7,NULL);
DROP TABLE IF EXISTS `agingtable_dryaging2`;
CREATE TABLE IF NOT EXISTS `agingtable_dryaging2` (
	`id`	INTEGER NOT NULL DEFAULT 0,
	`modus`	INTEGER,
	`setpoint_humidity`	INTEGER,
	`setpoint_temperature`	INTEGER,
	`circulation_air_duration`	INTEGER,
	`circulation_air_period`	INTEGER,
	`exhaust_air_duration`	INTEGER,
	`exhaust_air_period`	INTEGER,
	`days`	INTEGER NOT NULL DEFAULT 0,
	`comment`	TEXT,
	PRIMARY KEY(`id`)
);
INSERT INTO `agingtable_dryaging2` VALUES (1,4,85,2,1440,2160,900,12960,12,'Testtabelle');
INSERT INTO `agingtable_dryaging2` VALUES (2,NULL,30,4,2520,1080,NULL,6480,8,NULL);
INSERT INTO `agingtable_dryaging2` VALUES (3,NULL,70,NULL,1080,2520,NULL,15120,8,NULL);
INSERT INTO `agingtable_dryaging2` VALUES (4,NULL,60,NULL,720,2880,NULL,17280,8,NULL);
INSERT INTO `agingtable_dryaging2` VALUES (5,NULL,45,7,648,2952,NULL,17712,12,NULL);
INSERT INTO `agingtable_dryaging2` VALUES (6,NULL,33,NULL,540,3060,NULL,18360,8,'');
DROP TABLE IF EXISTS `agingtable_dryaging1`;
CREATE TABLE IF NOT EXISTS `agingtable_dryaging1` (
	`id`	INTEGER NOT NULL DEFAULT 0,
	`modus`	INTEGER,
	`setpoint_humidity`	INTEGER,
	`setpoint_temperature`	INTEGER,
	`circulation_air_duration`	INTEGER,
	`circulation_air_period`	INTEGER,
	`exhaust_air_duration`	INTEGER,
	`exhaust_air_period`	INTEGER,
	`days`	INTEGER NOT NULL DEFAULT 0,
	`comment`	TEXT,
	PRIMARY KEY(`id`)
);
INSERT INTO `agingtable_dryaging1` VALUES (1,4,85,2,1440,2160,900,12960,12,'Testtabelle');
INSERT INTO `agingtable_dryaging1` VALUES (2,NULL,30,4,2520,1080,NULL,6480,8,NULL);
INSERT INTO `agingtable_dryaging1` VALUES (3,NULL,70,NULL,1080,2520,NULL,15120,8,NULL);
INSERT INTO `agingtable_dryaging1` VALUES (4,NULL,60,NULL,720,2880,NULL,17280,8,NULL);
INSERT INTO `agingtable_dryaging1` VALUES (5,NULL,45,7,648,2952,NULL,17712,12,NULL);
INSERT INTO `agingtable_dryaging1` VALUES (6,NULL,33,NULL,540,3060,NULL,18360,8,NULL);
INSERT INTO `agingtable_dryaging1` VALUES (7,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'');
DROP TABLE IF EXISTS ``;
CREATE TABLE IF NOT EXISTS `` (
	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`name`	TEXT NOT NULL
);
INSERT INTO `` VALUES (1,'salami');
INSERT INTO `` VALUES (2,'dryaging1');
INSERT INTO `` VALUES (3,'dryaging2');
DROP INDEX IF EXISTS `all_sensors_index`;
CREATE INDEX IF NOT EXISTS `all_sensors_index` ON `all_sensors` (
	`last_change`	ASC
);
DROP INDEX IF EXISTS `all_scales_index`;
CREATE INDEX IF NOT EXISTS `all_scales_index` ON `all_scales` (
	`last_change`	ASC
);
COMMIT;
