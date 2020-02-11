# -*- coding: utf-8 -*-

"""This class is the class for pi-ager telegram notifications. """
 
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
from main.pi_ager_cl_database import cl_fact_database_config, cl_ab_database_config
import requests
from main.pi_ager_cx_exception import *
from main.pi_ager_cl_logger import cl_fact_logger
import requests

class cl_logic_telegram:
    def __init__(self):
        """
        Constructor for the telegram class
        """ 

        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        if "get_instance" not in inspect.stack()[1][3]:
            raise cx_direct_call("Please use factory class")
        
        """
        Read telegram setting from the database
        """
        self.db_telegram = cl_fact_db_telegram().get_instance()
        self.it_telegram = self.db_telegram.read_data_from_db()
        try:
            if self.it_telegram: 
                cl_fact_logger.get_instance().debug('bot_token  = ' + str(self.it_telegram[0]['bot_token']))
                cl_fact_logger.get_instance().debug('bot_chatID = ' + str(self.it_telegram[0]['bot_chatID']))
            
            self.bot_token  = str(self.it_telegram[0]['bot_token'])
            self.bot_chatID = str(self.it_telegram[0]['bot_chatID'])
        except IndexError as cx_error:
            raise(cx_error)
    def execute(self, alarm_subject, alarm_message):
        # logger.debug(pi_ager_logging.me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        
        self.send_telegram(
            self.bot_token,
            self.bot_chatID,
            alarm_subject,
            alarm_message)        
        
    def send_telegram(self, bot_token,bot_chatID,alarm_subject, alarm_message):
        """
        Send telegram
        """
        # logger.debug(pi_ager_logging.me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        alarm_message = alarm_message.replace("_", "\_")
        alarm_subject = alarm_subject.replace("_", "\_")
        send_text = str('https://api.telegram.org/bot' + bot_token + '/sendMessage?chat_id=' + bot_chatID + '&parse_mode=Markdown&text=' + alarm_subject + alarm_message)
        #cl_fact_logger.get_instance().debug(send_text)
        try:
            response = requests.get(send_text)
        except Exception as cx_error:
            sending_error = 'Error: unable to send telegram: {err}'.format(err=cx_error)
            # logger.error(sendefehler)
            cl_fact_logger.get_instance().error(sending_error)
        cl_fact_logger.get_instance().debug(response.json())
        return response.json()
class cl_db_telegram(cl_ab_database_config):

    def build_select_statement(self):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        return('SELECT * FROM config_telegram where active = 1 ')
    
class th_logic_telegram(cl_logic_telegram):   

    def __init__(self):
        pass

class cl_fact_logic_telegram(ABC):
    __o_instance = None
    
    @classmethod
    def set_instance(self, i_instance):
        """
        Factory method to set the telegram logic instance
        """
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        cl_fact_logic_telegram.__o_instance = i_instance
        
    @classmethod        
    def get_instance(self):
        """
        Factory method to get the telegram logic instance
        """
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        if cl_fact_logic_telegram.__o_instance is not None:
            return(cl_fact_logic_telegram.__o_instance)
        cl_fact_logic_telegram.__o_instance = cl_logic_telegram()
        return(cl_fact_logic_telegram.__o_instance)

    def __init__(self):
        """
        Constructor telegram logic factory
        """
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        pass    
    
class cl_fact_db_telegram(ABC):
    __o_instance = None
    
    @classmethod
    def set_instance(self, i_instance):
        """
        Factory method to set the db telegram instance
        """        
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        cl_fact_db_messenger.__o_instance = i_instance
        
    @classmethod        
    def get_instance(self):
        """
        Factory method to get the db telegram instance
        """        
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        if cl_fact_db_telegram.__o_instance is not None:
            return(cl_fact_db_telegram.__o_instance)
        cl_fact_db_telegram.__o_instance = cl_db_telegram()
        return(cl_fact_db_telegram.__o_instance)

    def __init__(self):
        """
        Constructor logic messenger factory
        """        
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        pass    
