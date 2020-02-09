# -*- coding: utf-8 -*-

"""This class is for sending an email notification."""

__author__ = "Claus Fischer"
__copyright__ = "Copyright 2019, The Pi-Ager Project"
__credits__ = ["Claus Fischer"]
__license__ = "GPL"
__version__ = "1.0.0"
__maintainer__ = "Claus Fischer"
__email__ = "DerBurgermeister@pi-ager.org"
__status__ = "Production"


import inspect
import pi_ager_names 
# import pi_ager_logging

from abc import ABC 
from messenger.pi_ager_cl_crypt import cl_fact_help_crypt
from messenger.pi_ager_cl_email_server import cl_fact_logic_email_server
from messenger.pi_ager_cl_email_recipient import cl_fact_logic_email_recipient
from main.pi_ager_cx_exception import *
from main.pi_ager_cl_logger import cl_fact_logger

# global logger
# logger = pi_ager_logging.create_logger(__name__)

        
class cl_logic_send_email:
    def __init__(self):
        """
        Constructor for the send email class
        """ 
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        if "get_instance" not in inspect.stack()[1][3]:
            raise cx_direct_call("Please use factory class")
                
        """
        Get email-server settings from the server class
        """
        self.logic_email_server = cl_fact_logic_email_server().get_instance()
        if not self.logic_email_server:
            return
                
        self.it_email_server = self.logic_email_server.get_data()
        if not self.it_email_server:
            return

            #raise cx_no_email_server_config_found 
        """
        Read email recipient's from the database
        """
        self.logic_email_recipient = cl_fact_logic_email_recipient().get_instance()
        if not self.logic_email_recipient:
            raise cx_no_email_recipient_config_found 
        self.it_email_recipient = self.logic_email_recipient.get_data()

    def execute(self, alarm_subject, alarm_message):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        cl_fact_logger.get_instance().debug(self.it_email_recipient)
        if not hasattr(cl_logic_send_email, "self.it_email_recipient"):
            # logger.info("No email recipient defined!")
            cl_fact_logger.get_instance().info("No email recipient defined!")
            return
        # logger.debug(cl_fact_logger.get_instance().me())
        # logger.debug(self.it_email_server)
        # logger.debug(self.it_email_recipient)
        # logger.debug('server = ' + str(self.it_email_server[0]['server']))
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        cl_fact_logger.get_instance().debug(self.it_email_server)
        cl_fact_logger.get_instance().debug(self.it_email_recipient)
        cl_fact_logger.get_instance().debug('server = ' + str(self.it_email_server[0]['server']))
        
        for i in range(len(self.it_email_recipient)):
            
            self.send_email(
                str(self.it_email_server[0]['server']), #self.server,
                str(self.it_email_server[0]['user']), #self.user,
                str(self.it_email_server[0]['password']), #self.password,
                str(self.it_email_server[0]['starttls']), #self.STARTTLS,
                str(self.it_email_server[0]['from_mail']), #self.from_mail,
                str(self.it_email_recipient[i]['to_mail']), #self.to_mail,
                alarm_subject,
                alarm_message)
                 
    def send_email(self, SERVER,USER,PASSWORD,STARTTLS,FROM,TO,SUBJECT,MESSAGE):
        """
        Send email
        """
        # logger.debug(cl_fact_logger.get_instance().me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        
        from smtplib import SMTP 
        from smtplib import SMTPException 
        from email.mime.text import MIMEText as text
        crypt = cl_fact_help_crypt.get_instance()
        decrypted_secret = crypt.decrypt(PASSWORD)
        if STARTTLS:
            port=587
        else:
            port=25
        try:
            s = SMTP(SERVER,port)
            if STARTTLS:
                s.starttls()
            
            s.login(USER,decrypted_secret)
            
    
            m = text(MESSAGE, 'plain', 'UTF-8')
    
            m['Subject'] = SUBJECT
            m['From'] = FROM
            m['To'] = TO
    
    
            s.sendmail(FROM,TO, m.as_string())
            s.quit()
            # logger.debug('Alert Email has been sent!')
            cl_fact_logger.get_instance().debug('Alert Email has been sent!')
        except SMTPException as cx_error:
            sendefehler = 'Error: unable to send email: {err}'.format(err=cx_error)
            # logger.error(sendefehler)
            cl_fact_logger.get_instance().error(sendefehler)
            
        except Exception as cx_error:
            #TODO err undefined!
            sendefehler = 'Error: unable to send email: {err}'.format(err=cx_error)
            # logger.error(sendefehler)
            cl_fact_logger.get_instance().error(sendefehler)
        
class th_logic_send_email(cl_logic_send_email):   

    
    def __init__(self):
        pass


class cl_fact_logic_send_email(ABC):
    __o_instance = None
    
    @classmethod
    def set_instance(self, i_instance):
        """
        Factory method to set the send email logic instance
        """
        # logger.debug(cl_fact_logger.get_instance().me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        cl_fact_logic_send_email.__o_instance = i_instance
        
    @classmethod        
    def get_instance(self):
        """
        Factory method to get the send email logic instance
        """
        # logger.debug(cl_fact_logger.get_instance().me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        if cl_fact_logic_send_email.__o_instance is not None:
            return(cl_fact_logic_send_email.__o_instance)
        cl_fact_logic_send_email.__o_instance = cl_logic_send_email()
        return(cl_fact_logic_send_email.__o_instance)

    def __init__(self):
        """
        Constructor send email logic factory
        """
        # logger.debug(cl_fact_logger.get_instance().me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        pass    
    