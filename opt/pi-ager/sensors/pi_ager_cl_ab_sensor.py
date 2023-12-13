# -*- coding: utf-8 -*-

"""This abstract classes is for handling the main sensor(s) for the Pi-Ager."""

from abc import ABC, abstractmethod
import inspect

#from pi_ager_cl_sensor_type import cl_fact_main_sensor_type
from main.pi_ager_cx_exception import *
    
    
class cl_ab_humidity_sensor(ABC):
    __max_humidity_errors = 10
    __HUMIDITY_SENSOR_STATES = ["Valid", "Invalid"]

    def __init__(self):
        self._current_humidity = 0
        self._old_humidity = 0
        self._state = 0

class cl_ab_temp_sensor(ABC):
    __max_temp_errors = 10
    __TEMP_SENSOR_STATES = ["Valid", "Invalid"]

    def __init__(self):
        self._current_temperature = 0
        self._old_temperature = 0
        self._state = 0

class cl_ab_sensor(cl_ab_temp_sensor, cl_ab_humidity_sensor):
    def __init__(self):
        pass
    @abstractmethod
    def get_dewpoint(self, temperature, humidity):
        pass       
    
    @abstractmethod
    def set_heading_on(self): #execute measurement
        pass

    @abstractmethod
    def set_heading_off(self): #execute measurement
        pass
    
    @abstractmethod
    def soft_reset(self): #execute measurement
        pass

