#!/usr/bin/python3
import time
from hx711 import Scale
import pi_ager_database
import pi_ager_names
from main.pi_ager_cl_logger import cl_fact_logger

cl_fact_logger.get_instance()

def tara_scale(scale, tara_key, data_table, calibrate_key, offset, settings_table):
    global logger
    logger.debug('performing tara')
    #scale.reset()
    #scale.tare()
    
    pi_ager_database.update_value_in_table(settings_table, pi_ager_names.offset_scale_key, 0) # set offset to zero to get right offset value
    offset = 0
    
    scale.setSamples(int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.samples_refunit_tara_key)))
    scale.setSpikes(int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.spikes_refunit_tara_key)))
    
    clear_history = scale.getWeight() # delete values out of history
    
    tara_measuring_endtime = pi_ager_database.get_current_time() + 1
    pi_ager_database.update_value_in_table(pi_ager_names.current_values_table, tara_key, 2)
    
    newoffset = scale_measures(scale, tara_measuring_endtime, data_table, 1, tara_key, calibrate_key, offset, settings_table)
    pi_ager_database.update_value_in_table(settings_table, pi_ager_names.offset_scale_key, newoffset)
    pi_ager_database.write_stop_in_database(tara_key)
    
    scale.setSamples(int(pi_ager_database.get_table_value(settings_table, pi_ager_names.samples_key)))
    scale.setSpikes(int(pi_ager_database.get_table_value(settings_table, pi_ager_names.spikes_key)))
    
    logger.debug('tara performed - runnig control-measurement')
    # im Anschluss eine Kontrollmessung machen
    scale_measures(scale, tara_measuring_endtime, data_table, 1, tara_key, calibrate_key, newoffset, settings_table)

def scale_measures(scale, scale_measuring_endtime, data_table, saving_period, tara_key, calibrate_scale_key, offset, settings_table):
    global logger
    logger.debug('scale_measures()')
    measure_start_time = pi_ager_database.get_current_time()
    
    save_time = 0
    current_time = measure_start_time
    while current_time <= int(scale_measuring_endtime):
        calibrate_scale = pi_ager_database.get_table_value(pi_ager_names.current_values_table, calibrate_scale_key)
        if calibrate_scale != 0:
            scale_measuring_endtime = current_time
        status_tara_scale = pi_ager_database.get_table_value(pi_ager_names.current_values_table, tara_key)
        if status_tara_scale == 1:
            tara_scale(scale, tara_key, data_table, calibrate_scale_key, offset, settings_table)
        value = scale.getMeasure()
        value = value - offset
        if status_tara_scale == 2:
            logger.debug('tara measurement performed')
            return value
        formated_value = round(value, 3)
        if (current_time - measure_start_time) % saving_period == 0 and current_time != save_time:      # speichern je nach datenbankeintrag fuer saving_period
            save_time = current_time
            pi_ager_database.write_scale(data_table,value)
            logger.debug('scale-value saved in database ' + time.strftime('%H:%M:%S', time.localtime()))
        current_time = pi_ager_database.get_current_time()
    logger.debug('measurement performed')

def get_scale_settings(scale_setting_rows):
    global logger
    scale_settings = {}
    for scale_setting_row in scale_setting_rows:
        scale_settings[scale_setting_row[pi_ager_names.key_field]] = scale_setting_row[pi_ager_names.value_field]
    return scale_settings
    
def get_first_calibrate_measure(scale, scale_settings_table, calibrate_scale_key):
    # scale.setReferenceUnit(1)
    scale.setReferenceUnit(pi_ager_database.get_table_value(scale_settings_table, pi_ager_names.referenceunit_key))
    scale.setSamples(int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.samples_refunit_tara_key)))
    scale.setSpikes(int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.spikes_refunit_tara_key)))
    # scale.reset()
    # scale.tare()
    clear_history = scale.getWeight()
    calibrate_value_before_weight = scale.getMeasure()
    pi_ager_database.write_current_value(calibrate_scale_key,2)
    scale.setSamples(int(pi_ager_database.get_table_value(scale_settings_table, pi_ager_names.samples_key)))
    scale.setSpikes(int(pi_ager_database.get_table_value(scale_settings_table, pi_ager_names.spikes_key)))
    return calibrate_value_before_weight
    
def calculate_reference_unit(scale, calibrate_scale_key, scale_settings_table, calibrate_value_first_measure):
    # scale.setReferenceUnit(1)
    old_ref_unit = pi_ager_database.get_table_value(scale_settings_table, pi_ager_names.referenceunit_key)
    scale.setReferenceUnit(old_ref_unit)
    scale.setSamples(int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.samples_refunit_tara_key)))
    scale.setSpikes(int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.spikes_refunit_tara_key)))
    
    calibrate_weight = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.calibrate_weight_key)
    clear_history = scale.getWeight()
    calibrate_value_after_weight = scale.getMeasure()
    reference_unit = (calibrate_value_after_weight - calibrate_value_first_measure)/calibrate_weight * old_ref_unit
    if reference_unit == 0:
        pi_ager_database.write_current_value(calibrate_scale_key,5)
    else:
        pi_ager_database.update_value_in_table(scale_settings_table, pi_ager_names.referenceunit_key, reference_unit)
        scale.setReferenceUnit(reference_unit)
        pi_ager_database.write_current_value(calibrate_scale_key,4)
    scale.setSamples(int(pi_ager_database.get_table_value(scale_settings_table, pi_ager_names.samples_key)))
    scale.setSpikes(int(pi_ager_database.get_table_value(scale_settings_table, pi_ager_names.spikes_key)))

def doScaleLoop():
    global logger
    scale1_settings_table = pi_ager_names.settings_scale1_table
    scale1_table = pi_ager_names.data_scale1_table
    scale2_settings_table = pi_ager_names.settings_scale2_table
    scale2_table = pi_ager_names.data_scale2_table

    scale1_setting_rows = pi_ager_database.get_scale_settings_from_table(scale1_settings_table)
    scale2_setting_rows = pi_ager_database.get_scale_settings_from_table(scale2_settings_table)

    scale1_settings = get_scale_settings(scale1_setting_rows)
    scale2_settings = get_scale_settings(scale2_setting_rows)
    
    scale1 = Scale(source=None, samples=int(scale1_settings[pi_ager_names.samples_key]), spikes=int(scale1_settings[pi_ager_names.spikes_key]), sleep=scale1_settings[pi_ager_names.sleep_key], dout=pi_ager_gpio_config.gpio_scale1_data, pd_sck=pi_ager_gpio_config.gpio_scale1_sync, gain=int(scale1_settings[pi_ager_names.gain_key]), bitsToRead=int(scale1_settings[pi_ager_names.bits_to_read_key]))
    scale2 = Scale(source=None, samples=int(scale2_settings[pi_ager_names.samples_key]), spikes=int(scale2_settings[pi_ager_names.spikes_key]), sleep=scale2_settings[pi_ager_names.sleep_key], dout=pi_ager_gpio_config.gpio_scale2_data, pd_sck=pi_ager_gpio_config.gpio_scale2_sync, gain=int(scale2_settings[pi_ager_names.gain_key]), bitsToRead=int(scale2_settings[pi_ager_names.bits_to_read_key]))

    while True:
        cl_fact_logger.get_instance().debug('doScaleLoop() ' + time.strftime('%H:%M:%S', time.localtime()))
        scale1.setReferenceUnit(pi_ager_database.get_table_value(scale1_settings_table, pi_ager_names.referenceunit_key))
        scale2.setReferenceUnit(pi_ager_database.get_table_value(scale2_settings_table, pi_ager_names.referenceunit_key))
        
        # scale1.setReferenceUnit(scale1_settings[pi_ager_names.referenceunit_key])
        # scale2.setReferenceUnit(scale2_settings[pi_ager_names.referenceunit_key])
        
        status_tara_scale1 = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_tara_scale1_key)
        status_scale1 = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_scale1_key)
        scale1_measuring_duration = pi_ager_database.get_table_value(pi_ager_names.settings_scale1_table, pi_ager_names.measuring_duration_key)
        saving_period_scale1 = pi_ager_database.get_table_value(pi_ager_names.settings_scale1_table, pi_ager_names.saving_period_key)
        status_tara_scale2 = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_tara_scale2_key)
        status_scale2 = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_scale2_key)
        scale2_measuring_duration = pi_ager_database.get_table_value(pi_ager_names.settings_scale2_table, pi_ager_names.measuring_duration_key)
        saving_period_scale2 = pi_ager_database.get_table_value(pi_ager_names.settings_scale2_table, pi_ager_names.saving_period_key)
        calibrate_scale1 = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.calibrate_scale1_key)
        calibrate_scale2 = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.calibrate_scale2_key)
        offset_scale1 = pi_ager_database.get_table_value(pi_ager_names.settings_scale1_table, pi_ager_names.offset_scale_key)
        offset_scale2 = pi_ager_database.get_table_value(pi_ager_names.settings_scale2_table, pi_ager_names.offset_scale_key)
        
        samples_scale1 = int(pi_ager_database.get_table_value(pi_ager_names.settings_scale1_table, pi_ager_names.samples_key))
        samples_scale2 = int(pi_ager_database.get_table_value(pi_ager_names.settings_scale2_table, pi_ager_names.samples_key))
        spikes_scale1 = int(pi_ager_database.get_table_value(pi_ager_names.settings_scale1_table, pi_ager_names.spikes_key))
        spikes_scale2 = int(pi_ager_database.get_table_value(pi_ager_names.settings_scale2_table, pi_ager_names.spikes_key))
        
        
        if pi_ager_database.get_table_value(pi_ager_names.debug_table, pi_ager_names.loglevel_console_key) == 10:
            measuring_interval_scale1 = pi_ager_database.get_table_value(pi_ager_names.debug_table, pi_ager_names.measuring_interval_debug_key)
            measuring_interval_scale2 = measuring_interval_scale1
        else:
            measuring_interval_scale1 = pi_ager_database.get_table_value(pi_ager_names.settings_scale1_table, pi_ager_names.scale_measuring_interval_key)
            measuring_interval_scale2 = pi_ager_database.get_table_value(pi_ager_names.settings_scale2_table, pi_ager_names.scale_measuring_interval_key)

        if calibrate_scale1 == 1:
            first_calibrate_value = get_first_calibrate_measure(scale1, scale1_settings_table, pi_ager_names.calibrate_scale1_key)
            
        if calibrate_scale2 == 1:
            first_calibrate_value = get_first_calibrate_measure(scale2, scale2_settings_table, pi_ager_names.calibrate_scale2_key)
            
        if calibrate_scale1 == 3:
            calculate_reference_unit(scale1, pi_ager_names.calibrate_scale1_key, pi_ager_names.settings_scale1_table, first_calibrate_value)
            
        if calibrate_scale2 == 3:
            calculate_reference_unit(scale2, pi_ager_names.calibrate_scale2_key, pi_ager_names.settings_scale2_table, first_calibrate_value)
            
        if status_tara_scale1 == 1:
            tara_scale(scale1, pi_ager_names.status_tara_scale1_key, pi_ager_names.data_scale1_table, pi_ager_names.calibrate_scale1_key, offset_scale1, pi_ager_names.settings_scale1_table)
        
        if status_tara_scale2 == 1:
            tara_scale(scale2, pi_ager_names.status_tara_scale2_key, pi_ager_names.data_scale2_table, pi_ager_names.calibrate_scale2_key, offset_scale2, pi_ager_names.settings_scale2_table)
            
        
        if status_scale1 == 1:
            if pi_ager_database.get_scale_table_row(scale1_table) != None:
                last_measure_scale1 = pi_ager_database.get_scale_table_row(scale1_table)[pi_ager_names.last_change_field]
                time_difference_scale1 = pi_ager_database.get_current_time() - last_measure_scale1
            else:
                time_difference_scale1 = measuring_interval_scale1 + 1
            if time_difference_scale1 >= measuring_interval_scale1:
                scale1_measuring_endtime =  pi_ager_database.get_current_time() + scale1_measuring_duration
                scale_measures(scale1, scale1_measuring_endtime, pi_ager_names.data_scale1_table, saving_period_scale1, pi_ager_names.status_tara_scale1_key,pi_ager_names.calibrate_scale1_key, offset_scale1, pi_ager_names.settings_scale1_table)

        if status_scale2 == 1:
            if pi_ager_database.get_scale_table_row(scale2_table) != None:
                last_measure_scale2 = pi_ager_database.get_scale_table_row(scale2_table)[pi_ager_names.last_change_field]
                time_difference_scale2 = pi_ager_database.get_current_time() - last_measure_scale2
            else:
                time_difference_scale2 = measuring_interval_scale2 + 1
            if time_difference_scale2 >= measuring_interval_scale2:
                scale2_measuring_endtime = pi_ager_database.get_current_time() + scale2_measuring_duration
                scale_measures(scale2, scale2_measuring_endtime, pi_ager_names.data_scale2_table, saving_period_scale2, pi_ager_names.status_tara_scale2_key, pi_ager_names.calibrate_scale2_key, offset_scale2, pi_ager_names.settings_scale2_table)

        
        time.sleep(2)