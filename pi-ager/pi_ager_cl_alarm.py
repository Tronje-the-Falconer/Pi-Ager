from abc import ABC
import inspect
import pi_ager_names
import pi_ager_gpio_config
import RPi.GPIO as gpio
from time import sleep

from pi_ager_cx_exception import *
        
class cl_alarm:
    def __init__(self):
        
        if "get_instance" not in inspect.stack()[1][3]:
            raise cx_direct_call("Please use factory class")
        
        
        gpio.setmode(pi_ager_names.board_mode)
        gpio.setwarnings(False)
        gpio.setup(pi_ager_names.gpio_alarm, gpio.OUT )
        self.alarm_gpio = pi_ager_names.gpio_alarm
        
        
        #self.pwm = gpio.PWM(self.alarm_gpio, 1)
        #self.DutyCycle = 100
        #self.Frequency = 0.05
        self.Duration  = 3
        self.Sleep     = 0.5
        self.High_time = 1
        self.Low_time  = 1
        
    def set_alarm_type(self,DutyCycle, Frequency, Duration, Sleep):
        self.DutyCycle = DutyCycle
        self.Frequency = Frequency
        self.Duration  = Duration
        self.Sleep     = Sleep

    def execute(self):
        """
        self.pwm.start(0)
        self.pwm.ChangeDutyCycle(self.DutyCycle)
        self.pwm.ChangeFrequency(self.Frequency)
                
        for x in range(0, self.Duration):
            self.pwm.start(1)
            sleep(self.Sleep)
            self.pwm.stop()
       """
        for x in range(0, self.Duration):
           gpio.output(self.alarm_gpio, True)
           sleep(self.High_time)
           gpio.output(self.alarm_gpio, False)
           sleep(self.Low_time)
           
class th_alarm(cl_alarm):   

    
    def __init__(self):
        pass


class cl_fact_alarm(ABC):
    __o_instance = None
    
    @classmethod
    def set_instance(self, i_instance):
        cl_fact_alarm.__o_instance = i_instance
        
    @classmethod        
    def get_instance(self):
        if cl_fact_alarm.__o_instance is not None:
            return(cl_fact_alarm.__o_instance)
        cl_fact_alarm.__o_instance = cl_alarm()
        return(cl_fact_alarm.__o_instance)

    def __init__(self):
        pass    
    
