from abc import ABC
import inspect
import traceback
import socket
import sqlite3 #Remove after test
import pi_ager_paths #Remove after test
import pi_ager_names
import pi_ager_logging
from pi_ager_cl_database import cl_fact_database_config
from pi_ager_cl_alarm import cl_fact_logic_alarm
from pi_ager_cl_send_email import cl_fact_logic_send_email, cl_logic_send_email
                             
from pi_ager_cx_exception import *
from _ast import Pass

global logger
logger = pi_ager_logging.create_logger(__name__)
logger.debug('logging initialised')

        
class cl_logic_messenger: #Sollte logic heissen und dann dec, db und helper...
    
    def __init__(self):
        """
        Constructor for the messenger class
        """ 
        logger.debug(pi_ager_logging.me())
        if "get_instance" not in inspect.stack()[1][3]:
            raise cx_direct_call("Please use factory class")
        
        
        
        """
        Read messenger data from DB
        """
        self.db_messenger = cl_fact_db_messenger().get_instance()
        self.it_messenger = self.db_messenger.read_data_from_db()
        
        
        
        
        self.logic_alarm = cl_fact_logic_alarm().get_instance()
        self.logic_send_email = cl_fact_logic_send_email().get_instance()
        
        self.exception_known = False
    def send(self, subject, message):
        if isinstance(self.logic_send_email, cl_logic_send_email): 
            self.logic_send_email.execute( subject, message )
        pass
    def alarm(self, replication, duration):
        if isinstance(self.logic_alarm, cl_logic_alarm): 
           
            if duration == 'short':
                self.logic_alarm.execute_short(replication = replication) 
            elif duration == 'middle':
                self.logic_alarm.execute_middle(replication = replication)
            elif duration == 'long':
                self.logic_alarm.execute_long(replication = replication)
            else:
                self.logic_alarm.execute_middle(replication = replication)  
        pass
    def handle_exception(self, cx_error):
        """
        Handle message to create alarm or email or telegram or pushover ... class
        """
        logger.debug(pi_ager_logging.me())
        self.cx_error  = cx_error
        logger.exception(self.cx_error, exc_info = True)
        logger.info("Exception raised: " + type(self.cx_error).__name__  )
        logger.info(self.it_messenger)
        
        if self.it_messenger: 
            logger.debug('id = ' + str(self.it_messenger[0]['id']))
            logger.debug('exception = ' + str(self.it_messenger[0]['exception']))
            logger.debug('message_type = ' + str(self.it_messenger[0]['message_type']))
            logger.debug('active = ' + str(self.it_messenger[0]['active']))
            
        logger.info('Check Exception for Alarm:  ' + str(self.cx_error.__class__.__name__ ))
        if str(self.cx_error.__class__.__name__ ) == 'cx_Sensor_not_defined':
            self.logic_alarm.execute_short(replication = 3)
            self.exception_known = True
        elif str(self.cx_error.__class__.__name__ ) == 'OperationalError':
            self.logic_alarm.execute_long(replication = 2)
            self.exception_known = True
        elif str(self.cx_error.__class__.__name__ ) == 'SHT1xError':
            self.logic_alarm.execute_middle(replication = 7)
            self.exception_known = True

                
        else:
            self.logic_alarm.execute_middle(replication = 12)
            
        
        logger.info('Check Exception for E-Mail: ' + str(self.cx_error.__class__.__name__))
        """
        if str(self.cx_error.__class__.__name__ ) == 'cx_Sensor_not_defined':
            self.logic_send_email.execute(self.cx_error, self.build_alarm_subject(), self.build_alarm_message())
            self.exception_known = True
        elif str(self.cx_error.__class__.__name__ ) == 'OperationalError':
            self.logic_send_email.execute(self.cx_error, self.build_alarm_subject(), self.build_alarm_message())
            self.exception_known = True
        else:
            self.logic_send_email.execute(self.cx_error, self.build_alarm_subject(), self.build_alarm_message())
            self.exception_known = False
        """
        logger.info('Check Exception for Telegram: ' + str(self.cx_error.__class__.__name__))
        
        logger.info('Check Exception for Pushover: ' + str(self.cx_error.__class__.__name__))
        return(self.exception_known)
    def build_alarm_message(self):
        return( str(traceback.format_exc()) )
    def build_alarm_subject(self):
        hostname = socket.gethostbyaddr(IP.rstrip())
        return('Exception ' + str(self.cx_error.__class__.__name__ ) + ' on Pi-Ager ' + hostname + 'occured')
class cl_db_messenger:
    __o_dirty = True
    def __init__(self):
        pass
        data = self.read_data_from_db()
    
    def build_select_statement(self):
        
        return('SELECT * FROM messenger where active = 1 ')

    def get_data(self):
        if self.is_dirty() is True:
            self.data = self.read_data_from_db()
            
        return(self.data)
    
    def read_data_from_db(self):
        """
        Read from db
        """
        database_config = cl_fact_database_config().get_instance()
        it_table = database_config.read_data_from_db(self.build_select_statement())
        cl_db_messenger.__o_dirty = False
        
        return it_table
 
        
    
    def is_dirty(self):
        return(cl_db_messenger.__o_dirty)
        pass
    
 
class th_logic_messenger(cl_logic_messenger):   
       
    def __init__(self):
        pass


class cl_fact_logic_messenger(ABC):
    __o_instance = None
    
    @classmethod
    def set_instance(self, i_instance):
        """
        Factory method to set the logic messenger instance
        """        
        logger.debug(pi_ager_logging.me())
        cl_fact_logic_messenger.__o_instance = i_instance
        
    @classmethod        
    def get_instance(self):
        """
        Factory method to get the logic messenger instance
        """        
        logger.debug(pi_ager_logging.me())
        if cl_fact_logic_messenger.__o_instance is not None:
            return(cl_fact_logic_messenger.__o_instance)
        cl_fact_logic_messenger.__o_instance = cl_logic_messenger()
        return(cl_fact_logic_messenger.__o_instance)

    def __init__(self):
        """
        Constructor logic messenger factory
        """        
        logger.debug(pi_ager_logging.me())
        pass    
    
class cl_fact_db_messenger(ABC):
    __o_instance = None
    
    @classmethod
    def set_instance(self, i_instance):
        """
        Factory method to set the db messenger instance
        """        
        logger.debug(pi_ager_logging.me())
        cl_fact_db_messenger.__o_instance = i_instance
        
    @classmethod        
    def get_instance(self):
        """
        Factory method to get the db messenger instance
        """        
        logger.debug(pi_ager_logging.me())
        if cl_fact_db_messenger.__o_instance is not None:
            return(cl_fact_db_messenger.__o_instance)
        cl_fact_db_messenger.__o_instance = cl_db_messenger()
        return(cl_fact_db_messenger.__o_instance)

    def __init__(self):
        """
        Constructor logic messenger factory
        """        
        logger.debug(pi_ager_logging.me())
        pass    
