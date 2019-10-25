# -*- coding: utf-8 -*-
 
"""This class is for handling the SHT3x sensor from sensirion."""

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

from sensors.pi_ager_cl_sensor_type import cl_fact_main_sensor_type
from sensors.pi_ager_cl_i2c_bus import cl_fact_i2c_bus_logic
from sensors.pi_ager_cl_i2c_sensor_sht import cl_fact_i2c_sensor_sht
from main.pi_ager_cx_exception import *
from messenger.pi_ager_cl_messenger import cl_fact_logic_messenger
from sensors.pi_ager_cl_sensor import cl_main_sensor#
from sensors.pi_ager_cl_ab_sensor import cl_ab_sensor

class cl_main_sensor_sht75(cl_main_sensor):
    
    def __init__(self, o_sensor_type):
        if "get_instance" not in inspect.stack()[1][3]:
            raise cx_direct_call(self,"Please use factory class" )
        self.o_sensor_type = o_sensor_type
        pass
    m_old_temperature = 0
    m_current_temperature = 0
    #self.i2c_bus = cl_fact_i2c_bus_logic.get_instance()
    #self.sht3x = cl_fact_i2c_sensor.get_instance(i2c_bus)
    #self.sht3x.i2c_start_command()
    
    def read_data(self):
    #    self.sht3x.get_data()
        pass 
    def get_temperature(self):
        
    #    self.read_temperature(self)
        cl_main_sensor.read
   
        if self.m_old_temperature is None:
            self.m_old_temperature = 0
        else:
      
            self.m_old_temperature = self.m_current_temperature
            
        self.m_current_temperature = 100.0
     
        self._check_temperature()

  
class th_main_sensor_sht75(cl_main_sensor_sht75):
#    SUPPORTED_MAIN_SENSOR_TYPES = ["SHT75", "DHT11", "DHT22"]
    NAME = 'Main_sensor'
    
    
    def __init__(self):
    
        self.get_type_raise = False
        self._type = 3
        #self._type_ui = "SHT75"
        
    def get_type(self):
        if self.get_type_raise == True:
            raise cx_Sensor_not_defined(self._type_ui)        
        return(self._type)

    
