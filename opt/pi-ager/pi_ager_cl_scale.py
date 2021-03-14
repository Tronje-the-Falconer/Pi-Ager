#!/usr/bin/python3
"""
    thread for scales

    mainfile for scales handling
"""
import time
from sensors.pi_ager_cl_sensor_hx711 import cl_scale

import pi_ager_database
import pi_ager_names
import pi_ager_gpio_config

from messenger.pi_ager_cl_messenger import cl_fact_logic_messenger
from main.pi_ager_cl_logger import cl_fact_logger

import threading

class cl_scale_thread(threading.Thread):
    
    def __init__(self, scale_id):
        super().__init__() 
        self.scale_id = scale_id
        self.stop_received = False
        
    def run(self):
        try:
            cl_fact_logger.get_instance().debug('logging initialised __________________________')
            cl_fact_logger.get_instance().info('Start scale loop' + str(self.scale_id + 1) + ' ' + time.strftime('%H:%M:%S', time.localtime()))
            if self.scale_id == 0:
                pi_ager_database.write_current_value(pi_ager_names.scale1_thread_alive_key, 1)
            else:
                pi_ager_database.write_current_value(pi_ager_names.scale2_thread_alive_key, 1)
                
            self.doScaleLoop()

        except Exception as cx_error:
            cl_fact_logic_messenger().get_instance().handle_exception(cx_error)

        finally:
            if self.scale_id == 0:
                pi_ager_database.write_current_value(pi_ager_names.calibrate_scale1_key, 0)
                pi_ager_database.write_current_value(pi_ager_names.status_tara_scale1_key, 0)
                pi_ager_database.write_current_value(pi_ager_names.scale1_thread_alive_key, 0)
            else:
                pi_ager_database.write_current_value(pi_ager_names.calibrate_scale2_key, 0)
                pi_ager_database.write_current_value(pi_ager_names.status_tara_scale2_key, 0)
                pi_ager_database.write_current_value(pi_ager_names.scale2_thread_alive_key, 0)
                
            pi_ager_database.write_current_value(pi_ager_names.calibrate_weight_key, 0)
                    
    def tara_scale(self, scale, tara_key, settings_table):
        cl_fact_logger.get_instance().debug('tara_scale start ' + time.strftime('%H:%M:%S', time.localtime()))

        scale.setOffset(0)
        newOffset = scale.getWeightFilteredFlushingHistory() # delete values out of history and get new weight without offset applied
        scale.setOffset( newOffset )
        
        scale.getWeightFilteredFlushingHistory()    # fill history with new values based on new offset
        
        pi_ager_database.update_value_in_table(settings_table, pi_ager_names.offset_scale_key, newOffset)
        pi_ager_database.write_stop_in_database(tara_key)
    
        cl_fact_logger.get_instance().debug('tara performed: ' + str(round(newOffset, 3)) + 'gr' + ' ' + time.strftime('%H:%M:%S', time.localtime()))

    def get_scale_settings(self, scale_setting_rows):
        cl_fact_logger.get_instance().debug('get_scale_settings() ' + time.strftime('%H:%M:%S', time.localtime()))    
        scale_settings = {}
        for scale_setting_row in scale_setting_rows:
            scale_settings[scale_setting_row[pi_ager_names.key_field]] = scale_setting_row[pi_ager_names.value_field]

        return scale_settings
    
    def get_first_calibrate_measure(self, scale, scale_settings_table, calibrate_scale_key):
        cl_fact_logger.get_instance().debug('get_first_calibrate_measure() ' + time.strftime('%H:%M:%S', time.localtime()))

        calibrate_value_before_weight = scale.getWeightFilteredFlushingHistory()
        
        pi_ager_database.write_current_value(calibrate_scale_key, 2)
        cl_fact_logger.get_instance().debug('get_first_calibrate_measure() done. Ref.-Unit = ' + str(round(calibrate_value_before_weight, 3)) + ' ' + time.strftime('%H:%M:%S', time.localtime()))
        return calibrate_value_before_weight
    
    def calculate_reference_unit(self, scale, calibrate_scale_key, scale_settings_table, calibrate_value_first_measure):
        cl_fact_logger.get_instance().debug('calculate_reference_unit() ' + time.strftime('%H:%M:%S', time.localtime()))
        old_ref_unit = pi_ager_database.get_table_value(scale_settings_table, pi_ager_names.referenceunit_key)
        # get weight value set by the user
        calibrate_weight = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.calibrate_weight_key)
        calibrate_value_after_weight = scale.getWeightFilteredFlushingHistory()
        new_reference_unit = (calibrate_value_after_weight - calibrate_value_first_measure)/calibrate_weight * old_ref_unit
        
        if new_reference_unit == 0: # possible handling error, when weight was not attached in the second phase to the the loadcell
            pi_ager_database.write_current_value(calibrate_scale_key, 5)
        else:
            pi_ager_database.update_value_in_table(scale_settings_table, pi_ager_names.referenceunit_key, new_reference_unit)
            scale.setReferenceUnit(new_reference_unit)
            pi_ager_database.write_current_value(calibrate_scale_key, 4)
            
        cl_fact_logger.get_instance().debug('calculate_reference_unit() done. Ref.-Unit = ' + str(round(new_reference_unit, 3)) + ' ' + time.strftime('%H:%M:%S', time.localtime()))
        
    def doScaleLoop(self):
        cl_fact_logger.get_instance().debug('doScaleLoop() ' + time.strftime('%H:%M:%S', time.localtime()))
        
        saving_period_start = pi_ager_database.get_current_time()
        measuring_interval_start = saving_period_start
        first_calibrate_value = 0
        scale_measuring_interval = 3    # same as in ajax.js, measured data at index.php is refreshed there every 3 seconds, less makes no sense
        
        if self.scale_id == 0:
            pi_ager_database.write_current_value(pi_ager_names.calibrate_scale1_key, 0)
            pi_ager_database.write_current_value(pi_ager_names.status_tara_scale1_key, 0)
            settings_scale_table = pi_ager_names.settings_scale1_table
            # scale_setting_rows = pi_ager_database.get_scale_settings_from_table(settings_scale_table)
            # scale_settings = self.get_scale_settings(scale_setting_rows)
            scale_key = pi_ager_names.scale1_key
            status_tara_scale_key = pi_ager_names.status_tara_scale1_key
            calibrate_scale_key = pi_ager_names.calibrate_scale1_key
            status_scale_key = pi_ager_names.status_scale1_key
            # generate scale instance
            scale = cl_scale(dout=pi_ager_gpio_config.gpio_scale1_data, pd_sck=pi_ager_gpio_config.gpio_scale1_sync)
        else:
            pi_ager_database.write_current_value(pi_ager_names.calibrate_scale2_key, 0)   
            pi_ager_database.write_current_value(pi_ager_names.status_tara_scale2_key, 0)
            settings_scale_table = pi_ager_names.settings_scale2_table
            # scale_setting_rows = pi_ager_database.get_scale_settings_from_table(settings_scale_table)
            # scale_settings = self.get_scale_settings(scale_setting_rows)
            scale_key = pi_ager_names.scale2_key
            status_tara_scale_key = pi_ager_names.status_tara_scale2_key
            calibrate_scale_key = pi_ager_names.calibrate_scale2_key
            status_scale_key = pi_ager_names.status_scale2_key 
            # generate scale instance
            scale = cl_scale(dout=pi_ager_gpio_config.gpio_scale2_data, pd_sck=pi_ager_gpio_config.gpio_scale2_sync)     
            
        while not self.stop_received and threading.main_thread().is_alive():    # loop for ever untill stop thread received and main.py not alive

            # cl_fact_logger.get_instance().debug('doScaleLoop() ' + time.strftime('%H:%M:%S', time.localtime()))
            
            status_scale = int(pi_ager_database.get_table_value(pi_ager_names.current_values_table, status_scale_key))
            status_tara_scale = int(pi_ager_database.get_table_value(pi_ager_names.current_values_table, status_tara_scale_key))  
            # scale.setSamples(int(pi_ager_database.get_table_value(settings_scale_table, pi_ager_names.samples_key)))
            scale.setOffset(pi_ager_database.get_table_value(settings_scale_table, pi_ager_names.offset_scale_key))
            scale.setReferenceUnit(pi_ager_database.get_table_value(settings_scale_table, pi_ager_names.referenceunit_key))            
            calibrate_scale = int(pi_ager_database.get_table_value(pi_ager_names.current_values_table, calibrate_scale_key))
            # scale_measuring_interval = pi_ager_database.get_table_value(settings_scale_table, pi_ager_names.scale_measuring_interval_key)
            saving_period_scale = pi_ager_database.get_table_value(settings_scale_table, pi_ager_names.saving_period_key)
            
            if status_scale == 1 or calibrate_scale in [1, 2, 3, 4, 5] or status_tara_scale in [1, 2]:

                if calibrate_scale == 1:
                    first_calibrate_value = self.get_first_calibrate_measure(scale, settings_scale_table, calibrate_scale_key)
                elif calibrate_scale == 2:
                    pass
                elif calibrate_scale == 3:
                    self.calculate_reference_unit(scale, calibrate_scale_key, settings_scale_table, first_calibrate_value)
                elif calibrate_scale == 4 or calibrate_scale == 5:
                    pass
                elif status_tara_scale == 1:
                    self.tara_scale(scale, status_tara_scale_key, settings_scale_table)
                elif status_tara_scale == 2:
                    pass
                else:
                    value = round(scale.getWeightFiltered(), 3) # get weight, put it into history and apply filter, do it every 2 seconds ( see sleep(2) below )
                    time_difference_measuring_interval = pi_ager_database.get_current_time() - measuring_interval_start
                    if time_difference_measuring_interval > scale_measuring_interval:   # save weight in current_values_table, when measuring interval expired
                        measuring_interval_start = pi_ager_database.get_current_time()
                        pi_ager_database.write_current_value(scale_key, value)
                        cl_fact_logger.get_instance().debug(pi_ager_names.current_values_table + ': ' + 'scale-value' + str(self.scale_id + 1) + ' = ' + str(value) + '  ' + time.strftime('%H:%M:%S', time.localtime()))
                        
                    time_difference_saving_period_interval = pi_ager_database.get_current_time() - saving_period_start
                    if time_difference_saving_period_interval > saving_period_scale:    # save value in all_scales_table, when saving_period expired
                        saving_period_start =  pi_ager_database.get_current_time()
                        if self.scale_id == 0:
                            pi_ager_database.write_all_scales(value, None)
                        else:
                            pi_ager_database.write_all_scales(None, value)
                        cl_fact_logger.get_instance().debug(pi_ager_names.all_scales_table + ': ' + 'scale-value' + str(self.scale_id + 1) + ' = ' + str(value) + '  ' + time.strftime('%H:%M:%S', time.localtime()))
                        
            time.sleep(2)

def main():
    scale1_thread = cl_scale_thread(0)
    scale1_thread.start()
    scale2_thread = cl_scale_thread(1)
    scale2_thread.start()  
    
    try:
        while True:
            time.sleep(1)
            pi_ager_database.write_current_value(pi_ager_names.calibrate_weight_key, 0)
            
    except KeyboardInterrupt:
        print("Ctrl-c received! Sending Stop to thread...")
        scale1_thread.stop_received = True
        scale2_thread.stop_received = True
        
    scale1_thread.join()
    scale2_thread.join()
    print('finis.')
    
if __name__ == '__main__':
    main()
    