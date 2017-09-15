global website_path
global graphs_website_path
global logfile_txt_file
global settings_json_file
global current_json_file
global config_json_file
global scales_json_file

def set_paths():    
    global website_path
    global graphs_website_path
    global logfile_txt_file
    global settings_json_file
    global current_json_file
    global config_json_file
    global scales_json_file

    website_path = '/var/www'
    graphs_website_path = website_path + '/images/graphs/'
    logfile_txt_file = website_path + '/logs/logfile.txt'
    sqlite3_file = '/var/www/config/pi-ager.sqlite3'
    settings_json_file = website_path + '/config/settings.json'
    current_json_file = website_path + '/config/current.json'
    config_json_file = website_path + '/config/config.json'
    scales_json_file = website_path + '/config/scales.json'
    
def get_path_graphs_website():
    global graphs_website_path
    return graphs_website_path
    
def get_path_logfile_txt_file():
    global logfile_txt_file
    return logfile_txt_file
    
def get_path_settings_json_file():
    global settings_json_file
    return settings_json_file
    
def get_path_current_json_file():
    global current_json_file
    return current_json_file
    
def get_path_config_json_file():
    global config_json_file
    return config_json_file
    
def get_path_scales_json_file():
    global scales_json_file
    return scales_json_file
    
