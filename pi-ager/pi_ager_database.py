import sqlite3
import time
import pi_ager_debug
import pi_ager_names
import pi_ager_paths

global cursor
global connection

def get_current_time():
    current_time = int(time.time())
    return current_time

def connect_database(command):
    global cursor
    global connection
    rows = None
    connection = sqlite3.connect(pi_ager_paths.sqlite3_file)
    connection.row_factory = sqlite3.Row
    cursor = connection.cursor()
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
    if pi_ager_debug.debugging == 'on':
        print(sql)
    connect_database(sql)
    row = cursor.fetchone()
    close_database()
    if pi_ager_debug.debugging == 'on':
        print ("DEBUG: " + str(row[0]))
        print ("DEBUG: " + str(row.keys()))
    value = row['value']
    return value

def get_scale_table_row(table):
    global cursor
    sql='SELECT ' + pi_ager_names.value_field + ',' + pi_ager_names.last_change_field + ' FROM ' + table + ' WHERE id = (SELECT MAX(id) from ' + table + ')'
    if pi_ager_debug.debugging == 'on':
        print(sql)
    connect_database(sql)
    row = cursor.fetchone()
    close_database()
    if pi_ager_debug.debugging == 'on':
        print ("DEBUG: " + str(row[0]))
        print ("DEBUG: " + str(row.keys()))
    return row

def read_config():
    global cursor
    connect_database('SELECT * FROM ' + pi_ager_names.config_settings_table + ' WHERE ' + pi_ager_names.key_field + ' ="sensortype" OR "key"="language" OR "key"="switch_on_cooling_compressor" OR "key" = "switch_off_cooling_compressor"OR "key"="switch_on_humidifier" OR "key" = "switch_off_humidifier" OR "key" = "delay_humidify" OR "key" = "uv_modus" OR "key" = "uv_duration" OR "key" = "uv_period" OR "key" = "switch_on_uv_hour" OR "key" = "switch_on_uv_minute" OR "key" = "light_modus" OR "key" = "light_duration" OR "key" = "light_period" OR "key" = "switch_on_light_hour" OR "key" = "switch_on_light_minute" OR "key" = "dehumidifier_modus" OR "key" = "referenceunit_scale1" OR "key" = "referenceunit_scale2"')
    rows = cursor.fetchall()
    close_database()
    return rows

# def read_scales():
    # global cursor
    # connect_database('SELECT * FROM current WHERE ' + pi_ager_names.key_field + ' = "scale1" OR "key" = "scale2"')
    # rows = cursor.fetchall()
    # if pi_ager_debug.debugging == 'on':
        # print ("DEBUG: " + rows)
        # print ("DEBUG: " + rows.keys())
    # close_database()
    # return rows

def read_settings():
    global cursor
    connect_database('SELECT * FROM ' + pi_ager_names.config_settings_table + ' WHERE ' + pi_ager_names.key_field + ' ="modus" OR "key"="setpoint_temperature" OR "key"="setpoint_humidity" OR "key"="circulation_air_period" OR "key"="circulation_air_duration" OR "key"="exhaust_air_period" OR "key"="exhaust_air_duration"')
    rows = cursor.fetchall()
    close_database()
    return rows

def read_agingtable_name():
    global cursor
    connect_database('SELECT * FROM ' + pi_ager_names.config_settings_table + ' WHERE ' + pi_ager_names.key_field + ' ="agingtable"')
    rows = cursor.fetchall()
    close_database()
    return rows

def get_current(table, all_rows):
    global cursor
    if all_rows:
        connect_database('SELECT * FROM ' + table)
    else:
        connect_database('SELECT * FROM ' + table + ' o WHERE "o.id" = (SELECT MAX(i.id) from ' + table + ' i)')
    
    rows = cursor.fetchall()
    close_database()
    return rows

def write_current(sensor_temperature, status_heater, status_exhaust_air, status_cooling_compressor, status_circulating_air, sensor_humidity, status_uv, status_light):
    if pi_ager_debug.debugging=='on':
        print('DEBUG: in write_current')
        print('INSERT INTO ' + pi_ager_names.sensor_temperature_table + '(' + str(pi_ager_names.value_field) + ',' + str(pi_ager_names.last_change_field) +') VALUES ('+ str(sensor_temperature) + ', ' + str(get_current_time()) + ')')
    connect_database('INSERT INTO ' + pi_ager_names.sensor_temperature_table + '(' + str(pi_ager_names.value_field) + ',' + str(pi_ager_names.last_change_field) +') VALUES ('+ str(sensor_temperature) + ', ' + str(get_current_time()) + ')')
    connect_database('INSERT INTO ' + pi_ager_names.status_heater_table + ' (' + str(pi_ager_names.value_field) + ',' + str(pi_ager_names.last_change_field) +') VALUES ('+ str(status_heater) + ',' + str(get_current_time()) + ')')
    connect_database('INSERT INTO ' + pi_ager_names.status_exhaust_air_table + ' (' + str(pi_ager_names.value_field) + ',' + str(pi_ager_names.last_change_field) +') VALUES ('+ str(status_exhaust_air) + ',' + str(get_current_time()) + ')')
    connect_database('INSERT INTO ' + pi_ager_names.status_cooling_compressor_table + ' (' + str(pi_ager_names.value_field) + ',' + str(pi_ager_names.last_change_field) +') VALUES ('+ str(status_cooling_compressor) + ',' + str(get_current_time()) + ')')
    connect_database('INSERT INTO ' + pi_ager_names.status_circulating_air_table + ' (' + str(pi_ager_names.value_field) + ',' + str(pi_ager_names.last_change_field) +') VALUES ('+ str(status_circulating_air) + ',' + str(get_current_time()) + ')')
    connect_database('INSERT INTO ' + pi_ager_names.sensor_humidity_table + ' (' + str(pi_ager_names.value_field) + ',' + str(pi_ager_names.last_change_field) +') VALUES ('+ str(sensor_humidity) + ',' + str(get_current_time()) + ')')
    connect_database('INSERT INTO ' + pi_ager_names.status_uv_table + ' (' + str(pi_ager_names.value_field) + ',' + str(pi_ager_names.last_change_field) +') VALUES ('+ str(status_uv) + ',' + str(get_current_time()) + ')')
    connect_database('INSERT INTO ' + pi_ager_names.status_light_table + ' (' + str(pi_ager_names.value_field) + ',' + str(pi_ager_names.last_change_field) +') VALUES ('+ str(status_light) + ',' + str(get_current_time()) + ')')
    close_database()

def write_scale(scale_table,value_scale):
    connect_database('INSERT INTO ' + scale_table + ' (' + str(pi_ager_names.value_field) + ',' + str(pi_ager_names.last_change_field) +') VALUES (' + str(value_scale) + ',' + str(get_current_time()) + ')')
    close_database()

def write_tables(agingtable):
    connect_database('UPDATE ' + pi_ager_names.config_settings_table + ' SET ' + pi_ager_names.value_field + '" = "' + agingtable +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' = "agingtable"')
    close_database()

def write_settings(modus, setpoint_temperature, setpoint_humidity, circulation_air_period, circulation_air_duration, exhaust_air_period, exhaust_air_duration):
    connect_database('UPDATE ' + pi_ager_names.config_settings_table + ' SET ' + pi_ager_names.value_field + '" = "' + str(modus) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' ="modus"')
    connect_database('UPDATE ' + pi_ager_names.config_settings_table + ' SET ' + pi_ager_names.value_field + '" = "' + str(setpoint_temperature) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' ="setpoint_temperature"')
    connect_database('UPDATE ' + pi_ager_names.config_settings_table + ' SET ' + pi_ager_names.value_field + '" = "' + str(setpoint_humidity) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' ="setpoint_humidity"')
    connect_database('UPDATE ' + pi_ager_names.config_settings_table + ' SET ' + pi_ager_names.value_field + '" = "' + str(circulation_air_period) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' ="circulation_air_period"')
    connect_database('UPDATE ' + pi_ager_names.config_settings_table + ' SET ' + pi_ager_names.value_field + '" = "' + str(circulation_air_duration) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' ="circulation_air_duration"')
    connect_database('UPDATE ' + pi_ager_names.config_settings_table + ' SET ' + pi_ager_names.value_field + '" = "' + str(exhaust_air_period) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' ="exhaust_air_period"')
    connect_database('UPDATE ' + pi_ager_names.config_settings_table + ' SET ' + pi_ager_names.value_field + '" = "' + str(exhaust_air_duration) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' ="exhaust_air_duration"')
    close_database()

def write_config(sensortype, language, switch_on_cooling_compressor, switch_off_cooling_compressor, switch_on_humidifier, switch_off_humidifier, delay_humidify, uv_modus, uv_duration, uv_period, switch_on_uv_hour, switch_on_uv_minute, light_modus, light_duration, light_period, switch_on_light_hour, switch_on_light_minute, dehumidifier_modus, referenceunit_scale1, referenceunit_scale2):
    connect_database('UPDATE ' + pi_ager_names.config_settings_table + ' SET ' + pi_ager_names.value_field + '" = "' + str(sensortype) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' ="sensortype"')
    connect_database('UPDATE ' + pi_ager_names.config_settings_table + ' SET ' + pi_ager_names.value_field + '" = "' + str(language) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' ="language"')
    connect_database('UPDATE ' + pi_ager_names.config_settings_table + ' SET ' + pi_ager_names.value_field + '" = "' + str(switch_on_cooling_compressor) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' ="switch_on_cooling_compressor"')
    connect_database('UPDATE ' + pi_ager_names.config_settings_table + ' SET ' + pi_ager_names.value_field + '" = "' + str(switch_off_cooling_compressor) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' ="switch_off_cooling_compressor"')
    connect_database('UPDATE ' + pi_ager_names.config_settings_table + ' SET ' + pi_ager_names.value_field + '" = "' + str(switch_on_humidifier) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' ="switch_on_humidifier"')
    connect_database('UPDATE ' + pi_ager_names.config_settings_table + ' SET ' + pi_ager_names.value_field + '" = "' + str(switch_off_humidifier) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' ="switch_off_humidifier"')
    connect_database('UPDATE ' + pi_ager_names.config_settings_table + ' SET ' + pi_ager_names.value_field + '" = "' + str(delay_humidify) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' ="delay_humidify"')
    connect_database('UPDATE ' + pi_ager_names.config_settings_table + ' SET ' + pi_ager_names.value_field + '" = "' + str(uv_modus) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' ="uv_modus"')
    connect_database('UPDATE ' + pi_ager_names.config_settings_table + ' SET ' + pi_ager_names.value_field + '" = "' + str((uv_duration / 60)) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' ="uv_duration"')
    connect_database('UPDATE ' + pi_ager_names.config_settings_table + ' SET ' + pi_ager_names.value_field + '" = "' + str((uv_period /60)) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' ="uv_period"')
    connect_database('UPDATE ' + pi_ager_names.config_settings_table + ' SET ' + pi_ager_names.value_field + '" = "' + str(switch_on_uv_hour) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' ="switch_on_uv_hour"')
    connect_database('UPDATE ' + pi_ager_names.config_settings_table + ' SET ' + pi_ager_names.value_field + '" = "' + str(switch_on_uv_minute) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' ="switch_on_uv_minute"')
    connect_database('UPDATE ' + pi_ager_names.config_settings_table + ' SET ' + pi_ager_names.value_field + '" = "' + str(light_modus) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' ="light_modus"')
    connect_database('UPDATE ' + pi_ager_names.config_settings_table + ' SET ' + pi_ager_names.value_field + '" = "' + str((light_duration / 60)) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' ="light_duration"')
    connect_database('UPDATE ' + pi_ager_names.config_settings_table + ' SET ' + pi_ager_names.value_field + '" = "' + str((light_period / 60)) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' = "' + pi_ager_names.light_period_key + '"')
    connect_database('UPDATE ' + pi_ager_names.config_settings_table + ' SET ' + pi_ager_names.value_field + '" = "' + str(switch_on_light_hour) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' = "' + pi_ager_names.switch_on_light_hour_key + '"')
    connect_database('UPDATE ' + pi_ager_names.config_settings_table + ' SET ' + pi_ager_names.value_field + '" = "' + str(switch_on_light_minute) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' = "' + pi_ager_names.switch_on_light_minute_key + '"')
    connect_database('UPDATE ' + pi_ager_names.config_settings_table + ' SET ' + pi_ager_names.value_field + '" = "' + str(dehumidifier_modus) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' = "' + pi_ager_names.dehumidifier_modus_key + '"')
    connect_database('UPDATE ' + pi_ager_names.config_settings_table + ' SET ' + pi_ager_names.value_field + '" = "' + str(referenceunit_scale1) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' = "' + pi_ager_names.referenceunit_scale1_key + '"')
    connect_database('UPDATE ' + pi_ager_names.config_settings_table + ' SET ' + pi_ager_names.value_field + '" = "' + str(referenceunit_scale2) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' = "' + pi_ager_names.referenceunit_scale2_key + '"')
    close_database()
    
    
    
    
    
    
    