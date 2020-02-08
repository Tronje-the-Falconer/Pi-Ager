# -*- coding: utf-8 -*-

"""This class is the messenger for pi-ager notifications(e-mail, alarm, telegram, pushover). """
 
__author__ = "Claus Fischer"
__copyright__ = "Copyright 2019, The Pi-Ager Project"
__credits__ = ["Claus Fischer"]
__license__ = "GPL"
__version__ = "1.0.0"
__maintainer__ = "Claus Fischer"
__email__ = "DerBurgermeister@pi-ager.org"
__status__ = "Productive"

from abc import ABC
import inspect
import traceback
import socket
import sys
import sqlite3 #Remove after test
import pi_ager_paths #Remove after test
import pi_ager_names
from main.pi_ager_cl_logger import cl_fact_logger
from main.pi_ager_cl_database import cl_fact_database_config, cl_ab_database_config
from messenger.pi_ager_cl_alarm import cl_fact_logic_alarm
from messenger.pi_ager_cl_send_email import cl_fact_logic_send_email, cl_logic_send_email
from messenger.pi_ager_cl_pushover import cl_fact_logic_pushover, cl_logic_pushover
from messenger.pi_ager_cl_telegram import cl_fact_logic_telegram, cl_logic_telegram

from main.pi_ager_cx_exception import *
from _ast import Pass

      
class cl_logic_messenger: #Sollte logic heissen und dann dec, db und helper...
    
    def __init__(self):
        """
        Constructor for the messenger class
        """ 
        # logger.debug(pi_ager_logging.me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
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
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        self.cx_error  = cx_error
        cl_fact_logger.get_instance().info("Exception raised: " + type(self.cx_error).__name__ + " - " + str(cx_error) )
        
        cl_fact_logger.get_instance().info(self.it_messenger)

        cl_fact_logger.get_instance().exception(self.cx_error, exc_info = True)
        cl_fact_logger.get_instance().info("Exception raised: " + type(self.cx_error).__name__  )
        cl_fact_logger.get_instance().info(self.it_messenger)
        
        if self.it_messenger: 
            cl_fact_logger.get_instance().debug('exception = ' + str(self.it_messenger[0]['exception']))
            cl_fact_logger.get_instance().debug('email     = ' + str(self.it_messenger[0]['email']))
            cl_fact_logger.get_instance().debug('pushover  = ' + str(self.it_messenger[0]['pushover']))
            cl_fact_logger.get_instance().debug('telegram  = ' + str(self.it_messenger[0]['telegram']))
            cl_fact_logger.get_instance().debug('alarm     = ' + str(self.it_messenger[0]['alarm']))
        cl_fact_logger.get_instance().info('Check Exception for Alarm:  ' + str(self.cx_error.__class__.__name__ ))
        if str(self.cx_error.__class__.__name__ ) == 'cx_Sensor_not_defined':
            self.logic_alarm.execute_short(replication = 3)
            self.exception_known = True
        elif str(self.cx_error.__class__.__name__ ) == 'OperationalError':
            self.logic_alarm.execute_long(replication = 2)
            self.exception_known = True
        elif str(self.cx_error.__class__.__name__ ) == 'SHT1xError':
            self.logic_alarm.execute_middle(replication = 7)
            self.exception_known = True
        elif str(self.cx_error.__class__.__name__ ) == 'cx_i2c_sht_temperature_crc_error':
            self.logic_alarm.execute_middle(replication = 7)
            self.exception_known = True
                
        else:
            self.logic_alarm.execute_middle(replication = 12)
            
        # logger.info('Check Exception for E-Mail: ' + str(self.cx_error.__class__.__name__))
        cl_fact_logger.get_instance().info('Check Exception for E-Mail: ' + str(self.cx_error.__class__.__name__))
        cl_fact_logic_send_email.get_instance().execute(self.build_alarm_subject(), self.build_alarm_message())
        
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
        cl_fact_logger.get_instance().info('Check Exception for Telegram: ' + str(self.cx_error.__class__.__name__))
        cl_fact_logic_telegram.get_instance().execute(self.build_alarm_subject(), self.build_alarm_message())
        cl_fact_logger.get_instance().info('Check Exception for Pushover: ' + str(self.cx_error.__class__.__name__))
        cl_fact_logic_pushover.get_instance().execute(self.build_alarm_subject(), self.build_alarm_message())
        
        if self.exception_known == False:
            cl_fact_logger.get_instance().critical(str(self.cx_error.__class__.__name__ ))
            sys.exit(0)
        return(self.exception_known)
    def build_alarm_message(self):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        return( str(traceback.format_exc()) )
    def build_alarm_subject(self):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        
        hostname = socket.gethostname()
        try:
            ip = socket.gethostbyname(hostname)
        except OSError:
            # Probably name lookup wasn't set up right; skip this test
            cl_fact_logger.get_instance().debug('name lookup failure')
        #self.assertTrue(ip.find('.') >= 0, "Error resolving host to ip.")
        try:
            hname, aliases, ipaddrs = socket.gethostbyaddr(ip)
        except OSError:
            # Probably a similar problem as above; skip this test
            cl_fact_logger.get_instance().debug('name lookup failure')
        #all_host_names = [hostname, hname] + aliases
        #hostname = socket.gethostbyaddr(IP.rstrip())
        return('Exception ' + str(self.cx_error.__class__.__name__ ) + ' on Pi-Ager Hostname ' + hname + ' occured')

class cl_db_messenger(cl_ab_database_config):

    def build_select_statement(self):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        return('SELECT * FROM config_messenger where active = 1 ')
 
class th_logic_messenger(cl_logic_messenger):   
       
    def __init__(self):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        pass


class cl_fact_logic_messenger(ABC):
    __o_instance = None
    
    @classmethod
    def set_instance(self, i_instance):
        """
        Factory method to set the logic messenger instance
        """        
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        cl_fact_logic_messenger.__o_instance = i_instance
        
    @classmethod        
    def get_instance(self):
        """
        Factory method to get the logic messenger instance
        """        
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        if cl_fact_logic_messenger.__o_instance is not None:
            return(cl_fact_logic_messenger.__o_instance)
        cl_fact_logic_messenger.__o_instance = cl_logic_messenger()
        return(cl_fact_logic_messenger.__o_instance)

    def __init__(self):
        """
        Constructor logic messenger factory
        """        
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        pass    
    
class cl_fact_db_messenger(ABC):
    __o_instance = None
    
    @classmethod
    def set_instance(self, i_instance):
        """
        Factory method to set the db messenger instance
        """        
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        cl_fact_db_messenger.__o_instance = i_instance
        
    @classmethod        
    def get_instance(self):
        """
        Factory method to get the db messenger instance
        """        
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        if cl_fact_db_messenger.__o_instance is not None:
            return(cl_fact_db_messenger.__o_instance)
        cl_fact_db_messenger.__o_instance = cl_db_messenger()
        return(cl_fact_db_messenger.__o_instance)

    def __init__(self):
        """
        Constructor logic messenger factory
        """        
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        pass    
