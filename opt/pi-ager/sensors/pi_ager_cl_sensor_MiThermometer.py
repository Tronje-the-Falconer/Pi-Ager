# -*- coding: utf-8 -*-
 
"""This class is for handling the Xiaomi Mi Temperature and Humidity Monitor 2 sensor."""

from main.pi_ager_cl_logger import cl_fact_logger
import time
from sensors.pi_ager_cl_sensor import cl_sensor
import pi_ager_names
import pi_ager_database
from messenger.pi_ager_cl_messenger import cl_fact_logic_messenger

class cl_sensor_MiThermometer(cl_sensor):
    
    def __init__(self, i_sensor_type, i_active_sensor):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        self.o_active_sensor = i_active_sensor  # MAIN or SECOND
        self.o_sensor_type = i_sensor_type      # class to get second sensor name e.g.
        self.temperature = None
        self.humidity = None
        self.address = None
        self.battery = None
        self.data_timestamp = None
        self.dewpoint = None
        self.absolute_humidity = None
        self.measured_data = None
        self.event_out_of_range = False
        
    def get_current_data(self):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        
        mi_data = pi_ager_database.get_table_value_from_field(pi_ager_names.atc_mi_thermometer_data_table, pi_ager_names.mi_data_key)
        if (mi_data != None):
            splited_data = mi_data.split(' ')
            try:
                self.address = splited_data[1]
                self.temperature = float(splited_data[2])
                self.humidity = float(splited_data[3])
                self.battery = float(splited_data[4])
                self.data_timestamp = int(splited_data[5])  # time in seconds when data was generated
                current_time = int(time.time()) # current time in seconds 
                if (current_time - self.data_timestamp) > 20:   # when difference is greater than 20 s, then bluetooth connection may be broken
                    if self.event_out_of_range == False:
                        self.event_out_of_range = True
                        cl_fact_logger.get_instance().info('MiSensor possibly out of range!')
                        cl_fact_logic_messenger().get_instance().handle_event('Mi_Sensor_failed') #if the second parameter is empty, the value is taken from the field envent_text in table config_messenger_event 
                    pi_ager_database.update_table_val(pi_ager_names.current_values_table, pi_ager_names.MiSensor_battery_key, None)    
                    return(None,None,None,None)
                    
                (self.dewpoint, self.absolute_humidity) = super().get_dewpoint(self.temperature, self.humidity)
                self.measured_data = (self.temperature, self.humidity, self.dewpoint, self.absolute_humidity)
                cl_fact_logger.get_instance().debug('MiThermometerData : ' + str(self.measured_data))
                pi_ager_database.update_table_val(pi_ager_names.current_values_table, pi_ager_names.MiSensor_battery_key, self.battery)
                cl_fact_logger.get_instance().debug('MiThermometer battery (V) : ' + str(self.battery))
                if self.event_out_of_range == True:
                        self.event_out_of_range = False
                        cl_fact_logger.get_instance().info('MiSensor within range again!')
                        cl_fact_logic_messenger().get_instance().handle_event('Mi_Sensor_ok') #if the second parameter is empty, the value is taken from the field envent_text in table config_messenger_event 
                return(self.measured_data)
            except:
                cl_fact_logger.get_instance().debug('MiThermometerData : ' + 'Data error')
                pi_ager_database.update_table_val(pi_ager_names.current_values_table, pi_ager_names.MiSensor_battery_key, None) 
                return(None,None,None,None)
        else:
            cl_fact_logger.get_instance().debug('MiThermometerData : ' + 'Data error')
            pi_ager_database.update_table_val(pi_ager_names.current_values_table, pi_ager_names.MiSensor_battery_key, None) 
            return(None,None,None,None)
            
   
    def _write_to_db(self):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())

    def execute(self):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        
    def soft_reset(self):
        """Performs Soft Reset on SHT chip"""
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        
    def set_heading_on(self):
        """Switch the heading on the sensor on"""
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
    
    def set_heading_off(self):
        """Switch the heading on the sensor off"""
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())

    
class cl_fact_sensor_MiThermometer: 
#    Only a singleton instance for main_sensor
    __o_instance = None

    @classmethod        
    def get_instance(self, i_sensor_type, i_active_sensor):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        if cl_fact_sensor_MiThermometer.__o_instance is not None:
            return(cl_fact_sensor_MiThermometer.__o_instance)
        cl_fact_sensor_MiThermometer.__o_instance = cl_sensor_MiThermometer(i_sensor_type, i_active_sensor)
        return(cl_fact_sensor_MiThermometer.__o_instance)

    @classmethod
    def set_instance(self, i_instance):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        cl_fact_sensor_MiThermometer.__o_instance = i_instance
    
    def __init__(self):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        pass    

