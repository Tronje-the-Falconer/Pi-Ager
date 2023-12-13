# -*- coding: utf-8 -*-
 
"""This class is for handling main sensor types."""

from abc import ABC, abstractmethod
import inspect

from main.pi_ager_cx_exception import *
# from main.pi_ager_cl_logger import *
from main.pi_ager_cl_logger import cl_fact_logger
import pi_ager_database
import pi_ager_names
       
class cl_main_sensor_type:
    __SUPPORTED_MAIN_SENSOR_TYPES = {1: "DHT11",
                                     2: "DHT22",
                                     3: "SHT75",
                                     4: "SHT85",
                                     5: "SHT3x",
                                     6: "AHT2x"}
    __NAME = 'Main_sensor'
    _type = 0
    _type_ui = ""
    def __init__(self):
        # frame,filename,line_number,function_name,lines,index = inspect.stack()[1]
        if "get_instance" not in inspect.stack()[1][3]:
            raise cx_direct_call("Please use factory class")

        try:
            self._read_sensor_type()
        except Exception as original_error:
            cl_fact_logger.get_instance().debug('Sensor type not found!')
            raise original_error

    def _get_type_ui(self):
        if self._is_valid() == False:
            raise cx_Sensor_not_defined(self._type_ui)
        self._type_ui = cl_main_sensor_type.__SUPPORTED_MAIN_SENSOR_TYPES[self._type] 
        return(self._type_ui)
    
    def _is_valid(self):
        if cl_main_sensor_type.__SUPPORTED_MAIN_SENSOR_TYPES[self._type]:
            return(True)
        else:
            return(False)
        
    def _read_sensor_type(self):
        self._type = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.sensortype_key))

        cl_fact_logger.get_instance().debug('Sensor number is: ' + str(self._type))
        if self._is_valid() == False:
            raise cx_Sensor_not_defined(self._type_ui)        
        self._type_ui = self._get_type_ui()        
        cl_fact_logger.get_instance().debug("Sensor type is: " + str(self._type_ui))
    
    def get_sensor_type(self):
        return(self._type)
    
    def get_sensor_type_ui(self):
        return(self._type_ui)
    
    def get_name(self):
        return(cl_main_sensor_type.__NAME)
    
    def get_supported_types(self):
        # print('[%s]' % ', '.join(map(str, cl_main_sensor_type.__SUPPORTED_MAIN_SENSOR_TYPES )))
        pass
        
class cl_second_sensor_type():
    __SUPPORTED_SECOND_SENSOR_TYPES = { 0: "disabled",
                                        4: "SHT85",
                                        5: "SHT3x",
                                        6: 'AHT2x',
                                        7: "MiThermometer"}
    __NAME = 'Second_sensor'
    _type = 0
    _type_ui = ""
    
    def __init__(self):
        # frame,filename,line_number,function_name,lines,index = inspect.stack()[1]
        if "get_instance" not in inspect.stack()[1][3]:
            raise cx_direct_call("Please use factory class")

        try:
            self._read_sensor_type()
        except Exception as original_error:
            cl_fact_logger.get_instance().debug('Sensor type not found!')
            raise original_error

    def _get_type_ui(self):
        if self._is_valid() == False:
            raise cx_Sensor_not_defined(self._type_ui)
        self._type_ui = cl_second_sensor_type.__SUPPORTED_SECOND_SENSOR_TYPES[self._type] 
        return(self._type_ui)
        
    def _is_valid(self):
        if cl_second_sensor_type.__SUPPORTED_SECOND_SENSOR_TYPES[self._type]:
            return(True)
        else:
            return(False)
        
    def _read_sensor_type(self):
        self._type = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.sensorsecondtype_key))

        cl_fact_logger.get_instance().debug('Second Sensor number is: ' + str(self._type))
        if self._is_valid() == False:
            raise cx_Sensor_not_defined(self._type_ui)        
        self._type_ui = self._get_type_ui()        
        cl_fact_logger.get_instance().debug("Second Sensor type is: " + str(self._type_ui))
    
    def get_sensor_type_ui(self):
        return(self._type_ui)
    
    def get_supported_types(self):
        # print('[%s]' % ', '.join(map(str, cl_second_sensor_type.__SUPPORTED_SECOND_SENSOR_TYPES )))
        pass


class cl_fact_main_sensor_type(ABC):
    __o_instance = None
    
    @classmethod
    def set_instance(self, i_instance):
        cl_fact_main_sensor_type.__o_instance = i_instance
        
    @classmethod        
    def get_instance(self):
        if cl_fact_main_sensor_type.__o_instance is not None:
            return(cl_fact_main_sensor_type.__o_instance)
        cl_fact_main_sensor_type.__o_instance = cl_main_sensor_type()
        return(cl_fact_main_sensor_type.__o_instance)

    def __init__(self):
        pass    

class cl_fact_second_sensor_type(ABC):
    __o_instance = None
    
    @classmethod
    def set_instance(self, i_instance):
        cl_fact_second_sensor_type.__o_instance = i_instance
        
    @classmethod        
    def get_instance(self):
        if cl_fact_second_sensor_type.__o_instance is not None:
            return(cl_fact_second_sensor_type.__o_instance)
        cl_fact_second_sensor_type.__o_instance = cl_second_sensor_type()
        return(cl_fact_second_sensor_type.__o_instance)

    def __init__(self):
        pass    
    
    
