#!/usr/bin/python3
# -*- coding: utf-8 -*-
"""
    pi-ager variables
    
    setting up variables
"""
#import RPi.GPIO as gpio

########################### Definition of variables
version_number = '3.1.0'
# tables names
config_settings_table = 'config'
data_sensor_temperature_table = 'sensor_temperature_data'
status_heater_table = 'heater_status'
status_exhaust_air_table = 'exhaust_air_status'
status_cooling_compressor_table = 'cooling_compressor_status'
status_circulating_air_table = 'circulating_air_status'
status_uv_table = 'uv_status'
status_light_table = 'light_status'
data_sensor_humidity_table = 'sensor_humidity_data'
status_dehumidifier_table = 'dehumidifier_status'
status_humidifier_table = 'humidifier_status'
data_scale1_table = 'scale1_data'
data_scale2_table = 'scale2_data'
current_values_table = 'current_values'
agingtables_table = 'agingtables'
settings_scale1_table = 'scale1_settings'
settings_scale2_table = 'scale2_settings'
data_sensor_temperature_meat1_table = 'sensor_temperature_meat1_data'
data_sensor_temperature_meat2_table = 'sensor_temperature_meat2_data'
data_sensor_temperature_meat3_table = 'sensor_temperature_meat3_data'
data_sensor_temperature_meat4_table = 'sensor_temperature_meat4_data'
debug_table = 'debug'
system_table = 'system'
meat_sensortypes_table = 'meat_sensortypes'

# table keys
switch_on_cooling_compressor_key = 'switch_on_cooling_compressor'
switch_off_cooling_compressor_key = 'switch_off_cooling_compressor'
switch_on_humidifier_key = 'switch_on_humidifier'
switch_off_humidifier_key = 'switch_off_humidifier'
delay_humidify_key = 'delay_humidify'
referenceunit_scale1_key = 'referenceunit_scale1'
referenceunit_scale2_key = 'referenceunit_scale2'
sensortype_key = 'sensortype'
language_key = 'language'
switch_on_light_hour_key = 'switch_on_light_hour'
switch_on_light_minute_key = 'switch_on_light_minute'
light_duration_key = 'light_duration'
light_period_key = 'light_period'
light_modus_key = 'light_modus'
switch_on_uv_hour_key = 'switch_on_uv_hour'
switch_on_uv_minute_key = 'switch_on_uv_minute'
uv_duration_key = 'uv_duration'
uv_period_key = 'uv_period'
uv_modus_key = 'uv_modus'
dehumidifier_modus_key = 'dehumidifier_modus'
circulation_air_period_key = 'circulation_air_period'
setpoint_temperature_key = 'setpoint_temperature'
exhaust_air_duration_key = 'exhaust_air_duration'
modus_key = 'modus'
setpoint_humidity_key = 'setpoint_humidity'
exhaust_air_period_key = 'exhaust_air_period'
circulation_air_duration_key = 'circulation_air_duration'
agingtable_key = 'agingtable'
sensor_temperature_key = 'sensor_temperature'
sensor_humidity_key = 'sensor_humidity'
status_pi_ager_key = 'status_piager'
status_agingtable_key = 'status_agingtable'
status_heater_key = 'status_heater'
status_exhaust_air_key = 'status_exhaust_air'
status_cooling_compressor_key = 'status_cooling_compressor'
status_circulating_air_key = 'status_circulating_air'
status_uv_key = 'status_uv'
status_light_key = 'status_light'
status_dehumidifier_key = 'status_dehumidifier'
status_humidifier_key = 'status_humidifier'
status_scale1_key = 'status_scale1'
status_scale2_key = 'status_scale2'
status_tara_scale1_key = 'status_tara_scale1'
status_tara_scale2_key = 'status_tara_scale2'
status_light_manual_key = 'status_light_manual'
status_uv_manual_key = 'status_uv_manual'
scale1_key = 'scale1'
scale2_key = 'scale2'
samples_key = 'samples'
spikes_key = 'spikes'
sleep_key = 'sleep'
gpio_data_key = 'gpio_data'
gpio_sync_key = 'gpio_sync'
gain_key = 'gain'
bits_to_read_key = 'bits_to_read'
referenceunit_key = 'referenceunit'
scale_measuring_interval_key = 'measuring_interval'
save_temperature_humidity_loops_key = 'save_temperature_humidity_loops'
sensorbus_key = 'sensorbus'
loglevel_file_key = 'loglevel_file'
loglevel_console_key = 'loglevel_console'
agingtable_period_key = 'agingtable_period'
agingtable_period_starttime_key = 'agingtable_period_starttime'
measuring_interval_debug_key = 'measuring_interval_debug'
agingtable_days_in_seconds_debug_key = 'agingtable_days_in_seconds_debug'
measuring_duration_key = 'measuring_duration'
samples_refunit_tara_key = 'samples_refunit_tara'
spikes_refunit_tara_key = 'spikes_refunit_tara'
saving_period_key = 'saving_period'
failure_temperature_delta_key = 'failure_temperature_delta'
failure_humidity_delta_key = 'failure_humidity_delta'
pi_revision_key = 'pi_revision'
pi_ager_version_key = 'pi_ager_version'
calibrate_scale1_key = 'calibrate_scale1'
calibrate_scale2_key = 'calibrate_scale2'
calibrate_weight_key = 'calibrate_weight'
offset_scale_key = 'offset'
temperature_meat1_key = 'temperature_meat1'
temperature_meat2_key = 'temperature_meat2'
temperature_meat3_key = 'temperature_meat3'
temperature_meat4_key = 'temperature_meat4'
meat1_sensortype_key = 'meat1_sensortype'
meat2_sensortype_key = 'meat2_sensortype'
meat3_sensortype_key = 'meat3_sensortype'
meat4_sensortype_key = 'meat4_sensortype'


# table fields
key_field = 'key'
value_field = 'value'
last_change_field = 'last_change'
id_field = 'id'
agingtable_name_field = 'name'
agingtable_modus_field = 'modus'
agingtable_setpoint_temperature_field = 'setpoint_temperature'
agingtable_setpoint_humidity_field = 'setpoint_humidity'
agingtable_circulation_air_duration_field = 'circulation_air_duration'
agingtable_circulation_air_period_field = 'circulation_air_period'
agingtable_exhaust_air_duration_field = 'exhaust_air_duration'
agingtable_exhaust_air_period_field = 'exhaust_air_period'
agingtable_days_field = 'days'
agingtable_comment_field = 'comment'
meat_sensortypes_name_field = 'name'
meat_sensortypes_a_field = 'a'
meat_sensortypes_b_field = 'b'
meat_sensortypes_c_field = 'c'
meat_sensortypes_Rn_field = 'Rn'
meat_sensortypes_Mode_field = 'Mode'
meat_sensortypes_RefVoltage_field = 'RefVoltage'
meat_sensortypes_Sensitivity_field = 'Sensitivity'
meat_sensortypes_Turns_field = 'Turns'
meat_sensortypes_nAverage_field = 'nAverage'

# Paths and urls
thread_url = 'https://www.grillsportverein.de/forum/threads/pi-ager-reifeschranksteuerung-mittels-raspberry-pi.273805/'
error_reporting_url = 'https://github.com/Tronje-the-Falconer/Pi-Ager/wiki/Error-reporting'
faq_url =  'https://github.com/Tronje-the-Falconer/Pi-Ager/wiki/FAQ'
sqlite_path = '/var/www/config/pi-ager.sqlite3'
logfile_txt_file = '/var/www/logs/logfile.txt'
pi_ager_log_file = '/var/www/logs/pi-ager.log'
changelogfile = '/var/www/changelog.txt'

# JSON Keys
last_change_temperature_json_key = 'last_change_temperature'
last_change_humidity_json_key = 'last_change_humidity'
last_change_scale1_json_key = 'last_change_scale1'
last_change_scale2_json_key = 'last_change_scale2'
last_change_temperature_meat1_json_key = 'last_change_temperature_meat1'
last_change_temperature_meat2_json_key = 'last_change_temperature_meat2'
last_change_temperature_meat3_json_key = 'last_change_temperature_meat3'
last_change_temperature_meat4_json_key = 'last_change_temperature_meat4'

pin_with_voltage = True                      # 3,3V = 1 | GPIO.HIGH  | TRUE
pin_without_voltage = (not pin_with_voltage) #   0V = 0 | GPIO.LOW   | FALSE
# Sainsmart Relais Vereinfachung 0 aktiv
relay_on = pin_without_voltage   # negative Logik!!! des Relay's, Schaltet bei 0 | GPIO.LOW  | False  ein
relay_off = (not relay_on)       # negative Logik!!! des Relay's, Schaltet bei 1 | GPIO.High | True aus

logspacer = "***********************************************"
logspacer2 = '-------------------------------------------------------'

######################################### DATABASE-CHECK

# field types
field_type = {}
field_type[key_field] = 'TEXT'
field_type[value_field] = 'REAL'
field_type[system_table + '_' + value_field] = 'TEXT'
field_type[last_change_field] = 'INTEGER'
field_type[id_field] = 'INTEGER'

id_value_tables = [data_sensor_temperature_table,data_sensor_humidity_table,status_heater_table,status_exhaust_air_table,status_cooling_compressor_table,status_circulating_air_table, status_uv_table, status_light_table, status_humidifier_table, status_dehumidifier_table, data_scale1_table, data_scale2_table, data_sensor_temperature_meat1_table, data_sensor_temperature_meat2_table, data_sensor_temperature_meat3_table, data_sensor_temperature_meat4_table, meat_sensortypes_table]

key_value_tables = [current_values_table, settings_scale1_table, settings_scale2_table, config_settings_table, debug_table, system_table]

# Arrays with defined keys in key-value-tables and default values
table_keys = {}

table_keys[config_settings_table] = (switch_on_cooling_compressor_key,switch_off_cooling_compressor_key,switch_on_humidifier_key,switch_off_humidifier_key,delay_humidify_key,
                                     sensortype_key,language_key,switch_on_light_hour_key,switch_on_light_minute_key,light_duration_key,light_period_key,light_modus_key,
                                     switch_on_uv_hour_key,switch_on_uv_minute_key,uv_duration_key,uv_period_key,uv_modus_key,dehumidifier_modus_key,
                                     circulation_air_period_key,setpoint_temperature_key,exhaust_air_duration_key,modus_key,setpoint_humidity_key,exhaust_air_period_key,
                                     circulation_air_duration_key,agingtable_key, failure_humidity_delta_key, failure_temperature_delta_key, samples_refunit_tara_key,
                                     spikes_refunit_tara_key, save_temperature_humidity_loops_key, sensorbus_key,
                                     meat1_sensortype_key, meat2_sensortype_key, meat3_sensortype_key, meat4_sensortype_key)

table_keys[current_values_table] = (sensor_temperature_key,sensor_humidity_key,status_circulating_air_key,status_cooling_compressor_key,status_exhaust_air_key,
                                    status_heater_key,status_light_key,status_uv_key,status_humidifier_key,status_dehumidifier_key,scale1_key,scale2_key,status_pi_ager_key,
                                    status_agingtable_key,status_scale1_key,status_scale2_key,status_tara_scale1_key,status_tara_scale2_key,agingtable_period_key,
                                    agingtable_period_starttime_key,status_light_manual_key,calibrate_scale1_key,calibrate_scale2_key,calibrate_weight_key,
                                    status_uv_manual_key,temperature_meat1_key,temperature_meat2_key,temperature_meat3_key,temperature_meat4_key)

table_keys[settings_scale1_table] = (samples_key,spikes_key,sleep_key,gain_key,bits_to_read_key,referenceunit_key,scale_measuring_interval_key,measuring_duration_key,saving_period_key,offset_scale_key)
table_keys[settings_scale2_table] = (samples_key,spikes_key,sleep_key,gain_key,bits_to_read_key,referenceunit_key,scale_measuring_interval_key,measuring_duration_key,saving_period_key,offset_scale_key)

table_keys[debug_table] = (measuring_interval_debug_key,agingtable_days_in_seconds_debug_key,loglevel_file_key,loglevel_console_key)

table_keys[system_table] = (pi_revision_key,pi_ager_version_key)

default_values = {}
#default values config table
default_values[config_settings_table + '_' + sensortype_key] = 3 #SHT
default_values[config_settings_table + '_' + language_key] = 1 #Deutsch de-DE
default_values[config_settings_table + '_' + save_temperature_humidity_loops_key] = 150

#default values scale1_settings table
default_values[settings_scale1_table + '_' + samples_key] = 300
default_values[settings_scale1_table + '_' + spikes_key] = 60
default_values[settings_scale1_table + '_' + sleep_key] = 0.1
default_values[settings_scale1_table + '_' + gain_key] = 128
default_values[settings_scale1_table + '_' + bits_to_read_key] = 24
default_values[settings_scale1_table + '_' + referenceunit_key] = 221.2
default_values[settings_scale1_table + '_' + scale_measuring_interval_key] = 120
default_values[settings_scale1_table + '_' + measuring_duration_key] = 100
default_values[settings_scale1_table + '_' + saving_period_key] = 240

#default values scale2_settings table
default_values[settings_scale2_table + '_' + samples_key] = 300
default_values[settings_scale2_table + '_' + spikes_key] = 60
default_values[settings_scale2_table + '_' + sleep_key] = 0.1
default_values[settings_scale2_table + '_' + gain_key] = 128
default_values[settings_scale2_table + '_' + bits_to_read_key] = 24
default_values[settings_scale2_table + '_' + referenceunit_key] = 221.2
default_values[settings_scale2_table + '_' + scale_measuring_interval_key] = 120
default_values[settings_scale2_table + '_' + measuring_duration_key] = 100
default_values[settings_scale2_table + '_' + saving_period_key] = 240

#default values debug table
default_values[debug_table + '_' + measuring_interval_debug_key] = 30
default_values[debug_table + '_' + agingtable_days_in_seconds_debug_key] = 1
default_values[debug_table + '_' + loglevel_file_key] = 10
default_values[debug_table + '_' + loglevel_console_key] = 20

#default values system table
default_values[system_table + '_' + pi_ager_version_key] = '"' + version_number + '"'

################################# JSON Creation

tables_dict = {}

tables_dict['config_settings_table'] = config_settings_table
tables_dict['data_sensor_temperature_table'] = data_sensor_temperature_table
tables_dict['status_heater_table'] = status_heater_table
tables_dict['status_exhaust_air_table'] = status_exhaust_air_table
tables_dict['status_cooling_compressor_table'] = status_cooling_compressor_table
tables_dict['status_circulating_air_table'] = status_circulating_air_table
tables_dict['status_uv_table'] = status_uv_table
tables_dict['status_light_table'] = status_light_table
tables_dict['data_sensor_humidity_table'] = data_sensor_humidity_table
tables_dict['status_dehumidifier_table'] = status_dehumidifier_table
tables_dict['status_humidifier_table'] = status_humidifier_table
tables_dict['data_scale1_table'] = data_scale1_table
tables_dict['data_scale2_table'] = data_scale2_table
tables_dict['current_values_table'] = current_values_table
tables_dict['agingtables_table'] = agingtables_table
tables_dict['settings_scale1_table'] = settings_scale1_table
tables_dict['settings_scale2_table'] = settings_scale2_table
tables_dict['data_sensor_temperature_meat1_table'] = data_sensor_temperature_meat1_table
tables_dict['data_sensor_temperature_meat2_table'] = data_sensor_temperature_meat2_table
tables_dict['data_sensor_temperature_meat3_table'] = data_sensor_temperature_meat3_table
tables_dict['data_sensor_temperature_meat4_table'] = data_sensor_temperature_meat4_table
tables_dict['debug_table'] = debug_table
tables_dict['system_table'] = system_table

table_keys_dict = {}

table_keys_dict['switch_on_cooling_compressor_key'] = switch_on_cooling_compressor_key
table_keys_dict['switch_off_cooling_compressor_key'] = switch_off_cooling_compressor_key
table_keys_dict['switch_on_humidifier_key'] = switch_on_humidifier_key
table_keys_dict['switch_off_humidifier_key'] = switch_off_humidifier_key
table_keys_dict['delay_humidify_key'] = delay_humidify_key
table_keys_dict['referenceunit_scale1_key'] = referenceunit_scale1_key
table_keys_dict['referenceunit_scale2_key'] = referenceunit_scale2_key
table_keys_dict['sensortype_key'] = sensortype_key
table_keys_dict['language_key'] = language_key
table_keys_dict['switch_on_light_hour_key'] = switch_on_light_hour_key
table_keys_dict['switch_on_light_minute_key'] = switch_on_light_minute_key
table_keys_dict['light_duration_key'] = light_duration_key
table_keys_dict['light_period_key'] = light_period_key
table_keys_dict['light_modus_key'] = light_modus_key
table_keys_dict['switch_on_uv_hour_key'] = switch_on_uv_hour_key
table_keys_dict['switch_on_uv_minute_key'] = switch_on_uv_minute_key
table_keys_dict['uv_duration_key'] = uv_duration_key
table_keys_dict['uv_period_key'] = uv_period_key
table_keys_dict['uv_modus_key'] = uv_modus_key
table_keys_dict['dehumidifier_modus_key'] = dehumidifier_modus_key
table_keys_dict['circulation_air_period_key'] = circulation_air_period_key
table_keys_dict['setpoint_temperature_key'] = setpoint_temperature_key
table_keys_dict['exhaust_air_duration_key'] = exhaust_air_duration_key
table_keys_dict['modus_key'] = modus_key
table_keys_dict['setpoint_humidity_key'] = setpoint_humidity_key
table_keys_dict['exhaust_air_period_key'] = exhaust_air_period_key
table_keys_dict['circulation_air_duration_key'] = circulation_air_duration_key
table_keys_dict['agingtable_key'] = agingtable_key
table_keys_dict['sensor_temperature_key'] = sensor_temperature_key
table_keys_dict['sensor_humidity_key'] = sensor_humidity_key
table_keys_dict['status_pi_ager_key'] = status_pi_ager_key
table_keys_dict['status_agingtable_key'] = status_agingtable_key
table_keys_dict['status_heater_key'] = status_heater_key
table_keys_dict['status_exhaust_air_key'] = status_exhaust_air_key
table_keys_dict['status_cooling_compressor_key'] = status_cooling_compressor_key
table_keys_dict['status_circulating_air_key'] = status_circulating_air_key
table_keys_dict['status_uv_key'] = status_uv_key
table_keys_dict['status_light_key'] = status_light_key
table_keys_dict['status_dehumidifier_key'] = status_dehumidifier_key
table_keys_dict['status_humidifier_key'] = status_humidifier_key
table_keys_dict['status_scale1_key'] = status_scale1_key
table_keys_dict['status_scale2_key'] = status_scale2_key
table_keys_dict['status_tara_scale1_key'] = status_tara_scale1_key
table_keys_dict['status_tara_scale2_key'] = status_tara_scale2_key
table_keys_dict['status_light_manual_key'] = status_light_manual_key
table_keys_dict['status_uv_manual_key'] = status_uv_manual_key
table_keys_dict['scale1_key'] = scale1_key
table_keys_dict['scale2_key'] = scale2_key
table_keys_dict['samples_key'] = samples_key
table_keys_dict['spikes_key'] = spikes_key
table_keys_dict['sleep_key'] = sleep_key
table_keys_dict['gpio_data_key'] = gpio_data_key
table_keys_dict['gpio_sync_key'] = gpio_sync_key
table_keys_dict['gain_key'] = gain_key
table_keys_dict['bits_to_read_key'] = bits_to_read_key
table_keys_dict['referenceunit_key'] = referenceunit_key
table_keys_dict['scale_measuring_interval_key'] = scale_measuring_interval_key
table_keys_dict['save_temperature_humidity_loops_key'] = save_temperature_humidity_loops_key
table_keys_dict['loglevel_file_key'] = loglevel_file_key
table_keys_dict['loglevel_console_key'] = loglevel_console_key
table_keys_dict['agingtable_period_key'] = agingtable_period_key
table_keys_dict['agingtable_period_starttime_key'] = agingtable_period_starttime_key
table_keys_dict['measuring_interval_debug_key'] = measuring_interval_debug_key
table_keys_dict['agingtable_days_in_seconds_debug_key'] = agingtable_days_in_seconds_debug_key
table_keys_dict['measuring_duration_key'] = measuring_duration_key
table_keys_dict['samples_refunit_tara_key'] = samples_refunit_tara_key
table_keys_dict['spikes_refunit_tara_key'] = spikes_refunit_tara_key
table_keys_dict['saving_period_key'] = saving_period_key
table_keys_dict['failure_temperature_delta_key'] = failure_temperature_delta_key
table_keys_dict['failure_humidity_delta_key'] = failure_humidity_delta_key
table_keys_dict['pi_revision_key'] = pi_revision_key
table_keys_dict['pi_ager_version_key'] = pi_ager_version_key
table_keys_dict['calibrate_scale1_key'] = calibrate_scale1_key
table_keys_dict['calibrate_scale2_key'] = calibrate_scale2_key
table_keys_dict['calibrate_weight_key'] = calibrate_weight_key
table_keys_dict['offset_scale_key'] = offset_scale_key
table_keys_dict['temperature_meat1_key'] = temperature_meat1_key
table_keys_dict['temperature_meat2_key'] = temperature_meat2_key
table_keys_dict['temperature_meat3_key'] = temperature_meat3_key
table_keys_dict['temperature_meat4_key'] = temperature_meat4_key
table_keys_dict['meat1_sensortype_key'] = meat1_sensortype_key
table_keys_dict['meat2_sensortype_key'] = meat2_sensortype_key
table_keys_dict['meat3_sensortype_key'] = meat3_sensortype_key
table_keys_dict['meat4_sensortype_key'] = meat4_sensortype_key

table_fields_dict = {}

table_fields_dict['key_field'] = key_field
table_fields_dict['value_field'] = value_field
table_fields_dict['last_change_field'] = last_change_field
table_fields_dict['id_field'] = id_field
table_fields_dict['agingtable_name_field'] = agingtable_name_field
table_fields_dict['agingtable_modus_field'] = agingtable_modus_field
table_fields_dict['agingtable_setpoint_temperature_field'] = agingtable_setpoint_temperature_field
table_fields_dict['agingtable_setpoint_humidity_field'] = agingtable_setpoint_humidity_field
table_fields_dict['agingtable_circulation_air_duration_field'] = agingtable_circulation_air_duration_field
table_fields_dict['agingtable_circulation_air_period_field'] = agingtable_circulation_air_period_field
table_fields_dict['agingtable_exhaust_air_duration_field'] = agingtable_exhaust_air_duration_field
table_fields_dict['agingtable_exhaust_air_period_field'] = agingtable_exhaust_air_period_field
table_fields_dict['agingtable_days_field'] = agingtable_days_field
table_fields_dict['agingtable_comment_field'] = agingtable_comment_field
table_fields_dict['meat_sensortypes_name_field'] = meat_sensortypes_name_field
table_fields_dict['meat_sensortypes_a_field'] = meat_sensortypes_a_field
table_fields_dict['meat_sensortypes_b_field'] = meat_sensortypes_b_field
table_fields_dict['meat_sensortypes_c_field'] = meat_sensortypes_c_field
table_fields_dict['meat_sensortypes_Rn_field'] = meat_sensortypes_Rn_field
table_fields_dict['meat_sensortypes_Mode_field'] = meat_sensortypes_Mode_field
table_fields_dict['meat_sensortypes_RefVoltage_field'] = meat_sensortypes_RefVoltage_field
table_fields_dict['meat_sensortypes_Sensitivity_field'] = meat_sensortypes_Sensitivity_field
table_fields_dict['meat_sensortypes_Turns_field'] = meat_sensortypes_Turns_field
table_fields_dict['meat_sensortypes_nAverage_field'] = meat_sensortypes_nAverage_field

path_url_dict = {}
path_url_dict['thread_url'] = thread_url
path_url_dict['error_reporting_url'] = error_reporting_url
path_url_dict['faq_url'] = faq_url
path_url_dict['sqlite_path'] = sqlite_path
path_url_dict['logfile_txt_file'] = logfile_txt_file
path_url_dict['pi_ager_log_file'] = pi_ager_log_file
path_url_dict['changelogfile'] = changelogfile

json_keys_dict = {}
json_keys_dict['last_change_temperature_json_key'] = last_change_temperature_json_key
json_keys_dict['last_change_humidity_json_key'] = last_change_humidity_json_key
json_keys_dict['last_change_scale1_json_key'] = last_change_scale1_json_key
json_keys_dict['last_change_scale2_json_key'] = last_change_scale2_json_key
json_keys_dict['last_change_temperature_meat1_json_key'] = last_change_temperature_meat1_json_key
json_keys_dict['last_change_temperature_meat2_json_key'] = last_change_temperature_meat2_json_key
json_keys_dict['last_change_temperature_meat3_json_key'] = last_change_temperature_meat3_json_key
json_keys_dict['last_change_temperature_meat4_json_key'] = last_change_temperature_meat4_json_key

hardcoded_values_dict = {}
hardcoded_values_dict['pi_ager_version'] = version_number

global dict_for_json_creation
dict_for_json_creation = {}

def add_to_dict_for_json_creation(dict):
    """
    adding dictionary for creating jsonfile (web)
    """
    global dict_for_json_creation
    
    for key,value in dict.items():
        dict_for_json_creation[key] = value

def create_json_file():
    """
    creating jsonfile for website
    """
    import json
    global dict_for_json_creation
    
    add_to_dict_for_json_creation(tables_dict)
    add_to_dict_for_json_creation(table_keys_dict)
    add_to_dict_for_json_creation(table_fields_dict)
    add_to_dict_for_json_creation(path_url_dict)
    add_to_dict_for_json_creation(json_keys_dict)
    add_to_dict_for_json_creation(hardcoded_values_dict)
    
    json_file = '/var/www/modules/names.json'

    
    with open(json_file, 'w') as file:
        file.write(json.dumps(dict_for_json_creation))


