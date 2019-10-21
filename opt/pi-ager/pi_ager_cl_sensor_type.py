from abc import ABC
import inspect

from pi_ager_cx_exception import *
import pi_ager_logging

global logger
logger = pi_ager_logging.create_logger(__name__) 

       
class cl_main_sensor_type:
    __SUPPORTED_MAIN_SENSOR_TYPES = {"DHT11": 1,
                                     "DHT22": 2,
                                     "SHT75": 3,
                                     "SHT3x": 4,
                                     "SHT85": 5}
    __NAME = 'Main_sensor'

    def __init__(self):
        logger.debug(pi_ager_logging.me())
        if "get_instance" not in inspect.stack()[1][3]:
            raise cx_direct_call("Please use factory class")
#        if direct:
#            raise cx_direct_call(self, "Please use factory class")
        _type = 0
        _type_ui = ""
        try:
            self.read_sensor_type()
        except Exception as original_error:
            raise original_error

#        cl_main_sensor_type.__SUPPORTED_MAIN_SENSOR_TYPES = ["SHT75", "DHT11", "DHT22"]
#        cl_main_sensor_type.__NAME = 'Main_sensor'        
        

    def _get_type_ui(self):
        logger.debug(pi_ager_logging.me())
        if self._is_valid() == False:
            raise cx_Sensor_not_defined(self.type_ui)
        self._cl_main_sensor_type.__SUPPORTED_MAIN_SENSOR_TYPES.get(self._type, -1) 
         
    
        return(self._type)
    
    def _is_valid(self):
        logger.debug(pi_ager_logging.me())
        if cl_main_sensor_type.__SUPPORTED_MAIN_SENSOR_TYPES.get(self._type, -1) != -1:
#        if self._type in cl_main_sensor_type.__SUPPORTED_MAIN_SENSOR_TYPES:
            return(True)
        else:
            return(False)
            
        
    def read_sensor_type(self):
        logger.debug(pi_ager_logging.me())
        if self._is_valid() == False:
            raise cx_Sensor_not_defined(self._type_ui)        
        self._type = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.sensortype_key)
        self._type_ui = self._get_type_ui()
        logger.debug("Sensor type is :", self._type_ui)    
        return()
    
    def get_sensor_type(self):
        logger.debug(pi_ager_logging.me())
        return(self._type)
    
    def get_name(self):
        logger.debug(pi_ager_logging.me())
        return(cl_main_sensor_type.__NAME)
    
    def get_supported_types(self):
        logger.debug(pi_ager_logging.me())
        print('[%s]' % ', '.join(map(str, cl_main_sensor_type.__SUPPORTED_MAIN_SENSOR_TYPES )))
   
class th_main_sensor_type(cl_main_sensor_type):   

    
    def __init__(self):
        self._type = ""
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
    
