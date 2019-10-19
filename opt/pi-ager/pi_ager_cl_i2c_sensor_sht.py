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

from abc import ABC, abstractmethod
from pi_ager_cx_exception import *

class cl_i2c_sensor_sht(ABC):
    
    __error_counter = 0
    __measuring_intervall = 300
    

    def __init__(self, o_i2c_bus):
        logger.debug(pi_ager_logging.me())
        self.o_sensor_type = o_sensor_type
        self.o_i2c_bus = o_i2c_bus
        
        

    def _calculate_checksum(value):
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
        #Write the read sensor command
        address = 0x44
        msb_data = 0x24
        lsb_data = 0x00
        self.o_i2c_bus.write_byte_data(address, msb_data, lsb_data)
        time.sleep(0.01) #This is so the sensor has tme to preform the mesurement and write its registers before you read it
        
        
        msb_data = 0x21
        lsb_data = 0x30
        self.o_i2c_bus.write_byte_data(address, msb_data, lsb_data)
        time.sleep(0.01) #This is so the sensor has tme to preform the mesurement and write its registers before you read it
    def read_data(self):
        self.data0 = self.o_i2c_bus.read_i2c_block_data(address, 0x00, 8)
        t_val = (self.data0[0]<<8) + self.data0[1] #convert the data
        t_crc_calc = _self._calculate_checksum(t_val)
        t_crc = self.data0[2]
        
        h_val = (self.data0[3] <<8) + self.data0[4]     # Convert the data
        h_crc_calc = _self._calculate_checksum(h_val)
        h_crc = self.data0[5]
        
        localtime = time.asctime( time.localtime(time.time()) )
        if hex(t_crc_calc) != hex(t_crc):
            logger.debug("Local current time :", localtime)    
            logger.debug("Temperature CRC calc is : %x " %t_crc_calc)
            logger.debug("Temperature CRC real is : %x " %t_crc) 
            raise cx_i2c_sht_temperature_crc_error
        
        if hex(h_crc_calc) != hex(h_crc):
            logger.debug("Local current time :", localtime)    
            logger.debug("Humitity CRC calc is : %x " %h_crc_calc)
            logger.debug("Humitity CRC real is : %x " %h_crc) 
            raise cx_i2c_sht_humitity_crc_error
        
             
    def get_temperature(self):
        t_val = (data0[0]<<8) + data0[1] #convert the data
        
        T_c = ((175.72 * t_val) / 2**16 - 1 ) - 45 #do the maths from datasheet
        T_f = ((315.0  * t_val) / 2**16 - 1 ) - 49 #do the maths from datasheet

        logger.debug("Temperature in Celsius is : %.2f C" %T_c)
        logger.debug("Temperature in Fahrenheit is : %.2f F" %T_f)
        
        pass
    def get_humitity(self):

        h_val = (self.data0[3] <<8) + self.data0[4]     # Convert the data
        
        H = ((100.0 * h_val) / 2**16 - 1 )

        logger.debug("Relative Humidity is : %.2f %%RH" %H)
        
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
    def get_instance(self):
        """
        Factory method to get the i2c logic instance
        """
        logger.debug(pi_ager_logging.me())
        if cl_fact_i2c_sensor_sht.__o_instance is not None:
            return(cl_fact_i2c_sensor_sht.__o_instance)
        cl_fact_i2c_sensor_sht.__o_instance = cl_i2c_sensor_sht()
        return(cl_fact_i2c_sensor_sht.__o_instance)

    def __init__(self):
        """
        Constructor i2c logic factory
        """
        logger.debug(pi_ager_logging.me())
        pass    
    
    
    
    def __init__(self):
        pass    

