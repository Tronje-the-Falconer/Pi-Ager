# -*- coding: utf-8 -*-

"""This class is for handling the main sensor(s) for the Pi-Ager."""

__author__ = "Claus Fischer"
__copyright__ = "Copyright 2019, The Pi-Ager Project"
__credits__ = ["Claus Fischer"]
__license__ = "GPL"
__version__ = "1.0.1"
__maintainer__ = "Claus Fischer"
__email__ = "DerBurgermeister@pi-ager.org"
__status__ = "Production"

from abc import ABC, abstractmethod
import math
import inspect

from main.pi_ager_cl_logger import cl_fact_logger
from datetime import datetime

from sensors.pi_ager_cl_sensor_type import cl_fact_main_sensor_type
#from sensors.pi_ager_cl_sensor_type import cl_fact_main_sensor_type, cl_fact_second_sensor_type

from main.pi_ager_cx_exception import *

from sensors.pi_ager_cl_ab_sensor import cl_ab_sensor
from main.pi_ager_cl_database import cl_fact_db_influxdb

class cl_sensor(cl_ab_sensor):
    
    __mean_temperature = 0
    __temperature_sum = 0
    __counter = 0
    
    def __init__(self, o_sensor_type):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        self._error_counter = 0
        self._max_errors = 3
        self.o_sensor_type = o_sensor_type
        
    def get_current_data(self):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        if (self._error_counter >= self._max_errors):
            self._execute_soft_reset()
                 
    def get_sensor_type_ui(self):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        return( self.o_sensor_type.get_sensor_type_ui())
    
    def get_sensor_type(self):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        return( self.o_sensor_type._get_type() )
    
    def delete_error_counter(self):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        self._error_counter = 0
        
    def check_error_counter(self):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        
        self._error_counter = self._error_counter + 1
        cl_fact_logger.get_instance().debug("Error counter: %i" % self._error_counter)
        cl_fact_logger.get_instance().debug("Max errors:    %i" % self._max_errors)
        
        if (self._error_counter >= self._max_errors):
            raise cx_measurement_error ('To much measurment errors occured!')
    def calc_mean_temperature(self, temperature):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        cl_sensor.__counter = cl_sensor.__counter + 1
        cl_sensor.__temperature_sum = cl_sensor.__temperature_sum + temperature
        cl_sensor.__mean_temperature = cl_sensor.__temperature_sum / cl_sensor.__counter
    
    def get_mean_temperature(self):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        return(cl_sensor.__mean_temperature)
    
    def get_dewpoint(self, temperature, humidity):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
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
        self._temperature_dewpoint = b*v/(a-v) 
        self._humidity_absolute = 10**5 * mw/R * DD/temperature_kelvin
        cl_fact_logger.get_instance().debug("Calculated DewPoint for Temp %.2f C and Hum %.2f is %.2f" % (temperature,humidity,self._temperature_dewpoint))

        calculated_dewpoint = (self._temperature_dewpoint, self._humidity_absolute)
        return(calculated_dewpoint)
    
    def _execute_soft_reset(self):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        pass
    
    def _write_to_db(self):
        """ Write the sensor data to DB"""
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        if 1 == 2:
            self._write_to_influxdb()
        pass
    def _write_to_influxdb(self):
        """ Write the sensor data to time series DB"""
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        
        influx_db = cl_fact_db_influxdb.get_instance()
        if (self._current_temperature != 0 ):
            json_body = [
                {
                    "measurement": "Temperature",
                    "tags": {
                    "sensor": "Main_Sensor",
                    "sensor_type": str(self.get_sensor_type_ui())
                    },
        #            "time": str(datetime.now()),
                    "fields": {
                    "value": float(self._current_temperature) 
                    }
                    }
            ]

            cl_fact_logger.get_instance().debug(json_body)
            influx_db.write_data_to_db(json_body) 

        if (self._current_humidity != 0 ):
            json_body = [
                {
                    "measurement": "relative Humidity",
                    "tags": {
                    "sensor": "Main_Sensor",
                    "sensor_type": str(self.get_sensor_type_ui())
                    },
        #            "time": str(datetime.now()),
                    "fields": {
                    "value": float(self._current_humidity) 
                    }
                    }
            ]

            cl_fact_logger.get_instance().debug(json_body)
            influx_db.write_data_to_db(json_body) 

        if (self._current_humidity != 0 ):        
            json_body = [
                {
                    "measurement": "DewPoint",
                    "tags": {
                    "sensor": "Main_Sensor",
                    "sensor_type": str(self.get_sensor_type_ui())
                    },
        #            "time": str(datetime.now()),
                    "fields": {
                    "value": float(self._temperature_dewpoint) 
                    }
                    }
            ]

            cl_fact_logger.get_instance().debug(json_body)
            influx_db.write_data_to_db(json_body) 

        if (self._humidity_absolute != 0 ):        
            json_body = [
                {
                    "measurement": "absolute Humidity",
                    "tags": {
                    "sensor": "Main_Sensor",
                    "sensor_type": str(self.get_sensor_type_ui())
                    },
        #            "time": str(datetime.now()),
                    "fields": {
                    "value": float(self._humidity_absolute) 
                    }
                    }
            ]

            cl_fact_logger.get_instance().debug(json_body)
            influx_db.write_data_to_db(json_body) 
        pass

    def execute(self):
        pass

       
class th_sensor():
#    SUPPORTED_MAIN_SENSOR_TYPES = ["SHT75", "DHT11", "DHT22"]
    NAME = 'Main_sensor'
    
    
    def __init__(self):
    
        self.get_type_raise = False
        self._type = "SHT75"
        
    def get_type(self):
        if self.get_type_raise == True:
            raise cx_Sensor_not_defined(self._type_ui)        
        return(self._type)

