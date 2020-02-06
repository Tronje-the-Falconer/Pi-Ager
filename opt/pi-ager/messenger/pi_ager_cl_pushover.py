
from abc import ABC
import inspect
import pi_ager_names
#import pi_ager_logging
from pushover import Client



from main.pi_ager_cx_exception import *
from main.pi_ager_cl_logger import cl_fact_logger



import requests



class cl_logic_pushover:
    def __init__(self):
        """
        Constructor for the pushover class
        """ 
        # logger.debug(pi_ager_logging.me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        if "get_instance" not in inspect.stack()[1][3]:
            raise cx_direct_call("Please use factory class")
        
        """
        Read pushover setting from the database
        """
        self.user_key = 'ubesc9691hnx7q7p7xfvj93uw1a9wd'
        self.api_token = 'agfreo188gcafdw5s4eo2ujfub57za&user=ubesc9691hnx7q7p7xfvj93uw1a9wd'
        client = Client(self.user_key, api_token=self.api_token)
        
    def execute(self, alarm_message, alarm_subject):
        # logger.debug(pi_ager_logging.me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        
        self.send_pushover(
            self.client,
            self.bot_chatID,
            alarm_subject,
            alarm_message)        
        
    def send_pushover(self, bot_token,bot_chatID,alarm_subject, alarm_message):
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
    
