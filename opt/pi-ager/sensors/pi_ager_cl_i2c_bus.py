# -*- coding: utf-8 -*-

"""This class is for the i2c bus"""

__author__ = "Claus Fischer"
__copyright__ = "Copyright 2019, The Pi-Ager Project"
__credits__ = ["Claus Fischer"]
__license__ = "GPL"
__version__ = "1.0.1"
__maintainer__ = "Claus Fischer"
__email__ = "DerBurgermeister@pi-ager.org"
__status__ = "Development"

# import inspect
# import time
import smbus2
# import sys
# import struct
# import pi_ager_logging
from main.pi_ager_cl_logger import cl_fact_logger

from abc import ABC, abstractmethod
from main.pi_ager_cx_exception import *
from messenger.pi_ager_cl_messenger import cl_fact_logic_messenger
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

class cl_i2c_bus_logic:
    _I2C_BUS_NUMBER = 3
    
    def __init__(self):
        try:
            self.bus1 = smbus2.SMBus(self._I2C_BUS_NUMBER)
        except Exception as cx_error:
            cl_fact_logic_messenger().get_instance().handle_exception(cx_error)
            
        if self.bus1 is None:
            raise cx_i2c_bus_error("Can't get I2C Bus" )
    
    def get_i2c_bus(self):
        cl_fact_logger.get_instance().debug("i2c object is : " + str(self.bus1) + " RPi I2C Bus number in use is : " + str(self._I2C_BUS_NUMBER))
        return(self.bus1)

    
class cl_fact_i2c_bus_logic(ABC):
    __o_instance = None
        
    @classmethod
    def set_instance(self, i_instance):
        """
        Factory method to set the i2c logic instance
        """
        cl_fact_i2c_bus_logic.__o_instance = i_instance
        
    @classmethod        
    def get_instance(self):
        """
        Factory method to get the i2c logic instance
        """
        if cl_fact_i2c_bus_logic.__o_instance is not None:
            return(cl_fact_i2c_bus_logic.__o_instance)
            
        cl_fact_i2c_bus_logic.__o_instance = cl_i2c_bus_logic()
        return(cl_fact_i2c_bus_logic.__o_instance)

    def __init__(self):
        """
        Constructor i2c logic factory
        """
        pass    
 
