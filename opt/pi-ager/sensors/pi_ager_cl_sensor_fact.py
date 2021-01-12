# -*- coding: utf-8 -*-


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
from main.pi_ager_cl_logger import cl_fact_logger
from sensors.pi_ager_cl_sensor_type import cl_fact_main_sensor_type, cl_fact_second_sensor_type
from main.pi_ager_cx_exception import *
from sensors.pi_ager_cl_sensor_sht75 import cl_fact_sensor_sht75
from sensors.pi_ager_cl_sensor_sht3x import cl_fact_sensor_sht3x
from sensors.pi_ager_cl_sensor_sht85 import cl_fact_sensor_sht85
from sensors.pi_ager_cl_sensor_dht11 import cl_fact_sensor_dht11
from sensors.pi_ager_cl_sensor_dht22 import cl_fact_sensor_dht22

class cl_fact_sensor:
    
#    Only a singleton instance for main_sensor
    __o_sensor_type = cl_fact_main_sensor_type().get_instance()
    __o_instance = None
    __ot_instances = {}
    @classmethod        
    def get_instance(self, i_active_sensor, i_address=None):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        cl_fact_logger.get_instance().debug("Active Sensor = " + i_active_sensor)
        try:
            cl_fact_sensor.__o_instance = cl_fact_sensor.__ot_instances.pop(i_active_sensor)
            cl_fact_logger.get_instance().debug("__ot_instance for " + i_active_sensor + " = " + str(cl_fact_sensor.__ot_instance))
        except KeyError:
            pass 
        if  cl_fact_sensor.__o_instance is not None :
            cl_fact_logger.get_instance().debug("Returning __ot_instance = " + str(cl_fact_sensor.__ot_instance))
            return(cl_fact_sensor.__o_instance)
        try:
            if   cl_fact_sensor.__o_sensor_type._get_type_ui( ) == 'SHT75':
                cl_fact_sensor.__o_instance = cl_fact_sensor_sht75.get_instance(i_active_sensor)
            elif cl_fact_sensor.__o_sensor_type._get_type_ui( ) == 'SHT3x':
                cl_fact_sensor.__o_instance = cl_fact_sensor_sht3x.get_instance(i_active_sensor,i_address)
            elif cl_fact_sensor.__o_sensor_type._get_type_ui( ) == 'SHT85':
                cl_fact_sensor.__o_instance = cl_fact_sensor_sht85.get_instance(i_active_sensor,i_address)
            elif cl_fact_sensor.__o_sensor_type._get_type_ui( ) == 'DHT22':
                cl_fact_sensor.__o_instance = cl_fact_sensor_dht22.get_instance(i_active_sensor)
            elif cl_fact_sensor.__o_sensor_type._get_type_ui( ) == 'DHT11':
                cl_fact_sensor.__o_instance = cl_fact_sensor_dht11.get_instance(i_active_sensor)
            cl_fact_logger.get_instance().debug("__ot_instances = " + str(cl_fact_sensor.__ot_instances))
            line = {i_active_sensor:cl_fact_sensor.__o_instance}
            cl_fact_sensor.__ot_instances.update(line)    
            cl_fact_logger.get_instance().debug("__ot_instances = " + str(cl_fact_sensor.__ot_instances))    
        except Exception as original_error:
            raise original_error        
        return(cl_fact_sensor.__o_instance)

    @classmethod
    def set_instance(self, i_instance):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        cl_fact_sensor.__o_instance = i_instance
              
    
    def __init__(self):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        
        pass    
 
