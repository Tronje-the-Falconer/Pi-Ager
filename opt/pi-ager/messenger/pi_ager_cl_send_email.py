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
from smtplib import SMTP , SMTP_SSL
from smtplib import SMTPException 
from email.mime.text import MIMEText as text
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

        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        cl_fact_logger.get_instance().debug(self.it_email_server)
        cl_fact_logger.get_instance().debug(self.it_email_recipient)
        cl_fact_logger.get_instance().debug('server = ' + str(self.it_email_server[0]['server']))
        
        for i in range(len(self.it_email_recipient)):
            if str(self.it_email_server[0]['port']) == "465":
                self.send_email_smtp_ssl(
                    str(self.it_email_server[0]['server']), 
                    str(self.it_email_server[0]['port']),
                    str(self.it_email_server[0]['user']), 
                    str(self.it_email_server[0]['password']),
                    str(self.it_email_server[0]['starttls']),
                    str(self.it_email_server[0]['from_mail']),
                    str(self.it_email_recipient[i]['to_mail']),
                    alarm_subject,
                    alarm_message)
            else:
                self.send_email_smtp(
                    str(self.it_email_server[0]['server']), 
                    str(self.it_email_server[0]['port']),
                    str(self.it_email_server[0]['user']), 
                    str(self.it_email_server[0]['password']),
                    str(self.it_email_server[0]['starttls']),
                    str(self.it_email_server[0]['from_mail']),
                    str(self.it_email_recipient[i]['to_mail']),
                    alarm_subject,
                    alarm_message)
                     
    def send_email_smtp(self, mail_server, mail_port,mail_user,mail_password,mail_starttls,mail_from,mail_to,mail_subject,mail_message):
        """
        Send email
        """
        # logger.debug(cl_fact_logger.get_instance().me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        
  
        crypt = cl_fact_help_crypt.get_instance()
        decrypted_secret = crypt.decrypt(mail_password).decode()

        cl_fact_logger.get_instance().debug('Server     =' + mail_server)
        cl_fact_logger.get_instance().debug('Port       =' + str(mail_port))
        cl_fact_logger.get_instance().debug('User       =' + mail_user)
        cl_fact_logger.get_instance().debug('Password   =' + decrypted_secret)
        
        try:
            
            server = SMTP(mail_server,mail_port)
            server.ehlo()
            if mail_starttls:
                server.starttls()
            
            server.login(mail_user,decrypted_secret)
            
    
            message = text(mail_message, 'plain', 'UTF-8')
    
            message['Subject'] = mail_subject
            message['From'] = mail_from
            message['To'] = mail_to
    
    
            server.sendmail(mail_from,mail_to, message.as_string())
            server.close()
            
            cl_fact_logger.get_instance().debug('Alert Email has been sent!')
        except SMTPException as cx_error:
            sending_error = 'Error: unable to send email: {err}'.format(err=cx_error)
            # logger.error(sendefehler)
            cl_fact_logger.get_instance().error(sending_error)
            
        except Exception as cx_error:
            #TODO err undefined!
            sending_error = 'Error: unable to send email: {err}'.format(err=cx_error)
            # logger.error(sendefehler)
            cl_fact_logger.get_instance().error(sending_error)

    def send_email_smtp_ssl(self, mail_server, mail_port,mail_user,mail_password,mail_starttls,mail_from,mail_to,mail_subject,mail_message):
        """
        Send email
        """
        # logger.debug(cl_fact_logger.get_instance().me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        
  
        crypt = cl_fact_help_crypt.get_instance()
        decrypted_secret = crypt.decrypt(mail_password).decode()

        cl_fact_logger.get_instance().debug('Server     =' + mail_server)
        cl_fact_logger.get_instance().debug('Port       =' + str(mail_port))
        cl_fact_logger.get_instance().debug('User       =' + mail_user)
        cl_fact_logger.get_instance().debug('Password   =' + decrypted_secret)
        
        try:
            
            server_ssl = SMTP_SSL(mail_server,mail_port)
            server_ssl.ehlo() 
            if mail_starttls:
                server_ssl.starttls()
            
            server_ssl.login(mail_user,decrypted_secret)
            
    
            message = text(mail_message, 'plain', 'UTF-8')
    
            message['Subject'] = mail_subject
            message['From'] = mail_from
            message['To'] = mail_to
    
    
            server_ssl.sendmail(mail_from,mail_to, message.as_string())
            server_ssl.close()
            
            cl_fact_logger.get_instance().debug('Alert Email has been sent!')
        except SMTPException as cx_error:
            sending_error = 'Error: unable to send email: {err}'.format(err=cx_error)
            # logger.error(sendefehler)
            cl_fact_logger.get_instance().error(sending_error)
            
        except Exception as cx_error:
            #TODO err undefined!
            sending_error = 'Error: unable to send email: {err}'.format(err=cx_error)
            # logger.error(sendefehler)
            cl_fact_logger.get_instance().error(sending_error)
        
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
    