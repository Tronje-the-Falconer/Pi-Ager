#!/usr/bin/python3
import time
from hx711 import Scale
import pi_ager_database
import pi_ager_names
from pi_ager_logging import create_logger

global logger
logger = create_logger(__name__)
logger.debug('logging initialised')

def tara_scale(scale, tara_key, data_table):
    global logger
    scale.reset()
    scale.tare()
    pi_ager_database.write_stop_in_database(tara_key)
    logger.debug('tara performed')
    tara_measuring_endtime = pi_ager_database.get_current_time() + 1
    scale_measures(scale, tara_measuring_endtime, data_table, 1, tara_key)

def scale_measures(scale, scale_measuring_endtime, data_table, saving_period, tara_key):
    global logger
    logger.debug('scale_measures()')
    measure_start_time = pi_ager_database.get_current_time()
    save_time = 0
    current_time = measure_start_time
    while current_time <= int(scale_measuring_endtime):
        status_tara_scale = pi_ager_database.get_table_value(pi_ager_names.current_values_table, tara_key)
        if status_tara_scale == 1:
            tara_scale(scale, tara_key, data_table)
        value = scale.getMeasure()
        formated_value = round(value, 3)
        if (current_time - measure_start_time) % saving_period == 0 and current_time != save_time:       # alle 5 Sekunden wird einmal gespeichert
            save_time = current_time
            pi_ager_database.write_scale(data_table,value)
        current_time = pi_ager_database.get_current_time()

def get_scale_settings(scale_setting_rows):
    global logger
    scale_settings = {}
    for scale_setting_row in scale_setting_rows:
        scale_settings[scale_setting_row[pi_ager_names.key_field]] = scale_setting_row[pi_ager_names.value_field]
    return scale_settings

def doScaleLoop():
    global logger
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
    
    scale1 = Scale(source=None, samples=int(scale1_settings[pi_ager_names.samples_key]), spikes=int(scale1_settings[pi_ager_names.spikes_key]), sleep=scale1_settings[pi_ager_names.sleep_key], dout=int(scale1_settings[pi_ager_names.gpio_data_key]), pd_sck=int(scale1_settings[pi_ager_names.gpio_sync_key]), gain=int(scale1_settings[pi_ager_names.gain_key]), bitsToRead=int(scale1_settings[pi_ager_names.bits_to_read_key]))
    scale2 = Scale(source=None, samples=int(scale2_settings[pi_ager_names.samples_key]), spikes=int(scale2_settings[pi_ager_names.spikes_key]), sleep=scale2_settings[pi_ager_names.sleep_key], dout=int(scale2_settings[pi_ager_names.gpio_data_key]), pd_sck=int(scale2_settings[pi_ager_names.gpio_sync_key]), gain=int(scale2_settings[pi_ager_names.gain_key]), bitsToRead=int(scale2_settings[pi_ager_names.bits_to_read_key]))

    while True:
        logger.debug('doScaleLoop() ' + time.strftime('%H:%M:%S', time.localtime()))
        scale1.setReferenceUnit(scale1_settings[pi_ager_names.referenceunit_key])
        scale2.setReferenceUnit(scale2_settings[pi_ager_names.referenceunit_key])
        
        status_tara_scale1 = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_tara_scale1_key)
        status_scale1 = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_scale1_key)
        scale1_measuring_duration = pi_ager_database.get_table_value(pi_ager_names.settings_scale1_table, pi_ager_names.measuring_duration_key)
        saving_period_scale1 = pi_ager_database.get_table_value(pi_ager_names.settings_scale1_table, pi_ager_names.saving_period_key)
        status_tara_scale2 = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_tara_scale2_key)
        status_scale2 = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_scale2_key)
        scale2_measuring_duration = pi_ager_database.get_table_value(pi_ager_names.settings_scale2_table, pi_ager_names.measuring_duration_key)
        saving_period_scale2 = pi_ager_database.get_table_value(pi_ager_names.settings_scale2_table, pi_ager_names.saving_period_key)
        
        if pi_ager_database.get_table_value(pi_ager_names.debug_table, pi_ager_names.loglevel_console_key) == 10:
            measuring_interval_scale1 = pi_ager_database.get_table_value(pi_ager_names.debug_table, pi_ager_names.measuring_interval_debug_key)
            measuring_interval_scale2 = measuring_interval_scale1
        else:
            measuring_interval_scale1 = pi_ager_database.get_table_value(pi_ager_names.settings_scale1_table, pi_ager_names.scale_measuring_interval_key)
            measuring_interval_scale2 = pi_ager_database.get_table_value(pi_ager_names.settings_scale2_table, pi_ager_names.scale_measuring_interval_key)

        if status_tara_scale1 == 1:
            tara_scale(scale1, pi_ager_names.status_tara_scale1_key, pi_ager_names.data_scale1_table)
        
        if status_tara_scale2 == 1:
            tara_scale(scale2, pi_ager_names.status_tara_scale2_key, pi_ager_names.data_scale2_table)
            
        
        if status_scale1 == 1:
            if pi_ager_database.get_scale_table_row(scale1_table) != None:
                last_measure_scale1 = pi_ager_database.get_scale_table_row(scale1_table)[pi_ager_names.last_change_field]
                time_difference_scale1 = pi_ager_database.get_current_time() - last_measure_scale1
            else:
                time_difference_scale1 = measuring_interval_scale1 + 1
            if time_difference_scale1 >= measuring_interval_scale1:
                scale1_measuring_endtime =  pi_ager_database.get_current_time() + scale1_measuring_duration
                scale_measures(scale1, scale1_measuring_endtime, pi_ager_names.data_scale1_table, saving_period_scale1, pi_ager_names.status_tara_scale1_key)

        if status_scale2 == 1:
            if pi_ager_database.get_scale_table_row(scale2_table) != None:
                last_measure_scale2 = pi_ager_database.get_scale_table_row(scale2_table)[pi_ager_names.last_change_field]
                time_difference_scale2 = pi_ager_database.get_current_time() - last_measure_scale2
            else:
                time_difference_scale2 = measuring_interval_scale2 + 1
            if time_difference_scale2 >= measuring_interval_scale2:
                scale2_measuring_endtime = pi_ager_database.get_current_time() + scale2_measuring_duration
                scale_measures(scale2, scale2_measuring_endtime, pi_ager_names.data_scale2_table, saving_period_scale2, pi_ager_names.status_tara_scale2_key )

        time.sleep(2)