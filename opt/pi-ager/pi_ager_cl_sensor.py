# -*- coding: utf-8 -*-

"""This class is for handling the main sensor(s) for the Pi-Ager."""

__author__ = "Claus Fischer"
__copyright__ = "Copyright 2019, The Pi-Ager Project"
__credits__ = ["Claus Fischer"]
__license__ = "GPL"
__version__ = "1.0.0"
__maintainer__ = "Claus Fischer"
__email__ = "DerBurgermeister@pi-ager.org"
__status__ = "Production"

from abc import ABC, abstractmethod
import inspect

from pi_ager_cl_sensor_type import cl_fact_main_sensor_type
#from pi_ager_cl_i2c_bus import cl_fact_i2c_bus_logic
from pi_ager_cx_exception import *
from pi_ager_sensor_sht3x import *

class cl_ab_humitity_sensor(ABC):
    __max_humitity_errors = 10
    def __init__(self):
        self.m_current_humitity = 0
        self.m_old_humitity = 0
        self.m_state = 0

class cl_ab_temp_sensor(ABC):
    __max_temp_errors = 10
    __TEMP_SENSOR_STATES = ["Valid", "Invalid"]
    def __init__(self):
        self._current_temperature = 0
        self._old_temperature = 0
        self._state = 1
        self._current_humitity = 0
        self._old_humitity = 0
    def get_current_temperature(self):
        return(self._current_temperature)
    
    def get_old_temperature(self):
        return(self._old_temperature)
    def get_current_humitity(self):
        return(self._current_humitity)
    def get_old_humitity(self):
        return(self._old_humitity)
    @abstractmethod
    def read_temperature(self):
        pass
    
    def get_temperature_state(self):
        return(cl_ab_temp_sensor.__TEMP_SENSOR_STATES[self.m_state])
    
    def _check_temperature(self):
        if abs(self.m_current_temperature - self.m_old_temperature) > 10:
            self.m_state = 1
        else:
            self.m_state = 0
        
    
class cl_main_sensor(cl_ab_temp_sensor, cl_ab_humitity_sensor):
    
    __error_counter = 0
    __measuring_intervall = 300
    

    def __init__(self, o_sensor_type):
        logger.debug(pi_ager_logging.me())
        self.o_sensor_type = o_sensor_type
        
    def get_sensor_type_ui(self):
        logger.debug(pi_ager_logging.me())
        return( self.o_sensor_type.get_sensor_type_ui() )
    
    def get_sensor_type(self):
        logger.debug(pi_ager_logging.me())
        return( self.o_sensor_type._get_type() )
    
    def get_dewpoint(self):
        logger.debug(pi_ager_logging.me())
        pass
        
    def execute_soft_reset(self):
        logger.debug(pi_ager_logging.me())
        pass
    

    
class th_main_sensor(cl_main_sensor_sht75):
#    SUPPORTED_MAIN_SENSOR_TYPES = ["SHT75", "DHT11", "DHT22"]
    NAME = 'Main_sensor'
    
    
    def __init__(self):
    
        self.get_type_raise = False
        self._type = "SHT"
        
    def get_type(self):
        if self.get_type_raise == True:
            raise cx_Sensor_not_defined(self._type_ui)        
        return(self._type)

    
class cl_fact_main_sensor:
    fact_main_sensor_type = cl_fact_main_sensor_type()
#    Only a singleton instance for main_sensor
    __o_sensor_type = fact_main_sensor_type.get_instance()
    __o_instance = None
    @classmethod        
    def get_instance(self):
        if cl_fact_main_sensor.__o_instance is not None :
            return(cl_fact_main_sensor.__o_instance)
        try:
            if   cl_fact_main_sensor.__o_sensor_type._get_type_ui( ) == 'SHT75':
                cl_fact_main_sensor.__o_instance = self.get_instance_sensor_sht75()
            elif cl_fact_main_sensor.__o_sensor_type._get_type_ui( ) == 'SHT3x':
                cl_fact_main_sensor.__o_instance = self.get_instance_sensor_sht3x()
            elif cl_fact_main_sensor.__o_sensor_type._get_type_ui( ) == 'SHT85':
                cl_fact_main_sensor.__o_instance = self.get_instance_sensor_sht85()   
            elif cl_fact_main_sensor.__o_sensor_type._get_type_ui( ) == 'DHT22':
                cl_fact_main_sensor.__o_instance = self.get_instance_sensor_dht22()
            elif cl_fact_main_sensor.__o_sensor_type._get_type_ui( ) == 'DHT11':
                cl_fact_main_sensor.__o_instance = self.get_instance_sensor_dht11()

        except Exception as original_error:
            raise original_error        
        return(cl_fact_main_sensor.__o_instance)

    @classmethod
    def set_instance(self, i_instance):
        cl_fact_main_sensor.__o_instance = i_instance
            
    @classmethod        
    def get_instance_sensor_sht75(self):
        return(cl_main_sensor_sht75(self.__o_sensor_type))
    @classmethod        
    def get_instance_sensor_sht3x(self):
        return(cl_main_sensor_sht3x(self.__o_sensor_type))    
    
    
    def __init__(self):
        pass    

