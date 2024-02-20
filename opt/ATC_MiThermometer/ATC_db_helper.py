#!/usr/bin/python3
# -*- coding: utf-8 -*-
"""
    database communication
    functions for communication with the database
"""

import sqlite3
import time

global cursor
global connection

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
    connection = sqlite3.connect('/var/www/config/pi-ager.sqlite3', isolation_level=None, timeout = 10)
    # Set journal mode to WAL (Write-Ahead Log)
    connection.execute('PRAGMA journal_mode = wal')
    connection.execute('PRAGMA synchronous = OFF')
    connection.execute('PRAGMA read_uncommitted = True')

    connection.row_factory = sqlite3.Row
    cursor = connection.cursor()

def execute_query(command):
    """
    function for executing a SQL
    """
    global cursor
    global connection
    
    count = 0
    while True:
        try:
            cursor.execute(command)
            connection.commit()
            return
        except Exception as cx_error:
            count = count + 1
            time.sleep(1)
            if (count == 3):
                return  #raise
    
def close_database():
    """
    function for closing the database connection
    """
    global connection
    connection.close()
    
def get_table_value_from_field(table, field):
    """
    function to read a value to a known field with max(id)
    """
    global cursor

    sql = 'SELECT ' + field + ' FROM ' + table + ' WHERE id = (SELECT MAX(id) FROM ' + table + ')'
    value = None

    open_database()
    execute_query(sql)
    row = cursor.fetchone()
    close_database()
    if (row != None):
        value = row[field]
    return value

def is_table_empty( table ):
    """
    check if table is empty
    return True, if empty, else False
    """
    sql = 'SELECT COUNT(*) as count FROM ' + table
    open_database()
    execute_query(sql)
    row = cursor.fetchone()
    close_database()
    if (row == None):
        return True
    numRows = row['count']
    if (numRows == 0):
        return True
    else:
        return False

def empty_table( table ):
    """
    empty table and reset auto_inc counter
    """
    sql1 = 'DELETE FROM ' + table
    sql2 = 'DELETE FROM SQLITE_SEQUENCE WHERE name=' + table
    open_database()
    execute_query(sql1)
    execute_query(sql2)    
    close_database()    
    
def write_atc_data( temperature, humidity, battvolt, battpercent, last_change):
    """
    write Bluetooth MiTemp sensor data into DB
    """
    if (is_table_empty('atc_data') == True):
        sql = 'INSERT INTO atc_data (temperature,humidity,battvolt,battpercent,last_change, id) VALUES (' + str(temperature) + ',' + str(humidity) + ',' + str(battvolt) + ',' + str(battpercent) + ',' + str(last_change) + ',1' + ')'
    else:
        sql = 'UPDATE atc_data SET temperature = ' + str(temperature) + ',humidity = ' + str(humidity) + ',battvolt = ' + str(battvolt) + ',battpercent = ' + str(battpercent) + ',last_change = ' + str(last_change) + ' WHERE id = 1'
    open_database()
    execute_query(sql)
    close_database()


    
        
        