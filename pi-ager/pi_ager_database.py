#!/usr/bin/python3
import sqlite3
import time
import pi_ager_names
import pi_ager_paths
# from pi_ager_logging import create_logger

global cursor
global connection

#global logger
#logger = create_logger(__name__)
#logger.debug('logging initialised')


def get_current_time():
    current_time = int(time.time())
    return current_time

def open_database():
    global cursor
    global connection
    rows = None
    connection = sqlite3.connect(pi_ager_paths.sqlite3_file)
    connection.row_factory = sqlite3.Row
    cursor = connection.cursor()

def execute_query(command):
    global cursor
    global connection
    cursor.execute(command)
    connection.commit()

def close_database():
    global connection
    connection.close()

def get_table_value(table, key):
    global cursor

    if key == None:
        sql='SELECT ' + pi_ager_names.value_field + ' FROM ' + table + ' o WHERE o.id = (SELECT MAX(i.id) from ' + table + ')'
    else:
        sql='SELECT ' + pi_ager_names.value_field + ' FROM ' + table + ' o WHERE o.key = "' + key + '" AND o.id = (SELECT MAX(i.id) from ' + table + ' i WHERE i.key = "' + key + '")'
    open_database()
    execute_query(sql)
    row = cursor.fetchone()
    close_database()
    value = row[pi_ager_names.value_field]
    return value

def get_last_change(table, key):
    global cursor

    if key == None:
        return None
    else:
        sql='SELECT ' + pi_ager_names.last_change_field + ' FROM ' + table + ' o WHERE o.key = "' + key + '" AND o.id = (SELECT MAX(i.id) from ' + table + ' i WHERE i.key = "' + key + '")'
    open_database()
    execute_query(sql)
    row = cursor.fetchone()
    close_database()
    last_change = row[pi_ager_names.last_change_field]
    return last_change

def write_current_value(key, value):
    global cursor

    if key == None:
        logstring = 'key ist None: ' + key + ' zu schreibender Wert: ' + str(value)
        #logger.debug(logstring)
        return
    sql='UPDATE ' + pi_ager_names.current_values_table + ' SET "' + pi_ager_names.value_field + '" = "' + str(value) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) + ' WHERE ' + pi_ager_names.key_field + ' = "' + key + '"'
    open_database()
    execute_query(sql)
    close_database()
    logstring = 'write_current_value Tabelle: ' + pi_ager_names.current_values_table + ' geschriebener Key/Value: ' + key + '/' + str(value)
    # logger.debug(logstring)

def get_scale_table_row(table):
    global cursor
    sql='SELECT ' + pi_ager_names.value_field + ', ' + pi_ager_names.last_change_field + ' FROM ' + table + ' WHERE id = (SELECT MAX(id) from ' + table + ')'
    open_database()
    execute_query(sql)
    row = cursor.fetchone()        
    close_database()
    return row

def get_agingtable_as_rows(agingtable):
    global cursor
    sql='SELECT * from agingtable_' + agingtable
    
    open_database()
    execute_query(sql)
    rows = cursor.fetchall()
    close_database()
    return rows

def get_scale_settings_from_table(scale_settings_table):
    global cursor

    open_database()
    sql = 'SELECT * FROM ' + scale_settings_table
    execute_query(sql)
    rows = cursor.fetchall()

    close_database()
    return rows

def read_config():
    global cursor
    open_database()
    execute_query('SELECT * FROM ' + pi_ager_names.config_settings_table + ' WHERE ' + pi_ager_names.key_field + ' ="sensortype" OR "key"="language" OR "key"="switch_on_cooling_compressor" OR "key" = "switch_off_cooling_compressor"OR "key"="switch_on_humidifier" OR "key" = "switch_off_humidifier" OR "key" = "delay_humidify" OR "key" = "uv_modus" OR "key" = "uv_duration" OR "key" = "uv_period" OR "key" = "switch_on_uv_hour" OR "key" = "switch_on_uv_minute" OR "key" = "light_modus" OR "key" = "light_duration" OR "key" = "light_period" OR "key" = "switch_on_light_hour" OR "key" = "switch_on_light_minute" OR "key" = "dehumidifier_modus" OR "key" = "referenceunit_scale1" OR "key" = "referenceunit_scale2"')
    rows = cursor.fetchall()
    close_database()
    return rows

def get_logging_value(destination):
    open_database()
    execute_query('SELECT ' + pi_ager_names.value_field + ' FROM ' + pi_ager_names.debug_table + ' WHERE ' + pi_ager_names.key_field + ' = "' + destination + '"')
    row = cursor.fetchone()
    close_database()
    logging_value = row[pi_ager_names.value_field]
    
    return logging_value
    
def read_settings():
    global cursor
    open_database()
    execute_query('SELECT * FROM ' + pi_ager_names.config_settings_table + ' WHERE ' + pi_ager_names.key_field + ' ="modus" OR "key"="setpoint_temperature" OR "key"="setpoint_humidity" OR "key"="circulation_air_period" OR "key"="circulation_air_duration" OR "key"="exhaust_air_period" OR "key"="exhaust_air_duration"')
    rows = cursor.fetchall()
    close_database()
    return rows

def read_agingtable_name_from_config():
    global cursor
    
    id_agingtable = get_table_value(pi_ager_names.config_settings_table, pi_ager_names.agingtable_key)
    sql = 'SELECT ' + pi_ager_names.agingtable_name_field + ' FROM ' + pi_ager_names.agingtables_table + ' WHERE ' + pi_ager_names.id_field + ' = ' + str(id_agingtable);
    open_database()
    execute_query(sql)
    row = cursor.fetchone()
    close_database()
    name = row[pi_ager_names.agingtable_name_field]
    return name

def get_current(table, all_rows):
    global cursor
    open_database()
    if all_rows:
        execute_query('SELECT * FROM ' + table)
    else:
        execute_query('SELECT * FROM ' + table + ' o WHERE "o.id" = (SELECT MAX(i.id) from ' + table + ' i)')
    
    rows = cursor.fetchall()
    close_database()
    return rows

def get_status_light_manual():
    status_light_manual = get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_light_manual_key)
    return status_light_manual
    
def write_current(loopnumber, sensor_temperature, status_heater, status_exhaust_air, status_cooling_compressor, status_circulating_air, sensor_humidity, status_uv, status_light, status_humidifier, status_dehumidifier):

    open_database()
    
    if loopnumber % 150 == 0:   # schreibt alle 150 Loops die Werte in die DB
        execute_query('INSERT INTO ' + pi_ager_names.data_sensor_temperature_table + '(' + str(pi_ager_names.value_field) + ',' + str(pi_ager_names.last_change_field) +') VALUES ('+ str(sensor_temperature) + ', ' + str(get_current_time()) + ')')
        execute_query('INSERT INTO ' + pi_ager_names.status_heater_table + ' (' + str(pi_ager_names.value_field) + ',' + str(pi_ager_names.last_change_field) +') VALUES ('+ str(status_heater) + ',' + str(get_current_time()) + ')')
        execute_query('INSERT INTO ' + pi_ager_names.status_exhaust_air_table + ' (' + str(pi_ager_names.value_field) + ',' + str(pi_ager_names.last_change_field) +') VALUES ('+ str(status_exhaust_air) + ',' + str(get_current_time()) + ')')
        execute_query('INSERT INTO ' + pi_ager_names.status_cooling_compressor_table + ' (' + str(pi_ager_names.value_field) + ',' + str(pi_ager_names.last_change_field) +') VALUES ('+ str(status_cooling_compressor) + ',' + str(get_current_time()) + ')')
        execute_query('INSERT INTO ' + pi_ager_names.status_circulating_air_table + ' (' + str(pi_ager_names.value_field) + ',' + str(pi_ager_names.last_change_field) +') VALUES ('+ str(status_circulating_air) + ',' + str(get_current_time()) + ')')
        execute_query('INSERT INTO ' + pi_ager_names.data_sensor_humidity_table + ' (' + str(pi_ager_names.value_field) + ',' + str(pi_ager_names.last_change_field) +') VALUES ('+ str(sensor_humidity) + ',' + str(get_current_time()) + ')')
        execute_query('INSERT INTO ' + pi_ager_names.status_uv_table + ' (' + str(pi_ager_names.value_field) + ',' + str(pi_ager_names.last_change_field) +') VALUES ('+ str(status_uv) + ',' + str(get_current_time()) + ')')
        execute_query('INSERT INTO ' + pi_ager_names.status_light_table + ' (' + str(pi_ager_names.value_field) + ',' + str(pi_ager_names.last_change_field) +') VALUES ('+ str(status_light) + ',' + str(get_current_time()) + ')')
        execute_query('INSERT INTO ' + pi_ager_names.status_humidifier_table + ' (' + str(pi_ager_names.value_field) + ',' + str(pi_ager_names.last_change_field) +') VALUES ('+ str(status_humidifier) + ',' + str(get_current_time()) + ')')
        execute_query('INSERT INTO ' + pi_ager_names.status_dehumidifier_table + ' (' + str(pi_ager_names.value_field) + ',' + str(pi_ager_names.last_change_field) +') VALUES ('+ str(status_dehumidifier) + ',' + str(get_current_time()) + ')')
        
    execute_query('UPDATE ' + pi_ager_names.current_values_table + ' SET "' + pi_ager_names.value_field + '" = "' + str(sensor_temperature) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' = "' + pi_ager_names.sensor_temperature_key + '"')
    execute_query('UPDATE ' + pi_ager_names.current_values_table + ' SET "' + pi_ager_names.value_field + '" = "' + str(status_heater) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' = "' + pi_ager_names.status_heater_key + '"')
    execute_query('UPDATE ' + pi_ager_names.current_values_table + ' SET "' + pi_ager_names.value_field + '" = "' + str(status_exhaust_air) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' = "' + pi_ager_names.status_exhaust_air_key + '"')
    execute_query('UPDATE ' + pi_ager_names.current_values_table + ' SET "' + pi_ager_names.value_field + '" = "' + str(status_cooling_compressor) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' = "' + pi_ager_names.status_cooling_compressor_key + '"')
    execute_query('UPDATE ' + pi_ager_names.current_values_table + ' SET "' + pi_ager_names.value_field + '" = "' + str(status_circulating_air) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' = "' + pi_ager_names.status_circulating_air_key + '"')
    execute_query('UPDATE ' + pi_ager_names.current_values_table + ' SET "' + pi_ager_names.value_field + '" = "' + str(sensor_humidity) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' = "' + pi_ager_names.sensor_humidity_key + '"')
    execute_query('UPDATE ' + pi_ager_names.current_values_table + ' SET "' + pi_ager_names.value_field + '" = "' + str(status_uv) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' = "' + pi_ager_names.status_uv_key + '"')
    execute_query('UPDATE ' + pi_ager_names.current_values_table + ' SET "' + pi_ager_names.value_field + '" = "' + str(status_light) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' = "' + pi_ager_names.status_light_key + '"')
    execute_query('UPDATE ' + pi_ager_names.current_values_table + ' SET "' + pi_ager_names.value_field + '" = "' + str(status_humidifier) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' = "' + pi_ager_names.status_humidifier_key + '"')
    execute_query('UPDATE ' + pi_ager_names.current_values_table + ' SET "' + pi_ager_names.value_field + '" = "' + str(status_dehumidifier) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' = "' + pi_ager_names.status_dehumidifier_key + '"')
    
    close_database()

def write_scale(scale_table,value_scale):

    if scale_table == pi_ager_names.data_scale1_table:
        scale_key = pi_ager_names.scale1_key
    elif scale_table == pi_ager_names.data_scale2_table:
        scale_key = pi_ager_names.scale2_key
        
    open_database()
    
    execute_query('INSERT INTO ' + scale_table + ' (' + str(pi_ager_names.value_field) + ',' + str(pi_ager_names.last_change_field) +') VALUES (' + str(value_scale) + ',' + str(get_current_time()) + ')')
    execute_query('UPDATE ' + pi_ager_names.current_values_table + ' SET "' + pi_ager_names.value_field + '" = "' + str(value_scale) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' = "' + scale_key + '"')
    
    close_database()

def write_tables(agingtable):

    open_database()
    
    execute_query('UPDATE ' + pi_ager_names.config_settings_table + ' SET "' + pi_ager_names.value_field + '" = "' + agingtable +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' = "agingtable"')
    
    close_database()

def write_settings(modus, setpoint_temperature, setpoint_humidity, circulation_air_period, circulation_air_duration, exhaust_air_period, exhaust_air_duration):
    open_database()

    execute_query('UPDATE ' + pi_ager_names.config_settings_table + ' SET "' + pi_ager_names.value_field + '" = "' + str(modus) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' ="modus"')
    execute_query('UPDATE ' + pi_ager_names.config_settings_table + ' SET "' + pi_ager_names.value_field + '" = "' + str(setpoint_temperature) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' ="setpoint_temperature"')
    execute_query('UPDATE ' + pi_ager_names.config_settings_table + ' SET "' + pi_ager_names.value_field + '" = "' + str(setpoint_humidity) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' ="setpoint_humidity"')
    execute_query('UPDATE ' + pi_ager_names.config_settings_table + ' SET "' + pi_ager_names.value_field + '" = "' + str(circulation_air_period) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' ="circulation_air_period"')
    execute_query('UPDATE ' + pi_ager_names.config_settings_table + ' SET "' + pi_ager_names.value_field + '" = "' + str(circulation_air_duration) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' ="circulation_air_duration"')
    execute_query('UPDATE ' + pi_ager_names.config_settings_table + ' SET "' + pi_ager_names.value_field + '" = "' + str(exhaust_air_period) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' ="exhaust_air_period"')
    execute_query('UPDATE ' + pi_ager_names.config_settings_table + ' SET "' + pi_ager_names.value_field + '" = "' + str(exhaust_air_duration) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' ="exhaust_air_duration"')

    close_database()

def write_startstop_status_in_database(module_key, status):
    
    open_database()
    
    sql = 'UPDATE ' + pi_ager_names.current_values_table + ' SET "' + pi_ager_names.value_field + '" = "' + str(status) + '" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) + ' WHERE ' + pi_ager_names.key_field + ' = "' + module_key + '"'
    execute_query(sql)
    
    close_database()

def write_start_in_database(module_key):
    write_startstop_status_in_database(module_key, 1)

def write_stop_in_database(module_key):
    write_startstop_status_in_database(module_key, 0)

def write_config(sensortype, language, switch_on_cooling_compressor, switch_off_cooling_compressor, switch_on_humidifier, switch_off_humidifier, delay_humidify, uv_modus, uv_duration, uv_period, switch_on_uv_hour, switch_on_uv_minute, light_modus, light_duration, light_period, switch_on_light_hour, switch_on_light_minute, dehumidifier_modus, referenceunit_scale1, referenceunit_scale2):

    open_database()

    execute_query('UPDATE ' + pi_ager_names.config_settings_table + ' SET "' + pi_ager_names.value_field + '" = "' + str(sensortype) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' ="sensortype"')
    execute_query('UPDATE ' + pi_ager_names.config_settings_table + ' SET "' + pi_ager_names.value_field + '" = "' + str(language) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' ="language"')
    execute_query('UPDATE ' + pi_ager_names.config_settings_table + ' SET "' + pi_ager_names.value_field + '" = "' + str(switch_on_cooling_compressor) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' ="switch_on_cooling_compressor"')
    execute_query('UPDATE ' + pi_ager_names.config_settings_table + ' SET "' + pi_ager_names.value_field + '" = "' + str(switch_off_cooling_compressor) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' ="switch_off_cooling_compressor"')
    execute_query('UPDATE ' + pi_ager_names.config_settings_table + ' SET "' + pi_ager_names.value_field + '" = "' + str(switch_on_humidifier) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' ="switch_on_humidifier"')
    execute_query('UPDATE ' + pi_ager_names.config_settings_table + ' SET "' + pi_ager_names.value_field + '" = "' + str(switch_off_humidifier) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' ="switch_off_humidifier"')
    execute_query('UPDATE ' + pi_ager_names.config_settings_table + ' SET "' + pi_ager_names.value_field + '" = "' + str(delay_humidify) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' ="delay_humidify"')
    execute_query('UPDATE ' + pi_ager_names.config_settings_table + ' SET "' + pi_ager_names.value_field + '" = "' + str(uv_modus) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' ="uv_modus"')
    execute_query('UPDATE ' + pi_ager_names.config_settings_table + ' SET "' + pi_ager_names.value_field + '" = "' + str((uv_duration / 60)) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' ="uv_duration"')
    execute_query('UPDATE ' + pi_ager_names.config_settings_table + ' SET "' + pi_ager_names.value_field + '" = "' + str((uv_period /60)) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' ="uv_period"')
    execute_query('UPDATE ' + pi_ager_names.config_settings_table + ' SET "' + pi_ager_names.value_field + '" = "' + str(switch_on_uv_hour) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' ="switch_on_uv_hour"')
    execute_query('UPDATE ' + pi_ager_names.config_settings_table + ' SET "' + pi_ager_names.value_field + '" = "' + str(switch_on_uv_minute) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' ="switch_on_uv_minute"')
    execute_query('UPDATE ' + pi_ager_names.config_settings_table + ' SET "' + pi_ager_names.value_field + '" = "' + str(light_modus) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' ="light_modus"')
    execute_query('UPDATE ' + pi_ager_names.config_settings_table + ' SET "' + pi_ager_names.value_field + '" = "' + str((light_duration / 60)) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' ="light_duration"')
    execute_query('UPDATE ' + pi_ager_names.config_settings_table + ' SET "' + pi_ager_names.value_field + '" = "' + str((light_period / 60)) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' = "' + pi_ager_names.light_period_key + '"')
    execute_query('UPDATE ' + pi_ager_names.config_settings_table + ' SET "' + pi_ager_names.value_field + '" = "' + str(switch_on_light_hour) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' = "' + pi_ager_names.switch_on_light_hour_key + '"')
    execute_query('UPDATE ' + pi_ager_names.config_settings_table + ' SET "' + pi_ager_names.value_field + '" = "' + str(switch_on_light_minute) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' = "' + pi_ager_names.switch_on_light_minute_key + '"')
    execute_query('UPDATE ' + pi_ager_names.config_settings_table + ' SET "' + pi_ager_names.value_field + '" = "' + str(dehumidifier_modus) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' = "' + pi_ager_names.dehumidifier_modus_key + '"')
    execute_query('UPDATE ' + pi_ager_names.config_settings_table + ' SET "' + pi_ager_names.value_field + '" = "' + str(referenceunit_scale1) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' = "' + pi_ager_names.referenceunit_scale1_key + '"')
    execute_query('UPDATE ' + pi_ager_names.config_settings_table + ' SET "' + pi_ager_names.value_field + '" = "' + str(referenceunit_scale2) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' = "' + pi_ager_names.referenceunit_scale2_key + '"')

    close_database()