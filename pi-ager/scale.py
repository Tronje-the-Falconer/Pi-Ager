#!/usr/bin/python3
import sys
import RPi.GPIO as GPIO
import time
import datetime
from hx711 import Scale
import pi_ager_database
import pi_ager_names

scale = sys.argv[1] # siehe https://www.mikrocontroller.net/topic/406385

if scale == pi_ager_names.scale1_key:
    scale_settings_table = pi_ager_names.settings_scale1_table
    status_scale_key = pi_ager_names.status_scale1_key
    scale_table = pi_ager_names.data_scale1_table
else:
    scale_settings_table = pi_ager_names.settings_scale2_table
    status_scale_key = pi_ager_names.status_scale2_key
    scale_table = pi_ager_names.data_scale2_table
    
scale_setting_rows = pi_ager_database.get_scale_settings_from_table(scale_settings_table)

for scale_setting_row in scale_setting_rows:
    scale_settings[scale_setting_row[pi_ager_names.key-field]] = scale_setting_row[pi_ager_names.value_field]
    

# 10KG China Zelle:
#   Gain 32 -> 60
#   Gain 128 -> 205
# 20kg China Zelle:
#   Gain 32 -> 
#   Gain 128 -> 102
# 50kg Edelstahl Zelle:
#   Gain 32 -> 
#   Gain 128 -> 74
# 20kg Edelstahl Zelle:
#   Gain 32 -> 
#   Gain 128 -> 186


# Standard Aufruf Parameter Scale (self, source=None, samples=20, spikes=4, sleep=0.1, dout=10, pd_sck=9, gain=128, bitsToRead=24)
scale = Scale(source=None, samples=scale_settings[pi_ager_names.sample_key], spikes=scale_settings[pi_ager_names.spikes_key], sleep=scale_settings[pi_ager_names.sleep_key], dout=scale_settings[pi_ager_names.gpio_data_key], pd_sck=scale_settings[pi_ager_names.gpio_sync_key], gain=scale_settings[pi_ager_names.gain_key], bitsToRead=scale_settings[pi_ager_names.bits_to_read_key])

scale.setReferenceUnit(scale_settings[pi_ager_names.referenceunit_key])

while True:

    try:
        status_tara_scale = pi_ager_database.get_table_value(pi_ager_names.current_values_table, status_tara_scale_key)
        status_scale = pi_ager_database.get_table_value(pi_ager_names.current_values_table, status_scale_key)
        last_measure = pi_ager_database.get_scale_table_row(scale_table)[pi_ager_names.last_change_field]
        measuring_interval = pi_ager_database.get_scale_table_row(scale_table)[pi_ager_names.agingtable_measuring_interval_field]
        time_difference = pi_ager_database.get_current_time() - last_measure
        
        
        if status_tara_scale == 1:
            scale.tare()
        if status_scale == 1 and time_difference > measuring_interval:
            value = scale.getMeasure()
            formated_value = round(value, 3)
            pi_ager_database.write_scale(pi_ager_names.data_scale1_table,value)

    except (KeyboardInterrupt, SystemExit):
        GPIO.cleanup()
        sys.exit()