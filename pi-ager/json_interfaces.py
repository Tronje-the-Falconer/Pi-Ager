import json
#---------------------------------------------------------------------------------- Function Lesen der config.json
def read_config_json(config_json_file):
    config_data = None
    with open(config_json_file, 'r') as configjsonfile:
        config_data = configjsonfile.read()
    data_configjsonfile = json.loads(config_data)
    return data_configjsonfile
#---------------------------------------------------------------------------------- Function Lesen der scales.json
def read_scales_json(scales_json_file):
    scales_data = None
    with open(scales_json_file, 'r') as scalesjsonfile:
        scales_data = scalesjsonfile.read()
    data_scalesjsonfile = json.loads(scales_data)
    return data_scalesjsonfile
#---------------------------------------------------------------------------------- Function Schreiben der current.json
# def write_current_json(sensor_temperature, sensor_humidity):
    # global current_json_file
    # current_data = json.dumps({"sensor_temperature":sensor_temperature, "status_heater":gpio.input(gpio_heater), "status_exhaust_air":gpio.input(gpio_exhausting_air), "status_cooling_compressor":gpio.input(gpio_cooling_compressor), "status_circulating_air":gpio.input(gpio_circulating_air),"sensor_humidity":sensor_humidity, 'last_change':int(time.time())})
    # with open(current_json_file, 'w') as currentjsonfile:
        # currentjsonfile.write(current_data)
# #---------------------------------------------------------------------------------- Function Lesen der settings.json
# def read_settings_json(settings_json_file):
    # global settings_json_file
    # settings_data = None
    # with open(settings_json_file, 'r') as settingsjsonfile:
        # settings_data = settingsjsonfile.read()
    # data_settingsjsonfile = json.loads(settings_data)
    # return data_settingsjsonfile