# -*- coding: utf-8 -*-

"""This class is for handling the main sensor(s) for the Pi-Ager."""

__author__ = "Claus Fischer"
__copyright__ = "Copyright 2021, The Pi-Ager Project"
__credits__ = ["Claus Fischer"]
__license__ = "GPL"
__version__ = "1.0.0"
__maintainer__ = "Claus Fischer"
__email__ = "DerBurgermeister@pi-ager.org"
__status__ = "Production"

from abc import ABC, abstractmethod
import math
import inspect

from sensors.pi_ager_cl_sensor import cl_fact_sensor
from main.pi_ager_cl_logger import cl_fact_logger
from datetime import datetime


#from sensors.pi_ager_cl_sensor_type import cl_fact_main_sensor_type

from main.pi_ager_cx_exception import *

from sensors.pi_ager_cl_ab_sensor import cl_ab_sensor
from main.pi_ager_cl_database import cl_fact_db_influxdb
        
class cl_active_main_sensor():
    
    def __init__(self, o_sensor_type, i_active_sensor, i_address):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        self.o_sensor_type = o_sensor_type
        self.o_sensor = cl_fact_sensor().get_instance(i_active_sensor, i_address)
    def execute(self):
        self_o_sensor.execute()

class cl_active_second_sensor():
    
    def __init__(self, o_sensor_type, i_active_sensor, i_address):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        self.o_sensor_type = o_sensor_type
        self.o_sensor = cl_fact_sensor().get_instance(i_active_sensor, i_address)
    def execute(self):
        self_o_sensor.execute()

       
class th_active_sensor():
#    SUPPORTED_MAIN_SENSOR_TYPES = ["SHT75", "DHT11", "DHT22"]
    NAME = 'Main_sensor'
    
    
    def __init__(self):
    
        self.get_type_raise = False
        self._type = "SHT75"
        
    def get_type(self):
        if self.get_type_raise == True:
            raise cx_Sensor_not_defined(self._type_ui)        
        return(self._type)

    


class cl_fact_active_main_sensor:
    
#    Only a singleton instance for main_sensor
    __o_main_sensor_type = cl_fact_main_sensor_type().get_instance()
    __o_instance = None
    
    @classmethod        
    def get_instance(self, i_address=None):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        active_sensor = 'MAIN'
        if cl_fact_active_main_sensor.__o_instance is not None :
        
            return(cl_fact_main_sensor.__o_instance)
        try:
            cl_fact_active_main_sensor.__o_instance = cl_fact_sensor.get_instance(active_sensor, i_address)
 
        except Exception as original_error:
            raise original_error        
        return(cl_fact_main_sensor.__o_instance)

    @classmethod
    def set_instance(self, i_active_sensor, i_instance, i_address=None,):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        cl_fact_main_sensor.__o_instance = i_instance
              
    
    def __init__(self):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        
        pass    
class cl_fact_active_second_sensor:
#    Only a singleton instance for main_sensor
    __o_main_sensor_type = cl_fact_main_sensor_type().get_instance()
    __o_instance = None
    
    @classmethod        
    def get_instance(self, i_address=None):    
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        active_sensor = 'SECOND'
        if cl_fact_active_second_sensor.__o_instance is not None :
        
            return(cl_fact_second_sensor.__o_instance)
        try:
            cl_fact_active_second_sensor.__o_instance = cl_fact_active_second_sensor.get_instance(active_sensor, i_address)
        except Exception as original_error:
            raise original_error        
        return(cl_fact_main_sensor.__o_instance)

    @classmethod
    def set_instance(self, i_active_sensor, i_instance, i_address=None):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        cl_fact_second_sensor.__o_instance = i_instance
              
    
    def __init__(self):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        
        pass    

    


