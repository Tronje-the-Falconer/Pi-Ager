# -*- coding: utf-8 -*-

"""This class is the class for pi-ager pushover notifications. """
 
__author__ = "Claus Fischer"
__copyright__ = "Copyright 2020, The Pi-Ager Project"
__credits__ = ["Claus Fischer"]
__license__ = "GPL"
__version__ = "1.0.1"
__maintainer__ = "Claus Fischer"
__email__ = "DerBurgermeister@pi-ager.org"
__status__ = "Productive"

from abc import ABC
import inspect
import pi_ager_names
from main.pi_ager_cl_database import cl_fact_database_config, cl_ab_database_config
from pushover import Pushover
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
        
        self.db_pushover = cl_fact_db_pushover().get_instance()
        self.it_pushover = self.db_pushover.read_data_from_db()
        
   
        
        
    def execute(self, alarm_subject, alarm_message):
        # logger.debug(pi_ager_logging.me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        
        try:
            if self.it_pushover:                
                user_key  = str(self.it_pushover[0]['user_key'])
                api_token = str(self.it_pushover[0]['api_token'])
                cl_fact_logger.get_instance().debug('user_key  = ' + user_key)
                cl_fact_logger.get_instance().debug('api_token = ' + api_token)
        except IndexError as cx_error:
            raise(cx_error)
        
        po = Pushover(api_token)
        po.user(user_key)
        
        self.send_pushover(
            po,
            alarm_subject,
            alarm_message)        
        
    def send_pushover(self, po, alarm_subject, alarm_message):
        """
        Send pushover
        """
        
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        try:
            po = Pushover(self.api_token)
            po.user(self.user_key)
            msg = po.msg(alarm_message)
            msg.set("title", alarm_subject)

            po.send(msg)

        except Exception as cx_error:
            #TODO err undefined!
            sendefehler = 'Error: unable to send pushover: {err}'.format(err=cx_error)
            # logger.error(sendefehler)
            cl_fact_logger.get_instance().error(sendefehler)

class cl_db_pushover(cl_ab_database_config):

    def build_select_statement(self):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        return('SELECT * FROM config_pushover where active = 1 ')
    
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
    
class cl_fact_db_pushover(ABC):
    __o_instance = None
    
    @classmethod
    def set_instance(self, i_instance):
        """
        Factory method to set the db pushover instance
        """        
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        cl_fact_db_messenger.__o_instance = i_instance
        
    @classmethod        
    def get_instance(self):
        """
        Factory method to get the db pushover instance
        """        
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        if cl_fact_db_pushover.__o_instance is not None:
            return(cl_fact_db_pushover.__o_instance)
        cl_fact_db_pushover.__o_instance = cl_db_pushover()
        return(cl_fact_db_pushover.__o_instance)

    def __init__(self):
        """
        Constructor logic messenger factory
        """        
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        pass    
