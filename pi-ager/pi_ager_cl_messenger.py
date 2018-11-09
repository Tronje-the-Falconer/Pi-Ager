from abc import ABC
import inspect
import pi_ager_names
import pi_ager_logging
from pi_ager_cl_alarm import cl_fact_alarm

from pi_ager_cx_exception import *

global logger
logger = pi_ager_logging.create_logger(__name__)
logger.debug('logging initialised')

        
class cl_messenger:
    
    def __init__(self, cx_error):
        
        if "get_instance" not in inspect.stack()[1][3]:
            raise cx_direct_call("Please use factory class")
        
        
        self.cx_error  = cx_error
        self.alarm = cl_fact_alarm().get_instance()
        self.exception_known = False
        
    def send(self):
        logger.exception(self.cx_error, exc_info = True)
        logger.info("Exception raised: " + type(self.cx_error).__name__  ) 
        logger.info('Check Exception for Alarm:  ' + str(self.cx_error.__class__.__name__ ))
        if str(self.cx_error.__class__.__name__ ) == 'cx_Sensor_not_defined':
            self.alarm.execute()
            self.exception_known = True
        if str(self.cx_error.__class__.__name__ ) == 'OperationalError':
            self.alarm.execute()
            self.exception_known = True
        
        
        logger.info('Check Exception for E-Mail: ' + str(self.cx_error.__class__.__name__))
        logger.info('No Email implemented')
        return(self.exception_known)
    
class th_messenger(cl_messenger):   

    
    def __init__(self):
        pass


class cl_fact_messenger(ABC):
    __o_instance = None
    
    @classmethod
    def set_instance(self, i_instance):
        cl_fact_messenger.__o_instance = i_instance
        
    @classmethod        
    def get_instance(self, Exception):
        if cl_fact_messenger.__o_instance is not None:
            return(cl_fact_messenger.__o_instance)
        cl_fact_messenger.__o_instance = cl_messenger(Exception)
        return(cl_fact_messenger.__o_instance)

    def __init__(self):
        pass    
    
