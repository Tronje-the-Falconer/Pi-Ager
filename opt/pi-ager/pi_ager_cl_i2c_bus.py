# -*- coding: utf-8 -*-

"""This class is for the i2c bus"""

__author__ = "Claus Fischer"
__copyright__ = "Copyright 2019, The Pi-Ager Project"
__credits__ = ["Claus Fischer"]
__license__ = "GPL"
__version__ = "1.0.0"
__maintainer__ = "Claus Fischer"
__email__ = "DerBurgermeister@pi-ager.org"
__status__ = "Development"

import inspect
import time
import smbus
import sys
import struct
import pi_ager_logging

from abc import ABC, abstractmethod
from pi_ager_cx_exception import *
from pi_ager_cl_messenger import cl_fact_logic_messenger
"""
* Below is also a good one to have, but be careful i2cdump from the below cause the sht31 interface to become unstable for me
 * and requires a hard-reset to recover correctly.
 * sudo apt-get install i2c-tools
 *
 * on PI make sure below 2 commands are in /boot/config.txt
 * dtparam=i2c_arm=on
 * dtparam=i2c1_baudrate=10000
 * I know we are slowing down the baurate from optimal, but it seems to be the most stable setting in my testing.
 * add another 0 to the above baudrate for max setting, ie dtparam=i2c1_baudrate=100000
"""

global logger
logger = pi_ager_logging.create_logger(__name__) 

class cl_i2c_bus_logic():
    
    __error_counter = 0
    __measuring_intervall = 300
    

    def __init__(self):
        logger.debug(pi_ager_logging.me())
        if "get_instance" not in inspect.stack()[1][3]:
            raise cx_direct_call(self,"Please use factory class" )
        try:
            self.bus1 = smbus.SMBus(1)
        except Exception as cx_error:
            cl_fact_logic_messenger().get_instance().handle_exception(cx_error)
        if self.bus1 is None:
            raise cx_i2c_bus_error(self,"Can't get I2C Bus" )
    def get_i2c_bus(self):
        logger.debug(pi_ager_logging.me())
        return(self.bus1)

class th_i2c_bus_logic():
    
    def __init__(self):
        pass
    
class cl_fact_i2c_bus_logic(ABC):
    __o_instance = None
        
    @classmethod
    def set_instance(self, i_instance):
        """
        Factory method to set the i2c logic instance
        """
        logger.debug(pi_ager_logging.me())
        cl_fact_i2c_bus_logic.__o_instance = i_instance
        
    @classmethod        
    def get_instance(self):
        """
        Factory method to get the i2c logic instance
        """
        logger.debug(pi_ager_logging.me())
        if cl_fact_i2c_bus_logic.__o_instance is not None:
            return(cl_fact_i2c_bus_logic.__o_instance)
        cl_fact_i2c_bus_logic.__o_instance = cl_i2c_bus_logic()
        return(cl_fact_i2c_bus_logic.__o_instance)

    def __init__(self):
        """
        Constructor i2c logic factory
        """
        logger.debug(pi_ager_logging.me())
        pass    
    
    def __init__(self):
        logger.debug(pi_ager_logging.me())
        pass    
