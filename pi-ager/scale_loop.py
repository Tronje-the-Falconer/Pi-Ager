#!/usr/bin/python3
"""
    loop scale
    
    loop for measuring on scales
    2018-10-20 fixed scale problems without scale sensors
    2018-11-02 Fixed wizard error
    2018-11-08 New median filter
"""
import time
import hx711
import pi_ager_database
import pi_ager_names
import pi_ager_logging
from pi_ager_cl_alarm import cl_fact_alarm
from pi_ager_cl_messenger import cl_fact_messenger
from statistics import mean as pi_mean, stdev as pi_stdev, pstdev as pi_pstdev, median_low as pi_median_low


global logger

logger = pi_ager_logging.create_logger(__name__)
logger.debug('scale logging initialised')

     

 
scale_list = []
scale_list1 = []
scale_list2 = []

scale_list_rounded = []
scale_list_rounded1 = []
scale_list_rounded2 = []

old_median_value = 0    
old_median_value1 = 0 
old_median_value2 = 0


error_count = 0
error_count1 = 0
error_count2 = 0

LIST_LENGHT = 3
MAX_DEVIATION = 3
MAX_ERROR_COUNT = 6
ROUND_DECIMAL_VALUE = 2



def tara_scale(scale, tara_key, data_table, calibrate_key, offset, settings_table):
    """
    tare scale
    """
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
    """
    measure on scale
    """
    global logger
    logger.debug('scale_measures()')
    
    global old_median_value1, old_median_value2
    global error_count1, error_count2
    global scale_list1, scale_list2
    

        
    measure_start_time = pi_ager_database.get_current_time()
    logger.debug('Scale start')
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
        formated_value = round(value, ROUND_DECIMAL_VALUE)
        if (current_time - measure_start_time) % saving_period == 0 and current_time != save_time:      # speichern je nach datenbankeintrag fuer saving_period
            save_time = current_time
            db_write1 = True
            db_write2 = True
            
            
       
            
            if data_table == pi_ager_names.data_scale1_table:
                cell_name = 'Cell 1: '
                #logger.info(cell_name +'Error counter before      = ' + str(error_count))
                old_median_value, error_count, scale_list, db_write = execute_filter(value, old_median_value1, error_count1, scale_list1, db_write1, cell_name)
                error_count1        = error_count
                old_median_value1   = old_median_value
                scale_list1         = scale_list
            elif data_table == pi_ager_names.data_scale2_table:
                cell_name = 'Cell 2: '
                #logger.info(cell_name +'Error counter before      = ' + str(error_count))
                old_median_value, error_count, scale_list, db_write = execute_filter(value, old_median_value2, error_count2, scale_list2, db_write2, cell_name)
                error_count2        = error_count
                old_median_value2   = old_median_value
                scale_list2         = scale_list
            logger.debug(cell_name +'Error counter after       = ' + str(error_count))
            median_value = pi_median_low(scale_list)
            scale_list_rounded = [ round(elem, ROUND_DECIMAL_VALUE) for elem in scale_list ]            
       
            logger.debug(cell_name +'(list) = ' + '( ' + str(len(scale_list)) + ' )' )
            logger.debug(cell_name +'       = ' + str(scale_list_rounded))
            logger.debug(cell_name +'Value                     = ' + str(round(value,ROUND_DECIMAL_VALUE)))
            logger.debug(cell_name +'Median low                = ' + str(round(median_value,ROUND_DECIMAL_VALUE)))
            logger.debug(cell_name +'Error counter             = ' + str(error_count))
                                
            if ( db_write == True or calibrate_scale != 0 or status_tara_scale != 0 ):
                pi_ager_database.write_scale(data_table,value)
                #logger.debug(cell_name +'scale-value saved in database ' + time.strftime('%H:%M:%S', time.localtime()))
                logger.debug(cell_name +'scale-value ' + str(round(value,ROUND_DECIMAL_VALUE)) + ' saved in database ' + time.strftime('%H:%M:%S', time.localtime()))
            
            

            scale_list_rounded_string = [ round(elem, ROUND_DECIMAL_VALUE) for elem in scale_list ]
            logger.debug(cell_name +'       = ' + str(scale_list_rounded_string))
            logger.debug(cell_name +'-------------------------------------------------')
            if data_table == pi_ager_names.data_scale1_table:
                old_median_value1 = median_value
            elif data_table == pi_ager_names.data_scale2_table:
                old_median_value2 = median_value
            
            
        current_time = pi_ager_database.get_current_time()
    logger.debug('measurement performed')                

def  execute_filter(value, old_median_value, error_count, scale_list, db_write, cell_name):
    global logger          
    logger.debug('scale_filter()')  
    #global error_count         
    #if len(scale_list) >= LIST_LENGHT:
    #    db_write = True
    #else:
    #    db_write = False           
    logger.debug(cell_name +'Median old                = ' + str(round(old_median_value,ROUND_DECIMAL_VALUE)))
    if old_median_value == 0 or error_count >= MAX_ERROR_COUNT: 
        logger.debug(cell_name +'old_median_value = ' + str(old_median_value) + ' error_count = ' + str(error_count) + ' MAX_ERROR_COUNT =' + str(MAX_ERROR_COUNT)) 
        scale_list.append(value)
        error_count = 0
        db_write = True
    elif abs(old_median_value-value) < MAX_DEVIATION: 
        logger.debug(cell_name +'old_median_value-value < MAX_DEVIATION | ' + str(round(abs(old_median_value-value), ROUND_DECIMAL_VALUE)) + ' < ' + str(MAX_DEVIATION))
        scale_list.append(value)
        error_count = 0
        db_write = True
    elif old_median_value != value:
        logger.debug(cell_name +'old_median_value != value | ' + str(round(old_median_value-value,ROUND_DECIMAL_VALUE)) + ' != ' + str(round(value,ROUND_DECIMAL_VALUE)))
        error_count = error_count + 1
        db_write = False
    scale_list1.sort()
    median_value = pi_median_low(scale_list)
    
    
    if len(scale_list) >= LIST_LENGHT:
        middle_of_scale_list = round(len(scale_list)/2)-1
        scale_list.pop(middle_of_scale_list)
        logger.debug(cell_name +'Deleting index            = ' + str(middle_of_scale_list))
    return old_median_value, error_count, scale_list, db_write
                

def get_scale_settings(scale_setting_rows):
    """
    reading scale settings
    """
    global logger
    logger.debug('get_scale_settings()')   
    scale_settings = {}
    for scale_setting_row in scale_setting_rows:
        scale_settings[scale_setting_row[pi_ager_names.key_field]] = scale_setting_row[pi_ager_names.value_field]
    return scale_settings
    
def get_first_calibrate_measure(scale, scale_settings_table, calibrate_scale_key):
    """
    getting first values on calibrating scale
    """
    global logger          
    logger.debug('get_first_calibrate_measure()')   
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
    logger.info('calibrate_value_before_weight = ' + str(calibrate_value_before_weight))
    return calibrate_value_before_weight
    
def calculate_reference_unit(scale, calibrate_scale_key, scale_settings_table, calibrate_value_first_measure):
    """
    calculate reference unit for scale
    """
    global logger          
    logger.debug('calculate_reference_unit()')   
    # scale.setReferenceUnit(1)
    old_ref_unit = pi_ager_database.get_table_value(scale_settings_table, pi_ager_names.referenceunit_key)
    scale.setReferenceUnit(old_ref_unit)
    scale.setSamples(int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.samples_refunit_tara_key)))
    scale.setSpikes(int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.spikes_refunit_tara_key)))
    
    calibrate_weight = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.calibrate_weight_key)
    clear_history = scale.getWeight()
    calibrate_value_after_weight = scale.getMeasure()
    logger.info('calibrate_value_after_weight = ' + str(calibrate_value_after_weight))
    reference_unit = (calibrate_value_after_weight - calibrate_value_first_measure)/calibrate_weight * old_ref_unit
    logger.info('reference_unit = ' + str(reference_unit))
    if reference_unit == 0:
        pi_ager_database.write_current_value(calibrate_scale_key,5)
    else:
        pi_ager_database.update_value_in_table(scale_settings_table, pi_ager_names.referenceunit_key, reference_unit)
        scale.setReferenceUnit(reference_unit)
        pi_ager_database.write_current_value(calibrate_scale_key,4)
    scale.setSamples(int(pi_ager_database.get_table_value(scale_settings_table, pi_ager_names.samples_key)))
    scale.setSpikes(int(pi_ager_database.get_table_value(scale_settings_table, pi_ager_names.spikes_key)))

def doScaleLoop():
    """
    scale mainloop
    """
    global logger
    logger.debug('doScaleLoop()')   
    logger.debug(__name__)
    
    scale1_setting_rows = pi_ager_database.get_scale_settings_from_table(pi_ager_names.settings_scale1_table)
    scale2_setting_rows = pi_ager_database.get_scale_settings_from_table(pi_ager_names.settings_scale2_table)

    scale1_settings = get_scale_settings(scale1_setting_rows)
    scale2_settings = get_scale_settings(scale2_setting_rows)


    


    while True:
        try:
            status_scale1 = int(pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_scale1_key))
            status_scale2 = int(pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_scale2_key))
            
            calibrate_scale1 = int(pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.calibrate_scale1_key))
            calibrate_scale2 = int(pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.calibrate_scale2_key))
            
            status_tara_scale1 = int(pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_tara_scale1_key))
            status_tara_scale2 = int(pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_tara_scale2_key))
    
            if status_scale1 == 1 or calibrate_scale1 in [1, 2, 3, 4, 5] or status_tara_scale1 in [1, 2]:
            
               
                scale1 = hx711.Scale(source=None, samples=int(scale1_settings[pi_ager_names.samples_key]), spikes=int(scale1_settings[pi_ager_names.spikes_key]), sleep=scale1_settings[pi_ager_names.sleep_key], dout=pi_ager_names.gpio_scale1_data, pd_sck=pi_ager_names.gpio_scale1_sync, gain=int(scale1_settings[pi_ager_names.gain_key]), bitsToRead=int(scale1_settings[pi_ager_names.bits_to_read_key]))
                scale1.setReferenceUnit(pi_ager_database.get_table_value(pi_ager_names.settings_scale1_table, pi_ager_names.referenceunit_key))
                
                if pi_ager_database.get_table_value(pi_ager_names.debug_table, pi_ager_names.loglevel_console_key) == 10:
                    measuring_interval_scale1 = pi_ager_database.get_table_value(pi_ager_names.debug_table, pi_ager_names.measuring_interval_debug_key)
                else:
                    measuring_interval_scale1 = pi_ager_database.get_table_value(pi_ager_names.settings_scale1_table, pi_ager_names.scale_measuring_interval_key)
    
                if calibrate_scale1 == 1:
                    first_calibrate_value = get_first_calibrate_measure(scale1, pi_ager_names.settings_scale1_table, pi_ager_names.calibrate_scale1_key)
                
                if calibrate_scale1 == 3:
                    calculate_reference_unit(scale1, pi_ager_names.calibrate_scale1_key, pi_ager_names.settings_scale1_table, first_calibrate_value)
                
                offset_scale1 = pi_ager_database.get_table_value(pi_ager_names.settings_scale1_table, pi_ager_names.offset_scale_key)
                
                scale1_measuring_duration = pi_ager_database.get_table_value(pi_ager_names.settings_scale1_table, pi_ager_names.measuring_duration_key)
                saving_period_scale1 = pi_ager_database.get_table_value(pi_ager_names.settings_scale1_table, pi_ager_names.saving_period_key)
                
                if status_tara_scale1 == 1:
                    tara_scale(scale1, pi_ager_names.status_tara_scale1_key, pi_ager_names.data_scale1_table, pi_ager_names.calibrate_scale1_key, offset_scale1, pi_ager_names.settings_scale1_table)
            
                if pi_ager_database.get_scale_table_row(pi_ager_names.data_scale1_table) != None:
                    last_measure_scale1 = pi_ager_database.get_scale_table_row(pi_ager_names.data_scale1_table)[pi_ager_names.last_change_field]
                    time_difference_scale1 = pi_ager_database.get_current_time() - last_measure_scale1
                else:
                    time_difference_scale1 = measuring_interval_scale1 + 1
                if time_difference_scale1 >= measuring_interval_scale1:
                    scale1_measuring_endtime =  pi_ager_database.get_current_time() + scale1_measuring_duration
                    scale_measures(scale1, scale1_measuring_endtime, pi_ager_names.data_scale1_table, saving_period_scale1, pi_ager_names.status_tara_scale1_key,pi_ager_names.calibrate_scale1_key, offset_scale1, pi_ager_names.settings_scale1_table)
    
     
            if status_scale2 == 1 or calibrate_scale2 in [1, 2, 3, 4, 5] or status_tara_scale2 in [1, 2]:
                scale2 = hx711.Scale(source=None, samples=int(scale2_settings[pi_ager_names.samples_key]), spikes=int(scale2_settings[pi_ager_names.spikes_key]), sleep=scale2_settings[pi_ager_names.sleep_key], dout=pi_ager_names.gpio_scale2_data, pd_sck=pi_ager_names.gpio_scale2_sync, gain=int(scale2_settings[pi_ager_names.gain_key]), bitsToRead=int(scale2_settings[pi_ager_names.bits_to_read_key]))
                scale2.setReferenceUnit(pi_ager_database.get_table_value(pi_ager_names.settings_scale2_table, pi_ager_names.referenceunit_key))
        
                
                if pi_ager_database.get_table_value(pi_ager_names.debug_table, pi_ager_names.loglevel_console_key) == 10:
                    measuring_interval_scale2 = pi_ager_database.get_table_value(pi_ager_names.debug_table, pi_ager_names.measuring_interval_debug_key)
                else:
                    measuring_interval_scale2 = pi_ager_database.get_table_value(pi_ager_names.settings_scale2_table, pi_ager_names.scale_measuring_interval_key)
    
                if calibrate_scale2 == 1:
                    first_calibrate_value = get_first_calibrate_measure(scale2, pi_ager_names.settings_scale2_table, pi_ager_names.calibrate_scale2_key)
                
                if calibrate_scale2 == 3:
                    calculate_reference_unit(scale2, pi_ager_names.calibrate_scale2_key, pi_ager_names.settings_scale2_table, first_calibrate_value)
         
                offset_scale2 = pi_ager_database.get_table_value(pi_ager_names.settings_scale2_table, pi_ager_names.offset_scale_key)
                
                scale2_measuring_duration = pi_ager_database.get_table_value(pi_ager_names.settings_scale2_table, pi_ager_names.measuring_duration_key)
                saving_period_scale2 = pi_ager_database.get_table_value(pi_ager_names.settings_scale2_table, pi_ager_names.saving_period_key)
                
                if status_tara_scale2 == 1:
                    tara_scale(scale2, pi_ager_names.status_tara_scale2_key, pi_ager_names.data_scale2_table, pi_ager_names.calibrate_scale2_key, offset_scale2, pi_ager_names.settings_scale2_table)
                
                if pi_ager_database.get_scale_table_row(pi_ager_names.data_scale2_table) != None:
                    last_measure_scale2 = pi_ager_database.get_scale_table_row(pi_ager_names.data_scale2_table)[pi_ager_names.last_change_field]
                    time_difference_scale2 = pi_ager_database.get_current_time() - last_measure_scale2
                else:
                    time_difference_scale2 = measuring_interval_scale2 + 1
                if time_difference_scale2 >= measuring_interval_scale2:
                    scale2_measuring_endtime = pi_ager_database.get_current_time() + scale2_measuring_duration
                    scale_measures(scale2, scale2_measuring_endtime, pi_ager_names.data_scale2_table, saving_period_scale2, pi_ager_names.status_tara_scale2_key, pi_ager_names.calibrate_scale2_key, offset_scale2, pi_ager_names.settings_scale2_table)
                
        except Exception as cx_error:
            cl_fact_messenger().get_instance(cx_error).send()
            pass

        time.sleep(2)