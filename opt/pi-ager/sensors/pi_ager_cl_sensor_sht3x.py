# -*- coding: utf-8 -*-
 
"""This class is for handling the SHT sensors SHT3x, SHT21, SHT85 with i2c bus interface from sensirion."""

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

from sensors.pi_ager_cl_sensor_type import cl_fact_main_sensor_type
from sensors.pi_ager_cl_i2c_bus import cl_fact_i2c_bus_logic
from sensors.pi_ager_cl_i2c_sensor_sht import cl_fact_i2c_sensor_sht
from main.pi_ager_cx_exception import *
from messenger.pi_ager_cl_messenger import cl_fact_logic_messenger
from sensors.pi_ager_cl_sensor import cl_main_sensor#
from sensors.pi_ager_cl_ab_sensor import cl_ab_sensor
from sensors.pi_ager_cl_sensor_sht import cl_main_sensor_sht

global logger
logger = pi_ager_logging.create_logger(__name__) 

class cl_main_sensor_sht3x(cl_main_sensor_sht):
    
    
    def __init__(self):
        logger.debug(pi_ager_logging.me())
        if "get_instance" not in inspect.stack()[1][3]:
            raise cx_direct_call(self,"Please use factory class" )
        #self.o_sensor_type = o_sensor_type
#        super().__init__(cl_fact_main_sensor_type.get_instance())
        super().__init__()
 
    def _send_i2c_start_command(self):
        logger.debug(pi_ager_logging.me())
        super()._send_i2c_start_command()
    
    def _read_data(self):
        logger.debug(pi_ager_logging.me())
        super()._read_data()
           
    def get_current_data(self):
        logger.debug(pi_ager_logging.me())
        self.measured_data = super().get_current_data()
        return(self.measured_data)
    """    
    def _get_current_temperature(self):
        logger.debug(pi_ager_logging.me())
        super()._get_current_temperature()
  
    def _get_current_humidity(self):
        logger.debug(pi_ager_logging.me())
        super()._get_current_humidity()
    """   
    
    def _write_to_db(self):
        logger.debug(pi_ager_logging.me())
        super()._write_to_db()

    def soft_reset(self):
        """Performs Soft Reset on SHT chip"""
        logger.debug(pi_ager_logging.me())
        super().soft_reset()
        
    def set_heading_on(self):
        """Switch the heading on the sensor on"""
        logger.debug(pi_ager_logging.me())
        super().set_heading_on()
        
    
    def set_heading_off(self):
        """Switch the heading on the sensor off"""
        logger.debug(pi_ager_logging.me())
        super().set_heading_off()

    def execute(self):
        logger.debug(pi_ager_logging.me())
        #self.get_current_data()
        self._write_to_db()
        
class th_main_sensor_sht3x(cl_main_sensor_sht3x):
#    SUPPORTED_MAIN_SENSOR_TYPES = ["SHT75", "DHT11", "DHT22"]
    NAME = 'Main_sensor'
    
    
    def __init__(self):
    
        self.get_type_raise = False
        self._type = "SHT3x"
        
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
        return(cl_fact_sensor_sht3x.__o_instance)

    @classmethod
    def set_instance(self, i_instance):
        cl_fact_sensor_sht3x.__o_instance = i_instance
    
    
    def __init__(self):
        pass    

