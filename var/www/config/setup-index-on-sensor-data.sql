----
-- phpLiteAdmin database dump (https://www.phpliteadmin.org/)
-- phpLiteAdmin version: 1.9.8.2
-- Exported: 9:56am on January 2, 2021 (CET)
-- database file: ./config/pi-ager.sqlite3
----
BEGIN TRANSACTION;


----
-- Drop index for temperature
----
DROP INDEX IF EXISTS "temperature";

----
-- structure for index temperature on table sensor_temperature_data
----
CREATE INDEX 'temperature' ON "sensor_temperature_data" ("last_change" ASC);

----
-- Drop index for humidity
----
DROP INDEX IF EXISTS "humidity";

----
-- structure for index humidity on table sensor_humidity_data
----
CREATE INDEX 'humidity' ON "sensor_humidity_data" ("last_change" ASC);

----
-- Drop index for ntc1
----
DROP INDEX IF EXISTS "ntc1";

----
-- structure for index ntc1 on table sensor_temperature_meat1_data
----
CREATE INDEX 'ntc1' ON "sensor_temperature_meat1_data" ("last_change" ASC);

----
-- Drop index for ntc2
----
DROP INDEX IF EXISTS "ntc2";

----
-- structure for index ntc2 on table sensor_temperature_meat2_data
----
CREATE INDEX 'ntc2' ON "sensor_temperature_meat2_data" ("last_change" ASC);

----
-- Drop index for ntc3
----
DROP INDEX IF EXISTS "ntc3";

----
-- structure for index ntc3 on table sensor_temperature_meat3_data
----
CREATE INDEX 'ntc3' ON "sensor_temperature_meat3_data" ("last_change" ASC);

----
-- Drop index for ntc4
----
DROP INDEX IF EXISTS "ntc4";

----
-- structure for index ntc4 on table sensor_temperature_meat4_data
----
CREATE INDEX 'ntc4' ON "sensor_temperature_meat4_data" ("last_change" ASC);

----
-- Drop index for scale1
----
DROP INDEX IF EXISTS "scale1";

----
-- structure for index scale1 on table scale1_data
----
CREATE INDEX 'scale1' ON "scale1_data" ("last_change" ASC);

----
-- Drop index for scale2
----
DROP INDEX IF EXISTS "scale2";

----
-- structure for index scale2 on table scale2_data
----
CREATE INDEX 'scale2' ON "scale2_data" ("last_change" ASC);
COMMIT;
