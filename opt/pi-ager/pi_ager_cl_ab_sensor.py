# -*- coding: utf-8 -*-

"""This abstract classes is for handling the main sensor(s) for the Pi-Ager."""

__author__ = "Claus Fischer"
__copyright__ = "Copyright 2019, The Pi-Ager Project"
__credits__ = ["Claus Fischer"]
__license__ = "GPL"
__version__ = "1.0.0"
__maintainer__ = "Claus Fischer"
__email__ = "DerBurgermeister@pi-ager.org"
__status__ = "Production"

from abc import ABC, abstractmethod
import inspect

#from pi_ager_cl_sensor_type import cl_fact_main_sensor_type
from pi_ager_cx_exception import *

class cl_ab_humitity_sensor(ABC):
    __max_humitity_errors = 10
    __HUMITIY_SENSOR_STATES = ["Valid", "Invalid"]

    def __init__(self):
        self._current_humitity = 0
        self._old_humitity = 0
        self._state = 0
    
    def get_current_humitity(self):
        return(self._current_humitity)
    
    def get_old_humitiy(self):
        return(self._old_humitity)
   
    @abstractmethod
    def read_humitity(self):
        pass

    def get_humitity_state(self):
        return(cl_ab_humitity_sensor.__HUMITIY_SENSOR_STATES[self._state])

    def _check_humitity(self):
        if abs(self._current_humitity - self._old_humitity) > 10:
            self._state = 1
        else:
            self._state = 0
class cl_ab_temp_sensor(ABC):
    __max_temp_errors = 10
    __TEMP_SENSOR_STATES = ["Valid", "Invalid"]

    def __init__(self):
        self._current_temperature = 0
        self._old_temperature = 0
        self._state = 0
    
    def get_current_temperature(self):
        return(self._current_temperature)
    
    def get_old_temperature(self):
        return(self._old_temperature)
    
    @abstractmethod
    def read_temperature(self):
        pass
    
    def get_temperature_state(self):
        return(cl_ab_temp_sensor.__TEMP_SENSOR_STATES[self._state])
    
    def _check_temperature(self):
        if abs(self._current_temperature - self._old_temperature) > 10:
            self._state = 1
        else:
            self.m_state = 0
        
    
