
from abc import ABC
import inspect
import pi_ager_names
#import pi_ager_logging



from main.pi_ager_cx_exception import *
from main.pi_ager_cl_logger import cl_fact_logger

#global logger
#logger = pi_ager_logging.create_logger(__name__)

        
class cl_logic_email:
    def __init__(self):
        """
        Constructor for the email class
        """ 
        # logger.debug(pi_ager_logging.me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        if "get_instance" not in inspect.stack()[1][3]:
            raise cx_direct_call("Please use factory class")
        
        """
        Read email setting from the database
        """
        self.server = 'smtp.1und1.de'
        self.user      = 'claus@fischnet.de'
        self.password  = 's3vEIV1u'
        self.STARTTLS  = True
        self.from_mail = 'messenger@fischnet.de'
        
        """
        Read email reciepient's from the database
        """
        self.to_mail = "claus@fischnet.de"
        
    def execute(self, cx_error_mail, alarm_message, alarm_subject):
        # logger.debug(pi_ager_logging.me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        
        self.send_email(
            self.server,
            self.user,
            self.password,
            self.STARTTLS,
            self.from_mail,
            self.to_mail,
            alarm_subject,
            alarm_message)        
    def send_email(self, SERVER,USER,PASSWORT,STARTTLS,FROM,TO,SUBJECT,MESSAGE):
        """
        Send email
        """
        # logger.debug(pi_ager_logging.me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        
        from smtplib import SMTP 
        from smtplib import SMTPException 
        from email.mime.text import MIMEText as text
        if STARTTLS:
            port=587
        else:
            port=25
        try:
            s = SMTP(SERVER,port)
            if STARTTLS:
                s.starttls()
            
            s.login(USER,PASSWORT)
            
    
            m = text(MESSAGE, 'plain', 'UTF-8')
    
            m['Subject'] = SUBJECT
            m['From'] = FROM
            m['To'] = TO
    
    
            s.sendmail(FROM,TO, m.as_string())
            s.quit()
            # logger.debug('Alert Email has been sent!')
            cl_fact_logger.get_instance().debug('Alert Email has been sent!')
        except SMTPException as cx_error:
            sendefehler = 'Error: unable to send email: {err}'.format(err=cs_error)
            # logger.error(sendefehler)
            cl_fact_logger.get_instance().error(sendefehler)
        except Exception as cx_error:
            #TODO err undefined!
            sendefehler = 'Error: unable to send email: {err}'.format(err=cx_error)
            # logger.error(sendefehler)
            cl_fact_logger.get_instance().error(sendefehler)
        
class th_logic_email(cl_logic_email):   

    
    def __init__(self):
        pass



class cl_fact_logic_email(ABC):
    __o_instance = None
    
    @classmethod
    def set_instance(self, i_instance):
        """
        Factory method to set the email logic instance
        """
        logger.debug(pi_ager_logging.me())
        cl_fact_logic_email.__o_instance = i_instance
        
    @classmethod        
    def get_instance(self):
        """
        Factory method to get the email logic instance
        """
        logger.debug(pi_ager_logging.me())
        if cl_fact_logic_email.__o_instance is not None:
            return(cl_fact_logic_email.__o_instance)
        cl_fact_logic_email.__o_instance = cl_logic_email()
        return(cl_fact_logic_email.__o_instance)

    def __init__(self):
        """
        Constructor email logic factory
        """
        logger.debug(pi_ager_logging.me())
        pass    
    
