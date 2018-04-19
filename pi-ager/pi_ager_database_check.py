#!/usr/bin/python3
# -*- coding: iso-8859-1 -*-
"""
    Datenbankueberpruefung
    
    Ueberpruefen der Datenbank auf Korrekte Struktur
"""
# Importieren der Module
import pi_ager_names
import pi_ager_database

def get_missing_tables(tables):
    """
    Analyse ob es fehlende Tabellen gibt
    """
    i = 0
    missing_tables = {}
    for table in tables:
        if not pi_ager_database.table_exists(table):
            missing_tables[i] = table
            i+=1
    return missing_tables.values()
    
def add_tables(tables, tables_are_key_value):
    """
    Hinzufügen von fehlenden Tabellen
    """
    for table in tables:
        if tables_are_key_value:
            pi_ager_database.add_key_value_table(table)
        else:
            pi_ager_database.add_id_value_table(table)

def check_and_repair_key_value_table_columns(tables):
    """
    Ueberpruefen und reparieren von Spalten in Key-Value-Tabellen
    """
    for table in tables:
        id_column_type_okay = False
        key_column_type_okay = False
        value_column_type_okay = False
        last_change_column_type_okay = False
        id_column_exists = False
        key_column_exists = False
        value_column_exists = False
        last_change_column_exists = False
        column_info_rows = pi_ager_database.get_column_infos(table)
        for row in column_info_rows:
            if row['name'] == pi_ager_names.id_field:
                id_column_exists = True
                if row['type'] == pi_ager_names.field_type[pi_ager_names.id_field]:
                    id_column_type_okay = True
            elif row['name'] == pi_ager_names.key_field:
                key_column_exists = True
                if row['type'] == pi_ager_names.field_type[pi_ager_names.key_field]:
                    key_column_type_okay = True
            elif row['name'] == pi_ager_names.value_field:
                value_column_exists = True
                if table == pi_ager_names.system_table:
                    if row['type'] == pi_ager_names.field_type[pi_ager_names.system_table + '_' + pi_ager_names.value_field]:
                        value_column_type_okay = True
                elif row['type'] == pi_ager_names.field_type[pi_ager_names.value_field]:
                    value_column_type_okay = True
            elif row['name'] == pi_ager_names.last_change_field:
                last_change_column_exists = True
                if row['type'] == pi_ager_names.field_type[pi_ager_names.last_change_field]:
                    last_change_column_type_okay = True
        if not id_column_exists:
            pi_ager_database.add_column(table, pi_ager_names.id_field)
        elif not id_column_type_okay:
            pi_ager_database.repair_column_type(table, pi_ager_names.id_field)
        if not key_column_exists:
            pi_ager_database.add_column(table, pi_ager_names.key_field)
        elif not key_column_type_okay:
            pi_ager_database.repair_column_type(table, pi_ager_names.key_field)
        if not value_column_exists:
            pi_ager_database.add_column(table, pi_ager_names.value_field)
        elif not value_column_type_okay:
            pi_ager_database.repair_column_type(table, pi_ager_names.value_field)
        if not last_change_column_exists:
            pi_ager_database.add_column(table, pi_ager_names.last_change_field)
        elif not last_change_column_type_okay:
            pi_ager_database.repair_column_type(table, pi_ager_names.last_change_field)
        

def check_and_repair_id_value_table_columns(tables):
    """
    Ueberpruefen und reparieren von Spalten in ID-Value-Tabellen
    """
    for table in tables:
        id_column_type_okay = False
        key_column_type_okay = False
        value_column_type_okay = False
        last_change_column_type_okay = False
        id_column_exists = False
        key_column_exists = False
        value_column_exists = False
        last_change_column_exists = False
        column_info_rows = pi_ager_database.get_column_infos(table)
        for row in column_info_rows:
            if row['name'] == pi_ager_names.id_field:
                id_column_exists = True
                if row['type'] == pi_ager_names.field_type[pi_ager_names.id_field]:
                    id_column_type_okay = True
            elif row['name'] == pi_ager_names.key_field:
                key_column_exists = True
                if row['type'] == pi_ager_names.field_type[pi_ager_names.key_field]:
                    key_column_type_okay = True
            elif row['name'] == pi_ager_names.value_field:
                value_column_exists = True
                if row['type'] == pi_ager_names.field_type[pi_ager_names.value_field]:
                    value_column_type_okay = True
            elif row['name'] == pi_ager_names.last_change_field:
                last_change_column_exists = True
                if row['type'] == pi_ager_names.field_type[pi_ager_names.last_change_field]:
                    last_change_column_type_okay = True
        if not id_column_exists:
            pi_ager_database.add_column(table, pi_ager_names.id_field)
        elif not id_column_type_okay:
            pi_ager_database.repair_column_type(table, pi_ager_names.id_field)
        if not key_column_exists:
            pi_ager_database.add_column(table, pi_ager_names.key_field)
        elif not key_column_type_okay:
            pi_ager_database.repair_column_type(table, pi_ager_names.key_field)
        if not value_column_exists:
            pi_ager_database.add_column(table, pi_ager_names.value_field)
        elif not value_column_type_okay:
            pi_ager_database.repair_column_type(table, pi_ager_names.value_field)
        if not last_change_column_exists:
            pi_ager_database.add_column(table, pi_ager_names.last_change_field)
        elif not last_change_column_type_okay:
            pi_ager_database.repair_column_type(table, pi_ager_names.last_change_field)
        
def check_table_contents():
    """
    Ueberpruefen von Tabellen Inhalten
    """
    for table in pi_ager_names.key_value_tables:
        for key in pi_ager_names.table_keys[table]:
            # Prüfen, ob key fehlt
            if not pi_ager_database.key_exists_in_table(key, table):
                default_value_key = table + '_' + key
                if default_value_key in pi_ager_names.default_values.keys():
                    # Wert von key in Tabelle schreiben
                    value = pi_ager_names.default_values[default_value_key]
                else:
                    value = 0
                pi_ager_database.insert_key_value_row_in_table(table,key,value)
            # Wert von key in Tabelle schreiben

def check_and_update_database():
    """
    Ueberpruefen der gesamten Datenbank, Hauptfunktion
    """
    #Tabellen prüfen und ergänzen
    missing_key_value_tables = get_missing_tables(pi_ager_names.key_value_tables)
    add_tables(missing_key_value_tables, True)
    missing_id_value_tables = get_missing_tables(pi_ager_names.id_value_tables)
    add_tables(missing_id_value_tables, False)
    
    #Spalten prüfen und ergänzen
    check_and_repair_key_value_table_columns(pi_ager_names.key_value_tables)
    check_and_repair_id_value_table_columns(pi_ager_names.id_value_tables)
    
    #Tabelleninhalte prüfen und ergänzen
    check_table_contents()
