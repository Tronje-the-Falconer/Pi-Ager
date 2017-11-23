#!/usr/bin/python3
import sqlite3
import pi_ager_names
import pi_ager_paths

def get_logging_value(destination):
    rows = None
    connection = sqlite3.connect(pi_ager_paths.sqlite3_file)
    connection.row_factory = sqlite3.Row
    cursor = connection.cursor()
	
	command = 'SELECT ' + pi_ager_names.value_field + ' FROM ' + pi_ager_names.debug_table + ' WHERE ' + pi_ager_names.key_field + ' = "' + destination + '"'
	cursor.execute(command)
    connection.commit()
	
    
    row = cursor.fetchone()
    connection.close()
	
	
    logging_value = row[pi_ager_names.value_field]
    
    return logging_value
