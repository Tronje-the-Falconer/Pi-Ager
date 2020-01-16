# -*- coding: utf-8 -*-
 
"""This class is for defining the e-Mail Server."""

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
from messenger.pi_ager_cl_crypt import cl_fact_help_crypt
from main.pi_ager_cl_database import cl_fact_database_config

from main.pi_ager_cx_exception import *
from main.pi_ager_cl_logger import cl_fact_logger
        
class cl_logic_email_server:
    def __init__(self):
        """
        Constructor for the email server class
        """ 
        # logger.debug(pi_ager_logging.me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        if "get_instance" not in inspect.stack()[1][3]:
            raise cx_direct_call("Please use factory class")
        
        
        """
        Read email-server settings from the database
        """
        self.db_email_server = cl_fact_db_email_server().get_instance()
        self.it_email_server = self.db_email_server.read_data_from_db()
        
    
    def get_data(self):
        # logger.debug(pi_ager_logging.me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        
        return(self.it_email_server)

class cl_db_email_server:
    __o_dirty = True
    def __init__(self):
        pass
    
    def get_data(self):
        if self.is_dirty() is True:
            self.data = self.read_data_from_db()
            
        return(self.data)
    
    def build_select_statement(self):
        return('SELECT * FROM email_server')
    
    def read_data_from_db(self):
        """
        Read from db
        """
        database_config = cl_fact_database_config().get_instance()
        it_table = database_config.read_data_from_db(self.build_select_statement())
        cl_db_email_server.__o_dirty = False
        
        return it_table
        
        
    
    def is_dirty(self):
        return(cl_db_messenger.__o_dirty)
        pass


class th_logic_email_server(cl_logic_email_server):   

    
    def __init__(self):
        pass



class cl_fact_logic_email_server(ABC):
    __o_instance = None
    
    @classmethod
    def set_instance(self, i_instance):
        """
        Factory method to set the email server logic instance
        """
        # logger.debug(pi_ager_logging.me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        cl_fact_logic_email_server.__o_instance = i_instance
        
    @classmethod        
    def get_instance(self):
        """
        Factory method to get the email server logic instance
        """
        # logger.debug(pi_ager_logging.me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        if cl_fact_logic_email_server.__o_instance is not None:
            return(cl_fact_logic_email_server.__o_instance)
        cl_fact_logic_email_server.__o_instance = cl_logic_email_server()
        return(cl_fact_logic_email_server.__o_instance)

    def __init__(self):
        """
        Constructor email server logic factory
        """
        # logger.debug(pi_ager_logging.me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        pass    
    
class cl_fact_db_email_server(ABC):
    __o_instance = None
    
    @classmethod
    def set_instance(self, i_instance):
        """
        Factory method to set the db email server instance
        """        
        # logger.debug(pi_ager_logging.me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        cl_fact_db_email_server.__o_instance = i_instance
        
    @classmethod        
    def get_instance(self):
        """
        Factory method to get the db messenger instance
        """        
        # logger.debug(pi_ager_logging.me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        if cl_fact_db_email_server.__o_instance is not None:
            return(cl_fact_db_email_server.__o_instance)
        cl_fact_db_email_server.__o_instance = cl_db_email_server()
        return(cl_fact_db_email_server.__o_instance)

    def __init__(self):
        """
        Constructor logic messenger factory
        """        
        # logger.debug(pi_ager_logging.me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        pass    