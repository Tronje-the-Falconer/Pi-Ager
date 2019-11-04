# -*- coding: utf-8 -*-

"""This class is for the sensirion i2c sensors SHT3x and SHT85"""

__author__ = "Claus Fischer"
__copyright__ = "Copyright 2019, The Pi-Ager Project"
__credits__ = ["Claus Fischer"]
__license__ = "GPL"
__version__ = "1.0.0"
__maintainer__ = "Claus Fischer"
__email__ = "DerBurgermeister@pi-ager.org"
__status__ = "Development"

import inspect
import struct
import time
import pi_ager_logging

from abc import ABC, abstractmethod
from main.pi_ager_cx_exception import *
from messenger.pi_ager_cl_messenger import cl_fact_logic_messenger

global logger
logger = pi_ager_logging.create_logger(__name__) 

class cl_i2c_sensor_sht(ABC):
    
    __error_counter = 0
    __measuring_intervall = 300


    def __init__(self, i_i2c_bus, i_address):
        logger.debug(pi_ager_logging.me())
        if "get_instance" not in inspect.stack()[1][3]:
            raise cx_direct_call(self,"Please use factory class" )
        #self._sensor_type = o_sensor_type
        self._i2c_bus = i_i2c_bus
        self._address = i_address
        
        
    def soft_reset(self,command):
        """Performs Soft Reset on SHT chip"""
        logger.debug(pi_ager_logging.me())
        self.i2c.write(command)
        

    def calculate_checksum(self, value):
        """4.12 Checksum Calculation from an unsigned short input"""
        logger.debug(pi_ager_logging.me())
        # CRC
        polynomial = 0x131  # //P(x)=x^8+x^5+x^4+1 = 100110001
        crc = 0xFF

        # calculates 8-Bit checksum with given polynomial
        #for byteCtr in [ord(x) for x in struct.pack(">H", value)]:
        for byteCtr in struct.pack(">H", value):
            crc ^= byteCtr
            for bit in range(8, 0, -1):
                if crc & 0x80:
                    crc = (crc << 1) ^ polynomial
                else:
                    crc = (crc << 1)
        return crc
    def i2c_start_command(self, i_msb_data, i_lsb_data):
        logger.debug(pi_ager_logging.me())
        #Write the sensor data
        self._i2c_bus.write_byte_data(self._address, i_msb_data, i_lsb_data)

    def read_data(self):
        logger.debug(pi_ager_logging.me())
        #Read the sensor data
        self.data0 = self._i2c_bus.read_i2c_block_data(self._address, 0x00, 6)
        
        return(self.data0)
        
             
    def set_heading_on(self,command):
        """Switch the heading on the sensor on"""
        logger.debug(pi_ager_logging.me())
        self.i2c.write(command)
        pass
    
    def set_heading_off(self,command):
        """Switch the heading on the sensor off"""
        logger.debug(pi_ager_logging.me())
        self.i2c.write(command)
        pass
    
    
class th_i2c_sensor_sht():
    
    def __init__(self):
        pass
        

    
class cl_fact_i2c_sensor_sht(ABC):
    __o_instance = None
        
    @classmethod
    def set_instance(self, i_instance):
        """
        Factory method to set the i2c logic instance
        """
        logger.debug(pi_ager_logging.me())
        cl_fact_i2c_sensor_sht.__o_instance = i_instance
        
    @classmethod        
    def get_instance(self, i_i2c_bus, i_address):
        """
        Factory method to get the i2c logic instance
        """
        logger.debug(pi_ager_logging.me())
        if cl_fact_i2c_sensor_sht.__o_instance is not None:
            return(cl_fact_i2c_sensor_sht.__o_instance)
        cl_fact_i2c_sensor_sht.__o_instance = cl_i2c_sensor_sht(i_i2c_bus, i_address)
        return(cl_fact_i2c_sensor_sht.__o_instance)

    def __init__(self):
        """
        Constructor i2c logic factory
        """
        logger.debug(pi_ager_logging.me())
        pass    
    
    
    
    def __init__(self):
        pass    

