# -*- coding: utf-8 -*-

"""This class is for the sensirion i2c sensors AHT2x """

# import inspect
# import struct
# import time
from main.pi_ager_cl_logger import cl_fact_logger

from abc import ABC, abstractmethod
from main.pi_ager_cx_exception import *
# from messenger.pi_ager_cl_messenger import cl_fact_logic_messenger

class cl_i2c_sensor_aht:

    def __init__(self, i_active_sensor, i_i2c_bus, i_address):
        cl_fact_logger.get_instance().debug('i2c address is ' + hex(i_address))
        self._i2c_bus = i_i2c_bus
        self._address = i_address
        
    def get_address(self):
        return(self._address)
    
    def calculate_checksum(self, value):
        crc = 0xff
        return crc
 
class cl_fact_i2c_sensor_aht(ABC):
    __o_instance = None
    __ot_instances = {}        
    @classmethod
    def set_instance(self, i_instance):
        """
        Factory method to set the i2c logic instance
        """
        # cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        cl_fact_i2c_sensor_aht.__o_instance = i_instance
        
    @classmethod        
    def get_instance(self, i_active_sensor, i_i2c_bus, i_address):
        """
        Factory method to get the i2c logic instance
        """
        cl_fact_logger.get_instance().debug("Old i2c __ot_instances = " + str(cl_fact_i2c_sensor_aht.__ot_instances))

        try:
            cl_fact_i2c_sensor_aht.__o_instance = cl_fact_i2c_sensor_aht.__ot_instances.pop(i_active_sensor)
            cl_fact_logger.get_instance().debug("i2c __ot_instance for " + i_active_sensor + " = " + str(cl_fact_i2c_sensor_aht.__o_instance) + " found ")
        except KeyError:
            cl_fact_logger.get_instance().debug("i2c __ot_instance for " + i_active_sensor + " not found ")
            cl_fact_i2c_sensor_aht.__o_instance = None
             
        if  cl_fact_i2c_sensor_aht.__o_instance is not None :
            cl_fact_logger.get_instance().debug("i2c __ot_instance for " + i_active_sensor + " = "+ str(cl_fact_i2c_sensor_aht.__o_instance) + " Returning")
            return(cl_fact_i2c_sensor_aht.__o_instance)
        
        cl_fact_i2c_sensor_aht.__o_instance = cl_i2c_sensor_aht(i_active_sensor, i_i2c_bus, i_address)
        cl_fact_logger.get_instance().debug("i2c __ot_instance for " + i_active_sensor + " =  " + str(cl_fact_i2c_sensor_aht.__o_instance) + " created" )
        line = {i_active_sensor:cl_fact_i2c_sensor_aht.__o_instance}
        cl_fact_i2c_sensor_aht.__ot_instances.update(line)   
        cl_fact_logger.get_instance().debug("New i2c __ot_instances = " + str(cl_fact_i2c_sensor_aht.__ot_instances))
        return(cl_fact_i2c_sensor_aht.__o_instance)

    def __init__(self):
        """
        Constructor i2c logic factory
        """
        pass    
    
