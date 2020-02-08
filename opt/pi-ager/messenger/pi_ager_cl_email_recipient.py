# -*- coding: utf-8 -*-
 
"""This class is for handling the various recipients for e-Mail notification."""

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
from main.pi_ager_cl_logger import cl_fact_logger
from main.pi_ager_cl_database import cl_fact_database_config, cl_ab_database_config
from main.pi_ager_cx_exception import *

class cl_logic_email_recipient:
    def __init__(self):
        """
        Constructor for the email recipient class
        """ 
        
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        if "get_instance" not in inspect.stack()[1][3]:
            raise cx_direct_call("Please use factory class")
        
        """
        Read email reciepient's from the database
        """
        
        self.db_email_recipient = cl_fact_db_email_recipient().get_instance()
        self.it_email_recipient = self.db_email_recipient.read_data_from_db()
        
    def get_data(self):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        
        return(self.it_email_recipient)
    def write_data(self, it_table):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        self.db_email_recipient.write_data_to_db(it_table)
        pass

class cl_db_email_recipient(cl_ab_database_config):
    
    def build_select_statement(self):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())

        return('SELECT * FROM config_email_recipient WHERE active = "1" ')
    
    def build_insert_statement(self):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())

        qmarks = ', '.join('?' * len(self.data))
        cols = ', '.join(self.data.keys())
        
        query = "INSERT INTO %s (%s) VALUES (%s)" % (tablename, cols, qmarks)
        return(query)
    

        
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
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        cl_fact_logic_email_recipient.__o_instance = i_instance
        
    @classmethod        
    def get_instance(self):
        """
        Factory method to get the email recipient logic instance
        """
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        if cl_fact_logic_email_recipient.__o_instance is not None:
            return(cl_fact_logic_email_recipient.__o_instance)
        cl_fact_logic_email_recipient.__o_instance = cl_logic_email_recipient()
        return(cl_fact_logic_email_recipient.__o_instance)

    def __init__(self):
        """
        Constructor email logic factory
        """
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        pass    
    
class cl_fact_db_email_recipient(ABC):
    __o_instance = None
    
    @classmethod
    def set_instance(self, i_instance):
        """
        Factory method to set the db email recipient instance
        """        
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        cl_fact_db_email_recipient.__o_instance = i_instance
        
    @classmethod        
    def get_instance(self):
        """
        Factory method to get the db email recipient instance
        """        
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        if cl_fact_db_email_recipient.__o_instance is not None:
            return(cl_fact_db_email_recipient.__o_instance)
        cl_fact_db_email_recipient.__o_instance = cl_db_email_recipient()
        return(cl_fact_db_email_recipient.__o_instance)

    def __init__(self):
        """
        Constructor db email recipient factory
        """        
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        pass    