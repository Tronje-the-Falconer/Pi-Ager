----
-- phpLiteAdmin database dump (https://www.phpliteadmin.org/)
-- phpLiteAdmin version: 1.9.8.2
-- Exported: 3:14pm on December 26, 2020 (CET)
-- database file: ./config/pi-ager.sqlite3
----
BEGIN TRANSACTION;

----
-- Drop table for scale2_data
----
DROP TABLE IF EXISTS "scale2_data";

----
-- Table structure for scale2_data
----
CREATE TABLE "scale2_data" ('id' INTEGER DEFAULT 0 PRIMARY KEY AUTOINCREMENT NOT NULL, 'value' REAL DEFAULT 0 NOT NULL, 'last_change' INTEGER DEFAULT 0 NOT NULL, key TEXT DEFAULT 0 NOT NULL);

----
-- Data dump for scale2_data, a total of 0 rows
----

----
-- Drop table for cooling_compressor_status
----
DROP TABLE IF EXISTS "cooling_compressor_status";

----
-- Table structure for cooling_compressor_status
----
CREATE TABLE "cooling_compressor_status" ('id' INTEGER DEFAULT 0 PRIMARY KEY AUTOINCREMENT NOT NULL, 'value' REAL DEFAULT 0 NOT NULL, 'last_change' INTEGER DEFAULT 0 NOT NULL, key TEXT DEFAULT 0 NOT NULL);

----
-- Data dump for cooling_compressor_status, a total of 140 rows
----
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('113','1.0','1600023974','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('114','0.0','1600025285','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('115','1.0','1600025449','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('116','0.0','1600217262','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('117','1.0','1600264190','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('118','0.0','1601404592','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('119','1.0','1601404786','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('120','0.0','1601491238','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('121','1.0','1601559176','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('122','0.0','1601563042','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('123','1.0','1604869618','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('124','0.0','1604871226','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('125','1.0','1604927748','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('126','0.0','1604930620','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('127','1.0','1604953855','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('128','0.0','1604958090','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('129','1.0','1605187071','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('130','0.0','1605192800','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('131','1.0','1605824350','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('132','0.0','1605825657','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('133','1.0','1605901130','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('134','0.0','1605913885','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('135','1.0','1605968127','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('136','0.0','1605970959','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('137','1.0','1606051498','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('138','0.0','1606053984','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('139','1.0','1606054371','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('140','0.0','1606055239','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('141','1.0','1606224247','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('142','0.0','1606226682','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('143','1.0','1606227069','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('144','0.0','1606229232','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('145','1.0','1607002521','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('146','0.0','1607004617','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('147','1.0','1607005727','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('148','0.0','1607006775','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('149','1.0','1607117858','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('150','0.0','1607120005','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('151','1.0','1607287915','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('152','0.0','1607295336','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('153','1.0','1608298687','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('154','0.0','1608300834','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('155','1.0','1608312488','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('156','0.0','1608313835','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('157','1.0','1608321891','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('158','0.0','1608332468','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('159','1.0','1608374490','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('160','0.0','1608377071','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('161','1.0','1608406029','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('162','0.0','1608406425','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('163','1.0','1608406570','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('164','0.0','1608414200','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('165','1.0','1608466720','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('166','0.0','1608473621','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('167','1.0','1608478902','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('168','0.0','1608479105','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('169','1.0','1608479266','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('170','0.0','1608479357','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('171','1.0','1608479553','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('172','0.0','1608479644','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('173','1.0','1608479764','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('174','0.0','1608481248','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('175','1.0','1608481318','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('176','0.0','1608482315','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('177','1.0','1608482320','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('178','0.0','1608483793','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('179','1.0','1608483968','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('180','0.0','1608484029','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('181','1.0','1608484127','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('182','0.0','1608484716','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('183','1.0','1608484841','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('184','0.0','1608485960','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('185','1.0','1608486070','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('186','0.0','1608486446','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('187','1.0','1608486491','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('188','0.0','1608486532','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('189','1.0','1608487514','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('190','0.0','1608487676','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('191','1.0','1608487717','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('192','0.0','1608487757','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('193','1.0','1608488519','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('194','0.0','1608488570','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('195','1.0','1608488745','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('196','0.0','1608488806','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('197','1.0','1608491728','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('198','0.0','1608493273','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('199','1.0','1608493403','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('200','0.0','1608493464','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('201','1.0','1608495573','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('202','0.0','1608498185','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('203','1.0','1608498266','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('204','0.0','1608498651','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('205','1.0','1608545896','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('206','0.0','1608546018','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('207','1.0','1608550412','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('208','0.0','1608550453','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('209','1.0','1608559646','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('210','0.0','1608586537','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('211','1.0','1608587835','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('212','0.0','1608588850','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('213','1.0','1608644618','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('214','0.0','1608645208','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('215','1.0','1608645293','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('216','0.0','1608647549','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('217','1.0','1608647705','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('218','0.0','1608673720','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('219','1.0','1608674138','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('220','0.0','1608680856','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('221','1.0','1608680988','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('222','0.0','1608681363','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('223','1.0','1608681518','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('224','0.0','1608681650','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('225','1.0','1608682531','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('226','0.0','1608682744','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('227','1.0','1608682779','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('228','0.0','1608683550','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('229','1.0','1608735795','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('230','0.0','1608750683','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('231','1.0','1608751402','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('232','0.0','1608752863','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('233','1.0','1608757443','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('234','0.0','1608844855','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('235','1.0','1608844885','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('236','0.0','1608847108','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('237','1.0','1608847202','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('238','0.0','1608848285','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('239','1.0','1608848487','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('240','0.0','1608883284','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('241','1.0','1608883325','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('242','0.0','1608890799','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('243','1.0','1608891123','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('244','0.0','1608897688','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('245','1.0','1608897873','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('246','0.0','1608897965','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('247','1.0','1608898000','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('248','0.0','1608903650','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('249','1.0','1608903670','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('250','0.0','1608909507','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('251','1.0','1608909643','0');
INSERT INTO "cooling_compressor_status" ("id","value","last_change","key") VALUES ('252','0.0','1608991189','0');

----
-- Drop table for heater_status
----
DROP TABLE IF EXISTS "heater_status";

----
-- Table structure for heater_status
----
CREATE TABLE "heater_status" ('id' INTEGER DEFAULT 0 PRIMARY KEY AUTOINCREMENT NOT NULL, 'value' REAL DEFAULT 0 NOT NULL, 'last_change' INTEGER DEFAULT 0 NOT NULL, key TEXT DEFAULT 0 NOT NULL);

----
-- Data dump for heater_status, a total of 72 rows
----
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('36','1.0','1601491238','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('37','0.0','1601558632','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('38','1.0','1601565226','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('39','0.0','1601571413','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('40','1.0','1604862202','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('41','0.0','1604865439','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('42','1.0','1604872210','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('43','0.0','1604927445','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('44','1.0','1604932677','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('45','0.0','1604949863','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('46','1.0','1604959698','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('47','0.0','1605041986','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('48','1.0','1605049574','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('49','0.0','1605104410','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('50','1.0','1605106248','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('51','0.0','1605125678','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('52','1.0','1605132649','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('53','0.0','1605186894','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('54','1.0','1605195080','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('55','0.0','1605204652','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('56','1.0','1605831684','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('57','0.0','1605879733','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('58','1.0','1605880182','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('59','0.0','1605895863','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('60','1.0','1605918556','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('61','0.0','1605964952','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('62','1.0','1605967313','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('63','0.0','1605967877','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('64','1.0','1605983532','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('65','0.0','1605987087','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('66','1.0','1605997166','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('67','0.0','1606051320','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('68','1.0','1606055583','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('69','0.0','1606167145','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('70','1.0','1606170229','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('71','0.0','1606222805','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('72','1.0','1606229671','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('73','0.0','1606243459','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('74','1.0','1606256394','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('75','0.0','1606940513','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('76','1.0','1606949363','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('77','0.0','1607000021','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('78','1.0','1607005214','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('79','0.0','1607005517','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('80','1.0','1607007654','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('81','0.0','1607019293','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('82','1.0','1607037713','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('83','0.0','1607111701','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('84','1.0','1607123766','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('85','0.0','1607199254','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('86','1.0','1607210376','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('87','0.0','1607273182','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('88','1.0','1607295849','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('89','0.0','1607360708','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('90','1.0','1607383248','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('91','0.0','1607454409','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('92','1.0','1607469963','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('93','0.0','1608226503','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('94','1.0','1608246862','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('95','0.0','1608297880','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('96','1.0','1608301190','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('97','0.0','1608312488','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('98','1.0','1608332468','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('99','0.0','1608373321','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('100','1.0','1608473713','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('101','0.0','1608476447','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('102','1.0','1608476643','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('103','0.0','1608477488','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('104','1.0','1608724057','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('105','0.0','1608725922','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('106','1.0','1608883284','0');
INSERT INTO "heater_status" ("id","value","last_change","key") VALUES ('107','0.0','1608883325','0');

----
-- Drop table for sensor_humidity_data
----
DROP TABLE IF EXISTS "sensor_humidity_data";

----
-- Table structure for sensor_humidity_data
----
CREATE TABLE "sensor_humidity_data" ('id' INTEGER DEFAULT 0 PRIMARY KEY AUTOINCREMENT NOT NULL, 'value' REAL DEFAULT 0 NOT NULL, 'last_change' INTEGER DEFAULT 0 NOT NULL, key TEXT DEFAULT 0 NOT NULL);

----
-- Data dump for sensor_humidity_data, a total of 0 rows
----

----
-- Drop table for circulating_air_status
----
DROP TABLE IF EXISTS "circulating_air_status";

----
-- Table structure for circulating_air_status
----
CREATE TABLE "circulating_air_status" ('id' INTEGER DEFAULT 0 PRIMARY KEY AUTOINCREMENT NOT NULL, 'value' REAL DEFAULT 0 NOT NULL, 'last_change' INTEGER DEFAULT 0 NOT NULL, key TEXT DEFAULT 0 NOT NULL);

----
-- Data dump for circulating_air_status, a total of 116 rows
----
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('239','1.0','1600023974','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('240','0.0','1600025285','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('241','1.0','1600025449','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('242','0.0','1601404592','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('243','1.0','1601404786','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('244','0.0','1601571423','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('245','1.0','1604862202','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('246','0.0','1604865439','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('247','1.0','1604865744','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('248','0.0','1605823008','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('249','1.0','1605823315','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('250','0.0','1608226503','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('251','1.0','1608226809','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('252','0.0','1608313835','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('253','1.0','1608321891','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('254','0.0','1608377071','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('255','1.0','1608406029','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('256','0.0','1608406425','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('257','1.0','1608406570','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('258','0.0','1608414200','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('259','1.0','1608466720','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('260','0.0','1608476447','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('261','1.0','1608476643','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('262','0.0','1608477498','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('263','1.0','1608477874','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('264','0.0','1608477955','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('265','1.0','1608478902','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('266','0.0','1608479105','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('267','1.0','1608479266','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('268','0.0','1608479357','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('269','1.0','1608479553','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('270','0.0','1608479644','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('271','1.0','1608479764','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('272','0.0','1608481248','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('273','1.0','1608481318','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('274','0.0','1608482315','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('275','1.0','1608482320','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('276','0.0','1608483793','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('277','1.0','1608483968','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('278','0.0','1608484029','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('279','1.0','1608484127','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('280','0.0','1608484716','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('281','1.0','1608484841','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('282','0.0','1608485960','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('283','1.0','1608486070','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('284','0.0','1608486446','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('285','1.0','1608486491','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('286','0.0','1608486532','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('287','1.0','1608487514','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('288','0.0','1608487676','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('289','1.0','1608487717','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('290','0.0','1608487757','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('291','1.0','1608488519','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('292','0.0','1608488570','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('293','1.0','1608488745','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('294','0.0','1608488806','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('295','1.0','1608491728','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('296','0.0','1608493273','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('297','1.0','1608493403','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('298','0.0','1608493464','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('299','1.0','1608495573','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('300','0.0','1608498185','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('301','1.0','1608498266','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('302','0.0','1608498651','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('303','1.0','1608545896','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('304','0.0','1608546018','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('305','1.0','1608550412','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('306','0.0','1608550453','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('307','1.0','1608559646','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('308','0.0','1608586537','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('309','1.0','1608587835','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('310','0.0','1608588850','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('311','1.0','1608641003','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('312','0.0','1608641014','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('313','1.0','1608644618','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('314','0.0','1608645208','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('315','1.0','1608645293','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('316','0.0','1608647549','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('317','1.0','1608647705','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('318','0.0','1608673720','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('319','1.0','1608674138','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('320','0.0','1608680856','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('321','1.0','1608680988','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('322','0.0','1608681363','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('323','1.0','1608681518','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('324','0.0','1608681650','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('325','1.0','1608682531','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('326','0.0','1608682744','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('327','1.0','1608682779','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('328','0.0','1608683550','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('329','1.0','1608724057','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('330','0.0','1608725932','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('331','1.0','1608727178','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('332','0.0','1608727381','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('333','1.0','1608735795','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('334','0.0','1608750683','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('335','1.0','1608751402','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('336','0.0','1608752863','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('337','1.0','1608757443','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('338','0.0','1608844855','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('339','1.0','1608844885','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('340','0.0','1608847108','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('341','1.0','1608847202','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('342','0.0','1608848285','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('343','1.0','1608848487','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('344','0.0','1608890799','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('345','1.0','1608891123','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('346','0.0','1608897688','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('347','1.0','1608897873','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('348','0.0','1608897965','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('349','1.0','1608898000','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('350','0.0','1608903650','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('351','1.0','1608903670','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('352','0.0','1608909507','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('353','1.0','1608909643','0');
INSERT INTO "circulating_air_status" ("id","value","last_change","key") VALUES ('354','0.0','1608991189','0');

----
-- Drop table for sensor_temperature_data
----
DROP TABLE IF EXISTS "sensor_temperature_data";

----
-- Table structure for sensor_temperature_data
----
CREATE TABLE "sensor_temperature_data" ('id' INTEGER DEFAULT 0 PRIMARY KEY AUTOINCREMENT NOT NULL, 'value' REAL DEFAULT 0 NOT NULL, 'last_change' INTEGER DEFAULT 0 NOT NULL, key TEXT DEFAULT 0 NOT NULL);

----
-- Data dump for sensor_temperature_data, a total of 0 rows
----

----
-- Drop table for exhaust_air_status
----
DROP TABLE IF EXISTS "exhaust_air_status";

----
-- Table structure for exhaust_air_status
----
CREATE TABLE "exhaust_air_status" ('id' INTEGER DEFAULT 0 PRIMARY KEY AUTOINCREMENT NOT NULL, 'value' REAL DEFAULT 0 NOT NULL, 'last_change' INTEGER DEFAULT 0 NOT NULL, key TEXT DEFAULT 0 NOT NULL);

----
-- Data dump for exhaust_air_status, a total of 300 rows
----
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1940','1.0','1600047028','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1941','0.0','1600047341','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1942','1.0','1600068938','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1943','0.0','1600069241','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1944','1.0','1600093927','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1945','0.0','1600094231','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1946','1.0','1600116778','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1947','0.0','1600117092','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1948','1.0','1600138686','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1949','0.0','1600138999','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1950','1.0','1600160595','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1951','0.0','1600160908','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1952','1.0','1600182503','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1953','0.0','1600182817','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1954','1.0','1600204406','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1955','0.0','1600204720','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1956','1.0','1600226313','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1957','0.0','1600226627','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1958','1.0','1600248221','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1959','0.0','1600248534','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1960','1.0','1600270127','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1961','0.0','1600270441','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1962','1.0','1600292039','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1963','0.0','1600292343','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1964','1.0','1600313937','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1965','0.0','1600314250','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1966','1.0','1600335847','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1967','0.0','1600336150','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1968','1.0','1600357745','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1969','0.0','1600358058','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1970','1.0','1600379648','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1971','0.0','1600379962','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1972','1.0','1600401554','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1973','0.0','1600401868','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1974','1.0','1600423466','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1975','0.0','1600423769','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1976','1.0','1600445363','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1977','0.0','1600445676','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1978','1.0','1600467269','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1979','0.0','1600467582','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1980','1.0','1600489176','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1981','0.0','1600489489','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1982','1.0','1600511085','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1983','0.0','1600511398','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1984','1.0','1600532993','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1985','0.0','1600533306','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1986','1.0','1600554906','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1987','0.0','1600555209','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1988','1.0','1600576806','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1989','0.0','1600577109','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1990','1.0','1600598704','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1991','0.0','1600599017','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1992','1.0','1600620611','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1993','0.0','1600620924','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1994','1.0','1600642517','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1995','0.0','1600642831','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1996','1.0','1600664426','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1997','0.0','1600664739','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1998','1.0','1600686333','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('1999','0.0','1600686647','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2000','1.0','1600708243','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2001','0.0','1600708556','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2002','1.0','1600730154','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2003','0.0','1600730457','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2004','1.0','1600752051','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2005','0.0','1600752365','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2006','1.0','1600773961','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2007','0.0','1600774274','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2008','1.0','1600795871','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2009','0.0','1600796174','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2010','1.0','1600817767','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2011','0.0','1600818081','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2012','1.0','1600839678','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2013','0.0','1600839981','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2014','1.0','1600861580','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2015','0.0','1600861883','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2016','1.0','1600883476','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2017','0.0','1600883790','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2018','1.0','1600905381','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2019','0.0','1600905695','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2020','1.0','1600927290','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2021','0.0','1600927604','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2022','1.0','1600949201','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2023','0.0','1600949504','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2024','1.0','1600971093','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2025','0.0','1600971407','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2026','1.0','1600992997','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2027','0.0','1600993311','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2028','1.0','1601014907','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2029','0.0','1601015221','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2030','1.0','1601036812','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2031','0.0','1601037126','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2032','1.0','1601058719','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2033','0.0','1601059033','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2034','1.0','1601080622','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2035','0.0','1601080936','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2036','1.0','1601102533','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2037','0.0','1601102836','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2038','1.0','1601124432','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2039','0.0','1601124735','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2040','1.0','1601146333','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2041','0.0','1601146636','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2042','1.0','1601168235','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2043','0.0','1601168538','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2044','1.0','1601190135','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2045','0.0','1601190448','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2046','1.0','1601212046','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2047','0.0','1601212349','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2048','1.0','1601233940','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2049','0.0','1601234254','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2050','1.0','1601255850','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2051','0.0','1601256164','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2052','1.0','1601277758','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2053','0.0','1601278072','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2054','1.0','1601299661','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2055','0.0','1601299975','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2056','1.0','1601321565','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2057','0.0','1601321879','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2058','1.0','1601343478','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2059','0.0','1601343781','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2060','1.0','1601365377','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2061','0.0','1601365680','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2062','1.0','1601387276','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2063','0.0','1601387590','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2064','1.0','1601426353','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2065','0.0','1601426667','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2066','1.0','1601448263','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2067','0.0','1601448566','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2068','1.0','1601470159','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2069','0.0','1601470472','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2070','1.0','1601492063','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2071','0.0','1601492377','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2072','1.0','1601513967','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2073','0.0','1601514281','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2074','1.0','1601535870','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2075','0.0','1601536184','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2076','1.0','1601557776','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2077','0.0','1601558089','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2078','1.0','1604919518','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2079','0.0','1604919831','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2080','1.0','1604941423','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2081','0.0','1604941736','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2082','1.0','1604963332','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2083','0.0','1604963635','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2084','1.0','1604991570','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2085','0.0','1604991884','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2086','1.0','1605013478','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2087','0.0','1605013791','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2088','1.0','1605035390','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2089','0.0','1605035694','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2090','1.0','1605057286','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2091','0.0','1605057599','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2092','1.0','1605079197','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2093','0.0','1605079500','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2094','1.0','1605101097','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2095','0.0','1605101400','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2096','1.0','1605122991','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2097','0.0','1605123305','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2098','1.0','1605144896','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2099','0.0','1605145210','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2100','1.0','1605166808','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2101','0.0','1605167111','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2102','1.0','1605188704','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2103','0.0','1605189018','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2104','1.0','1605210608','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2105','0.0','1605210922','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2106','1.0','1605844599','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2107','0.0','1605844912','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2108','1.0','1605866512','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2109','0.0','1605866815','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2110','1.0','1605888408','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2111','0.0','1605888722','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2112','1.0','1605910311','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2113','0.0','1605910624','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2114','1.0','1605932217','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2115','0.0','1605932530','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2116','1.0','1605954126','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2117','0.0','1605954439','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2118','1.0','1605976038','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2119','0.0','1605976341','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2120','1.0','1605997939','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2121','0.0','1605998242','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2122','1.0','1606019831','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2123','0.0','1606020145','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2124','1.0','1606041738','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2125','0.0','1606042052','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2126','1.0','1606063651','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2127','0.0','1606063953','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2128','1.0','1606085544','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2129','0.0','1606085857','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2130','1.0','1606107452','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2131','0.0','1606107765','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2132','1.0','1606129359','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2133','0.0','1606129673','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2134','1.0','1606151266','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2135','0.0','1606151580','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2136','1.0','1606173177','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2137','0.0','1606173480','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2138','1.0','1606195069','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2139','0.0','1606195383','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2140','1.0','1606216974','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2141','0.0','1606217287','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2142','1.0','1606238880','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2143','0.0','1606239193','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2144','1.0','1606260792','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2145','0.0','1606261095','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2146','1.0','1606282684','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2147','0.0','1606282997','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2148','1.0','1606304594','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2149','0.0','1606304907','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2150','1.0','1606947501','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2151','0.0','1606947815','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2152','1.0','1606969413','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2153','0.0','1606969717','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2154','1.0','1606991314','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2155','0.0','1606991617','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2156','1.0','1607013215','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2157','0.0','1607013518','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2158','1.0','1607035108','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2159','0.0','1607035423','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2160','1.0','1607057017','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2161','0.0','1607057331','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2162','1.0','1607078926','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2163','0.0','1607079240','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2164','1.0','1607100834','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2165','0.0','1607101149','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2166','1.0','1607122739','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2167','0.0','1607123054','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2168','1.0','1607144652','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2169','0.0','1607144955','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2170','1.0','1607166546','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2171','0.0','1607166859','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2172','1.0','1607188450','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2173','0.0','1607188764','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2174','1.0','1607210355','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2175','0.0','1607210669','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2176','1.0','1607232268','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2177','0.0','1607232572','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2178','1.0','1607254162','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2179','0.0','1607254476','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2180','1.0','1607276075','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2181','0.0','1607276379','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2182','1.0','1607297973','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2183','0.0','1607298287','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2184','1.0','1607319881','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2185','0.0','1607320195','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2186','1.0','1607341786','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2187','0.0','1607342102','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2188','1.0','1607363692','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2189','0.0','1607364005','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2190','1.0','1607385602','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2191','0.0','1607385904','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2192','1.0','1607407499','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2193','0.0','1607407804','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2194','1.0','1607429400','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2195','0.0','1607429705','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2196','1.0','1607451300','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2197','0.0','1607451614','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2198','1.0','1607473209','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2199','0.0','1607473522','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2200','1.0','1607495118','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2201','0.0','1607495421','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2202','1.0','1607517020','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2203','0.0','1607517324','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2204','1.0','1607538920','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2205','0.0','1607539224','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2206','1.0','1608248097','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2207','0.0','1608248411','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2208','1.0','1608270004','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2209','0.0','1608270318','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2210','1.0','1608291915','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2211','0.0','1608292218','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2212','1.0','1608371541','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2213','0.0','1608371551','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2214','1.0','1608545896','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2215','0.0','1608545906','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2216','1.0','1608567501','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2217','0.0','1608567806','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2218','1.0','1608666184','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2219','0.0','1608666499','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2220','1.0','1608724057','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2221','0.0','1608724067','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2222','1.0','1608745660','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2223','0.0','1608745975','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2224','1.0','1608812814','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2225','0.0','1608812824','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2226','1.0','1608904058','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2227','0.0','1608904965','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2228','1.0','1608917924','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2229','0.0','1608918830','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2230','1.0','1608931789','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2231','0.0','1608932694','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2232','1.0','1608945650','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2233','0.0','1608946555','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2234','1.0','1608959513','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2235','0.0','1608960419','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2236','1.0','1608973375','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2237','0.0','1608974280','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2238','1.0','1608987232','0');
INSERT INTO "exhaust_air_status" ("id","value","last_change","key") VALUES ('2239','0.0','1608988147','0');

----
-- Drop table for config
----
DROP TABLE IF EXISTS "config";

----
-- Table structure for config
----
CREATE TABLE 'config' ('id' INTEGER DEFAULT 0 PRIMARY KEY AUTOINCREMENT NOT NULL, 'key' TEXT DEFAULT 0 NOT NULL, 'value' REAL DEFAULT 0 NOT NULL, 'last_change' INTEGER DEFAULT 0 NOT NULL);

----
-- Data dump for config, a total of 36 rows
----
INSERT INTO "config" ("id","key","value","last_change") VALUES ('1','switch_on_cooling_compressor','1.0','1604862198');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('2','switch_off_cooling_compressor','0.0','1604862198');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('3','switch_on_humidifier','20.0','1604862198');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('4','switch_off_humidifier','0.0','1604862198');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('5','delay_humidify','5.0','1604862198');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('6','sensortype','4.0','1582013839');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('7','language','1.0','1608930753');
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
INSERT INTO "config" ("id","key","value","last_change") VALUES ('19','circulation_air_period','2160.0','1608911144');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('20','setpoint_temperature','2.0','1608911144');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('21','exhaust_air_duration','900.0','1608911144');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('22','modus','4.0','1608911144');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('23','setpoint_humidity','85.0','1608911144');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('24','exhaust_air_period','12960.0','1608911144');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('25','circulation_air_duration','1440.0','1608911144');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('26','agingtable','2.0','1608745053');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('27','failure_humidity_delta','10.0','0');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('28','failure_temperature_delta','4.0','0');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('29','samples_refunit_tara','20.0','0');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('30','spikes_refunit_tara','4.0','0');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('31','save_temperature_humidity_loops','27.0','0');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('32','sensorbus','0.0','0');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('33','meat1_sensortype','3.0','1608930753');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('34','meat2_sensortype','0.0','1608930753');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('35','meat3_sensortype','0.0','1608930753');
INSERT INTO "config" ("id","key","value","last_change") VALUES ('36','meat4_sensortype','19.0','1608930753');

----
-- Drop table for scale1_data
----
DROP TABLE IF EXISTS "scale1_data";

----
-- Table structure for scale1_data
----
CREATE TABLE "scale1_data" ('id' INTEGER DEFAULT 0 PRIMARY KEY AUTOINCREMENT NOT NULL, 'value' REAL DEFAULT 0 NOT NULL, 'last_change' INTEGER DEFAULT 0 NOT NULL, key TEXT DEFAULT 0 NOT NULL);

----
-- Data dump for scale1_data, a total of 0 rows
----

----
-- Drop table for light_status
----
DROP TABLE IF EXISTS "light_status";

----
-- Table structure for light_status
----
CREATE TABLE "light_status" ('id' INTEGER DEFAULT 0 PRIMARY KEY AUTOINCREMENT NOT NULL, 'value' REAL DEFAULT 0 NOT NULL, 'last_change' INTEGER DEFAULT 0 NOT NULL, key TEXT DEFAULT 0 NOT NULL);

----
-- Data dump for light_status, a total of 2 rows
----
INSERT INTO "light_status" ("id","value","last_change","key") VALUES ('11352','1.0','1600872822','0');
INSERT INTO "light_status" ("id","value","last_change","key") VALUES ('11353','0.0','1600872843','0');

----
-- Drop table for uv_status
----
DROP TABLE IF EXISTS "uv_status";

----
-- Table structure for uv_status
----
CREATE TABLE "uv_status" ('id' INTEGER DEFAULT 0 PRIMARY KEY AUTOINCREMENT NOT NULL, 'value' REAL DEFAULT 0 NOT NULL, 'last_change' INTEGER DEFAULT 0 NOT NULL, key TEXT DEFAULT 0 NOT NULL);

----
-- Data dump for uv_status, a total of 0 rows
----

----
-- Drop table for humidifier_status
----
DROP TABLE IF EXISTS "humidifier_status";

----
-- Table structure for humidifier_status
----
CREATE TABLE "humidifier_status" ('id' INTEGER DEFAULT 0 PRIMARY KEY AUTOINCREMENT NOT NULL, 'value' REAL DEFAULT 0 NOT NULL, 'last_change' INTEGER DEFAULT 0 NOT NULL, key TEXT DEFAULT 0 NOT NULL);

----
-- Data dump for humidifier_status, a total of 108 rows
----
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('747','1.0','1600024279','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('748','0.0','1600025285','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('749','1.0','1600025755','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('750','0.0','1600072387','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('751','1.0','1600072692','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('752','0.0','1600094441','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('753','1.0','1600094745','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('754','0.0','1600095182','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('755','1.0','1600095487','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('756','0.0','1601404592','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('757','1.0','1601405090','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('758','0.0','1601571423','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('759','1.0','1604862328','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('760','0.0','1604865439','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('761','1.0','1604865744','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('762','0.0','1604869618','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('763','1.0','1604869944','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('764','0.0','1604876277','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('765','1.0','1604876580','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('766','0.0','1604897919','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('767','1.0','1604898222','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('768','0.0','1604969972','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('769','1.0','1604970277','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('770','0.0','1605823008','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('771','1.0','1605823315','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('772','0.0','1606925906','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('773','1.0','1606926214','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('774','0.0','1608226503','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('775','1.0','1608226809','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('776','0.0','1608312488','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('777','1.0','1608312790','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('778','0.0','1608313835','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('779','1.0','1608322196','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('780','0.0','1608332468','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('781','1.0','1608371541','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('782','0.0','1608377071','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('783','1.0','1608406333','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('784','0.0','1608406425','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('785','1.0','1608406875','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('786','0.0','1608414200','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('787','1.0','1608467025','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('788','0.0','1608476447','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('789','1.0','1608476949','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('790','0.0','1608477498','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('791','1.0','1608477874','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('792','0.0','1608477955','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('793','1.0','1608480069','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('794','0.0','1608481248','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('795','1.0','1608481623','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('796','0.0','1608482315','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('797','1.0','1608482625','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('798','0.0','1608483793','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('799','1.0','1608484432','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('800','0.0','1608484716','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('801','1.0','1608485146','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('802','0.0','1608485960','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('803','1.0','1608486375','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('804','0.0','1608486446','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('805','1.0','1608492033','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('806','0.0','1608493273','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('807','1.0','1608495878','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('808','0.0','1608498185','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('809','1.0','1608498570','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('810','0.0','1608498651','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('811','1.0','1608559951','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('812','0.0','1608586537','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('813','1.0','1608588139','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('814','0.0','1608588850','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('815','1.0','1608644923','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('816','0.0','1608645208','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('817','1.0','1608645598','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('818','0.0','1608647549','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('819','1.0','1608648010','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('820','0.0','1608673720','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('821','1.0','1608674443','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('822','0.0','1608680856','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('823','1.0','1608681292','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('824','0.0','1608681363','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('825','1.0','1608683084','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('826','0.0','1608683550','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('827','1.0','1608724361','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('828','0.0','1608725932','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('829','1.0','1608727178','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('830','0.0','1608727381','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('831','1.0','1608736099','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('832','0.0','1608750683','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('833','1.0','1608751706','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('834','0.0','1608752863','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('835','1.0','1608812814','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('836','0.0','1608823585','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('837','1.0','1608825266','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('838','0.0','1608844004','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('839','1.0','1608845192','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('840','0.0','1608846180','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('841','1.0','1608846486','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('842','0.0','1608847108','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('843','1.0','1608848793','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('844','0.0','1608883284','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('845','1.0','1608883590','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('846','0.0','1608890799','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('847','1.0','1608891429','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('848','0.0','1608897688','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('849','1.0','1608898306','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('850','0.0','1608903650','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('851','1.0','1608903976','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('852','0.0','1608909507','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('853','1.0','1608909949','0');
INSERT INTO "humidifier_status" ("id","value","last_change","key") VALUES ('854','0.0','1608991189','0');

----
-- Drop table for dehumidifier_status
----
DROP TABLE IF EXISTS "dehumidifier_status";

----
-- Table structure for dehumidifier_status
----
CREATE TABLE "dehumidifier_status" ('id' INTEGER DEFAULT 0 PRIMARY KEY AUTOINCREMENT NOT NULL, 'value' REAL DEFAULT 0 NOT NULL, 'last_change' INTEGER DEFAULT 0 NOT NULL, key TEXT DEFAULT 0 NOT NULL);

----
-- Data dump for dehumidifier_status, a total of 0 rows
----

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
-- Drop table for sensor_temperature_meat1_data
----
DROP TABLE IF EXISTS "sensor_temperature_meat1_data";

----
-- Table structure for sensor_temperature_meat1_data
----
CREATE TABLE "sensor_temperature_meat1_data" ('id' INTEGER DEFAULT 0 PRIMARY KEY AUTOINCREMENT NOT NULL, 'value' REAL DEFAULT 0 NOT NULL, 'last_change' INTEGER DEFAULT 0 NOT NULL, key TEXT DEFAULT 0 NOT NULL);

----
-- Data dump for sensor_temperature_meat1_data, a total of 338 rows
----
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('2','25.33','1608898235','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('3','25.12','1608898510','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('4','24.89','1608898785','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('5','24.73','1608899061','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('6','24.66','1608899336','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('7','24.58','1608899612','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('8','24.41','1608899887','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('9','24.35','1608900163','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('10','24.45','1608900438','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('11','24.57','1608900714','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('12','25.75','1608900990','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('13','25.37','1608901265','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('14','25.48','1608901541','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('15','25.55','1608901816','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('16','25.67','1608902091','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('17','25.68','1608902366','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('18','25.32','1608902641','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('19','25.13','1608902916','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('20','25.18','1608903192','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('21','25.43','1608903467','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('22','25.73','1608903762','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('23','25.91','1608904037','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('24','26.11','1608904312','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('25','26.25','1608904588','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('26','26.25','1608904863','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('27','26.26','1608905139','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('28','26.32','1608905414','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('29','26.38','1608905690','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('30','26.35','1608905965','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('31','26.34','1608906241','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('32','26.3','1608906517','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('33','26.29','1608906792','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('34','26.18','1608907067','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('35','26.16','1608907343','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('36','26.1','1608907619','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('37','26.04','1608907894','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('38','26.0','1608908170','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('39','26.01','1608908446','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('40','25.99','1608908721','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('41','25.99','1608908997','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('42','25.97','1608909273','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('43','25.94','1608909684','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('44','25.94','1608909959','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('45','25.89','1608910235','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('46','25.88','1608910510','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('47','25.85','1608910786','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('48','25.82','1608911061','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('49','25.8','1608911336','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('50','25.74','1608911611','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('51','25.67','1608911886','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('52','25.51','1608912161','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('53','25.6','1608912436','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('54','25.73','1608912711','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('55','25.82','1608912986','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('56','25.85','1608913261','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('57','25.94','1608913536','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('58','25.98','1608913811','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('59','25.98','1608914086','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('60','25.88','1608914361','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('61','25.93','1608914635','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('62','25.91','1608914910','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('63','25.86','1608915185','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('64','25.86','1608915460','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('65','25.8','1608915735','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('66','25.77','1608916010','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('67','25.75','1608916284','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('68','25.7','1608916559','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('69','25.69','1608916834','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('70','25.7','1608917109','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('71','25.66','1608917384','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('72','25.6','1608917659','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('73','25.59','1608917934','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('74','25.56','1608918209','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('75','25.56','1608918484','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('76','25.54','1608918759','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('77','25.53','1608919034','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('78','25.53','1608919309','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('79','25.72','1608919584','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('80','25.81','1608919859','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('81','25.84','1608920134','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('82','26.0','1608920409','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('83','26.09','1608920684','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('84','26.03','1608920959','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('85','25.77','1608921234','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('86','25.66','1608921510','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('87','25.65','1608921785','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('88','25.6','1608922060','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('89','25.59','1608922335','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('90','25.57','1608922611','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('91','25.57','1608922886','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('92','25.74','1608923161','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('93','25.58','1608923436','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('94','25.57','1608923711','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('95','25.57','1608923986','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('96','25.48','1608924261','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('97','25.46','1608924536','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('98','25.46','1608924811','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('99','25.45','1608925086','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('100','25.65','1608925360','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('101','25.76','1608925636','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('102','25.8','1608925911','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('103','25.79','1608926185','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('104','25.84','1608926460','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('105','25.97','1608926735','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('106','26.08','1608927010','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('107','26.06','1608927286','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('108','26.03','1608927561','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('109','26.11','1608927836','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('110','26.07','1608928112','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('111','26.07','1608928387','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('112','26.07','1608928662','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('113','26.21','1608928937','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('114','26.25','1608929212','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('115','26.15','1608929487','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('116','26.36','1608929763','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('117','26.2','1608930038','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('118','26.22','1608930313','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('119','26.26','1608930588','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('120','26.13','1608930863','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('121','26.03','1608931138','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('122','25.95','1608931412','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('123','25.93','1608931687','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('124','26.0','1608931961','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('125','26.05','1608932236','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('126','26.1','1608932511','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('127','26.26','1608932785','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('128','26.33','1608933060','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('129','26.36','1608933334','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('130','26.33','1608933609','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('131','26.34','1608933883','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('132','26.38','1608934158','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('133','26.44','1608934432','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('134','25.84','1608934707','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('135','24.78','1608934982','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('136','24.29','1608935256','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('137','24.04','1608935531','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('138','24.39','1608935805','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('139','24.47','1608936080','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('140','24.33','1608936354','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('141','24.21','1608936629','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('142','24.1','1608936904','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('143','23.92','1608937178','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('144','23.8','1608937453','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('145','23.69','1608937728','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('146','23.58','1608938003','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('147','23.42','1608938277','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('148','23.3','1608938552','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('149','23.15','1608938826','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('150','23.02','1608939101','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('151','22.88','1608939376','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('152','22.7','1608939650','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('153','22.55','1608939925','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('154','22.42','1608940199','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('155','22.35','1608940474','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('156','22.26','1608940748','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('157','22.1','1608941023','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('158','22.01','1608941297','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('159','21.96','1608941572','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('160','21.91','1608941847','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('161','21.85','1608942121','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('162','21.78','1608942396','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('163','21.7','1608942670','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('164','21.64','1608942945','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('165','21.57','1608943220','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('166','21.51','1608943494','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('167','21.49','1608943769','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('168','21.42','1608944043','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('169','21.38','1608944318','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('170','21.36','1608944593','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('171','21.29','1608944867','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('172','21.24','1608945142','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('173','21.21','1608945416','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('174','21.17','1608945691','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('175','21.15','1608945966','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('176','21.13','1608946240','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('177','21.03','1608946515','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('178','21.07','1608946789','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('179','21.03','1608947064','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('180','20.99','1608947338','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('181','20.92','1608947613','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('182','20.93','1608947888','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('183','20.9','1608948162','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('184','20.88','1608948437','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('185','20.84','1608948711','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('186','20.8','1608948986','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('187','20.76','1608949260','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('188','20.75','1608949535','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('189','20.72','1608949810','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('190','20.7','1608950084','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('191','20.67','1608950359','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('192','20.62','1608950634','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('193','20.61','1608950908','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('194','20.56','1608951183','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('195','20.55','1608951457','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('196','20.5','1608951732','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('197','20.49','1608952007','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('198','20.46','1608952281','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('199','20.43','1608952556','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('200','20.41','1608952830','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('201','20.38','1608953105','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('202','20.32','1608953379','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('203','20.32','1608953654','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('204','20.3','1608953929','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('205','20.27','1608954203','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('206','20.25','1608954478','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('207','20.23','1608954752','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('208','20.21','1608955027','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('209','20.17','1608955302','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('210','20.15','1608955577','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('211','20.13','1608955851','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('212','20.1','1608956126','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('213','20.08','1608956401','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('214','20.07','1608956675','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('215','20.04','1608956950','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('216','20.04','1608957225','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('217','19.99','1608957499','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('218','19.97','1608957774','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('219','19.96','1608958048','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('220','19.93','1608958323','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('221','19.9','1608958598','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('222','19.88','1608958873','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('223','19.86','1608959147','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('224','19.86','1608959422','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('225','19.82','1608959697','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('226','19.8','1608959971','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('227','19.78','1608960246','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('228','19.76','1608960521','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('229','19.76','1608960795','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('230','19.72','1608961070','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('231','19.71','1608961344','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('232','19.68','1608961619','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('233','19.67','1608961894','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('234','19.65','1608962168','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('235','19.63','1608962443','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('236','19.62','1608962717','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('237','19.61','1608962992','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('238','19.57','1608963267','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('239','19.55','1608963541','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('240','19.55','1608963816','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('241','19.53','1608964091','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('242','19.51','1608964365','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('243','19.49','1608964640','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('244','19.52','1608964915','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('245','19.41','1608965189','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('246','19.08','1608965464','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('247','18.92','1608965739','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('248','18.79','1608966013','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('249','18.73','1608966288','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('250','18.64','1608966562','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('251','18.57','1608966837','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('252','18.53','1608967112','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('253','18.43','1608967386','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('254','18.27','1608967661','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('255','18.01','1608967935','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('256','18.63','1608968210','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('257','19.09','1608968484','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('258','19.49','1608968759','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('259','19.99','1608969033','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('260','20.44','1608969308','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('261','21.13','1608969582','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('262','21.76','1608969857','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('263','22.31','1608970132','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('264','22.84','1608970406','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('265','23.38','1608970681','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('266','23.82','1608970955','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('267','23.95','1608971230','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('268','24.21','1608971504','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('269','24.42','1608971779','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('270','24.54','1608972053','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('271','24.69','1608972328','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('272','24.73','1608972603','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('273','24.81','1608972877','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('274','24.78','1608973151','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('275','24.82','1608973426','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('276','24.78','1608973700','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('277','24.78','1608973975','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('278','24.64','1608974249','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('279','24.65','1608974523','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('280','24.63','1608974798','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('281','24.5','1608975073','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('282','24.53','1608975347','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('283','24.43','1608975622','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('284','24.47','1608975896','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('285','24.44','1608976171','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('286','24.27','1608976445','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('287','24.13','1608976720','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('288','24.11','1608976994','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('289','24.1','1608977269','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('290','24.1','1608977543','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('291','24.06','1608977817','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('292','24.03','1608978092','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('293','23.99','1608978366','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('294','23.91','1608978641','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('295','23.86','1608978915','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('296','23.8','1608979190','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('297','23.66','1608979464','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('298','23.67','1608979739','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('299','23.53','1608980014','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('300','23.57','1608980288','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('301','23.49','1608980562','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('302','23.45','1608980837','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('303','23.36','1608981112','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('304','23.22','1608981386','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('305','23.14','1608981661','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('306','23.21','1608981935','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('307','23.32','1608982209','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('308','23.43','1608982484','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('309','23.58','1608982759','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('310','23.67','1608983033','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('311','23.8','1608983307','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('312','23.88','1608983582','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('313','23.83','1608983857','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('314','23.83','1608984131','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('315','23.84','1608984405','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('316','24.01','1608984680','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('317','24.07','1608984954','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('318','24.22','1608985229','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('319','24.38','1608985503','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('320','24.42','1608985777','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('321','24.2','1608986052','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('322','24.44','1608986327','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('323','24.55','1608986601','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('324','24.47','1608986876','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('325','24.31','1608987150','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('326','24.38','1608987425','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('327','24.5','1608987700','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('328','24.69','1608987974','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('329','24.96','1608988249','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('330','25.13','1608988524','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('331','25.32','1608988798','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('332','25.45','1608989073','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('333','25.34','1608989348','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('334','25.45','1608989623','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('335','25.6','1608989897','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('336','25.8','1608990172','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('337','25.78','1608990447','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('338','25.82','1608990722','0');
INSERT INTO "sensor_temperature_meat1_data" ("id","value","last_change","key") VALUES ('339','25.87','1608990996','0');

----
-- Drop table for sensor_temperature_meat2_data
----
DROP TABLE IF EXISTS "sensor_temperature_meat2_data";

----
-- Table structure for sensor_temperature_meat2_data
----
CREATE TABLE "sensor_temperature_meat2_data" ('id' INTEGER DEFAULT 0 PRIMARY KEY AUTOINCREMENT NOT NULL, 'value' REAL DEFAULT 0 NOT NULL, 'last_change' INTEGER DEFAULT 0 NOT NULL, key TEXT DEFAULT 0 NOT NULL);

----
-- Data dump for sensor_temperature_meat2_data, a total of 0 rows
----

----
-- Drop table for sensor_temperature_meat3_data
----
DROP TABLE IF EXISTS "sensor_temperature_meat3_data";

----
-- Table structure for sensor_temperature_meat3_data
----
CREATE TABLE "sensor_temperature_meat3_data" ('id' INTEGER DEFAULT 0 PRIMARY KEY AUTOINCREMENT NOT NULL, 'value' REAL DEFAULT 0 NOT NULL, 'last_change' INTEGER DEFAULT 0 NOT NULL, key TEXT DEFAULT 0 NOT NULL);

----
-- Data dump for sensor_temperature_meat3_data, a total of 25 rows
----
INSERT INTO "sensor_temperature_meat3_data" ("id","value","last_change","key") VALUES ('2','25.27','1608891123','0');
INSERT INTO "sensor_temperature_meat3_data" ("id","value","last_change","key") VALUES ('3','25.25','1608891399','0');
INSERT INTO "sensor_temperature_meat3_data" ("id","value","last_change","key") VALUES ('4','25.29','1608891674','0');
INSERT INTO "sensor_temperature_meat3_data" ("id","value","last_change","key") VALUES ('5','25.32','1608891950','0');
INSERT INTO "sensor_temperature_meat3_data" ("id","value","last_change","key") VALUES ('6','25.43','1608892226','0');
INSERT INTO "sensor_temperature_meat3_data" ("id","value","last_change","key") VALUES ('7','25.29','1608892501','0');
INSERT INTO "sensor_temperature_meat3_data" ("id","value","last_change","key") VALUES ('8','25.36','1608892777','0');
INSERT INTO "sensor_temperature_meat3_data" ("id","value","last_change","key") VALUES ('9','25.38','1608893053','0');
INSERT INTO "sensor_temperature_meat3_data" ("id","value","last_change","key") VALUES ('10','25.38','1608893329','0');
INSERT INTO "sensor_temperature_meat3_data" ("id","value","last_change","key") VALUES ('11','25.38','1608893604','0');
INSERT INTO "sensor_temperature_meat3_data" ("id","value","last_change","key") VALUES ('12','25.38','1608893880','0');
INSERT INTO "sensor_temperature_meat3_data" ("id","value","last_change","key") VALUES ('13','25.38','1608894156','0');
INSERT INTO "sensor_temperature_meat3_data" ("id","value","last_change","key") VALUES ('14','25.37','1608894431','0');
INSERT INTO "sensor_temperature_meat3_data" ("id","value","last_change","key") VALUES ('15','25.43','1608894707','0');
INSERT INTO "sensor_temperature_meat3_data" ("id","value","last_change","key") VALUES ('16','25.45','1608894983','0');
INSERT INTO "sensor_temperature_meat3_data" ("id","value","last_change","key") VALUES ('17','25.5','1608895258','0');
INSERT INTO "sensor_temperature_meat3_data" ("id","value","last_change","key") VALUES ('18','25.43','1608895534','0');
INSERT INTO "sensor_temperature_meat3_data" ("id","value","last_change","key") VALUES ('19','25.43','1608895810','0');
INSERT INTO "sensor_temperature_meat3_data" ("id","value","last_change","key") VALUES ('20','25.41','1608896085','0');
INSERT INTO "sensor_temperature_meat3_data" ("id","value","last_change","key") VALUES ('21','25.46','1608896361','0');
INSERT INTO "sensor_temperature_meat3_data" ("id","value","last_change","key") VALUES ('22','25.47','1608896637','0');
INSERT INTO "sensor_temperature_meat3_data" ("id","value","last_change","key") VALUES ('23','25.47','1608896912','0');
INSERT INTO "sensor_temperature_meat3_data" ("id","value","last_change","key") VALUES ('24','25.5','1608897188','0');
INSERT INTO "sensor_temperature_meat3_data" ("id","value","last_change","key") VALUES ('25','25.49','1608897464','0');
INSERT INTO "sensor_temperature_meat3_data" ("id","value","last_change","key") VALUES ('26','25.61','1608897924','0');

----
-- Drop table for sensor_temperature_meat4_data
----
DROP TABLE IF EXISTS "sensor_temperature_meat4_data";

----
-- Table structure for sensor_temperature_meat4_data
----
CREATE TABLE "sensor_temperature_meat4_data" ('id' INTEGER DEFAULT 0 PRIMARY KEY AUTOINCREMENT NOT NULL, 'value' REAL DEFAULT 0 NOT NULL, 'last_change' INTEGER DEFAULT 0 NOT NULL, key TEXT DEFAULT 0 NOT NULL);

----
-- Data dump for sensor_temperature_meat4_data, a total of 363 rows
----
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('2','0.09','1608891123','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('3','0.09','1608891399','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('4','0.08','1608891674','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('5','0.09','1608891950','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('6','0.08','1608892226','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('7','0.09','1608892501','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('8','0.09','1608892777','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('9','0.08','1608893053','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('10','0.09','1608893329','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('11','0.08','1608893604','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('12','0.09','1608893880','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('13','0.08','1608894156','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('14','0.08','1608894431','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('15','0.09','1608894707','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('16','0.08','1608894983','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('17','0.09','1608895258','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('18','0.08','1608895534','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('19','0.09','1608895810','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('20','0.08','1608896085','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('21','0.08','1608896361','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('22','0.08','1608896637','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('23','0.09','1608896912','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('24','0.08','1608897188','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('25','0.09','1608897464','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('26','0.09','1608897924','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('27','0.09','1608898235','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('28','0.09','1608898510','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('29','0.09','1608898785','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('30','0.09','1608899061','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('31','0.09','1608899336','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('32','0.09','1608899612','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('33','0.09','1608899887','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('34','0.09','1608900163','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('35','0.09','1608900438','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('36','0.09','1608900714','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('37','0.09','1608900990','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('38','0.09','1608901265','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('39','0.08','1608901541','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('40','0.08','1608901816','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('41','0.09','1608902091','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('42','0.09','1608902366','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('43','0.09','1608902641','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('44','0.09','1608902916','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('45','0.09','1608903192','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('46','0.09','1608903467','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('47','0.09','1608903762','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('48','0.09','1608904037','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('49','0.09','1608904312','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('50','0.09','1608904588','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('51','0.09','1608904863','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('52','0.08','1608905139','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('53','0.08','1608905414','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('54','0.08','1608905690','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('55','0.09','1608905965','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('56','0.08','1608906241','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('57','0.08','1608906517','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('58','0.08','1608906792','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('59','0.08','1608907067','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('60','0.09','1608907343','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('61','0.09','1608907619','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('62','0.08','1608907894','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('63','0.08','1608908170','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('64','0.08','1608908446','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('65','0.08','1608908721','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('66','0.08','1608908997','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('67','0.09','1608909273','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('68','0.09','1608909684','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('69','0.08','1608909959','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('70','0.09','1608910235','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('71','0.08','1608910510','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('72','0.08','1608910786','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('73','0.09','1608911061','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('74','0.09','1608911336','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('75','0.07','1608911611','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('76','0.07','1608911886','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('77','0.08','1608912161','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('78','0.08','1608912436','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('79','0.07','1608912711','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('80','0.07','1608912986','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('81','0.07','1608913261','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('82','0.07','1608913536','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('83','0.08','1608913811','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('84','0.07','1608914086','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('85','0.08','1608914361','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('86','0.07','1608914635','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('87','0.07','1608914910','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('88','0.07','1608915185','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('89','0.08','1608915460','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('90','0.07','1608915735','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('91','0.08','1608916010','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('92','0.08','1608916284','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('93','0.07','1608916559','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('94','0.08','1608916834','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('95','0.08','1608917109','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('96','0.08','1608917384','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('97','0.08','1608917659','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('98','0.08','1608917934','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('99','0.07','1608918209','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('100','0.08','1608918484','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('101','0.08','1608918759','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('102','0.07','1608919034','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('103','0.08','1608919309','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('104','0.07','1608919584','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('105','0.08','1608919859','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('106','0.07','1608920134','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('107','0.08','1608920409','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('108','0.07','1608920684','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('109','0.07','1608920959','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('110','0.07','1608921234','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('111','0.09','1608921510','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('112','0.09','1608921785','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('113','0.09','1608922060','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('114','0.09','1608922335','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('115','0.08','1608922611','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('116','0.09','1608922886','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('117','0.09','1608923161','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('118','0.09','1608923436','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('119','0.09','1608923711','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('120','0.09','1608923986','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('121','0.09','1608924261','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('122','0.08','1608924536','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('123','0.09','1608924811','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('124','0.08','1608925086','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('125','0.08','1608925360','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('126','0.09','1608925636','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('127','0.09','1608925911','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('128','0.08','1608926185','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('129','0.09','1608926460','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('130','0.09','1608926735','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('131','0.09','1608927010','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('132','0.08','1608927286','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('133','0.09','1608927561','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('134','0.08','1608927836','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('135','0.08','1608928112','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('136','0.09','1608928387','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('137','0.08','1608928662','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('138','0.09','1608928937','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('139','0.09','1608929212','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('140','0.08','1608929487','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('141','0.08','1608929763','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('142','0.09','1608930038','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('143','0.09','1608930313','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('144','0.09','1608930588','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('145','-0.09','1608930863','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('146','-0.09','1608931138','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('147','-0.09','1608931412','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('148','-0.09','1608931687','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('149','-0.09','1608931961','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('150','-0.09','1608932236','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('151','-0.09','1608932511','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('152','-0.08','1608932785','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('153','-0.09','1608933060','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('154','-0.09','1608933334','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('155','-0.08','1608933609','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('156','-0.09','1608933883','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('157','-0.09','1608934158','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('158','-0.09','1608934432','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('159','-0.09','1608934707','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('160','-0.09','1608934982','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('161','-0.09','1608935256','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('162','-0.09','1608935531','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('163','-0.09','1608935805','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('164','-0.09','1608936080','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('165','-0.09','1608936354','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('166','-0.09','1608936629','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('167','-0.09','1608936904','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('168','-0.09','1608937178','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('169','-0.09','1608937453','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('170','-0.09','1608937728','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('171','-0.09','1608938003','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('172','-0.09','1608938277','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('173','-0.09','1608938552','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('174','-0.09','1608938826','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('175','-0.09','1608939101','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('176','-0.09','1608939376','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('177','-0.09','1608939650','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('178','-0.09','1608939925','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('179','-0.09','1608940199','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('180','-0.1','1608940474','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('181','-0.09','1608940748','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('182','-0.09','1608941023','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('183','-0.09','1608941297','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('184','-0.09','1608941572','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('185','-0.09','1608941847','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('186','-0.09','1608942121','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('187','-0.1','1608942396','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('188','-0.1','1608942670','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('189','-0.09','1608942945','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('190','-0.1','1608943220','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('191','-0.1','1608943494','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('192','-0.1','1608943769','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('193','-0.09','1608944043','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('194','-0.1','1608944318','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('195','-0.1','1608944593','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('196','-0.1','1608944867','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('197','-0.1','1608945142','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('198','-0.1','1608945416','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('199','-0.1','1608945691','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('200','-0.1','1608945966','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('201','-0.1','1608946240','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('202','-0.1','1608946515','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('203','-0.1','1608946789','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('204','-0.1','1608947064','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('205','-0.1','1608947338','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('206','-0.1','1608947613','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('207','-0.1','1608947888','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('208','-0.1','1608948162','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('209','-0.1','1608948437','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('210','-0.1','1608948711','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('211','-0.1','1608948986','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('212','-0.1','1608949260','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('213','-0.1','1608949535','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('214','-0.1','1608949810','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('215','-0.1','1608950084','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('216','-0.1','1608950359','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('217','-0.1','1608950634','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('218','-0.1','1608950908','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('219','-0.1','1608951183','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('220','-0.1','1608951457','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('221','-0.1','1608951732','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('222','-0.1','1608952007','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('223','-0.1','1608952281','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('224','-0.1','1608952556','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('225','-0.1','1608952830','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('226','-0.1','1608953105','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('227','-0.1','1608953379','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('228','-0.1','1608953654','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('229','-0.1','1608953929','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('230','-0.1','1608954203','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('231','-0.1','1608954478','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('232','-0.1','1608954752','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('233','-0.1','1608955027','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('234','-0.1','1608955302','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('235','-0.1','1608955577','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('236','-0.1','1608955851','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('237','-0.1','1608956126','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('238','-0.1','1608956401','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('239','-0.1','1608956675','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('240','-0.1','1608956950','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('241','-0.1','1608957225','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('242','-0.1','1608957499','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('243','-0.1','1608957774','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('244','-0.1','1608958048','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('245','-0.1','1608958323','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('246','-0.1','1608958598','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('247','-0.1','1608958873','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('248','-0.1','1608959147','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('249','-0.1','1608959422','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('250','-0.1','1608959697','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('251','-0.1','1608959971','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('252','-0.1','1608960246','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('253','-0.1','1608960521','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('254','-0.1','1608960795','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('255','-0.1','1608961070','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('256','-0.1','1608961344','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('257','-0.1','1608961619','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('258','-0.1','1608961894','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('259','-0.1','1608962168','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('260','-0.1','1608962443','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('261','-0.1','1608962717','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('262','-0.1','1608962992','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('263','-0.1','1608963267','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('264','-0.1','1608963541','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('265','-0.1','1608963816','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('266','-0.1','1608964091','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('267','-0.1','1608964365','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('268','-0.1','1608964640','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('269','-0.1','1608964915','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('270','-0.1','1608965189','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('271','-0.1','1608965464','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('272','-0.1','1608965739','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('273','-0.1','1608966013','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('274','-0.1','1608966288','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('275','-0.1','1608966562','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('276','-0.11','1608966837','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('277','-0.1','1608967112','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('278','-0.1','1608967386','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('279','-0.11','1608967661','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('280','-0.11','1608967935','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('281','-0.1','1608968210','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('282','-0.1','1608968484','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('283','-0.1','1608968759','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('284','-0.1','1608969033','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('285','-0.1','1608969308','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('286','-0.1','1608969582','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('287','-0.09','1608969857','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('288','-0.09','1608970132','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('289','-0.09','1608970406','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('290','-0.09','1608970681','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('291','-0.09','1608970955','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('292','-0.09','1608971230','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('293','-0.09','1608971504','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('294','-0.09','1608971779','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('295','-0.09','1608972053','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('296','-0.09','1608972328','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('297','-0.09','1608972603','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('298','-0.09','1608972877','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('299','-0.09','1608973151','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('300','-0.09','1608973426','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('301','-0.09','1608973700','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('302','-0.09','1608973975','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('303','-0.09','1608974249','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('304','-0.09','1608974523','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('305','-0.09','1608974798','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('306','-0.09','1608975073','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('307','-0.09','1608975347','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('308','-0.09','1608975622','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('309','-0.09','1608975896','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('310','-0.09','1608976171','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('311','-0.09','1608976445','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('312','-0.09','1608976720','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('313','-0.09','1608976994','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('314','-0.09','1608977269','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('315','-0.09','1608977543','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('316','-0.09','1608977817','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('317','-0.09','1608978092','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('318','-0.09','1608978366','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('319','-0.09','1608978641','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('320','-0.08','1608978915','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('321','-0.08','1608979190','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('322','-0.08','1608979464','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('323','-0.08','1608979739','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('324','-0.08','1608980014','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('325','-0.08','1608980288','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('326','-0.08','1608980562','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('327','-0.08','1608980837','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('328','-0.07','1608981112','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('329','-0.08','1608981386','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('330','-0.08','1608981661','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('331','-0.09','1608981935','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('332','-0.1','1608982209','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('333','-0.09','1608982484','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('334','-0.09','1608982759','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('335','-0.09','1608983033','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('336','-0.09','1608983307','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('337','-0.09','1608983582','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('338','-0.09','1608983857','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('339','-0.09','1608984131','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('340','-0.09','1608984405','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('341','-0.09','1608984680','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('342','-0.09','1608984954','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('343','-0.09','1608985229','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('344','-0.09','1608985503','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('345','-0.09','1608985777','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('346','-0.08','1608986052','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('347','-0.08','1608986327','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('348','-0.08','1608986601','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('349','-0.08','1608986876','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('350','-0.08','1608987150','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('351','-0.08','1608987425','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('352','-0.07','1608987700','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('353','-0.08','1608987974','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('354','-0.08','1608988249','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('355','-0.07','1608988524','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('356','-0.07','1608988798','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('357','-0.08','1608989073','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('358','-0.07','1608989348','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('359','-0.07','1608989623','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('360','-0.08','1608989897','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('361','-0.07','1608990172','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('362','-0.07','1608990447','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('363','-0.07','1608990722','0');
INSERT INTO "sensor_temperature_meat4_data" ("id","value","last_change","key") VALUES ('364','-0.07','1608990996','0');

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
INSERT INTO "debug" ("id","key","value","last_change") VALUES ('3','loglevel_file','20.0','0');
INSERT INTO "debug" ("id","key","value","last_change") VALUES ('4','loglevel_console','20.0','0');

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
INSERT INTO "system" ("id","key","value","last_change") VALUES ('1','pi_revision','c03111','1608891090');
INSERT INTO "system" ("id","key","value","last_change") VALUES ('3','pi_ager_version','3.2.2 Bugfix 1','0');

----
-- Drop table for agingtable_dryaging1
----
DROP TABLE IF EXISTS "agingtable_dryaging1";

----
-- Table structure for agingtable_dryaging1
----
CREATE TABLE 'agingtable_dryaging1' ('id' INTEGER DEFAULT 0 PRIMARY KEY NOT NULL ,'modus' INTEGER, "setpoint_humidity" INTEGER, "setpoint_temperature" INTEGER, "circulation_air_duration" INTEGER,"circulation_air_period" INTEGER, "exhaust_air_duration" INTEGER, "exhaust_air_period" INTEGER, "days" INTEGER DEFAULT 0 NOT NULL, 'comment' TEXT);

----
-- Data dump for agingtable_dryaging1, a total of 6 rows
----
INSERT INTO "agingtable_dryaging1" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","days","comment") VALUES ('1','4','85','2','1440','2160','900','12960','12','Testtabelle');
INSERT INTO "agingtable_dryaging1" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","days","comment") VALUES ('2',NULL,'30','4','2520','1080',NULL,'6480','8',NULL);
INSERT INTO "agingtable_dryaging1" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","days","comment") VALUES ('3',NULL,'70',NULL,'1080','2520',NULL,'15120','8',NULL);
INSERT INTO "agingtable_dryaging1" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","days","comment") VALUES ('4',NULL,'60',NULL,'720','2880',NULL,'17280','8',NULL);
INSERT INTO "agingtable_dryaging1" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","days","comment") VALUES ('5',NULL,'45','7','648','2952',NULL,'17712','12',NULL);
INSERT INTO "agingtable_dryaging1" ("id","modus","setpoint_humidity","setpoint_temperature","circulation_air_duration","circulation_air_period","exhaust_air_duration","exhaust_air_period","days","comment") VALUES ('6',NULL,'33',NULL,'540','3060',NULL,'18360','8',NULL);

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
INSERT INTO "scale1_settings" ("id","key","value","last_change") VALUES ('6','referenceunit','210.7','1600024093');
INSERT INTO "scale1_settings" ("id","key","value","last_change") VALUES ('7','measuring_interval','300.0','0');
INSERT INTO "scale1_settings" ("id","key","value","last_change") VALUES ('8','measuring_duration','15.0','0');
INSERT INTO "scale1_settings" ("id","key","value","last_change") VALUES ('9','saving_period','30.0','0');
INSERT INTO "scale1_settings" ("id","key","value","last_change") VALUES ('10','offset','507.115793582919','1600024118');

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
INSERT INTO "scale2_settings" ("id","key","value","last_change") VALUES ('6','referenceunit','500.0','1606926195');
INSERT INTO "scale2_settings" ("id","key","value","last_change") VALUES ('7','measuring_interval','300.0','0');
INSERT INTO "scale2_settings" ("id","key","value","last_change") VALUES ('8','measuring_duration','15.0','0');
INSERT INTO "scale2_settings" ("id","key","value","last_change") VALUES ('9','saving_period','30.0','0');
INSERT INTO "scale2_settings" ("id","key","value","last_change") VALUES ('10','offset','550227.920227908','1606926216');

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
INSERT INTO "config_email_recipient" ("id","to_mail","active") VALUES ('1','severin.hoffmann@t-online.de','1');

----
-- Drop table for config_telegram
----
DROP TABLE IF EXISTS "config_telegram";

----
-- Table structure for config_telegram
----
CREATE TABLE "config_telegram" (
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
    "user_key" TEXT NOT NULL,
    "api_token" TEXT NOT NULL,
    "active" INTEGER
);

----
-- Data dump for config_pushover, a total of 0 rows
----

----
-- Drop table for config_nfs_backup
----
DROP TABLE IF EXISTS "config_nfs_backup";

----
-- Table structure for config_nfs_backup
----
CREATE TABLE "config_nfs_backup" (
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
-- Drop table for config_alarm
----
DROP TABLE IF EXISTS "config_alarm";

----
-- Table structure for config_alarm
----
CREATE TABLE "config_alarm" (
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
INSERT INTO "config_alarm" ("alarm","replication","sleep","high_time","low_time","waveform","frequency") VALUES ('short','3','0.5','0.5','0.5','',NULL);

----
-- Drop table for config_email_server
----
DROP TABLE IF EXISTS "config_email_server";

----
-- Table structure for config_email_server
----
CREATE TABLE config_email_server (
    "server" TEXT NOT NULL,
    "user" TEXT NOT NULL,
    "password" TEXT NOT NULL,
    "starttls" INTEGER,
    "from_mail" TEXT NOT NULL,
    "port" INTEGER NOT NULL
);

----
-- Data dump for config_email_server, a total of 1 rows
----
INSERT INTO "config_email_server" ("server","user","password","starttls","from_mail","port") VALUES ('securesmtp.t-online.de','severin.hoffmann@t-online.de','gAAAAABf3dOOVrmMmFaESny0XNBaT5P3R5HiX8Jz-rfxivk5gzja0QCvPmnZxktM3k_ln6yO9evvLiRRKelEd9prngZrpVZ2eQ==','1','Pi-Ager_Test@test.de','465');

----
-- Drop table for config_messenger
----
DROP TABLE IF EXISTS "config_messenger";

----
-- Table structure for config_messenger
----
CREATE TABLE config_messenger (
    "exception" TEXT NOT NULL,
    "e-mail" INTEGER,
    "pushover" INTEGER,
    "telegram" INTEGER,
    "alarm" TEXT,
    "raise_exception" INTEGER
, "active" INTEGER);

----
-- Data dump for config_messenger, a total of 4 rows
----
INSERT INTO "config_messenger" ("exception","e-mail","pushover","telegram","alarm","raise_exception","active") VALUES ('cx_Sensor_not_defined','1','1','1','short','0','1');
INSERT INTO "config_messenger" ("exception","e-mail","pushover","telegram","alarm","raise_exception","active") VALUES ('OperationalError','1','1','1','short','1','1');
INSERT INTO "config_messenger" ("exception","e-mail","pushover","telegram","alarm","raise_exception","active") VALUES ('FileNotFoundError','1','1','1','short','1','1');
INSERT INTO "config_messenger" ("exception","e-mail","pushover","telegram","alarm","raise_exception","active") VALUES ('OSError','1','1','1','short','1','1');

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
-- Drop table for current_values
----
DROP TABLE IF EXISTS "current_values";

----
-- Table structure for current_values
----
CREATE TABLE "current_values" ('id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 'key' TEXT NOT NULL, 'value' REAL, 'last_change' INTEGER NOT NULL);

----
-- Data dump for current_values, a total of 29 rows
----
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('1','sensor_temperature','25.06','1608991189');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('2','sensor_humidity','33.02','1608991189');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('3','status_circulating_air','0.0','1608991189');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('4','status_cooling_compressor','0.0','1608991189');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('5','status_exhaust_air','0.0','1608991189');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('6','status_heater','0.0','1608991189');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('7','status_light','0.0','1608991189');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('8','status_uv','0.0','1608991189');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('9','status_humidifier','0.0','1608991189');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('10','status_dehumidifier','0.0','1608991189');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('11','scale1','-498.256435886352','1608990936');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('12','scale2','-550223.327500635','1608990951');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('13','status_piager','0.0','1608991179');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('14','status_agingtable','0.0','1608991179');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('15','status_scale1','0.0','1608991160');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('16','status_scale2','0.0','1608991166');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('17','status_tara_scale1','0.0','1608641257');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('18','status_tara_scale2','0.0','1608641257');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('19','agingtable_period','0.0','1608991173');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('20','agingtable_period_starttime','1608911144.0','1608911144');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('21','status_light_manual','0.0','1608641257');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('22','calibrate_scale1','0.0','1608641257');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('23','calibrate_scale2','0.0','1608641257');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('24','calibrate_weight','0.0','1608641257');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('25','status_uv_manual','0.0','1608641257');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('26','temperature_meat1','25.92','1608991189');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('27','temperature_meat2',NULL,'1608991189');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('28','temperature_meat3',NULL,'1608991189');
INSERT INTO "current_values" ("id","key","value","last_change") VALUES ('29','temperature_meat4','-0.08','1608991189');
COMMIT;
