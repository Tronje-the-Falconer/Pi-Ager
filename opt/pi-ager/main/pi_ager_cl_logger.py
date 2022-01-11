# -*- coding: utf-8 -*-
 
"""This class is the logger for the Pi-Ager."""

__author__ = "Claus Fischer"
__copyright__ = "Copyright 2019, The Pi-Ager Project"
__credits__ = ["Claus Fischer"]
__license__ = "GPL"
__version__ = "1.0.0"
__maintainer__ = "Claus Fischer"
__email__ = "DerBurgermeister@pi-ager.org"
__status__ = "Production"

from abc import ABC
import logging
from logging import handlers
import inspect
import pathlib
import os, stat, sys
import pi_ager_names
import pi_ager_paths
#import pi_ager_logging
import pi_ager_database_get_logging_value


# from main.pi_ager_cx_exception import *

class GroupWriteRotatingFileHandler(handlers.RotatingFileHandler):

    def doRollover(self):
        """
        Override base class method to make the new log file group writable.
        """
        # Rotate the file first.
        handlers.RotatingFileHandler.doRollover(self)

        # Add group write to the current permissions.
        currMode = os.stat(self.baseFilename).st_mode
        os.chmod(self.baseFilename, currMode | stat.S_IWOTH | stat.S_IWGRP)
        
class AppFilter(logging.Filter):
    # add modulename attribute to formatter string
    def filter(self, record):
        frameinfo = inspect.getouterframes(inspect.currentframe())
#        print('number of frames : ', len(frameinfo))
        if len(frameinfo) > 6:
            (frame, source, lineno, func, lines, index) = frameinfo[6]  # caller frame number is 6 with reference to AppFilter when single instance logger in pi_ager_cl_logger.py is used
            record.modulename = os.path.basename(source) + '(' + str(lineno) + ')'  # extract module name and line number of caller
        else:
            record.modulname = ''
        return True
                
class cl_logger:

    def __init__(self):
        """
        Constructor email logic factory
        """
        #logger.debug(pi_ager_logging.me())
        self.logger = self.create_logger(__name__)
        self.logger.debug('logging initialised')
        pass

    def debug(self, logstring, *args, **kwargs):
        self.logger.debug(logstring, *args, **kwargs)

    def info(self, logstring, *args, **kwargs):
        self.logger.info(logstring, *args, **kwargs)
        
    def warning(self, logstring, *args, **kwargs):
        self.logger.warning(logstring, *args, **kwargs)
        
    def error(self, logstring, *args, **kwargs):
        self.logger.error(logstring, *args, **kwargs)
        
    def critical(self, logstring, *args, **kwargs):
        self.logger.critical(logstring, *args, **kwargs)
        
    def exception(self, logstring, *args, **kwargs):
        self.logger.exception(logstring, *args, **kwargs)
        
    def get_logginglevel(self,loglevelstring):        # Builds a dict of dicts it_table from mysql db
        """
        setting loglevels
        """
        # global logger
        
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
        # global logger
        filepath = pi_ager_paths.get_path_logfile_txt_file()
        website_logfile = pathlib.Path(filepath)
        # filepermission = oct(os.stat(pi_ager_paths.logfile_txt_file)[stat.ST_MODE])[-3:]
        if not website_logfile.is_file():
            new_website_logfile = open(pi_ager_paths.get_path_logfile_txt_file(), "wb")
            new_website_logfile.close()
            # os.chmod(pi_ager_paths.get_path_logfile_txt_file(), stat.S_IWOTH|stat.S_IWGRP|stat.S_IWUSR|stat.S_IROTH|stat.S_IRGRP|stat.S_IRUSR)
        filepermission = oct(os.stat(pi_ager_paths.logfile_txt_file)[stat.ST_MODE])[-3:]
        if (filepermission != '666'):
            os.chmod(pi_ager_paths.get_path_logfile_txt_file(), stat.S_IWOTH|stat.S_IWGRP|stat.S_IWUSR|stat.S_IROTH|stat.S_IRGRP|stat.S_IRUSR)
    
    def check_pi_ager_logfile(self):
        """
        checking and setting permission for the pi_ager logfile 
        """
        # global logger
        filepath = pi_ager_paths.get_pi_ager_log_file_path()
        pi_ager_logfile = pathlib.Path(filepath)
        # filepermission = oct(os.stat(pi_ager_paths.pi_ager_log_file)[stat.ST_MODE])[-3:]
        if not pi_ager_logfile.is_file():
            new_pi_ager_logfile = open(filepath, "wb")
            new_pi_ager_logfile.close()
            #os.chmod(pi_ager_paths.get_path_logfile_txt_file(), stat.S_IWOTH|stat.S_IWGRP|stat.S_IWUSR|stat.S_IROTH|stat.S_IRGRP|stat.S_IRUSR)
        filepermission = oct(os.stat(pi_ager_paths.pi_ager_log_file)[stat.ST_MODE])[-3:]
        if (filepermission != '666'):
            os.chmod(filepath, stat.S_IWOTH|stat.S_IWGRP|stat.S_IWUSR|stat.S_IROTH|stat.S_IRGRP|stat.S_IRUSR)
        
    def create_logger(self,pythonfile):
        """
        creating loggers
        """
        self.check_website_logfile()
        loglevel_file_value = pi_ager_database_get_logging_value.get_logging_value('loglevel_file')
        loglevel_console_value = pi_ager_database_get_logging_value.get_logging_value('loglevel_console')
        #logging.handlers.RotatingFileHandler
        logging.handlers.GroupWriteRotatingFileHandler = GroupWriteRotatingFileHandler
        
        # Logger fuer website
        self.website_log_rotatingfilehandler = logging.handlers.GroupWriteRotatingFileHandler(pi_ager_paths.get_path_logfile_txt_file(), mode='a', maxBytes=262144, backupCount=36, encoding=None, delay=False)
        self.website_log_rotatingfilehandler.setLevel(logging.INFO)
        self.website_log_rotatingfilehandler_formatter = logging.Formatter('%(asctime)s %(message)s', '%y-%m-%d %H:%M:%S')
        self.website_log_rotatingfilehandler.setFormatter(self.website_log_rotatingfilehandler_formatter)
    
        # Logger fuer pi-ager debugging
        self.check_pi_ager_logfile()
        self.pi_ager_log_rotatingfilehandler = logging.handlers.GroupWriteRotatingFileHandler(pi_ager_paths.get_pi_ager_log_file_path(), mode='a', maxBytes=1048576, backupCount=20, encoding=None, delay=False)
        log_level = self.get_logginglevel(loglevel_file_value)
        self.pi_ager_log_rotatingfilehandler.setLevel(log_level)
        self.pi_ager_log_rotatingfilehandler_formatter = logging.Formatter('%(asctime)s %(levelname)-10s %(modulename)-35s %(message)s', '%m-%d %H:%M:%S')
        self.pi_ager_log_rotatingfilehandler.setFormatter(self.pi_ager_log_rotatingfilehandler_formatter)
    
        # Logger fuer die Console
        self.console_streamhandler = logging.StreamHandler(sys.stdout)
        log_level = self.get_logginglevel(loglevel_console_value)
        self.console_streamhandler.setLevel(log_level)
        self.console_streamhandler_formatter = logging.Formatter(' %(levelname)-10s %(modulename)-35s %(message)s')
        self.console_streamhandler.setFormatter(self.console_streamhandler_formatter)
        
        logger = logging.getLogger(pythonfile)
        logger.addFilter(AppFilter())
        logger.setLevel(logging.DEBUG)
        logger.addHandler(self.website_log_rotatingfilehandler)
        logger.addHandler(self.pi_ager_log_rotatingfilehandler)
        logger.addHandler(self.console_streamhandler)
        
        return logger
        
    def update_logger_loglevels( self ):
        """
        update log levels for console and file
        """
        loglevel_file_value = pi_ager_database_get_logging_value.get_logging_value('loglevel_file')
        loglevel_console_value = pi_ager_database_get_logging_value.get_logging_value('loglevel_console')

        log_level = self.get_logginglevel(loglevel_file_value)
        self.pi_ager_log_rotatingfilehandler.setLevel(log_level)
        
        log_level = self.get_logginglevel(loglevel_console_value)
        self.console_streamhandler.setLevel(log_level)
        
    def me(self):
        """
        Returns the logstring for logging in every method for the current code line (how i am)
        """
        prev_frame = inspect.currentframe().f_back
        the_class  = prev_frame.f_locals["self"].__class__
        the_method = prev_frame.f_code.co_name
        the_line   = prev_frame.f_code.co_firstlineno
        
        return("Line " + str(the_line) + str(the_class) + str(the_method))    
            
class th_logger(cl_logger):
    def __init__(self):
        print('logging initialised')
        self.logger = self.create_logger(__name__)
        self.logger.debug('logging initialised')
        pass
        
    def debug(self, i_logsting, *args, **kwargs):
        print(i_logsting)
        
    def info(self, i_logsting, *args, **kwargs):
        print(i_logstring)
    
    def warning(self, i_logsting, *args, **kwargs):
        print(i_logstring)
    
    def error(self, i_logsting, *args, **kwargs):
        print(i_logstring)
    
    def critical(self, i_logsting, *args, **kwargs):
        print(i_logstring)
        
    def exception(self, i_logsting, *args, **kwargs):
        print(i_logstring)
                                    
    def get_logginglevel(self,loglevelstring):        # Builds a dict of dicts it_table from mysql db
        """
        setting loglevels
        """
        # global logger
        
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
        pass
    def check_pi_ager_logfile(self):
        pass
    def create_logger(self,pythonfile):
        pass
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
        #logger.debug(pi_ager_logging.me())
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
    
