# -*- coding: utf-8 -*-

"""This class is the class for pi-ager pushover notifications. """
 
__author__ = "Claus Fischer"
__copyright__ = "Copyright 2020, The Pi-Ager Project"
__credits__ = ["Claus Fischer"]
__license__ = "GPL"
__version__ = "1.0.0"
__maintainer__ = "Claus Fischer"
__email__ = "DerBurgermeister@pi-ager.org"
__status__ = "Productive"
from abc import ABC
import inspect
import pi_ager_names
from pushover import Client
from main.pi_ager_cx_exception import *
from main.pi_ager_cl_logger import cl_fact_logger

class cl_logic_pushover:
    def __init__(self):
        """
        Constructor for the pushover class
        """ 
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        if "get_instance" not in inspect.stack()[1][3]:
            raise cx_direct_call("Please use factory class")
        
        """
        Read pushover setting from the database
        """
        self.user_key = 'ubesc9691hnx7q7p7xfvj93uw1a9wd'
        self.api_token = 'a3q7krzvz7s6dek5b998j2xqiiq5kv'
        self.client = Client(self.user_key, api_token=self.api_token)
        
    def execute(self, alarm_subject, alarm_message):
        # logger.debug(pi_ager_logging.me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        
        self.send_pushover(
            alarm_subject,
            alarm_message)        
        
    def send_pushover(self, alarm_subject, alarm_message):
        """
        Send pushover
        """
        # logger.debug(pi_ager_logging.me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        try:
            self.client.send_message(alarm_message, title=alarm_subject)
        except Exception as cx_error:
            #TODO err undefined!
            sendefehler = 'Error: unable to send pushover: {err}'.format(err=cx_error)
            # logger.error(sendefehler)
            cl_fact_logger.get_instance().error(sendefehler)

class cl_db_pushover:
    __o_dirty = True
    def __init__(self):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        
        data = self.read_data_from_db()
        pass
    def build_select_statement(self):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        return('SELECT * FROM pushover where active = 1 ')

    def get_data(self):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        if self.is_dirty() is True:
            self.data = self.read_data_from_db()
            
        return(self.data)
    
    def read_data_from_db(self):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        """
        Read from db
        """
        database_config = cl_fact_database_config().get_instance()
        it_table = database_config.read_data_from_db(self.build_select_statement())
        cl_db_messenger.__o_dirty = False
        
        return it_table
 
        
    
    def is_dirty(self):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        return(cl_db_messenger.__o_dirty)
        pass
    
class th_logic_pushover(cl_logic_pushover):   

    
    def __init__(self):
        pass

class cl_fact_logic_pushover(ABC):
    __o_instance = None
    
    @classmethod
    def set_instance(self, i_instance):
        """
        Factory method to set the pushover logic instance
        """
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        cl_fact_logic_pushover.__o_instance = i_instance
        
    @classmethod        
    def get_instance(self):
        """
        Factory method to get the pushover logic instance
        """
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        if cl_fact_logic_pushover.__o_instance is not None:
            return(cl_fact_logic_pushover.__o_instance)
        cl_fact_logic_pushover.__o_instance = cl_logic_pushover()
        return(cl_fact_logic_pushover.__o_instance)

    def __init__(self):
        """
        Constructor pushover logic factory
        """
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        pass    
    
