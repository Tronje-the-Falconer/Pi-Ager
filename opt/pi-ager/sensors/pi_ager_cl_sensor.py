# -*- coding: utf-8 -*-

"""This class is for handling the main sensor(s) for the Pi-Ager."""

__author__ = "Claus Fischer"
__copyright__ = "Copyright 2019, The Pi-Ager Project"
__credits__ = ["Claus Fischer"]
__license__ = "GPL"
__version__ = "1.0.0"
__maintainer__ = "Claus Fischer"
__email__ = "DerBurgermeister@pi-ager.org"
__status__ = "Production"

from abc import ABC, abstractmethod
import math
import inspect
import pi_ager_logging
import datetime


from sensors.pi_ager_cl_sensor_type import cl_fact_main_sensor_type

from main.pi_ager_cx_exception import *
#from pi_ager_cl_sensor_fact import *
from sensors.pi_ager_cl_ab_sensor import cl_ab_sensor
from main.pi_ager_cl_database import cl_fact_db_influxdb
        
global logger
logger = pi_ager_logging.create_logger(__name__) 
    
class cl_main_sensor(cl_ab_sensor):

    def __init__(self, o_sensor_type):
        logger.debug(pi_ager_logging.me())
        self._error_counter = 0
        self._max_errors = 1
        self._measuring_intervall = 300
        self.o_sensor_type = o_sensor_type
    def get_current_data(self):
        logger.debug(pi_ager_logging.me())
        if (self._error_counter >= self._max_errors):
            self._execute_soft_reset()
                 
    def get_sensor_type_ui(self):
        logger.debug(pi_ager_logging.me())
        return( self.o_sensor_type.get_sensor_type_ui() )
    
    def get_sensor_type(self):
        logger.debug(pi_ager_logging.me())
        return( self.o_sensor_type._get_type() )
    
    def get_dewpoint(self, temperature, humidity):
        logger.debug(pi_ager_logging.me())
        if (temperature >= 0):
            a = 7.5
            b = 237.3
        elif (temperature < 0 and temperature > -4):
            a = 7.6
            b = 240.7
        elif (temperature <= -4):
            a = 9.5
            b = 265.5
        
        R = 8314.3  #R* = 8314.3 J/(kmol*K) (universelle Gaskonstante)
        mw = 18.016 #mw = 18.016 "kg/kmol (Molekulargewicht des Wasserdampfes)
        temperature_kelvin = temperature + 273.15
        SDD = 6.1078 * 10**((a*temperature)/(b+temperature))
        DD = humidity/100 * SDD
        v = math.log10(DD/6.1078)
        temperature_dewpoint = b*v/(a-v) 
        humidity_absolute = 10**5 * mw/R * DD/temperature_kelvin
        calculated_dewpoint = (temperature_dewpoint, humidity_absolute)
        return(calculated_dewpoint)
    
    def _execute_soft_reset(self):
        logger.debug(pi_ager_logging.me())
        pass
    
    def _write_to_db(self):
        """ Write the sensor data to time series DB"""
        logger.debug(pi_ager_logging.me())
        
        influx_db = cl_fact_db_influxdb.get_instance()
        json_body = [
            {
                "measurement": "Temperature",
                "tags": {
                "sensor": "Main Sensor",
                "sensor_type": "SHT3x"
                },
                "time": datetime.datetime.today(),
                "fields": {
                "value": self._current_temperature 
                }
                }
        ]
        logger.debug(json_body)
        influx_db.write_data_to_db(json_body) 
        pass

    def execute(self):
        pass

       
class th_main_sensor():
#    SUPPORTED_MAIN_SENSOR_TYPES = ["SHT75", "DHT11", "DHT22"]
    NAME = 'Main_sensor'
    
    
    def __init__(self):
    
        self.get_type_raise = False
        self._type = "SHT75"
        
    def get_type(self):
        if self.get_type_raise == True:
            raise cx_Sensor_not_defined(self._type_ui)        
        return(self._type)

    


