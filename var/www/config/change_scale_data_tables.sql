----
-- phpLiteAdmin database dump (https://www.phpliteadmin.org/)
-- phpLiteAdmin version: 1.9.8.2
-- Exported: 11:16am on December 30, 2020 (CET)
-- database file: ./config/pi-ager.sqlite3
----
BEGIN TRANSACTION;

----
-- Drop table for scale1_data
----

DROP TABLE IF EXISTS "scale1_data";


CREATE TABLE "scale1_data" (
	"id"	INTEGER NOT NULL DEFAULT 0,
	"value"	REAL DEFAULT 0,
	"last_change"	INTEGER NOT NULL DEFAULT 0,
	"key"	TEXT NOT NULL DEFAULT 0,
	PRIMARY KEY("id" AUTOINCREMENT)
);

----
-- Drop table for scale2_data
----

DROP TABLE IF EXISTS "scale2_data";


CREATE TABLE "scale2_data" (
	"id"	INTEGER NOT NULL DEFAULT 0,
	"value"	REAL DEFAULT 0,
	"last_change"	INTEGER NOT NULL DEFAULT 0,
	"key"	TEXT NOT NULL DEFAULT 0,
	PRIMARY KEY("id" AUTOINCREMENT)
);


COMMIT;
