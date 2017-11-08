#!/usr/bin/python3
global website_path
global csv_path
global graphs_website_path
global logfile_txt_file
global pi_ager_log_file
global sqlite3_file


def set_paths():
    global website_path
    global csv_path
    global graphs_website_path
    global logfile_txt_file
    global pi_ager_log_file
    global sqlite3_file

    website_path = '/var/www'
    csv_path = '/var/www/csv/'
    graphs_website_path = '/var/www/images/graphs/'
    logfile_txt_file = '/var/www/logs/logfile.txt'
    pi_ager_log_file = '/var/www/logs/pi-ager.log'
    sqlite3_file = '/var/www/config/pi-ager.sqlite3'
    
def get_path_graphs_website():
    global graphs_website_path
    set_paths()
    return graphs_website_path
    
def get_path_logfile_txt_file():
    global logfile_txt_file
    set_paths()
    return logfile_txt_file
    
def get_path_sqlite3_file():
    global sqlite3_file
    set_paths()
    return sqlite3_file

def get_website_path():
    global website_path
    set_paths()
    return website_path
    
def get_pi_ager_log_file_path():
    global pi_ager_log_file
    set_paths()
    return pi_ager_log_file

def get_csv_path():
    global csv_path
    set_paths()
    return csv_path
