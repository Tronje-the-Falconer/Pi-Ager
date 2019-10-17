from abc import ABC, abstractmethod
import inspect

from pi_ager_cl_sensor_type import cl_fact_main_sensor_type
from pi_ager_cl_i2c_bus import cl_fact_i2c_bus_logic
from pi_ager_cx_exception import *

class cl_ab_humitity_sensor(ABC):
    __max_humitity_errors = 10
    def __init__(self):
        self.m_current_humitity = 0
        self.m_old_humitity = 0
        self.m_state = 0

class cl_ab_temp_sensor(ABC):
    __max_temp_errors = 10
    __TEMP_SENSOR_STATES = ["Valid", "Invalid"]
    def __init__(self):
        self.m_current_temperature = 0
        self.m_old_temperature = 0
        self.m_state = 1
    
    def get_current_temperature(self):
        return(self.m_current_temperature)
    
    def get_old_temperature(self):
        return(self.m_old_temperature)
    
    @abstractmethod
    def read_temperature(self):
        pass
    
    def get_temperature_state(self):
        return(cl_ab_temp_sensor.__TEMP_SENSOR_STATES[self.m_state])
    
    def _check_temperature(self):
        if abs(self.m_current_temperature - self.m_old_temperature) > 10:
            self.m_state = 1
        else:
            self.m_state = 0
        
    
class cl_main_sensor(cl_ab_temp_sensor, cl_ab_humitity_sensor):
    
    __error_counter = 0
    __measuring_intervall = 300
    

    def __init__(self, o_sensor_type):
        self.o_sensor_type = o_sensor_type
        
    def get_sensor_type(self):
        return( cl_fact_main_sensor_type.get_instance( ) )
    
    def get_dewpoint(self):
        pass
        
    def execute_soft_reset(self):
        pass
    

    
class cl_main_sensor_sht75(cl_main_sensor):
    
    def __init__(self, o_sensor_type):
        if "get_instance" not in inspect.stack()[1][3]:
            raise cx_direct_call(self,"Please use factory class" )
        self.o_sensor_type = o_sensor_type
        pass
    m_old_temperature = 0
    m_current_temperature = 0
    
    def read_temperature(self):
        cl_main_sensor.read_temperature(self)
   
        if self.m_old_temperature is None:
            self.m_old_temperature = 0
        else:
      
            self.m_old_temperature = self.m_current_temperature
            
        self.m_current_temperature = 100.0
     
        self._check_temperature()

class cl_main_sensor_sht3x(cl_main_sensor):
    
    def __init__(self, o_sensor_type):
        if "get_instance" not in inspect.stack()[1][3]:
            raise cx_direct_call(self,"Please use factory class" )
        self.o_sensor_type = o_sensor_type
        pass
    m_old_temperature = 0
    m_current_temperature = 0
    self.i2c_bus = cl_fact_i2c_bus_logic.get_instance()
    self.sht3x = cl_fact_i2c_sensor.get_instance(i2c_bus)
    self.sht3x.i2c_start_command()
    
    def read_data(self):
        self.sht3x.get_data()
    
    def get_temperature(self):
        
        self.read_temperature(self)
        cl_main_sensor.read
   
        if self.m_old_temperature is None:
            self.m_old_temperature = 0
        else:
      
            self.m_old_temperature = self.m_current_temperature
            
        self.m_current_temperature = 100.0
     
        self._check_temperature()

  
class th_main_sensor(cl_main_sensor_sht75):
#    SUPPORTED_MAIN_SENSOR_TYPES = ["SHT75", "DHT11", "DHT22"]
    NAME = 'Main_sensor'
    
    
    def __init__(self):
    
        self.get_type_raise = False
        self._type = "SHT"
        
    def get_type(self):
        if self.get_type_raise == True:
            raise cx_Sensor_not_defined(self._type_ui)        
        return(self._type)

    
class cl_fact_main_sensor:
    fact_main_sensor_type = cl_fact_main_sensor_type()
#    Only a singleton instance for main_sensor
    __o_sensor_type = fact_main_sensor_type.get_instance()
    __o_instance = None
    @classmethod        
    def get_instance(self):
        if cl_fact_main_sensor.__o_instance is not None :
            return(cl_fact_main_sensor.__o_instance)
        try:
            if   cl_fact_main_sensor.__o_sensor_type.get_type( ) == 'SHT75':
                cl_fact_main_sensor.__o_instance = self.get_instance_sensor_sht75()
            elif cl_fact_main_sensor.__o_sensor_type.get_type( ) == 'SHT3x':
                cl_fact_main_sensor.__o_instance = self.get_instance_sensor_sht3x()
            elif cl_fact_main_sensor.__o_sensor_type.get_type( ) == 'SHT85':
                cl_fact_main_sensor.__o_instance = self.get_instance_sensor_sht85()   
            elif cl_fact_main_sensor.__o_sensor_type.get_type( ) == 'DHT22':
                cl_fact_main_sensor.__o_instance = self.get_instance_sensor_sht75()
        except Exception as original_error:
            raise original_error        
        return(cl_fact_main_sensor.__o_instance)

    @classmethod
    def set_instance(self, i_instance):
        cl_fact_main_sensor.__o_instance = i_instance
            
    @classmethod        
    def get_instance_sensor_sht75(self):
        return(cl_main_sensor_sht75(self.__o_sensor_type))
    @classmethod        
    def get_instance_sensor_sht3x(self):
        return(cl_main_sensor_sht3x(self.__o_sensor_type))    
    
    
    def __init__(self):
        pass    

