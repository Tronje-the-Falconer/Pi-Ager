
from abc import ABC
import inspect
import pi_ager_names
#import pi_ager_logging
import requests


from main.pi_ager_cx_exception import *
from main.pi_ager_cl_logger import cl_fact_logger



import requests



class cl_logic_telegram:
    def __init__(self):
        """
        Constructor for the telegram class
        """ 
        # logger.debug(pi_ager_logging.me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        if "get_instance" not in inspect.stack()[1][3]:
            raise cx_direct_call("Please use factory class")
        
        """
        Read telegram setting from the database
        """
        bot_token = ''
        bot_chatID = ''
        
    def execute(self, alarm_message, alarm_subject):
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
        
        send_text = 'https://api.telegram.org/bot' + bot_token + '/sendMessage?chat_id=' + bot_chatID + '&parse_mode=Markdown&text=' + alarm_subject + alarm_message
        try:
            response = requests.get(send_text)
        except Exception as cx_error:
            #TODO err undefined!
            sendefehler = 'Error: unable to send telegram: {err}'.format(err=cx_error)
            # logger.error(sendefehler)
            cl_fact_logger.get_instance().error(sendefehler)

        return response.json()
    

                
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
        cl_fact_logger.get_instance().debug(pi_ager_logging.me())
        cl_fact_logic_telegram.__o_instance = i_instance
        
    @classmethod        
    def get_instance(self):
        """
        Factory method to get the telegram logic instance
        """
        cl_fact_logger.get_instance().debug(pi_ager_logging.me())
        if cl_fact_logic_telegram.__o_instance is not None:
            return(cl_fact_logic_telegram.__o_instance)
        cl_fact_logic_telegram.__o_instance = cl_logic_telegram()
        return(cl_fact_logic_telegram.__o_instance)

    def __init__(self):
        """
        Constructor telegram logic factory
        """
        cl_fact_logger.get_instance().debug(pi_ager_logging.me())
        pass    
    
