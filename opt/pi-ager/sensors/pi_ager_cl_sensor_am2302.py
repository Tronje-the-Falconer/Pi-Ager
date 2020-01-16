# -*- coding: utf-8 -*-
 
"""This class is for handling the am2302 sensor from sensirion."""

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
import pi_ager_logging
import time

import pi_ager_gpio_config

from sensors.pi_ager_cl_sensor_type import cl_fact_main_sensor_type
from main.pi_ager_cx_exception import *
from messenger.pi_ager_cl_messenger import cl_fact_logic_messenger
from sensors.pi_ager_cl_sensor import cl_main_sensor#
from sensors.pi_ager_cl_ab_sensor import cl_ab_sensor
from sensors.pi_ager_cl_dht_adafruit import cl_main_sensor_dht_adafruit
import Adafruit_DHT

global logger
logger = pi_ager_logging.create_logger(__name__) 

class cl_main_sensor_am2302(cl_main_sensor_dht_adafruit):
    
    def __init__(self, i_sensor):
        logger.debug(pi_ager_logging.me())
        if "get_instance" not in inspect.stack()[1][3]:
            raise cx_direct_call(self,"Please use factory class" )
        #self.o_sensor_type = o_sensor_type
        self._sensor = Adafruit_DHT.AM2302
        super().__init__(sensor)
        
       
        self._max_errors = 1
        self._old_temperature = 0
        self._current_temperature = 0
        self._temperature_dewpoint = 0
        self._humidity_absolute = 0
        self._old_humidity = 0
        self._current_humidity = 0
        
         
                

   
    def get_current_data(self):
        logger.debug(pi_ager_logging.me())
        self.measured_data = super().get_current_data()
        return(self.measured_data)
        
    def _write_to_db(self):
        super()._write_to_db()
        pass

    def execute(self):
        logger.debug(pi_ager_logging.me())
        #self.get_current_data()
        self._write_to_db()
        
class th_main_sensor_am2302(cl_main_sensor_am2302):

    NAME = 'Main_sensor'
    
    
    def __init__(self):
    
        self.get_type_raise = False
        self._type = "am2302"
        
    def get_type(self):
        if self.get_type_raise == True:
            raise cx_Sensor_not_defined(self._type_ui)        
        return(self._type)

    
class cl_fact_sensor_am2302: 
    fact_main_sensor_type = cl_fact_main_sensor_type()
#    Only a singleton instance for main_sensor
    __o_sensor_type = fact_main_sensor_type.get_instance()
    __o_instance = None

    @classmethod        
    def get_instance(self):
        if cl_fact_sensor_am2302.__o_instance is not None:
            return(cl_fact_sensor_am2302.__o_instance)
        cl_fact_sensor_am2302.__o_instance = cl_main_sensor_am2302()
        return(cl_fact_sensor_am2302.__o_instance)

    @classmethod
    def set_instance(self, i_instance):
        cl_fact_sensor_am2302.__o_instance = i_instance
    
    
    def __init__(self):
        pass    

