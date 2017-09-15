import json
import time
import RPi.GPIO as gpio
import pi_ager_paths

#---------------------------------------------------------------------------------- Function Schreiben der current.json
def write_current_json(sensor_temperature, sensor_humidity, gpio_heater, gpio_exhausting_air, gpio_cooling_compressor, gpio_circulating_air):
    global current_json_file
    pi_ager_paths.set_paths()
    current_json_file=pi_ager_paths.get_path_current_json_file()
    current_data = json.dumps({"sensor_temperature":sensor_temperature, "status_heater":gpio.input(gpio_heater), "status_exhaust_air":gpio.input(gpio_exhausting_air), "status_cooling_compressor":gpio.input(gpio_cooling_compressor), "status_circulating_air":gpio.input(gpio_circulating_air),"sensor_humidity":sensor_humidity, 'last_change':int(time.time())})
    with open(current_json_file, 'w') as currentjsonfile:
        currentjsonfile.write(current_data)
#---------------------------------------------------------------------------------- Function Lesen der settings.json
def read_settings_json():
    global settings_json_file
    pi_ager_paths.set_paths()
    settings_json_file=pi_ager_paths.get_path_settings_json_file()
    settings_data = None
    with open(settings_json_file, 'r') as settingsjsonfile:
        settings_data = settingsjsonfile.read()
    data_settingsjsonfile = json.loads(settings_data)
    return data_settingsjsonfile
#---------------------------------------------------------------------------------- Function Lesen der config.json
def read_config_json():
    global config_json_file
    pi_ager_paths.set_paths()
    config_json_file=pi_ager_paths.get_path_config_json_file()
    config_data = None
    with open(config_json_file, 'r') as configjsonfile:
        config_data = configjsonfile.read()
    data_configjsonfile = json.loads(config_data)
    return data_configjsonfile

def read_scales_json():
    global scales_json_file
    pi_ager_paths.set_paths()
    scales_json_file=pi_ager_paths.get_path_scales_json_file()
    scales_data = None
    with open(scales_json_file, 'r') as scalesjsonfile:
        scales_data = scalesjsonfile.read()
    data_scalesjsonfile = json.loads(scales_data)
    return data_scalesjsonfile
    
