#!/usr/bin/python3
"""
    logger for database module
    
    special module for getting loglevel in databasemodule 
"""
import sqlite3
import pi_ager_names
import pi_ager_paths

def get_logging_value(destination):
    """
    get loglevel
    """
    rows = None
    connection = sqlite3.connect(pi_ager_paths.sqlite3_file)
    connection.row_factory = sqlite3.Row
    cursor = connection.cursor()
    
    try:
        command = 'SELECT ' + pi_ager_names.value_field + ' FROM ' + pi_ager_names.debug_table + ' WHERE ' + pi_ager_names.key_field + ' = "' + destination + '"'
        cursor.execute(command)
        connection.commit()
        
        row = cursor.fetchone()
        
        connection.close()
        logging_value = row[pi_ager_names.value_field]
    except:
        logging_value = 10
    finally:
        return logging_value
