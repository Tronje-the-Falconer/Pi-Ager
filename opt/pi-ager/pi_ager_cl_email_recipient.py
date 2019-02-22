from abc import ABC
import inspect
import pi_ager_names
import pi_ager_logging
from pi_ager_cl_database import cl_fact_database_config



from pi_ager_cx_exception import *

global logger
logger = pi_ager_logging.create_logger(__name__)

        
class cl_logic_email_recipient:
    def __init__(self):
        """
        Constructor for the email recipient class
        """ 
        logger.debug(pi_ager_logging.me())
        if "get_instance" not in inspect.stack()[1][3]:
            raise cx_direct_call("Please use factory class")
        
        """
        Read email reciepient's from the database
        """
        
        self.db_email_recipient = cl_fact_db_email_recipient().get_instance()
        self.it_email_recipient = self.db_email_recipient.read_data_from_db()
        
    def get_data(self):
        logger.debug(pi_ager_logging.me())
        
        return(self.it_email_recipient)
             

class cl_db_email_recipient:
    __o_dirty = True
    def __init__(self):
        pass
    
    def get_data(self):
        if self.is_dirty() is True:
            self.data = self.read_data_from_db()
            
        return(self.data)
    
    def build_select_statement(self):
        return('SELECT * FROM email_recipient WHERE active = "1" ')
    
    def read_data_from_db(self):
        """
        Read from db
        """
        database_config = cl_fact_database_config().get_instance()
        it_table = database_config.read_data_from_db(self.build_select_statement())
        cl_db_email_recipient.__o_dirty = False
        
        return it_table
        
    
    def is_dirty(self):
        return(cl_db_messenger.__o_dirty)
        pass

        
class th_logic_email(cl_logic_email_recipient):   

    
    def __init__(self):
        pass



class cl_fact_logic_email_recipient(ABC):
    __o_instance = None
    
    @classmethod
    def set_instance(self, i_instance):
        """
        Factory method to set the email recipient logic instance
        """
        logger.debug(pi_ager_logging.me())
        cl_fact_logic_email_recipient.__o_instance = i_instance
        
    @classmethod        
    def get_instance(self):
        """
        Factory method to get the email recipient logic instance
        """
        logger.debug(pi_ager_logging.me())
        if cl_fact_logic_email_recipient.__o_instance is not None:
            return(cl_fact_logic_email_recipient.__o_instance)
        cl_fact_logic_email_recipient.__o_instance = cl_logic_email_recipient()
        return(cl_fact_logic_email_recipient.__o_instance)

    def __init__(self):
        """
        Constructor email logic factory
        """
        logger.debug(pi_ager_logging.me())
        pass    
    
class cl_fact_db_email_recipient(ABC):
    __o_instance = None
    
    @classmethod
    def set_instance(self, i_instance):
        """
        Factory method to set the db email recipient instance
        """        
        logger.debug(pi_ager_logging.me())
        cl_fact_db_email_recipient.__o_instance = i_instance
        
    @classmethod        
    def get_instance(self):
        """
        Factory method to get the db email recipient instance
        """        
        logger.debug(pi_ager_logging.me())
        if cl_fact_db_email_recipient.__o_instance is not None:
            return(cl_fact_db_email_recipient.__o_instance)
        cl_fact_db_email_recipient.__o_instance = cl_db_email_recipient()
        return(cl_fact_db_email_recipient.__o_instance)

    def __init__(self):
        """
        Constructor db email recipient factory
        """        
        logger.debug(pi_ager_logging.me())
        pass    