# -*- coding: utf-8 -*-
 
"""This class is for handling the SHT85 sensor from sensirion."""

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
# import pi_ager_logging
from main.pi_ager_cl_logger import cl_fact_logger
import time

# from sensors.pi_ager_cl_sensor_type import cl_fact_main_sensor_type
# from sensors.pi_ager_cl_i2c_bus import cl_fact_i2c_bus_logic
# from sensors.pi_ager_cl_i2c_sensor_sht import cl_fact_i2c_sensor_sht
from main.pi_ager_cx_exception import *
# from messenger.pi_ager_cl_messenger import cl_fact_logic_messenger
# from sensors.pi_ager_cl_sensor import cl_sensor
# from sensors.pi_ager_cl_ab_sensor import cl_ab_sensor
from sensors.pi_ager_cl_sensor_sht import cl_sensor_sht

class cl_sensor_sht85(cl_sensor_sht):
    # i_active_sensor : 'MAIN' or 'SECOND'
    # i_sensor_type   : class cl_main_sensor_type or cl_second_sensor_type
    # i_address       : i2c address        
    def __init__(self, i_sensor_type, i_active_sensor, i_address):
        # cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        # if "get_instance" not in inspect.stack()[1][3]:
        #    raise cx_direct_call(self,"Please use factory class" )
        cl_fact_logger.get_instance().debug("i2c address is" + (str(i_address) if i_address == None else hex(i_address)))
        self.o_sensor_type = i_sensor_type
        self.o_address     = i_address
        super().__init__(self.o_sensor_type, i_active_sensor, self.o_address)

    def get_current_data(self):
        # cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        self.measured_data = super().get_current_data()
        return(self.measured_data)
    
class cl_fact_sensor_sht85(ABC): 
    __o_instance = None

    @classmethod        
    def get_instance(self, i_sensor_type, i_active_sensor, i_address):
        # cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        cl_fact_logger.get_instance().debug("cl_fact_sensor_sht85.get_instance")
        if cl_fact_sensor_sht85.__o_instance is not None:
            return(cl_fact_sensor_sht85.__ot_instance)
        cl_fact_sensor_sht85.__o_instance = cl_sensor_sht85(i_sensor_type, i_active_sensor, i_address)
        return(cl_fact_sensor_sht85.__o_instance)

    @classmethod
    def set_instance(self, i_active_sensor, i_instance):
        # cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        cl_fact_sensor_sht85.__o_instance = i_instance
    
    
    def __init__(self):
        # cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        pass    

