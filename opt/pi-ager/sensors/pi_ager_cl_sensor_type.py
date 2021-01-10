# -*- coding: utf-8 -*-
 
"""This class is for handling main sensor types."""

__author__ = "Claus Fischer"
__copyright__ = "Copyright 2019, The Pi-Ager Project"
__credits__ = ["Claus Fischer"]
__license__ = "GPL"
__version__ = "1.0.0"
__maintainer__ = "Claus Fischer"
__email__ = "DerBurgermeister@pi-ager.org"
__status__ = "Production"
from abc import ABC
import inspect

from main.pi_ager_cx_exception import *
from main.pi_ager_cl_logger import *
# import pi_ager_logging
from main.pi_ager_cl_logger import cl_fact_logger
import pi_ager_database
import pi_ager_names

# global logger
# logger = pi_ager_logging.create_logger(__name__) 

       
class cl_main_sensor_type:
    __SUPPORTED_MAIN_SENSOR_TYPES = {1: "DHT11",
                                     2: "DHT22",
                                     3: "SHT75",
                                     4: "SHT85",
                                     5: "SHT3x"}
    __NAME = 'Main_sensor'
    _type = 0
    _type_ui = ""
    def __init__(self):
        # logger.debug(cl_fact_logger.get_instance().me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        if "get_instance" not in inspect.stack()[1][3]:
            raise cx_direct_call("Please use factory class")
#        if direct:
#            raise cx_direct_call(self, "Please use factory class")

        try:
            self._read_sensor_type()
        except Exception as original_error:
            # logger.debug('Sensor type not found!')
            cl_fact_logger.get_instance().debug('Sensor type not found!')
            raise original_error

#        cl_main_sensor_type.__SUPPORTED_MAIN_SENSOR_TYPES = ["SHT75", "DHT11", "DHT22"]
#        cl_main_sensor_type.__NAME = 'Main_sensor'        
        

    def _get_type_ui(self):
        # logger.debug(cl_fact_logger.get_instance().me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        if self._is_valid() == False:
            raise cx_Sensor_not_defined(self._type_ui)
        self._type_ui = cl_main_sensor_type.__SUPPORTED_MAIN_SENSOR_TYPES[self._type] 
         
    
        return(self._type_ui)
    
    def _is_valid(self):
        # logger.debug(cl_fact_logger.get_instance().me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        if cl_main_sensor_type.__SUPPORTED_MAIN_SENSOR_TYPES[self._type]:
#        if self._type in cl_main_sensor_type.__SUPPORTED_MAIN_SENSOR_TYPES:
            return(True)
        else:
            return(False)
            
        
    def _read_sensor_type(self):
        # logger.debug(cl_fact_logger.get_instance().me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        self._type = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.sensortype_key))

        # logger.info('Sensor number is: ' + str(self._type))
        cl_fact_logger.get_instance().info('Sensor number is: ' + str(self._type))
        if self._is_valid() == False:
            raise cx_Sensor_not_defined(self._type_ui)        
        self._type_ui = self._get_type_ui()        
        # logger.debug("Sensor type is: " + str(self._type_ui))
        cl_fact_logger.get_instance().debug("Sensor type is: " + str(self._type_ui))
        return()
    
    def get_sensor_type(self):
        # logger.debug(cl_fact_logger.get_instance().me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        return(self._type)
    
    def get_sensor_type_ui(self):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        # logger.debug(cl_fact_logger.get_instance().me())
        return(self._type_ui)
    
    def get_name(self):
        # logger.debug(cl_fact_logger.get_instance().me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        return(cl_main_sensor_type.__NAME)
    
    def get_supported_types(self):
        # logger.debug(cl_fact_logger.get_instance().me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        print('[%s]' % ', '.join(map(str, cl_main_sensor_type.__SUPPORTED_MAIN_SENSOR_TYPES )))

class cl_second_sensor_type(cl_main_sensor_type):
    __SUPPORTED_SECOND_SENSOR_TYPES = {4: "SHT85",
                                       5: "SHT3x"}
    __NAME = 'Second_sensor'
    _type = 0
    _type_ui = ""
    
    """
    def __init__(self):
        # logger.debug(cl_fact_logger.get_instance().me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        if "get_instance" not in inspect.stack()[1][3]:
            raise cx_direct_call("Please use factory class")
#        if direct:
#            raise cx_direct_call(self, "Please use factory class")

        try:
            self._read_sensor_type()
        except Exception as original_error:
            # logger.debug('Sensor type not found!')
            cl_fact_logger.get_instance().debug('Sensor type not found!')
            raise original_error

#        cl_main_sensor_type.__SUPPORTED_MAIN_SENSOR_TYPES = ["SHT75", "DHT11", "DHT22"]
#        cl_main_sensor_type.__NAME = 'Main_sensor'        
    """   
    def _get_type_ui(self):
        # logger.debug(cl_fact_logger.get_instance().me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        if self._is_valid() == False:
            raise cx_Sensor_not_defined(self._type_ui)
        self._type_ui = cl_second_sensor_type.__SUPPORTED_SECOND_SENSOR_TYPES[self._type] 
         
    
        return(self._type_ui)
    def _is_valid(self):
        # logger.debug(cl_fact_logger.get_instance().me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        if cl_second_sensor_type.__SUPPORTED_SECOND_SENSOR_TYPES[self._type]:
#        if self._type in cl_main_sensor_type.__SUPPORTED_MAIN_SENSOR_TYPES:
            return(True)
        else:
            return(False)
            
        
    def _read_sensor_type(self):
        # logger.debug(cl_fact_logger.get_instance().me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        self._type = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.sensorsecondtype_key))

        # logger.info('Sensor number is: ' + str(self._type))
        cl_fact_logger.get_instance().info('Second Sensor number is: ' + str(self._type))
        if self._is_valid() == False:
            raise cx_Sensor_not_defined(self._type_ui)        
        self._type_ui = self._get_type_ui()        
        # logger.debug("Sensor type is: " + str(self._type_ui))
        cl_fact_logger.get_instance().debug("Second Sensor type is: " + str(self._type_ui))
        return()
    """
    def get_sensor_type(self):
        # logger.debug(cl_fact_logger.get_instance().me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        return(self._type)
    
    def get_sensor_type_ui(self):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        # logger.debug(cl_fact_logger.get_instance().me())
        return(self._type_ui)
    
    def get_name(self):
        # logger.debug(cl_fact_logger.get_instance().me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        return(cl_main_sensor_type.__NAME)
    """
    def get_supported_types(self):
        # logger.debug(cl_fact_logger.get_instance().me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        print('[%s]' % ', '.join(map(str, cl_second_sensor_type.__SUPPORTED_SECOND_SENSOR_TYPES )))




   
class th_main_sensor_type(cl_main_sensor_type):   

    __NAME = 'Main_sensor'
    def __init__(self):
        self._type = 3 #SHT75
        self._type_ui = "SHT75"
        self.is_valid = True
        
        pass
  
    def _is_valid(self):
        if self.is_valid == True:
            return(True)
        else:
            return(False)

    def get_type(self):
        if self.is_valid == False:
            raise cx_Sensor_not_defined("This sensor is not defined")        
        return(self._type)
    def _get_type_ui(self):
        return(self._type_ui)
    def get_name(self):
        # logger.debug(cl_fact_logger.get_instance().me())
        #cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        return(th_main_sensor_type.__NAME)
    

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
    
    
