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
from pi_ager_cl_sensor_sht3x import cl_fact_sensor_sht3x
#from pi_ager_sensor_sht75 import *
    
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
#                cl_fact_main_sensor.__o_instance = self.get_instance_sensor_sht75()
                pass
            elif cl_fact_main_sensor.__o_sensor_type._get_type_ui( ) == 'SHT3x':
                cl_fact_main_sensor.__o_instance = cl_fact_sensor_sht3x.get_instance()
            elif cl_fact_main_sensor.__o_sensor_type._get_type_ui( ) == 'SHT85':
#                cl_fact_main_sensor.__o_instance = self.get_instance_sensor_sht85()
                pass   
            elif cl_fact_main_sensor.__o_sensor_type._get_type_ui( ) == 'DHT22':
#                cl_fact_main_sensor.__o_instance = self.get_instance_sensor_dht22()
                pass
            elif cl_fact_main_sensor.__o_sensor_type._get_type_ui( ) == 'DHT11':
#                cl_fact_main_sensor.__o_instance = self.get_instance_sensor_dht11()
                pass
        except Exception as original_error:
            raise original_error        
        return(cl_fact_main_sensor.__o_instance)

    @classmethod
    def set_instance(self, i_instance):
        cl_fact_main_sensor.__o_instance = i_instance
              
    
    def __init__(self):
        pass    

