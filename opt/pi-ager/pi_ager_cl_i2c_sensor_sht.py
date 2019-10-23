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
from pi_ager_cx_exception import *
from pi_ager_cl_messenger import cl_fact_logic_messenger

global logger
logger = pi_ager_logging.create_logger(__name__) 

class cl_i2c_sensor_sht(ABC):
    
    __error_counter = 0
    __measuring_intervall = 300
    _RESET = 0x30A2
    _HEATER_ON = 0x306D
    _HEATER_OFF = 0x3066
    _STATUS = 0xF32D
    _TRIGGER = 0x2C06
    _STATUS_BITS_MASK = 0xFFFC

    def __init__(self, i_i2c_bus):
        logger.debug(pi_ager_logging.me())
        if "get_instance" not in inspect.stack()[1][3]:
            raise cx_direct_call(self,"Please use factory class" )
        #self._sensor_type = o_sensor_type
        self._i2c_bus = i_i2c_bus
        
        
    def solf_reset(self):
        """Performs Soft Reset on SHT chip"""
        logger.debug(pi_ager_logging.me())
        self.i2c.write(self._RESET)
        

    def _calculate_checksum(self, value):
        """4.12 Checksum Calculation from an unsigned short input"""
        logger.debug(pi_ager_logging.me())
        # CRC
        polynomial = 0x131  # //P(x)=x^8+x^5+x^4+1 = 100110001
        crc = 0xFF

        # calculates 8-Bit checksum with given polynomial
        for byteCtr in [ord(x) for x in struct.pack(">H", value)]:
            crc ^= byteCtr
            for bit in range(8, 0, -1):
                if crc & 0x80:
                    crc = (crc << 1) ^ polynomial
                else:
                    crc = (crc << 1)
        return crc
    def i2c_start_command(self):
        logger.debug(pi_ager_logging.me())
        #Write the read sensor command
        address = 0x44
        msb_data = 0x24
        lsb_data = 0x00
        
        self._i2c_bus.write_byte_data(address, msb_data, lsb_data)
        time.sleep(0.01) #This is so the sensor has tme to preform the mesurement and write its registers before you read it
        
        
        msb_data = 0x21
        lsb_data = 0x30
        self._i2c_bus.write_byte_data(address, msb_data, lsb_data)
        time.sleep(0.01) #This is so the sensor has tme to preform the mesurement and write its registers before you read it
    def read_data(self):
        logger.debug(pi_ager_logging.me())
        address = 0x44
        self.data0 = self._i2c_bus.read_i2c_block_data(address, 0x00, 8)
        self.t_val = (self.data0[0]<<8) + self.data0[1] #convert the data
        #t_crc_calc = self._calculate_checksum(t_val)
        self.t_crc = self.data0[2]
        
        self.h_val = (self.data0[3] <<8) + self.data0[4]     # Convert the data
        #h_crc_calc = _self._calculate_checksum(h_val)
        self.h_crc = self.data0[5]
        
        localtime = time.asctime( time.localtime(time.time()) )
        #if hex(t_crc_calc) != hex(t_crc):
        if 1 != 1:
            logger.debug("Local current time :", localtime)    
            logger.debug("Temperature CRC calc is : %x " %t_crc_calc)
            logger.debug("Temperature CRC real is : %x " %t_crc) 
            raise cx_i2c_sht_temperature_crc_error
        
        #if hex(h_crc_calc) != hex(h_crc):
        if 1 != 1:
            logger.debug("Local current time :", localtime)    
            logger.debug("Humitity CRC calc is : %x " %h_crc_calc)
            logger.debug("Humitity CRC real is : %x " %h_crc) 
            raise cx_i2c_sht_humitity_crc_error
        
             
    def get_temperature(self):
        logger.debug(pi_ager_logging.me())
        t_val = (self.data0[0]<<8) + self.data0[1] #convert the data
        
        Temperature_Celsius    = ((175.72 * self.t_val) / 2**16 - 1 ) - 45 #do the maths from datasheet
        Temperature_Fahrenheit = ((315.0  * self.t_val) / 2**16 - 1 ) - 49 #do the maths from datasheet

        logger.debug("Temperature in Celsius is : %.2f C" %Temperature_Celsius)
        logger.debug("Temperature in Fahrenheit is : %.2f F" %Temperature_Fahrenheit)
        
        return(Temperature_Celsius, Temperature_Fahrenheit)
    def get_humitity(self):
        logger.debug(pi_ager_logging.me())
        h_val = (self.data0[3] <<8) + self.data0[4]     # Convert the data
        
        Humitity = ((100.0 * self.h_val) / 2**16 - 1 )

        logger.debug("Relative Humidity is : %.2f %%RH" %Humitity)
        
        return(Humitity)
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
    def get_instance(self, i_i2c_bus):
        """
        Factory method to get the i2c logic instance
        """
        logger.debug(pi_ager_logging.me())
        if cl_fact_i2c_sensor_sht.__o_instance is not None:
            return(cl_fact_i2c_sensor_sht.__o_instance)
        cl_fact_i2c_sensor_sht.__o_instance = cl_i2c_sensor_sht(i_i2c_bus)
        return(cl_fact_i2c_sensor_sht.__o_instance)

    def __init__(self):
        """
        Constructor i2c logic factory
        """
        logger.debug(pi_ager_logging.me())
        pass    
    
    
    
    def __init__(self):
        pass    

