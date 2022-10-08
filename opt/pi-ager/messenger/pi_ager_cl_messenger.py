# -*- coding: utf-8 -*-

"""This class is the messenger for pi-ager notifications(e-mail, alarm, telegram, pushover). """
 
__author__ = "Claus Fischer"
__copyright__ = "Copyright 2019, The Pi-Ager Project"
__credits__ = ["Claus Fischer"]
__license__ = "GPL"
__version__ = "1.0.1"
__maintainer__ = "Claus Fischer"
__email__ = "DerBurgermeister@pi-ager.org"
__status__ = "Productive"

""" Modified 16.06.21 by phylax to handle also unknown exceptions """

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
from messenger.pi_ager_cl_alarm import cl_fact_logic_alarm, cl_logic_alarm
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
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        if "get_instance" not in inspect.stack()[1][3]:
            raise cx_direct_call("Please use factory class")

        """
        Read messenger data from exception DB
        """
        self.db_messenger_exception = cl_fact_db_messenger_exception().get_instance()
        self.it_messenger_exception = self.db_messenger_exception.read_data_from_db()

        """
        Read messenger data from event DB
        """
        self.db_messenger_event = cl_fact_db_messenger_event().get_instance()
        self.it_messenger_event = self.db_messenger_event.read_data_from_db()


        self.exception_known = False
    def send_mail(self, subject, message):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        cl_fact_logger.get_instance().info('Send E-Mail' )
        cl_fact_logic_send_email.get_instance().execute(subject, message)
    
    def send_pushover(self, subject, message):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        cl_fact_logger.get_instance().info('Send Pushover' )
        cl_fact_logic_pushover.get_instance().execute(subject, message)

    def send_telegram(self, subject, message):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        cl_fact_logger.get_instance().info('Send Telegram' )
        cl_fact_logic_telegram.get_instance().execute(subject, message)


    def alarm(self, alarm):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        cl_fact_logger.get_instance().info('Check Exception for Alarm:  ' + str(self.cx_error.__class__.__name__ ))
        cl_fact_logic_alarm().get_instance().execute_alarm(alarm)
        pass
    
    def send_messages(self, messenger_exception_row):
        if self.is_not_blank(messenger_exception_row['alarm']): 
            cl_fact_logger.get_instance().info('Trigger Alarm:  ' + str(self.cx_error.__class__.__name__ ))
            try:
                cl_fact_logic_alarm().get_instance().execute_alarm(messenger_exception_row['alarm'])
            except:
                cl_fact_logger.get_instance().info('Alarm settings not active: ')
                
        if messenger_exception_row['telegram'] == 1:
            cl_fact_logger.get_instance().info('Trigger Telegram: ' + str(self.cx_error.__class__.__name__))
            try:
                cl_fact_logic_telegram.get_instance().execute(self.build_alarm_subject(), self.build_alarm_message())
            except:
                cl_fact_logger.get_instance().info('Telegram settings not active: ')
                
        if messenger_exception_row['pushover'] == 1:
            cl_fact_logger.get_instance().info('Trigger Pushover: ' + str(self.cx_error.__class__.__name__))
            try:
                cl_fact_logic_pushover.get_instance().execute(self.build_alarm_subject(), self.build_alarm_message())
            except:
                cl_fact_logger.get_instance().info('Pushover settings not active: ')

        if messenger_exception_row['e-mail'] == 1:
            cl_fact_logger.get_instance().info('Trigger E-Mail: ' + str(self.cx_error.__class__.__name__))
            try:
                cl_fact_logic_send_email.get_instance().execute(self.build_alarm_subject(), self.build_alarm_message())
            except:
                cl_fact_logger.get_instance().info('E-Mail settings not active: ')
                        
        if messenger_exception_row['raise_exception'] == 1:
            cl_fact_logger.get_instance().critical(str(self.cx_error.__class__.__name__ ))
            self.stop_piager()  # set stop pi-ager in current_values table
            sys.exit(0)
    
    def stop_piager(self):
        """
        stop piager and agingtable in database when exception occurred, befor sys.exit
        """
        self.database = cl_fact_database_config.get_instance()
        sql_statement1 = 'UPDATE ' + pi_ager_names.current_values_table + ' SET "' + pi_ager_names.value_field + '" = "0"' + ' WHERE "' + pi_ager_names.key_field + '" = "' + pi_ager_names.status_pi_ager_key + '";'
        sql_statement2 = 'UPDATE ' + pi_ager_names.current_values_table + ' SET "' + pi_ager_names.value_field + '" = "0"' + ' WHERE "' + pi_ager_names.key_field + '" = "' + pi_ager_names.status_agingtable_key + '";'
        sql_statement3 = 'UPDATE ' + pi_ager_names.current_values_table + ' SET "' + pi_ager_names.value_field + '" = "0"' + ' WHERE "' + pi_ager_names.key_field + '" = "' + pi_ager_names.status_scale1_key + '";'
        sql_statement4 = 'UPDATE ' + pi_ager_names.current_values_table + ' SET "' + pi_ager_names.value_field + '" = "0"' + ' WHERE "' + pi_ager_names.key_field + '" = "' + pi_ager_names.status_scale2_key + '";'
        sql_statement5 = 'UPDATE ' + pi_ager_names.current_values_table + ' SET "' + pi_ager_names.value_field + '" = "0"' + ' WHERE "' + pi_ager_names.key_field + '" = "' + pi_ager_names.agingtable_period_key + '";'
        sql_statement6 = 'UPDATE ' + pi_ager_names.current_values_table + ' SET "' + pi_ager_names.value_field + '" = "1"' + ' WHERE "' + pi_ager_names.key_field + '" = "' + pi_ager_names.agingtable_period_day_key + '";'
        
        cl_fact_logger.get_instance().debug('stop pi_ager sql statement : ' + sql_statement1)
        cl_fact_logger.get_instance().debug('stop agingtable sql statement : ' + sql_statement2 )
        cl_fact_logger.get_instance().debug('stop scale1 sql statement : ' + sql_statement3 )
        cl_fact_logger.get_instance().debug('stop scale1 sql statement : ' + sql_statement4 )
        cl_fact_logger.get_instance().debug('reset agingtable period sql statement : ' + sql_statement5 )
        cl_fact_logger.get_instance().debug('reset agingtable period day sql statement : ' + sql_statement6 )
        
        self.database.write_data_to_db(sql_statement1)
        self.database.write_data_to_db(sql_statement2)
        self.database.write_data_to_db(sql_statement3)
        self.database.write_data_to_db(sql_statement4)
        self.database.write_data_to_db(sql_statement5)
        self.database.write_data_to_db(sql_statement6)   
        
    def handle_exception(self, cx_error):
        """
        Handle exception to create alarm or email or telegram or pushover ... class
        """
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        self.cx_error       = cx_error
        self.cx_error_name  = type(self.cx_error).__name__
        cl_fact_logger.get_instance().info("Exception raised: " + self.cx_error_name + " - " + str(cx_error) + "\n" + self.build_alarm_subject() + self.build_alarm_message() )
       
        self.it_messenger_exception = self.db_messenger_exception.read_data_from_db()
        self.exception_known = False
        
        for item in self.it_messenger_exception:
            if item.get('exception') == self.cx_error_name :
                self.exception_known = True
                cl_fact_logger.get_instance().debug('Known exception name: ' + item['exception'])
                cl_fact_logger.get_instance().debug('Known exception e-mail: ' + str(item['e-mail']))
                cl_fact_logger.get_instance().debug('Known exception pushover: ' + str(item['pushover']))
                cl_fact_logger.get_instance().debug('Known exception telegram: ' + str(item['telegram']))
                cl_fact_logger.get_instance().debug('Known exception alarm: ' + str(item['alarm']))
                cl_fact_logger.get_instance().debug('Known exception raise_exception: ' + str(item['raise_exception']))
                cl_fact_logger.get_instance().debug('Known exception active: ' + str(item['active']))
                cl_fact_logger.get_instance().debug('Known exception id: ' + str(item['id']))
                
                if (item['active'] == 1):
                    self.send_messages(item)
        
        # if we land here exception is unknown or should not be handled
        if (self.exception_known == False):
            item = self.it_messenger_exception[0]   # first row with data for unknown exception
            if (item['active'] == 1):
                self.send_messages(item)
                    
        return(self.exception_known)

    def is_not_blank(self, s):
        return bool(s and not s.isspace())
        
    def handle_event(self, event, info_text=None):
        """
        Handle event to create alarm or email or telegram or pushover ... class
        The second parameter info_text and the value taken from the field envent_text in table config_messenger_event are merged
        """
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        cl_fact_logger.get_instance().info('Event state: ' + event )
        
        self.event = event
        self.it_messenger_event = self.db_messenger_event.read_data_from_db()
        for item in self.it_messenger_event:
            if item.get('event') == event :
                if (info_text == None):
                    self.info_text = item['event_text']
                else:
                    self.info_text    = item['event_text'] + '\n' + info_text
                
#                cl_fact_logger.get_instance().info('Info text is: '+ str(self.info_text) )
                
                cl_fact_logger.get_instance().debug(item['event'])
                if self.is_not_blank(item['alarm']):
                    cl_fact_logger.get_instance().info('Trigger Alarm:  ' + event)
                    try:
                        cl_fact_logic_alarm().get_instance().execute_alarm(item['alarm'])
                    except:
                        cl_fact_logger.get_instance().info('Alarm settings not active: ')
                
                if item['telegram'] == 1:
                    cl_fact_logger.get_instance().info('Trigger Telegram: ' + event)
                    try:
                        cl_fact_logic_telegram.get_instance().execute(self.build_event_subject(), self.build_event_message())
                    except:
                        cl_fact_logger.get_instance().info('Telegram settings not active: ')
                
                if item['pushover'] == 1:
                    cl_fact_logger.get_instance().info('Trigger Pushover: ' + event)
                    try:
                        cl_fact_logic_pushover.get_instance().execute(self.build_event_subject(), self.build_event_message())
                    except:
                        cl_fact_logger.get_instance().info('Pushover settings not active: ')

                if item['e-mail'] == 1:
                    cl_fact_logger.get_instance().info('Trigger E-Mail: ' + event)
                    try:
                        cl_fact_logic_send_email.get_instance().execute(self.build_event_subject(), self.build_event_message())
                    except:
                        cl_fact_logger.get_instance().info('E-Mail settings not active: ')
                        
                
                return()




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
            cl_fact_logger.get_instance().debug('Host name lookup failure')
        #self.assertTrue(ip.find('.') >= 0, "Error resolving host to ip.")
        try:
            hname, aliases, ipaddrs = socket.gethostbyaddr(ip)
        except OSError:
            # Probably a similar problem as above; skip this test
            cl_fact_logger.get_instance().debug('Host name lookup failure')

        return('Exception ' + str(self.cx_error.__class__.__name__ ) + ' on Pi-Ager Hostname ' + hostname + ' occured \n' )

    def build_event_subject(self):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        
        hostname = socket.gethostname()
        try:
            ip = socket.gethostbyname(hostname)
        except OSError:
            # Probably name lookup wasn't set up right; skip this test
            cl_fact_logger.get_instance().debug('Host name lookup failure')
        #self.assertTrue(ip.find('.') >= 0, "Error resolving host to ip.")
        try:
            hname, aliases, ipaddrs = socket.gethostbyaddr(ip)
        except OSError:
            # Probably a similar problem as above; skip this test
            cl_fact_logger.get_instance().debug('Host name lookup failure')

        return('Event ' + self.event + ' on Pi-Ager Hostname ' + hostname + ' raised \n')
    
    def build_event_message(self):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        return( 'Message: ' + self.info_text + '\n' )

class cl_db_messenger_exception(cl_ab_database_config):

    def build_select_statement(self):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        # return('SELECT * FROM config_messenger_exception where active = 1 ')
        # select all exceptions in table, active is checked in handle_exception
        return('SELECT * FROM config_messenger_exception')
        
class cl_db_messenger_event(cl_ab_database_config):

    def build_select_statement(self):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        return('SELECT * FROM config_messenger_event where active = 1 ')
 
 
 
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
    
class cl_fact_db_messenger_exception(ABC):
    __o_instance = None
    
    @classmethod
    def set_instance(self, i_instance):
        """
        Factory method to set the db messenger instance
        """        
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        cl_fact_db_messenger_exception.__o_instance = i_instance
        
    @classmethod        
    def get_instance(self):
        """
        Factory method to get the db messenger instance
        """        
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        if cl_fact_db_messenger_exception.__o_instance is not None:
            return(cl_fact_db_messenger_exception.__o_instance)
        cl_fact_db_messenger_exception.__o_instance = cl_db_messenger_exception()
        return(cl_fact_db_messenger_exception.__o_instance)

    def __init__(self):
        """
        Constructor logic messenger exception factory
        """        
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        pass    

class cl_fact_db_messenger_event(ABC):
    __o_instance = None
    
    @classmethod
    def set_instance(self, i_instance):
        """
        Factory method to set the db messenger instance
        """        
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        cl_fact_db_messenger_event.__o_instance = i_instance
        
    @classmethod        
    def get_instance(self):
        """
        Factory method to get the db messenger instance
        """        
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        if cl_fact_db_messenger_event.__o_instance is not None:
            return(cl_fact_db_messenger_event.__o_instance)
        cl_fact_db_messenger_event.__o_instance = cl_db_messenger_event()
        return(cl_fact_db_messenger_event.__o_instance)

    def __init__(self):
        """
        Constructor logic messenger event factory
        """        
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        pass    
