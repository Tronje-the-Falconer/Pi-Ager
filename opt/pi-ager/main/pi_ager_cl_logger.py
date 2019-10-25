from abc import ABC
import logging
import inspect
import pathlib
import os, stat
import pi_ager_names
import pi_ager_paths
#import pi_ager_logging
import pi_ager_database_get_logging_value


from pi_ager_cx_exception import *

class cl_logger:

    def __init__(self):
        """
        Constructor email logic factory
        """
        #logger.debug(pi_ager_logging.me())
        self.logger = self.create_logger(__name__)
        self.logger.debug('logging initialised')
        pass
        
    def debug(self, logsting):
        self.logger.debug(logsting)

    def info(self, logsting):
        self.logger.info(logsting)
    
    def warning(self, logsting):
        self.logger.warning(logsting)
    
    def error(self, logsting):
        self.logger.error(logsting)
    
    def critical(self, logsting):
        self.logger.critical(logsting)
                                
    def get_logginglevel(self,loglevelstring):        # Builds a dict of dicts it_table from mysql db
        """
        setting loglevels
        """
        global logger
        
        if loglevelstring == 10:
            loglevel = logging.DEBUG
        elif loglevelstring == 20:
            loglevel = logging.INFO
        elif loglevelstring == 30:
            loglevel = logging.WARNING
        elif loglevelstring == 40:
            loglevel = logging.ERROR
        elif loglevelstring == 50:
            loglevel = logging.CRITICAL
    
        return(loglevel)
    
    def check_website_logfile(self):
        """
        checking and setting permission for the website logfile 
        """
        global logger
        filepath = pi_ager_paths.get_path_logfile_txt_file()
        website_logfile = pathlib.Path(filepath)
        filepermission = oct(os.stat(pi_ager_paths.logfile_txt_file)[stat.ST_MODE])[-3:]
        if not website_logfile.is_file():
            new_website_logfile = open(pi_ager_paths.get_path_logfile_txt_file(), "wb")
            new_website_logfile.close()
            #os.chmod(pi_ager_paths.get_path_logfile_txt_file(), stat.S_IWOTH|stat.S_IWGRP|stat.S_IWUSR|stat.S_IROTH|stat.S_IRGRP|stat.S_IRUSR)
        if (filepermission != '666'):
            os.chmod(pi_ager_paths.get_path_logfile_txt_file(), stat.S_IWOTH|stat.S_IWGRP|stat.S_IWUSR|stat.S_IROTH|stat.S_IRGRP|stat.S_IRUSR)
    
    def create_logger(self,pythonfile):
        """
        creating loggers
        """
        self.check_website_logfile()
        loglevel_file_value = pi_ager_database_get_logging_value.get_logging_value('loglevel_file')
        loglevel_console_value = pi_ager_database_get_logging_value.get_logging_value('loglevel_console')
        
        # Logger fuer website
        website_log_rotatingfilehandler = logging.handlers.RotatingFileHandler(pi_ager_paths.get_path_logfile_txt_file(), mode='a', maxBytes=1048576, backupCount=36, encoding=None, delay=False)
        website_log_rotatingfilehandler.setLevel(logging.INFO)
        website_log_rotatingfilehandler_formatter = logging.Formatter('%(asctime)s %(message)s', '%y-%m-%d %H:%M:%S')
        website_log_rotatingfilehandler.setFormatter(website_log_rotatingfilehandler_formatter)
    
        # Logger fuer pi-ager debugging
        pi_ager_log_rotatingfilehandler = logging.handlers.RotatingFileHandler(pi_ager_paths.get_pi_ager_log_file_path(), mode='a', maxBytes=2097152, backupCount=20, encoding=None, delay=False)
        pi_ager_log_rotatingfilehandler.setLevel(self.get_logginglevel(loglevel_file_value))
        pi_ager_log_rotatingfilehandler_formatter = logging.Formatter('%(asctime)s %(name)-27s %(levelname)-8s %(message)s', '%m-%d %H:%M:%S')
        pi_ager_log_rotatingfilehandler.setFormatter(pi_ager_log_rotatingfilehandler_formatter)
    
        # Logger fuer die Console
        console_streamhandler = logging.StreamHandler()
        console_streamhandler.setLevel(self.get_logginglevel(loglevel_console_value))
        console_streamhandler_formatter = logging.Formatter(' %(levelname)-10s: %(name)-8s %(message)s')
        console_streamhandler.setFormatter(console_streamhandler_formatter)
        
        logger = logging.getLogger(pythonfile)
        logger.setLevel(logging.DEBUG)
        logger.addHandler(website_log_rotatingfilehandler)
        logger.addHandler(pi_ager_log_rotatingfilehandler)
        logger.addHandler(console_streamhandler)
        
        return logger
    def me(self):
        """
        Returns the logsting for logging in every method for the current code line (how i am)
        """
        prev_frame = inspect.currentframe().f_back
        the_class  = prev_frame.f_locals["self"].__class__
        the_method = prev_frame.f_code.co_name
        the_line   = prev_frame.f_code.co_firstlineno
        
        return("Line " + str(the_line) + str(the_class) + str(the_method))    
            

class cl_fact_logger(ABC):
    __o_instance = None
    
    @classmethod
    def set_instance(self, i_instance):
        """
        Factory method to set the logger instance
        """
        logger.debug(pi_ager_logging.me())
        cl_fact_logger.__o_instance = i_instance
        
    @classmethod        
    def get_instance(self):
        """
        Factory method to get the database instance
        """
        
        if cl_fact_logger.__o_instance is not None:
            return(cl_fact_logger.__o_instance)
        cl_fact_logger.__o_instance = cl_logger()
        cl_fact_logger.__o_instance.debug('logger factory done')
        return(cl_fact_logger.__o_instance)

    def __init__(self):
        """
        Constructor email logic factory
        """
        #"logger.debug(pi_ager_logging.me())
        pass    
    
