#!/usr/bin/python3
import RPi.GPIO as gpio
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
loglevel_file_key = 'loglevel_file'
loglevel_console_key = 'loglevel_console'
agingtable_period_key = 'agingtable_period'
agingtable_period_starttime_key = 'agingtable_period_starttime'
measuring_interval_debug_key = 'measuring_interval_debug'
agingtable_days_in_seconds_debug_key = 'agingtable_days_in_seconds_debug'
measuring_duration_key = 'measuring_duration'
saving_period_key = 'saving_period'
failure_temperature_delta_key = 'failure_temperature_delta'
failure_humidity_delta_key = 'failure_humidity_delta'

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

# hardcoded values
# Pinbelegung
# Pinleiste vertikal Pin 1 oben links pin 2 oben rechts
board_mode = gpio.BCM              # GPIO board mode (BCM = Broadcom SOC channel number - numbers after GPIO Bsp. GPIO12=12 [GPIO.BOARD = Pin by number Bsp: GPIO12=32])

# linke Pinleiste:
# 3 V
gpio_notinuse_0 = 2
gpio_heater = 3                    # GPIO fuer Heizkabel
gpio_cooling_compressor = 4        # GPIO fuer Kuehlschrankkompressor
# Ground
gpio_sensor_data = 17              # GPIO fuer Data Temperatur/Humidity Sensor
gpio_sensor_sync = 27              # GPIO fuer Sync Temperatur/Humidity Sensor
gpio_switch = 22                   # GPIO fuer manuellen Schalter
# 3 V
gpio_scale2_data = 10               # GPIO fuer Waage2 Data
gpio_scale2_sync = 9                # GPIO fuer Waage2 Sync
gpio_notinuse_1 = 11
# Ground
gpio_notinuse_2 =  0
gpio_scale1_data = 5                # GPIO fuer Waage1 Data
gpio_scale1_sync = 6                # GPIO fuer Waage1 Sync
gpio_alarm = 13                     # GPIO fuer Piezzo
gpio_temperature_meat_MISO = 19     # GPIO fuer A/D Wandler Fleischtemperatursensoren
gpio_notinuse_3 = 26
# Ground

# rechte Pinleiste:#
# 5 V
# 5 V
# Ground
gpio_display_txd = 14            # GPIO fuer Display
gpio_display_rxd = 15            # GPIO fuer Diplay
gpio_humidifier = 18               # GPIO fuer Luftbefeuchter
# Ground
gpio_exhausting_air = 23           # GPIO fuer Austauschluefter
gpio_circulating_air = 24          # GPIO fuer Umluftventilator
# Ground
gpio_uv = 25                       # GPIO fuer UV Licht
gpio_light = 8                     # GPIO fuer Licht
gpio_dehumidifier = 7              # GPIO fuer Entfeuchter
gpio_notinuse_4 = 1
# Ground
gpio_notinuse_5 = 12
# Ground
gpio_temperature_meat_CSO = 16     # GPIO fuer A/D Wandler Fleischtemperatursensoren
gpio_temperature_meat_MOSI = 20    # GPIO fuer A/D Wandler Fleischtemperatursensoren
gpio_temperature_meat_SCLK = 21    # GPIO fuer A/D Wandler Fleischtemperatursensoren Sync


# Sainsmart Relais Vereinfachung 0 aktiv
relay_on = False               # negative Logik!!! des Relay's, Schaltet bei 0 | GPIO.LOW  | False  ein
relay_off = (not relay_on)     # negative Logik!!! des Relay's, Schaltet bei 1 | GPIO.High | True aus

logspacer = "***********************************************"
logspacer2 = '-------------------------------------------------------'