from abc import ABC
import inspect

from pi_ager_cx_exception import *
        
class cl_main_sensor_type:
    __SUPPORTED_MAIN_SENSOR_TYPES = ["SHT75", "SHT85", "SHT3x", "DHT11", "DHT22"]
    __NAME = 'Main_sensor'

    def __init__(self):
        if "get_instance" not in inspect.stack()[1][3]:
            raise cx_direct_call("Please use factory class")
#        if direct:
#            raise cx_direct_call(self, "Please use factory class")
        try:
            self._type = self._get_type_ui()
        except Exception as original_error:
            raise original_error

#        cl_main_sensor_type.__SUPPORTED_MAIN_SENSOR_TYPES = ["SHT75", "DHT11", "DHT22"]
#        cl_main_sensor_type.__NAME = 'Main_sensor'        
        

    def _get_type_ui(self):
        self._type_ui = 'SHT75'
        self._type = self._type_ui
        if self._is_valid() == False:
            raise cx_Sensor_not_defined(self.type_ui)
        return(self._type)
    
    def _is_valid(self):
        #print(self.get_supported_types())
        #print(self._type)
        if self._type in cl_main_sensor_type.__SUPPORTED_MAIN_SENSOR_TYPES:
            return(True)
        else:
            return(False)
            
        
    def get_type(self):
        if self._is_valid() == False:
            raise cx_Sensor_not_defined(self._type_ui)        
        return(self._type)
    
    def get_name(self):
        return(cl_main_sensor_type.__NAME)
    
    def get_supported_types(self):
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
    
