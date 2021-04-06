----
-- phpLiteAdmin database dump (https://www.phpliteadmin.org/)
-- phpLiteAdmin version: 1.9.8.2
-- Exported: 11:37am on April 6, 2021 (CEST)
-- database file: ./config/pi-ager.sqlite3
----
BEGIN TRANSACTION;

----
-- Drop table for agingtable_salami1
----
DROP TABLE IF EXISTS "agingtable_salami1";

----
-- Table structure for agingtable_salami1
----
CREATE TABLE agingtable_salami1 (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, modus INTEGER NULL, setpoint_humidity INTEGER NULL, setpoint_temperature INTEGER NULL, circulation_air_duration INTEGER NULL, circulation_air_period INTEGER NULL, exhaust_air_duration INTEGER NULL, exhaust_air_period INTEGER NULL, days INTEGER NULL, comment TEXT NULL);

----
-- Data dump for agingtable_salami1, a total of 12 rows
----
INSERT INTO "agingtable_salami1" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","days","comment") VALUES ('1','4','93','18','900','3600','300','21600','1','Erstversuch Salami');
INSERT INTO "agingtable_salami1" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","days","comment") VALUES ('2',NULL,NULL,'23',NULL,NULL,NULL,NULL,'1',NULL);
INSERT INTO "agingtable_salami1" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","days","comment") VALUES ('3',NULL,'92','22',NULL,NULL,NULL,NULL,'2',NULL);
INSERT INTO "agingtable_salami1" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","days","comment") VALUES ('4',NULL,'91','20',NULL,NULL,NULL,NULL,'2',NULL);
INSERT INTO "agingtable_salami1" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","days","comment") VALUES ('5',NULL,'90','18',NULL,'5400',NULL,NULL,'2',NULL);
INSERT INTO "agingtable_salami1" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","days","comment") VALUES ('6',NULL,'89','16',NULL,NULL,NULL,NULL,'2',NULL);
INSERT INTO "agingtable_salami1" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","days","comment") VALUES ('7',NULL,'88','15',NULL,NULL,NULL,NULL,'1',NULL);
INSERT INTO "agingtable_salami1" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","days","comment") VALUES ('8',NULL,'87',NULL,NULL,NULL,NULL,NULL,'1',NULL);
INSERT INTO "agingtable_salami1" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","days","comment") VALUES ('9',NULL,'86','14',NULL,NULL,NULL,NULL,'1',NULL);
INSERT INTO "agingtable_salami1" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","days","comment") VALUES ('10',NULL,'85','13',NULL,NULL,NULL,NULL,'1',NULL);
INSERT INTO "agingtable_salami1" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","days","comment") VALUES ('11',NULL,'80','12',NULL,'7200',NULL,NULL,'7',NULL);
INSERT INTO "agingtable_salami1" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","days","comment") VALUES ('12',NULL,'75',NULL,NULL,'10800',NULL,NULL,'7',NULL);

----
-- Drop table for cooling_compressor_status
----
DROP TABLE IF EXISTS "cooling_compressor_status";

----
-- Table structure for cooling_compressor_status
----
CREATE TABLE "cooling_compressor_status" ('id' INTEGER DEFAULT 0 PRIMARY KEY AUTOINCREMENT NOT NULL, 'value' REAL DEFAULT 0 NOT NULL, 'last_change' INTEGER DEFAULT 0 NOT NULL, key TEXT DEFAULT 0 NOT NULL);

----
-- Data dump for cooling_compressor_status, a total of 1 rows
----
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('35','0.0','1617701125','0');

----
-- Drop table for heater_status
----
DROP TABLE IF EXISTS "heater_status";

----
-- Table structure for heater_status
----
CREATE TABLE "heater_status" ('id' INTEGER DEFAULT 0 PRIMARY KEY AUTOINCREMENT NOT NULL, 'value' REAL DEFAULT 0 NOT NULL, 'last_change' INTEGER DEFAULT 0 NOT NULL, key TEXT DEFAULT 0 NOT NULL);

----
-- Data dump for heater_status, a total of 1 rows
----
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('51','0.0','1617701125','0');

----
-- Drop table for circulating_air_status
----
DROP TABLE IF EXISTS "circulating_air_status";

----
-- Table structure for circulating_air_status
----
CREATE TABLE "circulating_air_status" ('id' INTEGER DEFAULT 0 PRIMARY KEY AUTOINCREMENT NOT NULL, 'value' REAL DEFAULT 0 NOT NULL, 'last_change' INTEGER DEFAULT 0 NOT NULL, key TEXT DEFAULT 0 NOT NULL);

----
-- Data dump for circulating_air_status, a total of 1 rows
----
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('57','0.0','1617701125','0');

----
-- Drop table for exhaust_air_status
----
DROP TABLE IF EXISTS "exhaust_air_status";

----
-- Table structure for exhaust_air_status
----
CREATE TABLE "exhaust_air_status" ('id' INTEGER DEFAULT 0 PRIMARY KEY AUTOINCREMENT NOT NULL, 'value' REAL DEFAULT 0 NOT NULL, 'last_change' INTEGER DEFAULT 0 NOT NULL, key TEXT DEFAULT 0 NOT NULL);

----
-- Data dump for exhaust_air_status, a total of 1 rows
----
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('548','0.0','1617701125','0');

----
-- Drop table for light_status
----
DROP TABLE IF EXISTS "light_status";

----
-- Table structure for light_status
----
CREATE TABLE "light_status" ('id' INTEGER DEFAULT 0 PRIMARY KEY AUTOINCREMENT NOT NULL, 'value' REAL DEFAULT 0 NOT NULL, 'last_change' INTEGER DEFAULT 0 NOT NULL, key TEXT DEFAULT 0 NOT NULL);

----
-- Data dump for light_status, a total of 1 rows
----
INSERT INTO "light_status" ("id","value","last_change","key") VALUES ('2','0.0','1617701126','0');

----
-- Drop table for uv_status
----
DROP TABLE IF EXISTS "uv_status";

----
-- Table structure for uv_status
----
CREATE TABLE "uv_status" ('id' INTEGER DEFAULT 0 PRIMARY KEY AUTOINCREMENT NOT NULL, 'value' REAL DEFAULT 0 NOT NULL, 'last_change' INTEGER DEFAULT 0 NOT NULL, key TEXT DEFAULT 0 NOT NULL);

----
-- Data dump for uv_status, a total of 1 rows
----
INSERT INTO "uv_status" ("id","value","last_change","key") VALUES ('2','0.0','1617701125','0');

----
-- Drop table for humidifier_status
----
DROP TABLE IF EXISTS "humidifier_status";

----
-- Table structure for humidifier_status
----
CREATE TABLE "humidifier_status" ('id' INTEGER DEFAULT 0 PRIMARY KEY AUTOINCREMENT NOT NULL, 'value' REAL DEFAULT 0 NOT NULL, 'last_change' INTEGER DEFAULT 0 NOT NULL, key TEXT DEFAULT 0 NOT NULL);

----
-- Data dump for humidifier_status, a total of 1 rows
----
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('117','0.0','1617701126','0');

----
-- Drop table for dehumidifier_status
----
DROP TABLE IF EXISTS "dehumidifier_status";

----
-- Table structure for dehumidifier_status
----
CREATE TABLE "dehumidifier_status" ('id' INTEGER DEFAULT 0 PRIMARY KEY AUTOINCREMENT NOT NULL, 'value' REAL DEFAULT 0 NOT NULL, 'last_change' INTEGER DEFAULT 0 NOT NULL, key TEXT DEFAULT 0 NOT NULL);

----
-- Data dump for dehumidifier_status, a total of 1 rows
----
INSERT INTO "dehumidifier_status" ("id","value","last_change","key") VALUES ('2','0.0','1617701126','0');

----
-- Drop table for agingtable_dryaging2
----
DROP TABLE IF EXISTS "agingtable_dryaging2";

----
-- Table structure for agingtable_dryaging2
----
CREATE TABLE 'agingtable_dryaging2' ( 'id' INTEGER DEFAULT 0 PRIMARY KEY NOT NULL , 'modus' INTEGER, 'setpoint_humidity' INTEGER, 'setpoint_temperature' INTEGER, 'circulation_air_duration' INTEGER,'circulation_air_period' INTEGER, 'exhaust_air_duration' INTEGER, 'exhaust_air_period' INTEGER, 'days' INTEGER DEFAULT 0 NOT NULL, 'comment' TEXT);

----
-- Data dump for agingtable_dryaging2, a total of 6 rows
----
INSERT INTO "agingtable_dryaging2" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","days","comment") VALUES ('1','4','85','2','1440','2160','900','12960','12','Testtabelle');
INSERT INTO "agingtable_dryaging2" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","days","comment") VALUES ('2',NULL,'30','4','2520','1080',NULL,'6480','8',NULL);
INSERT INTO "agingtable_dryaging2" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","days","comment") VALUES ('3',NULL,'70',NULL,'1080','2520',NULL,'15120','8',NULL);
INSERT INTO "agingtable_dryaging2" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","days","comment") VALUES ('4',NULL,'60',NULL,'720','2880',NULL,'17280','8',NULL);
INSERT INTO "agingtable_dryaging2" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","days","comment") VALUES ('5',NULL,'45','7','648','2952',NULL,'17712','12',NULL);
INSERT INTO "agingtable_dryaging2" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","days","comment") VALUES ('6',NULL,'33',NULL,'540','3060',NULL,'18360','8','');

----
-- Drop table for agingtable_salami
----
DROP TABLE IF EXISTS "agingtable_salami";

----
-- Table structure for agingtable_salami
----
CREATE TABLE 'agingtable_salami' ( 'id' INTEGER DEFAULT 0 PRIMARY KEY NOT NULL , 'modus' INTEGER, 'setpoint_humidity' INTEGER, 'setpoint_temperature' INTEGER, 'circulation_air_duration' INTEGER,'circulation_air_period' INTEGER, 'exhaust_air_duration' INTEGER, 'exhaust_air_period' INTEGER, 'days' INTEGER DEFAULT 0 NOT NULL, 'comment' TEXT);

----
-- Data dump for agingtable_salami, a total of 12 rows
----
INSERT INTO "agingtable_salami" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","days","comment") VALUES ('1','4','93','21','900','3600','900','21600','1',NULL);
INSERT INTO "agingtable_salami" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","days","comment") VALUES ('2',NULL,NULL,'20',NULL,NULL,NULL,NULL,'1',NULL);
INSERT INTO "agingtable_salami" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","days","comment") VALUES ('3',NULL,'92','19',NULL,NULL,NULL,NULL,'2',NULL);
INSERT INTO "agingtable_salami" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","days","comment") VALUES ('4',NULL,'91','18',NULL,NULL,NULL,NULL,'2',NULL);
INSERT INTO "agingtable_salami" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","days","comment") VALUES ('5',NULL,'90','17',NULL,'5400',NULL,NULL,'2',NULL);
INSERT INTO "agingtable_salami" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","days","comment") VALUES ('6',NULL,'89','16',NULL,NULL,NULL,NULL,'2',NULL);
INSERT INTO "agingtable_salami" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","days","comment") VALUES ('7',NULL,'88','15',NULL,NULL,NULL,NULL,'1',NULL);
INSERT INTO "agingtable_salami" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","days","comment") VALUES ('8',NULL,'87',NULL,NULL,NULL,NULL,NULL,'1',NULL);
INSERT INTO "agingtable_salami" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","days","comment") VALUES ('9',NULL,'86','14',NULL,NULL,NULL,NULL,'1',NULL);
INSERT INTO "agingtable_salami" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","days","comment") VALUES ('10',NULL,'85','13',NULL,NULL,NULL,NULL,'1',NULL);
INSERT INTO "agingtable_salami" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","days","comment") VALUES ('11',NULL,'80','12',NULL,'7200',NULL,NULL,'7',NULL);
INSERT INTO "agingtable_salami" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","days","comment") VALUES ('12',NULL,'75',NULL,NULL,'10800',NULL,NULL,'7',NULL);

----
-- Drop table for debug
----
DROP TABLE IF EXISTS "debug";

----
-- Table structure for debug
----
CREATE TABLE 'debug' ('id' INTEGER DEFAULT 0 PRIMARY KEY AUTOINCREMENT NOT NULL, 'key' TEXT DEFAULT 0 NOT NULL, 'value' REAL DEFAULT 0 NOT NULL, last_change INTEGER DEFAULT 0 NOT NULL);

----
-- Data dump for debug, a total of 4 rows
----
INSERT INTO "debug" ("id","key","value","last_change") VALUES ('1','measuring_interval_debug','30.0','0');
INSERT INTO "debug" ("id","key","value","last_change") VALUES ('2','agingtable_days_in_seconds_debug','86400.0','0');
INSERT INTO "debug" ("id","key","value","last_change") VALUES ('3','loglevel_file','10.0','0');
INSERT INTO "debug" ("id","key","value","last_change") VALUES ('4','loglevel_console','10.0','0');

----
-- Drop table for agingtables
----
DROP TABLE IF EXISTS "agingtables";

----
-- Table structure for agingtables
----
CREATE TABLE 'agingtables' ('id' INTEGER DEFAULT 0 PRIMARY KEY AUTOINCREMENT NOT NULL,'name' TEXT DEFAULT 0 NOT NULL);

----
-- Data dump for agingtables, a total of 3 rows
----
INSERT INTO "agingtables" ("id","name") VALUES ('1','salami');
INSERT INTO "agingtables" ("id","name") VALUES ('2','dryaging1');
INSERT INTO "agingtables" ("id","name") VALUES ('3','dryaging2');

----
-- Drop table for system
----
DROP TABLE IF EXISTS "system";

----
-- Table structure for system
----
CREATE TABLE 'system' ('id' INTEGER DEFAULT 0 PRIMARY KEY AUTOINCREMENT NOT NULL, 'key' TEXT DEFAULT 0 NOT NULL,'value' TEXT DEFAULT 0 NOT NULL, 'last_change' INTEGER DEFAULT 0 NOT NULL DEFAULT 0);

----
-- Data dump for system, a total of 2 rows
----
INSERT INTO "system" ("id","key","value","last_change") VALUES ('1','pi_revision','9000c1','1617699555');
INSERT INTO "system" ("id","key","value","last_change") VALUES ('3','pi_ager_version','3.3.0 pre','0');

----
-- Drop table for agingtable_dryaging1
----
DROP TABLE IF EXISTS "agingtable_dryaging1";

----
-- Table structure for agingtable_dryaging1
----
CREATE TABLE 'agingtable_dryaging1' ('id' INTEGER DEFAULT 0 PRIMARY KEY NOT NULL ,'modus' INTEGER, "setpoint_humidity" INTEGER, "setpoint_temperature" INTEGER, "circulation_air_duration" INTEGER,"circulation_air_period" INTEGER, "exhaust_air_duration" INTEGER, "exhaust_air_period" INTEGER, "days" INTEGER DEFAULT 0 NOT NULL, 'comment' TEXT);

----
-- Data dump for agingtable_dryaging1, a total of 7 rows
----
INSERT INTO "agingtable_dryaging1" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","days","comment") VALUES ('1','4','85','2','1440','2160','900','12960','12','Testtabelle');
INSERT INTO "agingtable_dryaging1" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","days","comment") VALUES ('2',NULL,'30','4','2520','1080',NULL,'6480','8',NULL);
INSERT INTO "agingtable_dryaging1" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","days","comment") VALUES ('3',NULL,'70',NULL,'1080','2520',NULL,'15120','8',NULL);
INSERT INTO "agingtable_dryaging1" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","days","comment") VALUES ('4',NULL,'60',NULL,'720','2880',NULL,'17280','8',NULL);
INSERT INTO "agingtable_dryaging1" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","days","comment") VALUES ('5',NULL,'45','7','648','2952',NULL,'17712','12',NULL);
INSERT INTO "agingtable_dryaging1" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","days","comment") VALUES ('6',NULL,'33',NULL,'540','3060',NULL,'18360','8',NULL);
INSERT INTO "agingtable_dryaging1" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","days","comment") VALUES ('7',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1','');

----
-- Drop table for config_email_recipient
----
DROP TABLE IF EXISTS "config_email_recipient";

----
-- Table structure for config_email_recipient
----
CREATE TABLE "config_email_recipient" (
    "id" INTEGER NOT NULL,
    "to_mail" TEXT NOT NULL,
    "active" INTEGER
);

----
-- Data dump for config_email_recipient, a total of 1 rows
----
INSERT INTO "config_email_recipient" ("id","to_mail","active") VALUES ('1','max.mustermann@provider.de','0');

----
-- Drop table for meat_sensortypes
----
DROP TABLE IF EXISTS "meat_sensortypes";

----
-- Table structure for meat_sensortypes
----
CREATE TABLE 'meat_sensortypes' ('id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 'name' TEXT DEFAULT NULL, 'a' REAL DEFAULT NULL, 'b' REAL DEFAULT NULL, 'c' REAL DEFAULT NULL, 'Rn' REAL DEFAULT NULL, 'Mode' TEXT DEFAULT NULL, 'RefVoltage' REAL DEFAULT NULL, 'Sensitivity' REAL DEFAULT NULL, 'Turns' INTEGER DEFAULT NULL, 'nAverage' INTEGER DEFAULT NULL, key TEXT DEFAULT 0 NOT NULL, value REAL DEFAULT 0 NOT NULL, last_change INTEGER DEFAULT 0 NOT NULL);

----
-- Data dump for meat_sensortypes, a total of 20 rows
----
INSERT INTO "meat_sensortypes" ("id","name","a","b","c","Rn","Mode","RefVoltage","Sensitivity","Turns","nAverage","key","value","last_change") VALUES ('0','------',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0','0.0','0');
INSERT INTO "meat_sensortypes" ("id","name","a","b","c","Rn","Mode","RefVoltage","Sensitivity","Turns","nAverage","key","value","last_change") VALUES ('1','Fantast','0.003355834','0.00025698192','1.6391056e-06','50.08',NULL,NULL,NULL,NULL,NULL,'0','0.0','0');
INSERT INTO "meat_sensortypes" ("id","name","a","b","c","Rn","Mode","RefVoltage","Sensitivity","Turns","nAverage","key","value","last_change") VALUES ('2','MAVERICK','0.003356158','0.00022237925','2.652016e-06','1004.0',NULL,NULL,NULL,NULL,NULL,'0','0.0','0');
INSERT INTO "meat_sensortypes" ("id","name","a","b","c","Rn","Mode","RefVoltage","Sensitivity","Turns","nAverage","key","value","last_change") VALUES ('3','iGrill2','0.0033562424','0.00025319218','2.7988397e-06','99.61',NULL,NULL,NULL,NULL,NULL,'0','0.0','0');
INSERT INTO "meat_sensortypes" ("id","name","a","b","c","Rn","Mode","RefVoltage","Sensitivity","Turns","nAverage","key","value","last_change") VALUES ('4','ThermoWorks','0.0033556417','0.0002519145','2.360696e-06','97.31',NULL,NULL,NULL,NULL,NULL,'0','0.0','0');
INSERT INTO "meat_sensortypes" ("id","name","a","b","c","Rn","Mode","RefVoltage","Sensitivity","Turns","nAverage","key","value","last_change") VALUES ('5','ET-73','0.00335672','0.000291888','4.39054e-06','200.0',NULL,NULL,NULL,NULL,NULL,'0','0.0','0');
INSERT INTO "meat_sensortypes" ("id","name","a","b","c","Rn","Mode","RefVoltage","Sensitivity","Turns","nAverage","key","value","last_change") VALUES ('6','PERFEKTION','0.003356199','0.00024352911','3.4519389e-06','200.1',NULL,NULL,NULL,NULL,NULL,'0','0.0','0');
INSERT INTO "meat_sensortypes" ("id","name","a","b","c","Rn","Mode","RefVoltage","Sensitivity","Turns","nAverage","key","value","last_change") VALUES ('7','100K6A1B','0.00335639','0.000241116','2.43362e-06','100.0',NULL,NULL,NULL,NULL,NULL,'0','0.0','0');
INSERT INTO "meat_sensortypes" ("id","name","a","b","c","Rn","Mode","RefVoltage","Sensitivity","Turns","nAverage","key","value","last_change") VALUES ('8','FANTAST_NEU','0.00334519','0.000243825','2.61726e-06','220.0',NULL,NULL,NULL,NULL,NULL,'0','0.0','0');
INSERT INTO "meat_sensortypes" ("id","name","a","b","c","Rn","Mode","RefVoltage","Sensitivity","Turns","nAverage","key","value","last_change") VALUES ('9','100K3950','0.0033527432','0.00025977101','3.693425e-06','100.0',NULL,NULL,NULL,NULL,NULL,'0','0.0','0');
INSERT INTO "meat_sensortypes" ("id","name","a","b","c","Rn","Mode","RefVoltage","Sensitivity","Turns","nAverage","key","value","last_change") VALUES ('10','Inkbird','0.0033552456','0.00025608666','1.9317204e-06','48.59',NULL,NULL,NULL,NULL,NULL,'0','0.0','0');
INSERT INTO "meat_sensortypes" ("id","name","a","b","c","Rn","Mode","RefVoltage","Sensitivity","Turns","nAverage","key","value","last_change") VALUES ('11','5K3A1B','0.0033555','0.000257','2.43e-06','5.0',NULL,NULL,NULL,NULL,NULL,'0','0.0','0');
INSERT INTO "meat_sensortypes" ("id","name","a","b","c","Rn","Mode","RefVoltage","Sensitivity","Turns","nAverage","key","value","last_change") VALUES ('12','ET-735','0.0033566373','0.00019664428','9.6248678e-07','998.9',NULL,NULL,NULL,NULL,NULL,'0','0.0','0');
INSERT INTO "meat_sensortypes" ("id","name","a","b","c","Rn","Mode","RefVoltage","Sensitivity","Turns","nAverage","key","value","last_change") VALUES ('13','Acurite','0.0033555291','0.00025249073','2.5667292e-06','50.21',NULL,NULL,NULL,NULL,NULL,'0','0.0','0');
INSERT INTO "meat_sensortypes" ("id","name","a","b","c","Rn","Mode","RefVoltage","Sensitivity","Turns","nAverage","key","value","last_change") VALUES ('14','ODC','0.0033538614','0.00022352517','2.4382337e-06','1000.0',NULL,NULL,NULL,NULL,NULL,'0','0.0','0');
INSERT INTO "meat_sensortypes" ("id","name","a","b","c","Rn","Mode","RefVoltage","Sensitivity","Turns","nAverage","key","value","last_change") VALUES ('15','Santos','0.0033561093','0.00023552814','2.1375541e-06','200.82',NULL,NULL,NULL,NULL,NULL,'0','0.0','0');
INSERT INTO "meat_sensortypes" ("id","name","a","b","c","Rn","Mode","RefVoltage","Sensitivity","Turns","nAverage","key","value","last_change") VALUES ('16','SmokeMAX','0.0033561946','0.0002550005','2.5976258e-06','99.645',NULL,NULL,NULL,NULL,NULL,'0','0.0','0');
INSERT INTO "meat_sensortypes" ("id","name","a","b","c","Rn","Mode","RefVoltage","Sensitivity","Turns","nAverage","key","value","last_change") VALUES ('17','Weber_6743','0.0033558796','0.00027111149','3.1838428e-06','102.315',NULL,NULL,NULL,NULL,NULL,'0','0.0','0');
INSERT INTO "meat_sensortypes" ("id","name","a","b","c","Rn","Mode","RefVoltage","Sensitivity","Turns","nAverage","key","value","last_change") VALUES ('18','LEM-HO6P-AC',NULL,NULL,NULL,NULL,'AC','1.6672','76.67','2','100','0','0.0','0');
INSERT INTO "meat_sensortypes" ("id","name","a","b","c","Rn","Mode","RefVoltage","Sensitivity","Turns","nAverage","key","value","last_change") VALUES ('19','LEM-HO6P-DC',NULL,NULL,NULL,NULL,'DC','1.6672','76.67','2','50','0','0.0','0');

----
-- Drop table for config_nfs_backup
----
DROP TABLE IF EXISTS "config_nfs_backup";

----
-- Table structure for config_nfs_backup
----
CREATE TABLE "config_nfs_backup" (
    "id" INTEGER,
    "nfsvol" TEXT,
    "subdir" TEXT,
    "nfsmount" TEXT,
    "backup_path" TEXT,
    "number_of_backups" INTEGER,
    "backup_name" TEXT,
    "nfsopt" TEXT,
    "active" INTEGER
);

----
-- Data dump for config_nfs_backup, a total of 0 rows
----

----
-- Drop table for config_telegram
----
DROP TABLE IF EXISTS "config_telegram";

----
-- Table structure for config_telegram
----
CREATE TABLE "config_telegram" (
    "id" INTEGER,
    "bot_token" TEXT NOT NULL,
    "bot_chatID" TEXT NOT NULL,
    "active" INTEGER
);

----
-- Data dump for config_telegram, a total of 0 rows
----

----
-- Drop table for config_pushover
----
DROP TABLE IF EXISTS "config_pushover";

----
-- Table structure for config_pushover
----
CREATE TABLE "config_pushover" (
    "id" INTEGER,
    "user_key" TEXT NOT NULL,
    "api_token" TEXT NOT NULL,
    "active" INTEGER
);

----
-- Data dump for config_pushover, a total of 0 rows
----

----
-- Drop table for config
----
DROP TABLE IF EXISTS "config";

----
-- Table structure for config
----
CREATE TABLE 'config' ('id' INTEGER DEFAULT 0 PRIMARY KEY AUTOINCREMENT NOT NULL, 'key' TEXT DEFAULT 0 NOT NULL, 'value' REAL DEFAULT 0 NOT NULL, 'last_change' INTEGER DEFAULT 0 NOT NULL);

----
-- Data dump for config, a total of 40 rows
----
INSERT INTO "config" ("id","key","value","last_change") VALUES ('1','switch_on_cooling_compressor','2.0','1604862198');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('2','switch_off_cooling_compressor','0.0','1604862198');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('3','switch_on_humidifier','20.0','1604862198');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('4','switch_off_humidifier','0.0','1604862198');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('5','delay_humidify','5.0','1604862198');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('6','sensortype','5.0','1582013839');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('7','language','2.0','1617699411');
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
INSERT INTO "config" ("id","key","value","last_change") VALUES ('19','circulation_air_period','3600.0','1617699188');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('20','setpoint_temperature','21.0','1617699188');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('21','exhaust_air_duration','900.0','1617699188');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('22','modus','4.0','1617699188');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('23','setpoint_humidity','93.0','1617699188');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('24','exhaust_air_period','21600.0','1617699188');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('25','circulation_air_duration','900.0','1617699188');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('26','agingtable','1.0','1610707601');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('27','failure_humidity_delta','10.0','0');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('28','failure_temperature_delta','4.0','0');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('29','samples_refunit_tara','20.0','0');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('30','spikes_refunit_tara','4.0','0');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('31','save_temperature_humidity_loops','27.0','0');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('32','sensorbus','0.0','0');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('33','meat1_sensortype','3.0','1617699411');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('34','meat2_sensortype','0.0','1617699411');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('35','meat3_sensortype','0.0','1617699411');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('36','meat4_sensortype','3.0','1617699411');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('37','customtime_for_diagrams','7200.0','0');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('38','secondsensortype','0.0','0');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('39','agingtable_startperiod','1.0','1613305041');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('40','agingtable_startday','1.0','1613305041');

----
-- Drop table for config_messenger_exception
----
DROP TABLE IF EXISTS "config_messenger_exception";

----
-- Table structure for config_messenger_exception
----
CREATE TABLE "config_messenger_exception" (
    "id" INTEGER,
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
INSERT INTO "config_messenger_exception" ("id","exception","e-mail","pushover","telegram","alarm","raise_exception","active") VALUES ('1','cx_Sensor_not_defined','0','0','0','short','1','1');
INSERT INTO "config_messenger_exception" ("id","exception","e-mail","pushover","telegram","alarm","raise_exception","active") VALUES ('2','OperationalError','0','0','0','short','1','1');
INSERT INTO "config_messenger_exception" ("id","exception","e-mail","pushover","telegram","alarm","raise_exception","active") VALUES ('3','FileNotFoundError','0','0','0','short','1','1');
INSERT INTO "config_messenger_exception" ("id","exception","e-mail","pushover","telegram","alarm","raise_exception","active") VALUES ('4','OSError','0','0','0','short','1','1');
INSERT INTO "config_messenger_exception" ("id","exception","e-mail","pushover","telegram","alarm","raise_exception","active") VALUES ('5','cx_measurement_error','0','0','0','short','1','1');

----
-- Drop table for config_messenger_event
----
DROP TABLE IF EXISTS "config_messenger_event";

----
-- Table structure for config_messenger_event
----
CREATE TABLE "config_messenger_event" (
    "id" INTEGER,
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
INSERT INTO "config_messenger_event" ("id","event","e-mail","pushover","telegram","alarm","event_text","active") VALUES ('1','Pi-Ager_started','0','0','0','','Test','0');

----
-- Drop table for config_alarm
----
DROP TABLE IF EXISTS "config_alarm";

----
-- Table structure for config_alarm
----
CREATE TABLE "config_alarm" (
    "id" INTEGER,
    "alarm" TEXT NOT NULL DEFAULT ('short'),
    "replication" INTEGER DEFAULT (3),
    "sleep" REAL DEFAULT (0.5),
    "high_time" REAL DEFAULT (0.5),
    "low_time" REAL DEFAULT (0.5),
    "waveform" TEXT,
    "frequency" REAL
);

----
-- Data dump for config_alarm, a total of 1 rows
----
INSERT INTO "config_alarm" ("id","alarm","replication","sleep","high_time","low_time","waveform","frequency") VALUES ('1','short','3','0.5','0.5','0.5','',NULL);

----
-- Drop table for all_sensors
----
DROP TABLE IF EXISTS "all_sensors";

----
-- Table structure for all_sensors
----
CREATE TABLE 'all_sensors' ('id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 'tempint' REAL DEFAULT NULL, 'tempext' REAL DEFAULT NULL, 'humint' REAL DEFAULT NULL, 'humext' REAL DEFAULT NULL, 'dewint' REAL DEFAULT NULL, 'dewext' REAL DEFAULT NULL, 'humintabs' REAL DEFAULT NULL, 'humextabs' REAL DEFAULT NULL, 'ntc1' REAL DEFAULT NULL, 'ntc2' REAL DEFAULT NULL, 'ntc3' REAL DEFAULT NULL, 'ntc4' REAL DEFAULT NULL, 'last_change' INTEGER NOT NULL);

----
-- Data dump for all_sensors, a total of 0 rows
----

----
-- Drop table for all_scales
----
DROP TABLE IF EXISTS "all_scales";

----
-- Table structure for all_scales
----
CREATE TABLE 'all_scales' ('id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 'scale1' REAL DEFAULT NULL, 'scale2' REAL DEFAULT NULL, 'last_change' INTEGER NOT NULL);

----
-- Data dump for all_scales, a total of 0 rows
----

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
INSERT INTO "scale1_settings" ("id","key","value","last_change") VALUES ('6','referenceunit','205.0','1613669467');
INSERT INTO "scale1_settings" ("id","key","value","last_change") VALUES ('7','measuring_interval','300.0','0');
INSERT INTO "scale1_settings" ("id","key","value","last_change") VALUES ('8','measuring_duration','15.0','0');
INSERT INTO "scale1_settings" ("id","key","value","last_change") VALUES ('9','saving_period','30.0','0');
INSERT INTO "scale1_settings" ("id","key","value","last_change") VALUES ('10','offset','528.4','1613669506');

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
INSERT INTO "scale2_settings" ("id","key","value","last_change") VALUES ('6','referenceunit','205.0','1613243596');
INSERT INTO "scale2_settings" ("id","key","value","last_change") VALUES ('7','measuring_interval','300.0','0');
INSERT INTO "scale2_settings" ("id","key","value","last_change") VALUES ('8','measuring_duration','15.0','0');
INSERT INTO "scale2_settings" ("id","key","value","last_change") VALUES ('9','saving_period','30.0','0');
INSERT INTO "scale2_settings" ("id","key","value","last_change") VALUES ('10','offset','500.0','1613243410');

----
-- Drop table for config_email_server
----
DROP TABLE IF EXISTS "config_email_server";

----
-- Table structure for config_email_server
----
CREATE TABLE "config_email_server" ('id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 'server' TEXT NOT NULL, 'user' TEXT NOT NULL, 'password' TEXT NOT NULL, 'port' INTEGER NOT NULL);

----
-- Data dump for config_email_server, a total of 0 rows
----

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
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('1','sensor_temperature','21.25','1617701095');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('2','sensor_humidity','36.12','1617701095');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('3','status_circulating_air','0.0','1617701095');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('4','status_cooling_compressor','0.0','1617701095');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('5','status_exhaust_air','0.0','1617701095');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('6','status_heater','0.0','1617701095');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('7','status_light','0.0','1617701095');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('8','status_uv','0.0','1617701095');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('9','status_humidifier','0.0','1617701095');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('10','status_dehumidifier','0.0','1617701095');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('11','scale1','-518.608','1617701059');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('12','scale2','-491.828','1617701062');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('13','status_piager','0.0','1617701072');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('14','status_agingtable','0.0','1617701072');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('15','status_scale1','0.0','1617701058');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('16','status_scale2','0.0','1617701066');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('17','status_tara_scale1','0.0','1617699555');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('18','status_tara_scale2','0.0','1617699555');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('19','agingtable_period','0.0','1614454803');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('20','agingtable_period_starttime','1614454563.0','1614454564');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('21','status_light_manual','0.0','1608641257');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('22','calibrate_scale1','0.0','1617699555');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('23','calibrate_scale2','0.0','1617699555');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('24','calibrate_weight','0.0','1617699474');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('25','status_uv_manual','0.0','1608641257');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('26','temperature_meat1','22.114','1617701095');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('27','temperature_meat2',NULL,'1617701095');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('28','temperature_meat3',NULL,'1617701095');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('29','temperature_meat4','21.997','1617701095');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('30','sensor_dewpoint','5.65','1617701095');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('31','sensor_extern_temperature','23.31','1611420926');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('32','sensor_extern_humidity','38.79','1611420926');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('33','sensor_extern_dewpoint','8.5','1611420926');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('34','agingtable_period_day','1.0','1614454803');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('35','scale1_thread_alive','1.0','1617699555');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('36','scale2_thread_alive','1.0','1617699555');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('37','aging_thread_alive','1.0','1617699555');

----
-- Drop index for all_sensors_index
----
DROP INDEX IF EXISTS "all_sensors_index";

----
-- structure for index all_sensors_index on table all_sensors
----
CREATE INDEX 'all_sensors_index' ON "all_sensors" ("last_change" ASC);

----
-- Drop index for all_scales_index
----
DROP INDEX IF EXISTS "all_scales_index";

----
-- structure for index all_scales_index on table all_scales
----
CREATE INDEX 'all_scales_index' ON "all_scales" ("last_change" ASC);
COMMIT;
