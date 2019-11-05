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

#from abc import ABC, abstractmethod
import inspect
import pi_ager_logging
import time

from sensors.pi_ager_cl_sensor_type import cl_fact_main_sensor_type
from sensors.pi_ager_cl_i2c_bus import cl_fact_i2c_bus_logic
from sensors.pi_ager_cl_i2c_sensor_sht import cl_fact_i2c_sensor_sht
from main.pi_ager_cx_exception import *
from messenger.pi_ager_cl_messenger import cl_fact_logic_messenger
from sensors.pi_ager_cl_sensor import cl_main_sensor#
from sensors.pi_ager_cl_ab_sensor import cl_ab_sensor

global logger
logger = pi_ager_logging.create_logger(__name__) 

class cl_main_sensor_sht85(cl_main_sensor):
    
    _RESET = 0x30A2
    _HEATER_ON = 0x306D
    _HEATER_OFF = 0x3066
    _STATUS = 0xF32D
    _TRIGGER = 0x2C06
    _STATUS_BITS_MASK = 0xFFFC
    
    def __init__(self):
        logger.debug(pi_ager_logging.me())
        if "get_instance" not in inspect.stack()[1][3]:
            raise cx_direct_call(self,"Please use factory class" )
        #self.o_sensor_type = o_sensor_type
        super().__init__(cl_fact_main_sensor_type.get_instance())
                    
        self._max_errors = 1
        self._old_temperature = 0
        self._current_temperature = 0
        self._temperature_dewpoint = 0
        self._humidity_absolute = 0
        self._old_humidity = 0
        self._current_humidity = 0
        
        self.address = 0x44
        try:
            self._i2c_bus = cl_fact_i2c_bus_logic.get_instance().get_i2c_bus()
            logger.debug(self._i2c_bus)
            self._i2c_sensor = cl_fact_i2c_sensor_sht.get_instance(self._i2c_bus, self.address)
            self._send_i2c_start_command()
        except Exception as cx_error:
            cl_fact_logic_messenger().get_instance().handle_exception(cx_error)
 


 
    def _send_i2c_start_command(self):
        logger.debug(pi_ager_logging.me())
        
        msb_data = 0x24
        lsb_data = 0x00
        
        #Write the sensor data
        self._i2c_bus.write_byte_data(self._i2c_sensor.get_address(), msb_data, lsb_data)

        time.sleep(0.01) #This is so the sensor has tme to preform the mesurement and write its registers before you read it
        
        
        msb_data = 0x21
        lsb_data = 0x30
        #Write the sensor data
        self._i2c_bus.write_byte_data(self._i2c_sensor.get_address(), msb_data, lsb_data)

        time.sleep(0.01) #This is so the sensor has tme to preform the mesurement and write its registers before you read it
   
    
    def _read_data(self):
        logger.debug(pi_ager_logging.me())
        try:
            self.data0 = self._i2c_bus.read_i2c_block_data(self._i2c_sensor.get_address(), 0x00, 6)
        except Exception as cx_error:
            self._error_counter = self._error_counter + 1
            cl_fact_logic_messenger().get_instance().handle_exception(cx_error)
            
        self.t_val = (self.data0[0]<<8) + self.data0[1] #convert the data
        self.h_val = (self.data0[3] <<8) + self.data0[4]     # Convert the data
        
        "CRC Values"
        t_crc_calc = self._i2c_sensor.calculate_checksum(self.t_val)
        h_crc_calc = self._i2c_sensor.calculate_checksum(self.h_val)
        
        t_crc = self.data0[2]
        h_crc = self.data0[5]

        localtime = time.asctime( time.localtime(time.time()) )
        if hex(t_crc_calc) != hex(t_crc):
        #if 1 != 1:
            logger.debug("Local current time :", localtime)    
            logger.debug("Temperature CRC calc is : %x " %t_crc_calc)
            logger.debug("Temperature CRC real is : %x " %t_crc) 
            logger.error("CRC ")
            raise cx_i2c_sht_temperature_crc_error
        
        if hex(h_crc_calc) != hex(h_crc):
        #if 1 != 1:
            logger.debug("Local current time :", localtime)    
            logger.debug("Humidity CRC calc is : %x " %h_crc_calc)
            logger.debug("Humidity CRC real is : %x " %h_crc) 
            raise cx_i2c_sht_humidity_crc_error
   
   

   
    def get_current_data(self):
        logger.debug(pi_ager_logging.me())
        self._read_data()
        self._current_temperature = self._get_current_temperature()
        self._current_humidity    = self._get_current_humidity()
        self._dewpoint            = super().get_dewpoint(self._current_temperature, self._current_humidity)
    
        (temperature_dewpoint, humidity_absolute) = self._dewpoint
        
        self.measured_data = (self._current_temperature, self._current_humidity, temperature_dewpoint)
        return(self.measured_data)
        
    
    def _get_current_temperature(self):
        logger.debug(pi_ager_logging.me())
        #self._read_data()
#        self._current_temperature = self._i2c_sensor.get_temperature()
        t_val = (self.data0[0]<<8) + self.data0[1] #convert the data
        
        Temperature_Celsius    = ((175.72 * self.t_val) / 2**16 - 1 ) - 45 #do the maths from datasheet
        Temperature_Fahrenheit = ((315.0  * self.t_val) / 2**16 - 1 ) - 49 #do the maths from datasheet

        logger.debug("Temperature in Celsius is : %.2f C" %Temperature_Celsius)
        logger.debug("Temperature in Fahrenheit is : %.2f F" %Temperature_Fahrenheit)
        
        self._current_temperature = Temperature_Celsius
        
        if self._old_temperature is None:
            self._old_temperature = 0
        else:
      
            self._old_temperature = self._current_temperature
            
        return(self._current_temperature)
  
    def _get_current_humidity(self):
        logger.debug(pi_ager_logging.me())
        #self._read_data()
#        self._current_humidity = self._i2c_sensor.get_humidity()
        h_val = (self.data0[3] <<8) + self.data0[4]     # Convert the data
        
        Humidity = ((100.0 * self.h_val) / 2**16 - 1 )

        logger.debug("Relative Humidity is : %.2f %%RH" %Humidity)
        
        self._current_humidity = Humidity
        
        if self._old_humidity is None:
            self._old_humidity = 0
        else:
            self._old_humidity = self._current_humidity

        return(self._current_humidity)
   
    def _write_to_db(self):
        super()._write_to_db()
        pass

    def soft_reset(self):
        """Performs Soft Reset on SHT chip"""
        logger.debug(pi_ager_logging.me())
        self._i2c_bus.write(self._RESET)
        
    def set_heading_on(self):
        """Switch the heading on the sensor on"""
        logger.debug(pi_ager_logging.me())
        self._i2c_bus.write(self._HEATER_ON)
        pass
    
    def set_heading_off(self):
        """Switch the heading on the sensor off"""
        logger.debug(pi_ager_logging.me())
        self._i2c_bus.write(self._HEATER_OFF)
        pass

    def execute(self):
        logger.debug(pi_ager_logging.me())
        #self.get_current_data()
        self._write_to_db()
        
class th_main_sensor_sht85(cl_main_sensor_sht85):
#    SUPPORTED_MAIN_SENSOR_TYPES = ["SHT75", "DHT11", "DHT22"]
    NAME = 'Main_sensor'
    
    
    def __init__(self):
    
        self.get_type_raise = False
        self._type = "SHT85"
        
    def get_type(self):
        if self.get_type_raise == True:
            raise cx_Sensor_not_defined(self._type_ui)        
        return(self._type)

    
class cl_fact_sensor_sht85: 
    fact_main_sensor_type = cl_fact_main_sensor_type()
#    Only a singleton instance for main_sensor
    __o_sensor_type = fact_main_sensor_type.get_instance()
    __o_instance = None

    @classmethod        
    def get_instance(self):
        if cl_fact_sensor_sht85.__o_instance is not None:
            return(cl_fact_sensor_sht85.__o_instance)
        cl_fact_sensor_sht85.__o_instance = cl_main_sensor_sht85()
        return(cl_fact_sensor_sht85.__o_instance)

    @classmethod
    def set_instance(self, i_instance):
        cl_fact_sensor_sht85.__o_instance = i_instance
    
    
    def __init__(self):
        pass    

