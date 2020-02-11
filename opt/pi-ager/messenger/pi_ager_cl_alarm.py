# -*- coding: utf-8 -*-

"""This class is for alarm notification."""

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
import pi_ager_names
import pi_ager_gpio_config
from main.pi_ager_cl_database import cl_fact_database_config, cl_ab_database_config
import RPi.GPIO as gpio
from time import sleep

from main.pi_ager_cx_exception import *

from main.pi_ager_cl_logger import cl_fact_logger
# import pi_ager_logging

# global logger
# logger = pi_ager_logging.create_logger(__name__)
        
class cl_logic_alarm:
    def __init__(self):
        # logger.debug(pi_ager_logging.me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
   
        if "get_instance" not in inspect.stack()[1][3]:
            raise cx_direct_call("Please use factory class")
      
        gpio.setmode(pi_ager_gpio_config.board_mode)
        gpio.setwarnings(False)
        gpio.setup(pi_ager_gpio_config.gpio_alarm, gpio.OUT )
        self.alarm_gpio = pi_ager_gpio_config.gpio_alarm
        """
        Read alarm setting from the database
        """
        
        self.db_alarm = cl_fact_db_alarm().get_instance()
        self.it_alarm = self.db_alarm.read_data_from_db()


    def execute_alarm(self, alarm):
        # logger.debug(pi_ager_logging.me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        cl_fact_logger.get_instance().debug("Check Alarm: " + alarm)
        for item in self.it_alarm:
            if item.get('alarm') == alarm:
                cl_fact_logger.get_instance().debug("Found Alarm: " + alarm + " with replication " + str(item['replication']) + ", high_time " + str(item['high_time']) + ", low_time " + str(item['low_time']))
                for x in range(0, item['replication']):
                   gpio.output(self.alarm_gpio, True)
                   sleep(item['high_time'])
                   gpio.output(self.alarm_gpio, False)
                   sleep(item['low_time'])

class cl_db_alarm(cl_ab_database_config):

    def build_select_statement(self):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        return('SELECT * FROM config_alarm ')
    
class th_logic_alarm(cl_logic_alarm):   

    
    def __init__(self):
        pass


class cl_fact_logic_alarm(ABC):
    __o_instance = None
    
    @classmethod
    def set_instance(self, i_instance):
        cl_fact_logic_alarm.__o_instance = i_instance
        
    @classmethod        
    def get_instance(self):
        if cl_fact_logic_alarm.__o_instance is not None:
            return(cl_fact_logic_alarm.__o_instance)
        cl_fact_logic_alarm.__o_instance = cl_logic_alarm()
        return(cl_fact_logic_alarm.__o_instance)

    def __init__(self):
        pass    
    
class cl_fact_db_alarm(ABC):
    __o_instance = None
    
    @classmethod
    def set_instance(self, i_instance):
        """
        Factory method to set the db alarm instance
        """        
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        cl_fact_db_messenger.__o_instance = i_instance
        
    @classmethod        
    def get_instance(self):
        """
        Factory method to get the db alarm instance
        """        
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        if cl_fact_db_alarm.__o_instance is not None:
            return(cl_fact_db_alarm.__o_instance)
        cl_fact_db_alarm.__o_instance = cl_db_alarm()
        return(cl_fact_db_alarm.__o_instance)

    def __init__(self):
        """
        Constructor logic messenger factory
        """        
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        pass    
