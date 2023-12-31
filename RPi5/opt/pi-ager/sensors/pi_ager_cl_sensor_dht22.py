# -*- coding: utf-8 -*-
 
"""This class is for handling the dht22 sensor from sensirion."""

__author__ = "Claus Fischer"
__copyright__ = "Copyright 2019, The Pi-Ager Project"
__credits__ = ["Claus Fischer"]
__license__ = "GPL"
__version__ = "1.0.0"
__maintainer__ = "Claus Fischer"
__email__ = "DerBurgermeister@pi-ager.org"
__status__ = "Production"

from abc import ABC, abstractmethod
# import inspect
from main.pi_ager_cl_logger import cl_fact_logger
# import time

# import pi_ager_gpio_config

# from sensors.pi_ager_cl_sensor_type import cl_fact_main_sensor_type
from main.pi_ager_cx_exception import *
# from messenger.pi_ager_cl_messenger import cl_fact_logic_messenger
# from sensors.pi_ager_cl_sensor import cl_sensor
# from sensors.pi_ager_cl_ab_sensor import cl_ab_sensor
from sensors.pi_ager_cl_sensor_dht_adafruit import cl_sensor_dht_adafruit
import Adafruit_DHT


class cl_sensor_dht22(cl_sensor_dht_adafruit):
    
    def __init__(self, i_sensor_type, i_active_sensor):
        # cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        # if "get_instance" not in inspect.stack()[1][3]:
        #     raise cx_direct_call(self,"Please use factory class" )
        # self.o_sensor_type = i_sensor_type
        self._sensor_dht = Adafruit_DHT.DHT22
        # super().__init__(self.o_sensor_type)
        super().__init__()
  
    def get_current_data(self):
        # cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        self.measured_data = super().get_current_data()
        return(self.measured_data)

    
class cl_fact_sensor_dht22(ABC): 
    # fact_main_sensor_type = cl_fact_main_sensor_type()
    __o_instance = None

    @classmethod        
    def get_instance(self, i_sensor_type, i_active_sensor):
        # cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        if cl_fact_sensor_dht22.__o_instance is not None:
            return(cl_fact_sensor_dht22.__o_instance)
        cl_fact_sensor_dht22.__o_instance = cl_sensor_dht22(i_sensor_type, i_active_sensor)
        return(cl_fact_sensor_dht22.__o_instance)

    @classmethod
    def set_instance(self, i_instance):
        # cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        cl_fact_sensor_dht22.__o_instance = i_instance
    
    
    def __init__(self):
        # cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        pass    

