# -*- coding: utf-8 -*-
 
"""This class is for handling the SHT75 sensor from sensirion."""

__author__ = "Claus Fischer"
__copyright__ = "Copyright 2019, The Pi-Ager Project"
__credits__ = ["Claus Fischer"]
__license__ = "GPL"
__version__ = "1.0.0"
__maintainer__ = "Claus Fischer"
__email__ = "DerBurgermeister@pi-ager.org"
__status__ = "Production"

from abc import ABC, abstractmethod
from main.pi_ager_cl_logger import cl_fact_logger
# import time
import pi_sht1x
import pi_ager_gpio_config

# from sensors.pi_ager_cl_sensor_type import cl_fact_main_sensor_type
from main.pi_ager_cx_exception import *
# from messenger.pi_ager_cl_messenger import cl_fact_logic_messenger
from sensors.pi_ager_cl_sensor import cl_sensor
# from sensors.pi_ager_cl_ab_sensor import cl_ab_sensor

class cl_sensor_sht75(cl_sensor):
    
    def __init__(self, i_sensor_type, i_active_sensor):
        # cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())

        self._current_temperature = 0
        self._current_humidity = 0
        self._max_errors = 5
        
        self._sensor_sht = pi_sht1x.SHT1x(pi_ager_gpio_config.gpio_sensor_data, pi_ager_gpio_config.gpio_sensor_sync, gpio_mode=pi_ager_gpio_config.board_mode)
                
    def get_current_data(self):
        # cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        self._error_counter = 0            

        while self._error_counter < self._max_errors:
        
            try:
                hum_offset = self.get_humidity_offset()    
                self._current_temperature = self._get_current_temperature()
                humidity_s    = self._get_current_humidity() + hum_offset
                if (humidity_s > 100.0):
                    humidity_s = 100.0
                if (humidity_s < 0.0):
                    humidity_s = 0.0
                self._current_humidity = humidty_s
                
                self._dewpoint            = super().get_dewpoint(self._current_temperature, self._current_humidity)
                cl_fact_logger.get_instance().debug("sht75 temperature : %.2f °C" % self._current_temperature)
                cl_fact_logger.get_instance().debug("sht75 humidity : %.2f %%RH" % self._current_humidity)
        
                (temperature_dewpoint, humidity_absolute) = self._dewpoint
        
                self.measured_data = (self._current_temperature, self._current_humidity, temperature_dewpoint, humidity_absolute)
                return(self.measured_data)
                
            except Exception as cx_error:
                self._error_counter = self._error_counter + 1
                if (self._error_counter == 1):
                    cl_fact_logger.get_instance().exception(cx_error)
                else:
                    cl_fact_logger.get_instance().error(f"Retry getting measurement from SHT75. Current retry count : {self._error_counter}, max retry count : {self._max_errors}")       

        cl_fact_logger.get_instance().debug('Too many measurement errors occurred!')
        raise cx_measurement_error(_('Too many measurement errors occurred!'))
        
    def _get_current_temperature(self):
        # cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())

        self._sensor_sht.read_temperature()
        
        Temperature_Celsius    = self._sensor_sht.temperature_celsius
        # Temperature_Fahrenheit = self._sensor_sht.temperature_celsius * 9/5 + 32

        # cl_fact_logger.get_instance().debug("Temperature in Celsius is : %.2f °C" %Temperature_Celsius)
        # cl_fact_logger.get_instance().debug("Temperature in Fahrenheit is : %.2f F" %Temperature_Fahrenheit)
        
        self._current_temperature = Temperature_Celsius
        
        return(self._current_temperature)
  
    def _get_current_humidity(self):
        # cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())

        self._sensor_sht.read_humidity()
        Humidity = self._sensor_sht.humidity

        # cl_fact_logger.get_instance().debug("Relative Humidity is : %.2f %%RH" %Humidity)
        
        self._current_humidity = Humidity
        return(self._current_humidity)

        
class cl_fact_sensor_sht75(ABC): 
    __o_instance = None

    @classmethod        
    def get_instance(self, i_sensor_type, i_active_sensor):
        # cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        if cl_fact_sensor_sht75.__o_instance is not None:
            return(cl_fact_sensor_sht75.__o_instance)
        cl_fact_sensor_sht75.__o_instance = cl_sensor_sht75(i_sensor_type, i_active_sensor)
        return(cl_fact_sensor_sht75.__o_instance)

    @classmethod
    def set_instance(self, i_instance):
        # cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        cl_fact_sensor_sht75.__o_instance = i_instance
    
    
    def __init__(self):
        # cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        pass    

