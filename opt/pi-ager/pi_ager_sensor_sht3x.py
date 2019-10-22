# -*- coding: utf-8 -*-
 
"""This class is for handling the SHT3x sensor from sensirion."""

__author__ = "Claus Fischer"
__copyright__ = "Copyright 2019, The Pi-Ager Project"
__credits__ = ["Claus Fischer"]
__license__ = "GPL"
__version__ = "1.0.0"
__maintainer__ = "Claus Fischer"
__email__ = "DerBurgermeister@pi-ager.org"
__status__ = "Production"

#from abc import ABC, abstractmethod
import inspect

from pi_ager_cl_sensor_type import cl_fact_main_sensor_type
from pi_ager_cl_i2c_bus import cl_fact_i2c_bus_logic
from pi_ager_cl_i2c_sensor_sht import cl_fact_i2c_sensor_sht
from pi_ager_cx_exception import *
from pi_ager_cl_ab_sensor import cl_ab_temp_sensor, cl_ab_humitity_sensor

class cl_main_sensor_sht3x(cl_ab_temp_sensor, cl_ab_humitity_sensor):
    
    def __init__(self):
        logger.debug(pi_ager_logging.me())
        if "get_instance" not in inspect.stack()[1][3]:
            raise cx_direct_call(self,"Please use factory class" )
        #self.o_sensor_type = o_sensor_type
        pass
        _old_temperature = 0
        _current_temperature = 0
        self.i2c_bus = cl_fact_i2c_bus_logic.get_instance()
        self.sht3x = cl_fact_i2c_sensor_sht.get_instance(i2c_bus)
        self.sht3x.i2c_start_command()
    
    def _read_data(self):
        logger.debug(pi_ager_logging.me())
        self.sht3x.read_data()
    
    def get_temperature(self):
        logger.debug(pi_ager_logging.me())
        self._read_data()
        self._current_temperature = self.sht3x_get_temperature(self)
        
        if self._old_temperature is None:
            self._old_temperature = 0
        else:
      
            self._old_temperature = self._current_temperature
            
        #self.m_current_temperature = 100.0
     
        self._check_temperature()

  
class th_main_sensor_sht3x(cl_main_sensor_sht3x):
#    SUPPORTED_MAIN_SENSOR_TYPES = ["SHT75", "DHT11", "DHT22"]
    NAME = 'Main_sensor'
    
    
    def __init__(self):
    
        self.get_type_raise = False
        self._type = "SHT"
        
    def get_type(self):
        if self.get_type_raise == True:
            raise cx_Sensor_not_defined(self._type_ui)        
        return(self._type)

    
class cl_fact_sensor_sht3x: 
    fact_main_sensor_type = cl_fact_main_sensor_type()
#    Only a singleton instance for main_sensor
    __o_sensor_type = fact_main_sensor_type.get_instance()
    __o_instance = None

    @classmethod        
    def get_instance(self):
        if cl_fact_sensor_sht3x.__o_instance is not None:
            return(cl_fact_sensor_sht3x.__o_instance)
        cl_fact_sensor_sht3x.__o_instance = cl_main_sensor_sht3x()
        return(cl_fact_main_sensor_type.__o_instance)

    @classmethod
    def set_instance(self, i_instance):
        cl_fact_sensor_sht3x.__o_instance = i_instance
    
    
    def __init__(self):
        pass    

