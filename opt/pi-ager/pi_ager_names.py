#!/usr/bin/python3
# -*- coding: utf-8 -*-
"""
    pi-ager variables
    
    setting up variables
"""
#import RPi.GPIO as gpio

########################### Definition of variables
version_number = '4.0.0'
# tables names
config_settings_table = 'config'

status_heater_table = 'heater_status'
status_exhaust_air_table = 'exhaust_air_status'
status_cooling_compressor_table = 'cooling_compressor_status'
status_circulating_air_table = 'circulating_air_status'
status_uv_table = 'uv_status'
status_light_table = 'light_status'
status_dehumidifier_table = 'dehumidifier_status'
status_humidifier_table = 'humidifier_status'
current_values_table = 'current_values'
agingtables_table = 'agingtables'
settings_scale1_table = 'scale1_settings'
settings_scale2_table = 'scale2_settings'
debug_table = 'debug'
system_table = 'system'
meat_sensortypes_table = 'meat_sensortypes'
all_sensors_table = 'all_sensors'
all_scales_table = 'all_scales'
nextion_table = 'nextion'
atc_mi_thermometer_mac_table = 'atc_mi_thermometer_mac'
atc_mi_thermometer_data_table = 'atc_mi_thermometer_data'
defrost_table = 'config_defrost'
config_current_check_table = 'config_current_check'
time_meter_table = 'time_meter'
config_mqtt_table = 'config_mqtt'
humidity_offset_table = 'humidity_offset'

# table keys
cooling_hysteresis_key = 'cooling_hysteresis'
heating_hysteresis_key = 'heating_hysteresis'
delay_humidify_key = 'delay_humidify'
saturation_point_key = 'saturation_point'                                         
delay_cooler_key = 'delay_cooler'
diagram_modus_key = 'diagram_modus'
referenceunit_scale1_key = 'referenceunit_scale1'
referenceunit_scale2_key = 'referenceunit_scale2'
sensortype_key = 'sensortype'
sensorsecondtype_key = 'secondsensortype'
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
sensor_dewpoint_key = 'sensor_dewpoint'
sensor_humidity_abs_key = 'sensor_humidity_abs'

second_sensor_temperature_key = 'sensor_extern_temperature'
second_sensor_humidity_key = 'sensor_extern_humidity'
second_sensor_dewpoint_key = 'sensor_extern_dewpoint'
second_sensor_humidity_abs_key = 'sensor_extern_humidity_abs'

switch_control_uv_light_key = 'switch_control_uv_light'
switch_control_light_key = 'switch_control_light'

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
customtime_for_diagrams_key = 'customtime_for_diagrams'
measuring_interval_debug_key = 'measuring_interval_debug'
agingtable_hours_in_seconds_debug_key = 'agingtable_hours_in_seconds_debug'
measuring_duration_key = 'measuring_duration'
samples_refunit_tara_key = 'samples_refunit_tara'
spikes_refunit_tara_key = 'spikes_refunit_tara'
saving_period_key = 'saving_period'
failure_temperature_delta_key = 'failure_temperature_delta'
failure_humidity_delta_key = 'failure_humidity_delta'
failure_dewpoint_delta_key = 'failure_dewpoint_delta'
agingtable_period_hour_key = 'agingtable_period_hour'
agingtable_starthour_key = 'agingtable_starthour'
agingtable_startperiod_key = 'agingtable_startperiod'
aging_thread_alive_key = 'aging_thread_alive'
scale1_thread_alive_key = 'scale1_thread_alive'
scale2_thread_alive_key = 'scale2_thread_alive'

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
tft_display_type_key = 'tft_display_type'
internal_temperature_low_limit_key = 'internal_temperature_low_limit'
internal_temperature_high_limit_key = 'internal_temperature_high_limit'
internal_temperature_hysteresis_key = 'internal_temperature_hysteresis'
shutdown_on_batlow_key = 'shutdown_on_batlow'
dewpoint_check_key = 'dewpoint_check'
status_humidity_check_key = 'status_humidity_check'
humidity_check_hysteresis_key = 'humidity_check_hysteresis'
mi_data_key = 'mi_data'
mi_mac_last3bytes_key = 'mi_mac_last3bytes'
MiSensor_battery_key = 'MiSensor_battery'
status_defrost_key = 'status_defrost'
uv_check_key = 'uv_check'

temperature_avg_key = 'temperature_avg'
humidity_avg_key = 'humidity_avg'
humidity_abs_avg_key = 'humidity_abs_avg'
temp_avg_maxlen_key = 'temp_avg_maxlen'
hum_avg_maxlen_key = 'hum_avg_maxlen'

humidifier_hysteresis_key = 'humidifier_hysteresis'
dehumidifier_hysteresis_key = 'dehumidifier_hysteresis'
humidifier_hysteresis_offset_key = 'humidifier_hysteresis_offset'
dehumidifier_hysteresis_offset_key = 'dehumidifier_hysteresis_offset'

delay_monitoring_humidifier_key = 'delay_monitoring_humidifier'
tolerance_monitoring_humidifier_key = 'tolerance_monitoring_humidifier'
check_monitoring_humidifier_key = 'check_monitoring_humidifier'

cooling_hysteresis_offset_key = 'cooling_hysteresis_offset'
heating_hysteresis_offset_key = 'heating_hysteresis_offset'

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
agingtable_hours_field = 'hours'
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
tempint_field = 'tempint'
tempext_field = 'tempext'
humint_field = 'humint'
humext_field = 'humext'
dewint_field = 'dewint'
dewext_field = 'dewext'
humintabs_field = 'humintabs'
humextabs_field = 'humextabs'
ntc1_field = 'ntc1'
ntc2_field = 'ntc2'
ntc3_field = 'ntc3'
ntc4_field = 'ntc4'
scale1_field = 'scale1'
scale2_field = 'scale2'
progress_field = 'progress'
status_field = 'status'
defrost_active_field = 'active'
defrost_temperature_field = 'temperature'
defrost_cycle_hours_field = 'cycle_hours'
current_check_active_field = 'current_check_active'
current_threshold_field = 'current_threshold'
repeat_event_cycle_field = 'repeat_event_cycle'
uv_light_seconds_field = 'uv_light_seconds'
pi_ager_seconds_field = 'pi_ager_seconds'
defrost_temp_limit_field = 'temp_limit'
defrost_circulate_air_field = 'circulate_air'
tempintavg_field = 'tempintavg'
humintavg_field = 'humintavg'
broker_address_field = 'broker_address'
port_field = 'port'
username_field = 'username'
password_field = 'password'
mqtt_active_field = 'mqtt_active'

# Paths and urls
thread_url = 'https://www.grillsportverein.de/forum/threads/pi-ager-reifeschranksteuerung-mittels-raspberry-pi-release-3-3-x.342426/'
error_reporting_url = 'https://github.com/Tronje-the-Falconer/Pi-Ager/wiki/Error-reporting'
faq_url =  'https://pi-ager.org/faq/'
sqlite_path = '/var/www/config/pi-ager.sqlite3'
logfile_txt_file = '/var/www/logs/logfile.txt'
pi_ager_log_file = '/var/www/logs/pi-ager.log'
changelogfile = '/var/www/changelog.txt'

# JSON Keys
last_change_temperature_json_key = 'last_change_temperature'
last_change_humidity_json_key = 'last_change_humidity'
last_change_dewpoint_json_key = 'last_change_dewpoint'

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

logspacer  = "****************"
logspacer2 = '--'

SUPPORTED_MAIN_SENSOR_TYPES = {1: "DHT11",
                                     2: "DHT22",
                                     3: "SHT75",
                                     4: "SHT85",
                                     5: "SHT3x",
                                     6: "SHT3x-mod",
                                     7: "AHT1x",
                                     8: "AHT1x-mod",
                                     9: "AHT2x",
                                     10: "AHT30",
                                     11: "SHT4x-A",
                                     12: "SHT4x-B",
                                     13: "SHT4x-C"}
                                     
SUPPORTED_SECOND_SENSOR_TYPES = { 0: "disabled",
                                        4: "SHT85",
                                        5: "SHT3x",
                                        6: "SHT3x-mod",
                                        7: "AHT1x",
                                        8: "AHT1x-mod",      
                                        9: "AHT2x",
                                        10: "AHT30",
                                        11: "SHT4x-A",
                                        12: "SHT4x-B",
                                        13: "SHT4x-C",
                                        14: "MiThermometer"}
                                        
I2C_SENSOR_ADDRESS = {  0: None,
                        1: None,
                        2: None,
                        3: None,
                        4: 0x44,
                        5: 0x44,
                        6: 0x45,
                        7: 0x38,
                        8: 0x39,
                        9: 0x38,
                        10: 0x38,
                        11: 0x44,
                        12: 0x45,
                        13: 0x46,
                        14: None }
                        