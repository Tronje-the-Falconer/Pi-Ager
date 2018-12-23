from abc import ABC
import inspect
import traceback
import pi_ager_names
import pi_ager_logging
from pi_ager_cl_alarm import cl_fact_alarm
from pi_ager_cl_email import cl_fact_email
                             
from pi_ager_cx_exception import *

global logger
logger = pi_ager_logging.create_logger(__name__)
logger.debug('logging initialised')

        
class cl_logic_messenger: #Sollte logic heissen und dann dec, db und helper...
    
    def __init__(self, cx_error):
        """
        Constructor for the messenger class
        """ 
        logger.debug(pi_ager_logging.me())
        if "get_instance" not in inspect.stack()[1][3]:
            raise cx_direct_call("Please use factory class")
        
        
        self.cx_error  = cx_error
        self.logic_alarm = cl_fact_logic_alarm().get_instance()
        self.logic_email = cl_fact_logic_email().get_instance()
        self.exception_known = False
        
    def send(self):
        """
        Send message to alarm or email or telegram or pushover ... class
        """         
        logger.debug(pi_ager_logging.me())
        logger.exception(self.cx_error, exc_info = True)
        logger.info("Exception raised: " + type(self.cx_error).__name__  ) 
        logger.info('Check Exception for Alarm:  ' + str(self.cx_error.__class__.__name__ ))
        if str(self.cx_error.__class__.__name__ ) == 'cx_Sensor_not_defined':
            self.logic_alarm.execute_short(Duration = 3)
            self.exception_known = True
        elif str(self.cx_error.__class__.__name__ ) == 'OperationalError':
            self.logic_alarm.execute_long(Duration = 2)
            self.exception_known = True
        elif str(self.cx_error.__class__.__name__ ) == 'SHT1xError':
            self.logic_alarm.execute_middle(Duration = 7)
            self.exception_known = True
        else:
            self.logic_alarm.execute_middle()
            
        
        logger.info('Check Exception for E-Mail: ' + str(self.cx_error.__class__.__name__))
        if str(self.cx_error.__class__.__name__ ) == 'cx_Sensor_not_defined':
            self.logic_email.execute(self.cx_error, self.build_alarm_message())
            self.exception_known = True
        elif str(self.cx_error.__class__.__name__ ) == 'OperationalError':
            self.logic_email.execute(self.cx_error, self.build_alarm_message())
            self.exception_known = True
        else:
            self.logic_email.execute(self.cx_error, self.build_alarm_message())
            self.exception_known = False
        
        logger.info('Check Exception for Telegram: ' + str(self.cx_error.__class__.__name__))
        
        logger.info('Check Exception for Pushover: ' + str(self.cx_error.__class__.__name__))
        return(self.exception_known)
    def build_alarm_message(self):
        return( str(traceback.format_exc()) )
    
class cl_db_messenger:
    __o_dirty = True
    def __init__(self):
        pass
    
    def get_data(self):
        if self.is_dirty() is True:
            self.read_data_from_db()
            
        pass
    
    def read_data_from_db(self):
        """
        Read from db
        """
        
        """
         # Builds a dict of dicts from sqlite db
        data_dict = {}
        conn = sqlite3.connect(self.garden_db_path)
        # Need to allow write permissions by others
        conn.row_factory = sqlite3.Row
        c = conn.cursor()
        c.execute('SELECT * FROM messenger where active = "x" ')
        tuple_list = c.fetchall()
        conn.close()
        # Building dict from table rows
        for item in tuple_list:
            data_dict[item[0]] = {
                "owner":item[1],
                "description":item[2],
                "age":item[3],
                "score":item[4],
                "dead":item[5],
            }
        return data_dict
        """ 
        cl_db_messenger.__o_dirty = False
        pass
    
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
    def get_instance(self, Exception):
        """
        Factory method to get the logic messenger instance
        """        
        logger.debug(pi_ager_logging.me())
        if cl_fact_logic_messenger.__o_instance is not None:
            return(cl_fact_logic_messenger.__o_instance)
        cl_fact_logic_messenger.__o_instance = cl_logic_messenger(Exception)
        return(cl_fact_logic_messenger.__o_instance)

    def __init__(self):
        """
        Constructor logic messenger factory
        """        
        logger.debug(pi_ager_logging.me())
        pass    
    
