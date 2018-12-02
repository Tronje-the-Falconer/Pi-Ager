from abc import ABC
import inspect
import pi_ager_names
import pi_ager_logging
import pi_ager_gpio_config
import RPi.GPIO as gpio
from time import sleep

from pi_ager_cx_exception import *

global logger
logger = pi_ager_logging.create_logger(__name__)

        
class cl_alarm:
    def __init__(self):
        #logger.info("Line " + str(inspect.stack()[0][2]) + ": " + __name__  + "->"+ str(inspect.stack()[0][3]))
        #logger.info("Line " + str(inspect.stack()[0][1]) + ": " + __name__  + "->"+ str(inspect.stack()[0][4]))
        #logger.info("Line " + str(inspect.stack()[0][5]) + ": " + __name__  + "->"+ str(inspect.stack()[0][0]))
        
        
        #prev_frame = inspect.currentframe().f_back
#        current_frame = inspect.currentframe()
 #       the_class  = current_frame.f_locals["self"].__class__
  #      the_method = current_frame.f_code.co_name
   #     the_line   = current_frame.f_code.co_firstlineno
        
        logger.info(pi_ager_logging.me())
        
        
        
        if "get_instance" not in inspect.stack()[1][3]:
            raise cx_direct_call("Please use factory class")
        
        
        gpio.setmode(pi_ager_names.board_mode)
        gpio.setwarnings(False)
        gpio.setup(pi_ager_names.gpio_alarm, gpio.OUT )
        self.alarm_gpio = pi_ager_names.gpio_alarm
        
        self.Duration  = 3
        self.Sleep     = 0.5
        self.High_time = 1
        self.Low_time  = 1
        
    def set_alarm_type(self,DutyCycle, Frequency, Duration, Sleep):
        #logger.info("Line " + str(inspect.stack()[0][2]) + ": " + __name__  + "->"+ str(inspect.stack()[0][3]))
        logger.info(pi_ager_logging.me())
        self.DutyCycle = DutyCycle
        self.Frequency = Frequency
        self.Duration  = Duration
        self.Sleep     = Sleep

    def execute_alarm(self):
        #logger.info("Line " + str(inspect.stack()[0][2]) + ": " + __name__  + "->"+ str(inspect.stack()[0][3]))
        logger.info(pi_ager_logging.me())
        for x in range(0, self.Duration):
           gpio.output(self.alarm_gpio, True)
           sleep(self.High_time)
           gpio.output(self.alarm_gpio, False)
           sleep(self.Low_time)

    def execute_short(self, Duration):
        #logger.info("Line " + str(inspect.stack()[0][2]) + ": " + __name__  + "->"+ str(inspect.stack()[0][3]))
        logger.info(pi_ager_logging.me())
        self.Duration = Duration
        self.Sleep    = 0.5
        self.High_time = 0.5
        self.Low_time  = 0.5
        self.execute_alarm()
           
    def execute_middle(self, Duration):
        #logger.info("Line " + str(inspect.stack()[0][2]) + ": " + __name__  + "->"+ str(inspect.stack()[0][3]))
        logger.info(pi_ager_logging.me())
        self.Duration = Duration
        self.Sleep    = 0.5
        self.High_time = 1
        self.Low_time  = 1
        self.execute_alarm()
        
    def execute_long(self, Duration):
        #logger.info("Line " + str(inspect.stack()[0][2]) + ": " + __name__  + "->"+ str(inspect.stack()[0][3]))
        logger.info(pi_ager_logging.me())
        self.Duration = Duration
        self.Sleep    = 0.5
        self.High_time = 2
        self.Low_time  = 2
        self.execute_alarm()
        
class th_alarm(cl_alarm):   

    
    def __init__(self):
        pass


class cl_fact_alarm(ABC):
    __o_instance = None
    
    @classmethod
    def set_instance(self, i_instance):
        #logger.info("Line " + str(inspect.stack()[0][2]) + ": " + __name__  + "->"+ str(inspect.stack()[0][3]))
        logger.info(pi_ager_logging.me())
        cl_fact_alarm.__o_instance = i_instance
        
    @classmethod        
    def get_instance(self):
        #logger.info("Line " + str(inspect.stack()[0][2]) + ": " + __name__  + "->"+ str(inspect.stack()[0][3]))
        logger.info(pi_ager_logging.me())
        if cl_fact_alarm.__o_instance is not None:
            return(cl_fact_alarm.__o_instance)
        cl_fact_alarm.__o_instance = cl_alarm()
        return(cl_fact_alarm.__o_instance)

    def __init__(self):
        #logger.info("Line " + str(inspect.stack()[0][2]) + ": " + __name__  + "->"+ str(inspect.stack()[0][3]))
        logger.info(pi_ager_logging.me())
        pass    
    
