# -*- coding: utf-8 -*-
 
"""This class is for handling the AHT30 sensor """

import inspect
import pi_ager_names
from pi_ager_database import get_table_row
from main.pi_ager_cl_logger import cl_fact_logger
import time

from abc import ABC, abstractmethod
from main.pi_ager_cx_exception import *
# from messenger.pi_ager_cl_messenger import cl_fact_logic_messenger
from sensors.pi_ager_cl_sensor_aht import cl_sensor_aht

class cl_sensor_aht30(cl_sensor_aht):
    # i_active_sensor : 'MAIN' or 'SECOND'
    # i_sensor_type   : class cl_main_sensor_type or cl_second_sensor_type
    # i_address       : i2c address
    def __init__(self, i_sensor_type, i_active_sensor, i_address):
        # cl_fact_logger.get_instance().debug("i2c address is : " + hex(i_address) + ". active sensor is : " + i_active_sensor + ". i_sensor_type is : " + i_sensor_type.get_sensor_type_ui())
        cl_fact_logger.get_instance().debug("i2c address is : " + str(i_address) + ". active sensor is : " + str(i_active_sensor) + ". i_sensor_type is : " + str(i_sensor_type))
        self.o_sensor_type = i_sensor_type
        self.o_address     = i_address
        
        super().__init__(self.o_sensor_type, i_active_sensor, self.o_address)
        humidity_offsets = get_table_row(pi_ager_names.humidity_offset_table, 1)
        self.humidity_offset = humidity_offsets['AHT30']
        
        self.check_init_status()
        
    def get_current_data(self):
        (temperature, humidity, dewpoint, humidity_absolute) = super().get_current_data()
        humidity += self.humidity_offset
        return temperature, humidity, dewpoint, humidity_absolute

        
class cl_fact_sensor_aht30(ABC): 
    __o_instance = None
    __ot_instances = {}
    @classmethod        
    def get_instance(self, i_sensor_type, i_active_sensor, i_address):
        cl_fact_logger.get_instance().debug("cl_fact_sensor_aht30.get_instance")
        cl_fact_logger.get_instance().debug("Old aht30 __ot_instances = " + str(cl_fact_sensor_aht30.__ot_instances))

        cl_fact_sensor_aht30.__o_instance = cl_fact_sensor_aht30.__ot_instances.get(i_active_sensor)
        if  cl_fact_sensor_aht30.__o_instance is not None :
            cl_fact_logger.get_instance().debug("aht30  __ot_instance = " + str(cl_fact_sensor_aht30.__o_instance)+ " Returning")
            return(cl_fact_sensor_aht30.__o_instance)

        cl_fact_sensor_aht30.__o_instance = cl_sensor_aht30(i_sensor_type, i_active_sensor, i_address)
        cl_fact_logger.get_instance().debug("aht30 __ot_instance " + i_active_sensor + str(cl_fact_sensor_aht30.__o_instance) + " created for " )
        line = {i_active_sensor:cl_fact_sensor_aht30.__o_instance}
        cl_fact_sensor_aht30.__ot_instances.update(line)   
        cl_fact_logger.get_instance().debug("New aht30 __ot_instances = " + str(cl_fact_sensor_aht30.__ot_instances))
        return(cl_fact_sensor_aht30.__o_instance)

    @classmethod
    def set_instance(self, i_active_sensor, i_instance):
        cl_fact_sensor_aht30.__o_instance = i_instance
        
    def __init__(self):
        pass    

