import time
from hx711 import Scale
import pi_ager_database
import pi_ager_names
import pi_ager_logging

def scale_measures(scale, status_tara_scale, status_scale):
    
    if status_tara_scale == 1:
        scale.reset()
        scale.tare()

    value = scale.getMeasure()
    formated_value = round(value, 3)
    pi_ager_database.write_scale(pi_ager_names.data_scale1_table,value)
    
def get_scale_settings(scale_setting_rows):
    scale_settings = {}
    for scale_setting_row in scale_setting_rows:
        scale_settings[scale_setting_row[pi_ager_names.key_field]] = scale_setting_row[pi_ager_names.value_field]
    return scale_settings
    
def doScaleLoop():
    scale1_settings_table = pi_ager_names.settings_scale1_table
    status_scale1_key = pi_ager_names.status_scale1_key
    scale1_table = pi_ager_names.data_scale1_table
    scale2_settings_table = pi_ager_names.settings_scale2_table
    status_scale2_key = pi_ager_names.status_scale2_key
    scale2_table = pi_ager_names.data_scale2_table
        
    scale1_setting_rows = pi_ager_database.get_scale_settings_from_table(scale1_settings_table)
    scale2_setting_rows = pi_ager_database.get_scale_settings_from_table(scale2_settings_table)

    scale1_settings = get_scale_settings(scale1_setting_rows)
    scale2_settings = get_scale_settings(scale2_setting_rows)
    
    scale1 = Scale(source=None, samples=scale1_settings[pi_ager_names.samples_key], spikes=scale1_settings[pi_ager_names.spikes_key], sleep=scale1_settings[pi_ager_names.sleep_key], dout=int(scale1_settings[pi_ager_names.gpio_data_key]), pd_sck=int(scale1_settings[pi_ager_names.gpio_sync_key]), gain=scale1_settings[pi_ager_names.gain_key], bitsToRead=int(scale1_settings[pi_ager_names.bits_to_read_key]))
    scale1.setReferenceUnit(scale1_settings[pi_ager_names.referenceunit_key])

    scale2 = Scale(source=None, samples=scale2_settings[pi_ager_names.samples_key], spikes=scale2_settings[pi_ager_names.spikes_key], sleep=scale2_settings[pi_ager_names.sleep_key], dout=int(scale2_settings[pi_ager_names.gpio_data_key]), pd_sck=int(scale2_settings[pi_ager_names.gpio_sync_key]), gain=scale2_settings[pi_ager_names.gain_key], bitsToRead=int(scale2_settings[pi_ager_names.bits_to_read_key]))
    scale2.setReferenceUnit(scale2_settings[pi_ager_names.referenceunit_key])
    
    try:
        while True:

            status_tara_scale1 = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_tara_scale1_key)
            status_scale1 = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_scale1_key)
            
            status_tara_scale2 = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_tara_scale2_key)
            status_scale2 = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_scale2_key)
            
            if status_scale1 == 1:
                measuring_interval_scale1 = pi_ager_database.get_table_value(pi_ager_names.settings_scale1_table, pi_ager_names.scale_measuring_interval_key)
                if pi_ager_database.get_scale_table_row(scale1_table) != None:
                    last_measure_scale1 = pi_ager_database.get_scale_table_row(scale1_table)[pi_ager_names.last_change_field]
                    time_difference_scale1 = pi_ager_database.get_current_time() - last_measure_scale1
                else:
                    time_difference_scale1 = 0
                if time_difference_scale1 >= measuring_interval_scale1:
                    scale_measures(scale1, status_tara_scale1, status_scale1, measuring_interval_scale1, time_difference_scale1)
            
            if status_scale2 == 1:
                measuring_interval_scale2 = pi_ager_database.get_table_value(pi_ager_names.settings_scale2_table, pi_ager_names.scale_measuring_interval_key)
                if pi_ager_database.get_scale_table_row(scale2_table) != None:
                    last_measure_scale2 = pi_ager_database.get_scale_table_row(scale2_table)[pi_ager_names.last_change_field]
                    time_difference_scale2 = pi_ager_database.get_current_time() - last_measure_scale2
                else:
                    time_difference_scale2 = 0
                if time_difference_scale2 >= measuring_interval_scale2:
                    scale_measures(scale2, status_tara_scale2, status_scale2, measuring_interval_scale2, time_difference_scale2)
                
                
            time.sleep(2)

    except KeyboardInterrupt:
        pass
