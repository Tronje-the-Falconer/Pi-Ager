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
# import inspect

from main.pi_ager_cl_logger import cl_fact_logger
# from datetime import datetime

from sensors.pi_ager_cl_sensor_type import cl_fact_main_sensor_type
#from sensors.pi_ager_cl_sensor_type import cl_fact_main_sensor_type, cl_fact_second_sensor_type

from main.pi_ager_cx_exception import *

from sensors.pi_ager_cl_ab_sensor import cl_ab_sensor
# from main.pi_ager_cl_database import cl_fact_db_influxdb

class cl_sensor(cl_ab_sensor):

    def __init__(self, o_sensor_type):
#        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        self._error_counter = 0
        self._max_errors = 3
        self.o_sensor_type = o_sensor_type
        
    @abstractmethod    
    def get_current_data(self):
        pass
#        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
#         if (self._error_counter >= self._max_errors):
#            self._execute_soft_reset()
#        return 0, 0, 0, 0
                 
    def get_sensor_type_ui(self):
#        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        return( self.o_sensor_type.get_sensor_type_ui())
    
    def get_sensor_type(self):
#        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        return( self.o_sensor_type._get_type() )

    def get_dewpoint(self, temperature, humidity):
#        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        if humidity == 0:
            humidity = 1
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
        cl_fact_logger.get_instance().debug("Calculated DewPoint for Temp %.2f C and Hum %.2f is %.2f, HumAbs is %.2f grams/m³" % (temperature,humidity,self._temperature_dewpoint, self._humidity_absolute))

        calculated_dewpoint = (self._temperature_dewpoint, self._humidity_absolute)
        return(calculated_dewpoint)

