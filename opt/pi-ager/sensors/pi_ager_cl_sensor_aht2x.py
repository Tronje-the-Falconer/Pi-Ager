# -*- coding: utf-8 -*-
 
"""This class is for handling the AHT2x sensor """

import inspect
from main.pi_ager_cl_logger import cl_fact_logger
import time

from abc import ABC, abstractmethod
from main.pi_ager_cx_exception import *
# from messenger.pi_ager_cl_messenger import cl_fact_logic_messenger
from sensors.pi_ager_cl_sensor_aht import cl_sensor_aht

class cl_sensor_aht2x(cl_sensor_aht):
    # i_active_sensor : 'MAIN' or 'SECOND'
    # i_sensor_type   : class cl_main_sensor_type or cl_second_sensor_type
    # i_address       : i2c address
    def __init__(self, i_sensor_type, i_active_sensor, i_address):
        # cl_fact_logger.get_instance().debug("i2c address is : " + hex(i_address) + ". active sensor is : " + i_active_sensor + ". i_sensor_type is : " + i_sensor_type.get_sensor_type_ui())
        cl_fact_logger.get_instance().debug("i2c address is : " + str(i_address) + ". active sensor is : " + str(i_active_sensor) + ". i_sensor_type is : " + str(i_sensor_type))
        self.o_sensor_type = i_sensor_type
        self.o_address     = i_address
        
        super().__init__(i_active_sensor, self.o_sensor_type, self.o_address)

    def get_current_data(self):
        self.measured_data = super().get_current_data()
        return(self.measured_data)

    
class cl_fact_sensor_aht2x(ABC): 
    __o_instance = None
    __ot_instances = {}
    @classmethod        
    def get_instance(self, i_sensor_type, i_active_sensor, i_address):
        cl_fact_logger.get_instance().debug("cl_fact_sensor_aht2x.get_instance")
        cl_fact_logger.get_instance().debug("Old aht2x __ot_instances = " + str(cl_fact_sensor_aht2x.__ot_instances))

        cl_fact_sensor_aht2x.__o_instance = cl_fact_sensor_aht2x.__ot_instances.get(i_active_sensor)
        if  cl_fact_sensor_aht2x.__o_instance is not None :
            cl_fact_logger.get_instance().debug("aht2x  __ot_instance = " + str(cl_fact_sensor_aht2x.__o_instance)+ " Returning")
            return(cl_fact_sensor_aht2x.__o_instance)

#        try:
#            cl_fact_sensor_aht2x.__o_instance = cl_fact_sensor_aht2x.__ot_instances.pop(i_active_sensor)
#            cl_fact_logger.get_instance().debug("aht2x __ot_instance for " + i_active_sensor + " = " + str(cl_fact_sensor_aht2x.__o_instance))
#        except KeyError:
#            cl_fact_logger.get_instance().debug("aht2x __ot_instance not found for " + i_active_sensor)
#           cl_fact_sensor_aht2x.__o_instance = None 
#        if  cl_fact_sensor_aht2x.__o_instance is not None :
#            cl_fact_logger.get_instance().debug("aht2x  __ot_instance = " + str(cl_fact_sensor_aht2x.__o_instance)+ " Returning")
#            return(cl_fact_sensor_aht3x.__o_instance)
        
        cl_fact_sensor_aht2x.__o_instance = cl_sensor_aht2x(i_sensor_type, i_active_sensor, i_address)
        cl_fact_logger.get_instance().debug("aht2x __ot_instance " + i_active_sensor + str(cl_fact_sensor_aht2x.__o_instance) + " created for " )
        line = {i_active_sensor:cl_fact_sensor_aht2x.__o_instance}
        cl_fact_sensor_aht2x.__ot_instances.update(line)   
        cl_fact_logger.get_instance().debug("New aht2x __ot_instances = " + str(cl_fact_sensor_aht2x.__ot_instances))
        return(cl_fact_sensor_aht2x.__o_instance)

    @classmethod
    def set_instance(self, i_active_sensor, i_instance):
        cl_fact_sensor_aht2x.__o_instance = i_instance
        
    def __init__(self):
        pass    

