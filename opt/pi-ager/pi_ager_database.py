#!/usr/bin/python3
# -*- coding: utf-8 -*-
"""
    database communication
    functions for communication with the database
"""


import sqlite3
import time
import pi_ager_names
import pi_ager_paths
# import pi_ager_logging

from main.pi_ager_cl_logger import cl_fact_logger

global cursor
global connection

from main.pi_ager_cl_logger import cl_fact_logger
cl_fact_logger.get_instance()
#global logger
# logger = pi_ager_logging.create_logger(__name__)
# logger.debug('logging initialised')
#logger = 
#print("xxxx")
#print(logger)

#cl_fact_logger.get_instance().debug("logging initialised __________________________")
#cl_fact_logger.get_instance().debug("logging initialised __________________________","xx")


def get_current_time():
    """
    function for reading out the current time stamp
    """
    current_time = int(time.time())
    return current_time

def open_database():
    """
    function to open the database connection
    """
    global cursor
    global connection
    rows = None
    """
    Changed due to database locked errors
        -removed: connection = sqlite3.connect(pi_ager_paths.sqlite3_file)
        
        -added timeout = 5 (five seconds)
        -enabled shared cache
        -set isolation level to None
        -set journal mode WAL
    
    See also
    https://docs.python.org/2/library/sqlite3.html#sqlite3.Connection.isolation_level
    
    http://charlesleifer.com/blog/going-fast-with-sqlite-and-python/
    """
    
    #Enable shared chache
    sqlite3.enable_shared_cache(True)
    # Open database in autocommit mode by setting isolation_level to None.
    connection = sqlite3.connect(pi_ager_paths.sqlite3_file, isolation_level=None, timeout = 10)
    # Set journal mode to WAL (Write-Ahead Log)
    connection.execute('PRAGMA journal_mode = wal')
    connection.execute('PRAGMA synchronous = OFF')
    connection.execute('PRAGMA read_uncommitted = True')
        
    connection.row_factory = sqlite3.Row
    cursor = connection.cursor()
    
    """
    cursor.execute('PRAGMA journal_mode = OFF')
    cursor.execute('PRAGMA synchronous = OFF')
    cursor.execute('PRAGMA read_uncommitted = True')
    """
def execute_query(command):
    """
    function for executing a SQL
    """
    global cursor
    global connection
    
    cursor.execute(command)
    connection.commit()
        
def close_database():
    """
    function for closing the database connection
    """
    global connection
    connection.close()

def table_exists(table):
    """
    function to check if a table exists
    """
    global cursor
    sql = 'SELECT count(*) FROM sqlite_master WHERE type="table" AND name="' + table + '";'
    
    open_database()
    execute_query(sql)
    row = cursor.fetchone()
    close_database()
    count = row['count(*)']
    
    if count >= 1:
        return True
    else:
        return False
        
def column_exists_in_table(column,table):
    """
    function to check if a column exists
    """
    try:
        sql = 'SELECT ' + column + ' FROM ' + table
        open_database()
        execute_query(sql)
        close_database()
        return True
    except:
        close_database()
        return False
        
def key_exists_in_table(key, table):
    """
    function to check if a key column exists in the table
    """
    global cursor
    sql = 'SELECT EXISTS(SELECT 1 FROM ' + table + ' WHERE ' + pi_ager_names.key_field + ' = "' + key + '" LIMIT 1) as result;'
    open_database()
    execute_query(sql)
    row = cursor.fetchone()
    close_database()
    result = row['result']
    if result == 1:
        return True
    else:
        return False
    
def get_column_infos(table):
    """
    function for obtaining the column information
    """
    global cursor
    sql = 'PRAGMA table_info(' + table + ')'
    open_database()
    execute_query(sql)
    rows = cursor.fetchall()
    close_database()
    return rows
    
def add_key_value_table(table):
    """
    function for generating a key value table
    """
    sql = 'CREATE TABLE "' + table + '" ("id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, "key" TEXT NOT NULL, "value" REAL NOT NULL, "last_change" INTEGER NOT NULL)'
    open_database()
    execute_query(sql)
    close_database()
    
def add_id_value_table(table):
    """
    function for generating an id-value table
    """
    sql = 'CREATE TABLE "' + table + '" ("id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, "value" REAL NOT NULL, "last_change" INTEGER NOT NULL)'
    open_database()
    execute_query(sql)
    close_database()
    
def get_table_value(table, key):
    """
    function to read a value to a known key
    """
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
    """
    function for reading the last change time stamp
    """
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
    
def get_table_value_last_change(table, key):
    """
    function to read value and last_change to a known key
    """
    global cursor

    sql='SELECT ' + pi_ager_names.value_field + ', ' + pi_ager_names.last_change_field + ' FROM ' + table + ' o WHERE o.key = "' + key + '" AND o.id = (SELECT MAX(i.id) from ' + table + ' i WHERE i.key = "' + key + '")'
    open_database()
    execute_query(sql)
    row = cursor.fetchone()
    close_database()

    return row
        

def write_current_value(key, value):
    """
    function for writing a value
    """
    global cursor

    if key == None:
        logstring = 'key ist None: ' + key + ' zu schreibender Wert: ' + str(value)
        # logger.debug(logstring)
        cl_fact_logger.get_instance().debug(logstring)
        return
    sql='UPDATE ' + pi_ager_names.current_values_table + ' SET "' + pi_ager_names.value_field + '" = "' + str(value) +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) + ' WHERE ' + pi_ager_names.key_field + ' = "' + key + '"'
    open_database()
    execute_query(sql)
    close_database()
    logstring = 'write_current_value Tabelle: ' + pi_ager_names.current_values_table + ' geschriebener Key/Value: ' + key + ' / ' + str(value)
    # logger.debug(logstring)
    cl_fact_logger.get_instance().debug(logstring)

def get_scale_table_row(table):
    """
    function for reading the latest value and timestamp
    """
    global cursor
    sql='SELECT ' + pi_ager_names.value_field + ', ' + pi_ager_names.last_change_field + ' FROM ' + table + ' WHERE id = (SELECT MAX(id) from ' + table + ')'
    open_database()
    execute_query(sql)
    row = cursor.fetchone()        
    close_database()
    return row

def get_agingtable_as_rows(agingtable):
    """
    function for reading the agingtable
    """
    global cursor
    sql='SELECT * from agingtable_' + agingtable
    
    open_database()
    execute_query(sql)
    rows = cursor.fetchall()
    close_database()
    return rows

def get_meatsensor_types_table_as_rows():
    """
    function for reading the meatsensor_table
    """
    global cursor
    sql='SELECT * from ' + pi_ager_names.meat_sensortypes_table
    
    open_database()
    execute_query(sql)
    rows = cursor.fetchall()
    close_database()
    return rows

def get_meatsensor_parameter_row( id ):
    """
    function for reading parameter row of the meatsensor_table selected by id
    """
    global cursor
    sql = 'SELECT * FROM ' + pi_ager_names.meat_sensortypes_table + ' WHERE id = ' + str(id)
    
    open_database()
    execute_query(sql)
    row = cursor.fetchone()
    close_database()
    return row

def get_scale_settings_from_table(scale_settings_table):
    """
    function for reading the scale_settings-table
    """
    global cursor

    open_database()
    sql = 'SELECT * FROM ' + scale_settings_table
    execute_query(sql)
    rows = cursor.fetchall()

    close_database()
    return rows

def read_config():
    """
    function for reading the config table
    """
    global cursor
    open_database()
    execute_query('SELECT * FROM ' + pi_ager_names.config_settings_table + ' WHERE ' + pi_ager_names.key_field + ' ="sensortype" OR "key"="language" OR "key"="switch_on_cooling_compressor" OR "key" = "switch_off_cooling_compressor"OR "key"="switch_on_humidifier" OR "key" = "switch_off_humidifier" OR "key" = "delay_humidify" OR "key" = "uv_modus" OR "key" = "uv_duration" OR "key" = "uv_period" OR "key" = "switch_on_uv_hour" OR "key" = "switch_on_uv_minute" OR "key" = "light_modus" OR "key" = "light_duration" OR "key" = "light_period" OR "key" = "switch_on_light_hour" OR "key" = "switch_on_light_minute" OR "key" = "dehumidifier_modus" OR "key" = "referenceunit_scale1" OR "key" = "referenceunit_scale2"')
    rows = cursor.fetchall()
    close_database()
    return rows
    
def read_settings():
    """
    function for reading the settings table
    """
    global cursor
    open_database()
    execute_query('SELECT * FROM ' + pi_ager_names.config_settings_table + ' WHERE ' + pi_ager_names.key_field + ' ="modus" OR "key"="setpoint_temperature" OR "key"="setpoint_humidity" OR "key"="circulation_air_period" OR "key"="circulation_air_duration" OR "key"="exhaust_air_period" OR "key"="exhaust_air_duration"')
    rows = cursor.fetchall()
    close_database()
    return rows

def read_agingtable_name_from_config():
    """
    function for reading the currently set agingtabletable
    """
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
    """
    function for reading all values of a table
    """
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
    """
    function for reading the value of the manual light control
    """
    status_light_manual = get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_light_manual_key)
    return status_light_manual
    
def get_status_uv_manual():
    """
    function for reading the value of the manual uv-light control
    """
    status_uv_manual = get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_uv_manual_key)
    return status_uv_manual
    
def write_current_sensordata(loopnumber, sensor_temperature, sensor_humidity, sensor_meat1, sensor_meat2, sensor_meat3, sensor_meat4):
    """
    function for writing diagram-relevant sensor data
    """
    
    save_loop = int(get_table_value(pi_ager_names.config_settings_table, pi_ager_names.save_temperature_humidity_loops_key))
    
    if loopnumber % save_loop == 0:   # schreibt für HaMa's Diagramme alle 150 Loops die Werte in die DB
        current_time = str(get_current_time())   # get current_time timestamp the same for all sensor data tables
        open_database()
        execute_query('INSERT INTO ' + pi_ager_names.data_sensor_temperature_table + '(' + str(pi_ager_names.value_field) + ',' + str(pi_ager_names.last_change_field) +') VALUES ('+ str(sensor_temperature) + ', ' + current_time + ')')
        execute_query('INSERT INTO ' + pi_ager_names.data_sensor_humidity_table + ' (' + str(pi_ager_names.value_field) + ',' + str(pi_ager_names.last_change_field) +') VALUES ('+ str(sensor_humidity) + ',' + current_time + ')')
        if (sensor_meat1 != None):
            #temp = 'NULL' if (sensor_meat1 == None) else str(sensor_meat1)
            execute_query('INSERT INTO ' + pi_ager_names.data_sensor_temperature_meat1_table + ' (' + str(pi_ager_names.value_field) + ',' + str(pi_ager_names.last_change_field) +') VALUES ('+ str(sensor_meat1) + ',' + current_time + ')')
        if (sensor_meat2 != None):
            #temp = 'NULL' if (sensor_meat2 == None) else str(sensor_meat2)
            execute_query('INSERT INTO ' + pi_ager_names.data_sensor_temperature_meat2_table + ' (' + str(pi_ager_names.value_field) + ',' + str(pi_ager_names.last_change_field) +') VALUES ('+ str(sensor_meat2) + ',' + current_time + ')')
        if (sensor_meat3 != None):
            #temp = 'NULL' if (sensor_meat3 == None) else str(sensor_meat3)
            execute_query('INSERT INTO ' + pi_ager_names.data_sensor_temperature_meat3_table + ' (' + str(pi_ager_names.value_field) + ',' + str(pi_ager_names.last_change_field) +') VALUES ('+ str(sensor_meat3) + ',' + current_time + ')')
        if (sensor_meat4 != None):
            #temp = 'NULL' if (sensor_meat4 == None) else str(sensor_meat4)
            execute_query('INSERT INTO ' + pi_ager_names.data_sensor_temperature_meat4_table + ' (' + str(pi_ager_names.value_field) + ',' + str(pi_ager_names.last_change_field) +') VALUES ('+ str(sensor_meat4) + ',' + current_time + ')')

        
        close_database()

def write_current(sensor_temperature, status_heater, status_exhaust_air, status_cooling_compressor, status_circulating_air, sensor_humidity, status_uv, status_light, status_humidifier, status_dehumidifier, temp_sensor1_data, temp_sensor2_data, temp_sensor3_data, temp_sensor4_data):
    """
    function for writing the current values including meat sensor values
    """
    
    write_changed_values(status_heater, status_exhaust_air, status_cooling_compressor, status_circulating_air, status_uv, status_light, status_humidifier, status_dehumidifier)
    
    open_database()

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
    
    temp = 'NULL' if (temp_sensor1_data == None) else str(temp_sensor1_data)
    execute_query('UPDATE ' + pi_ager_names.current_values_table + ' SET "' + pi_ager_names.value_field + '" = ' + temp +' , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' = "' + pi_ager_names.temperature_meat1_key + '"')
    temp = 'NULL' if (temp_sensor2_data == None) else str(temp_sensor2_data)
    execute_query('UPDATE ' + pi_ager_names.current_values_table + ' SET "' + pi_ager_names.value_field + '" = ' + temp +' , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' = "' + pi_ager_names.temperature_meat2_key + '"')
    temp = 'NULL' if (temp_sensor3_data == None) else str(temp_sensor3_data)
    execute_query('UPDATE ' + pi_ager_names.current_values_table + ' SET "' + pi_ager_names.value_field + '" = ' + temp +' , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' = "' + pi_ager_names.temperature_meat3_key + '"')
    temp = 'NULL' if (temp_sensor4_data == None) else str(temp_sensor4_data)
    execute_query('UPDATE ' + pi_ager_names.current_values_table + ' SET "' + pi_ager_names.value_field + '" = ' + temp +' , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' = "' + pi_ager_names.temperature_meat4_key + '"')
     
    close_database()

def update_value_in_table(table, key, value):
    """
    function to update a value in a table
    """
    open_database()
    
    sql = 'UPDATE ' + table + ' SET "' + pi_ager_names.value_field + '" = "' + str(value) +'" , "' + str(pi_ager_names.last_change_field) + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' = "' + key + '"'
    execute_query(sql)
    
    close_database()
    
def insert_key_value_row_in_table(table, key, value):
    """
    function to insert a value into a table
    """
    # open_database()
    if key == None:
        sql = 'INSERT INTO ' + table + ' (' + pi_ager_names.value_field + ',' + str(pi_ager_names.last_change_field) + ') VALUES (' + str(value) + ',0)'
    elif not column_exists_in_table(pi_ager_names.last_change_field,table):
        sql = 'INSERT INTO ' + table + ' ("' + pi_ager_names.key_field + '","' + pi_ager_names.value_field + '") VALUES ("' + key + '",' + str(value) + ')'
    else:
        sql = 'INSERT INTO ' + table + ' ("' + pi_ager_names.key_field + '","' + pi_ager_names.value_field + '","' + str(pi_ager_names.last_change_field) + '") VALUES ("' + key + '",' + str(value) + ',0)'
    open_database()
    execute_query(sql)
    
    close_database()
    
def write_scale(scale_table,value_scale):
    """
    function for writing the configuration values of the scales
    """

    str_current_time = str(get_current_time())
    open_database()
    
    if scale_table == pi_ager_names.data_scale1_table:
        scale_key = pi_ager_names.scale1_key
        execute_query('INSERT INTO ' + pi_ager_names.data_scale1_table + ' (' + str(pi_ager_names.value_field) + ',' + str(pi_ager_names.last_change_field) +') VALUES (' + str(value_scale) + ',' + str_current_time + ')')
        execute_query('INSERT INTO ' + pi_ager_names.data_scale2_table + ' (' + str(pi_ager_names.value_field) + ',' + str(pi_ager_names.last_change_field) +') VALUES (' + 'NULL' + ',' + str_current_time + ')')
        execute_query('UPDATE ' + pi_ager_names.current_values_table + ' SET "' + pi_ager_names.value_field + '" = "' + str(value_scale) +'" , "' + pi_ager_names.last_change_field + '" = ' + str_current_time +' WHERE ' + pi_ager_names.key_field + ' = "' + scale_key + '"')
    elif scale_table == pi_ager_names.data_scale2_table:
        scale_key = pi_ager_names.scale2_key
        execute_query('INSERT INTO ' + pi_ager_names.data_scale2_table + ' (' + str(pi_ager_names.value_field) + ',' + str(pi_ager_names.last_change_field) +') VALUES (' + str(value_scale) + ',' + str_current_time + ')')
        execute_query('INSERT INTO ' + pi_ager_names.data_scale1_table + ' (' + str(pi_ager_names.value_field) + ',' + str(pi_ager_names.last_change_field) +') VALUES (' + 'NULL' + ',' + str_current_time + ')')
        execute_query('UPDATE ' + pi_ager_names.current_values_table + ' SET "' + pi_ager_names.value_field + '" = "' + str(value_scale) +'" , "' + pi_ager_names.last_change_field + '" = ' + str_current_time +' WHERE ' + pi_ager_names.key_field + ' = "' + scale_key + '"')        

    close_database()

def write_tables(agingtable):
    """
    function for adding a agingtable
    """

    open_database()
    
    execute_query('UPDATE ' + pi_ager_names.config_settings_table + ' SET "' + pi_ager_names.value_field + '" = "' + agingtable +'" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) +' WHERE ' + pi_ager_names.key_field + ' = "agingtable"')
    
    close_database()

def write_settings(modus, setpoint_temperature, setpoint_humidity, circulation_air_period, circulation_air_duration, exhaust_air_period, exhaust_air_duration):
    """
    function for writing the setting table
    """
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
    """
    function for writing start or stop (0,1) in the status
    """
    
    open_database()
    
    sql = 'UPDATE ' + pi_ager_names.current_values_table + ' SET "' + pi_ager_names.value_field + '" = "' + str(status) + '" , "' + pi_ager_names.last_change_field + '" = ' + str(get_current_time()) + ' WHERE ' + pi_ager_names.key_field + ' = "' + module_key + '"'
    execute_query(sql)
    
    close_database()

def write_start_in_database(module_key):
    """
    function for writing a start (1) in a status
    """
    write_startstop_status_in_database(module_key, 1)

def write_stop_in_database(module_key):
    """
    function for writing a stop (2) in a status
    """
    write_startstop_status_in_database(module_key, 0)

def write_config(sensortype, language, switch_on_cooling_compressor, switch_off_cooling_compressor, switch_on_humidifier, switch_off_humidifier, delay_humidify, uv_modus, uv_duration, uv_period, switch_on_uv_hour, switch_on_uv_minute, light_modus, light_duration, light_period, switch_on_light_hour, switch_on_light_minute, dehumidifier_modus, referenceunit_scale1, referenceunit_scale2):
    """
    function for writing the config values
    """
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
    
def write_changed_values(status_heater, status_exhaust_air, status_cooling_compressor, status_circulating_air, status_uv, status_light, status_humidifier, status_dehumidifier):
    """
    function for writing with modified sensor and status values
    """
    current_value_rows = get_current(pi_ager_names.current_values_table, True)
    
    open_database()

    current_values = {}
    for current_row in current_value_rows:
        current_values[current_row[pi_ager_names.key_field]] = current_row[pi_ager_names.value_field]
    
    if status_heater != current_values[pi_ager_names.status_heater_key]:
        execute_query('INSERT INTO ' + pi_ager_names.status_heater_table + ' (' + str(pi_ager_names.value_field) + ',' + str(pi_ager_names.last_change_field) +') VALUES ('+ str(status_heater) + ',' + str(get_current_time()) + ')')
    if status_exhaust_air != current_values[pi_ager_names.status_exhaust_air_key]:
        execute_query('INSERT INTO ' + pi_ager_names.status_exhaust_air_table + ' (' + str(pi_ager_names.value_field) + ',' + str(pi_ager_names.last_change_field) +') VALUES ('+ str(status_exhaust_air) + ',' + str(get_current_time()) + ')')
    if status_cooling_compressor != current_values[pi_ager_names.status_cooling_compressor_key]:
        execute_query('INSERT INTO ' + pi_ager_names.status_cooling_compressor_table + ' (' + str(pi_ager_names.value_field) + ',' + str(pi_ager_names.last_change_field) +') VALUES ('+ str(status_cooling_compressor) + ',' + str(get_current_time()) + ')')
    if status_circulating_air != current_values[pi_ager_names.status_circulating_air_key]:
        execute_query('INSERT INTO ' + pi_ager_names.status_circulating_air_table + ' (' + str(pi_ager_names.value_field) + ',' + str(pi_ager_names.last_change_field) +') VALUES ('+ str(status_circulating_air) + ',' + str(get_current_time()) + ')')
    if status_uv != current_values[pi_ager_names.status_uv_key]:
        execute_query('INSERT INTO ' + pi_ager_names.status_uv_table + ' (' + str(pi_ager_names.value_field) + ',' + str(pi_ager_names.last_change_field) +') VALUES ('+ str(status_uv) + ',' + str(get_current_time()) + ')')
    if status_light != current_values[pi_ager_names.status_light_key]:
        execute_query('INSERT INTO ' + pi_ager_names.status_light_table + ' (' + str(pi_ager_names.value_field) + ',' + str(pi_ager_names.last_change_field) +') VALUES ('+ str(status_light) + ',' + str(get_current_time()) + ')')
    if status_humidifier != current_values[pi_ager_names.status_humidifier_key]:
        execute_query('INSERT INTO ' + pi_ager_names.status_humidifier_table + ' (' + str(pi_ager_names.value_field) + ',' + str(pi_ager_names.last_change_field) +') VALUES ('+ str(status_humidifier) + ',' + str(get_current_time()) + ')')
    if status_dehumidifier != current_values[pi_ager_names.status_dehumidifier_key]:
        execute_query('INSERT INTO ' + pi_ager_names.status_dehumidifier_table + ' (' + str(pi_ager_names.value_field) + ',' + str(pi_ager_names.last_change_field) +') VALUES ('+ str(status_dehumidifier) + ',' + str(get_current_time()) + ')')

    close_database()

def add_column(table, fieldname):
    """
    function for adding a column
    """
    sql = 'ALTER TABLE ' + table +' ADD ' + fieldname + ' ' + pi_ager_names.field_type[fieldname]
    if fieldname == pi_ager_names.id_field:
        sql = sql + ' PRIMARY KEY AUTOINCREMENT NOT NULL'
    else:
        sql = sql + ' DEFAULT 0 NOT NULL'
    
    open_database()
    execute_query(sql)
    close_database()
    
def repair_column_type(table, fieldname):
    """
    function to repair a column
    """
    # Wird später implementiert
    # logstring = 'Wrong fieldtype in field ' + fieldname + ' of table ' + table + '!'
    # logger.debug(logstring)
    # cl_fact_logger.get_instance().debug(logstring)
    pass
