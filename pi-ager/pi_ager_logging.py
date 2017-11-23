#!/usr/bin/python3
import logging
from logging.handlers import RotatingFileHandler
import pi_ager_paths
from pi_ager_get_logging_value import get_logging_value
from pathlib import Path
import os, stat

def get_logginglevel(loglevelstring):
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
    
    return loglevel
    
def check_website_logfile():
    global logger
    filepath = pi_ager_paths.get_path_logfile_txt_file()
    website_logfile = Path(filepath)
    if not website_logfile.is_file():
        new_website_logfile = open(pi_ager_paths.get_path_logfile_txt_file(), "wb")
        new_website_logfile.close()
        os.chmod(pi_ager_paths.get_path_logfile_txt_file(), stat.S_IWOTH|stat.S_IWGRP|stat.S_IWUSR|stat.S_IROTH|stat.S_IRGRP|stat.S_IRUSR)

def create_logger(pythonfile):
    check_website_logfile()
    loglevel_file_value = get_logging_value('loglevel_file')
    loglevel_console_value = get_logging_value('loglevel_console')
    
    # Logger fuer website
    website_log_filehandler = logging.FileHandler(pi_ager_paths.get_path_logfile_txt_file(), mode='a', encoding=None, delay=False)
    website_log_filehandler.setLevel(logging.INFO)
    website_log_filehandler_formatter = logging.Formatter('%(asctime)s %(message)s', '%y-%m-%d %H:%M:%S')
    website_log_filehandler.setFormatter(website_log_filehandler_formatter)

    # Logger fuer pi-ager debugging
    pi_ager_log_rotatingfilehandler = RotatingFileHandler(pi_ager_paths.get_pi_ager_log_file_path(), mode='a', maxBytes=2097152, backupCount=20, encoding=None, delay=False)
    pi_ager_log_rotatingfilehandler.setLevel(get_logginglevel(loglevel_file_value))
    pi_ager_log_rotatingfilehandler_formatter = logging.Formatter('%(asctime)s %(name)-27s %(levelname)-8s %(message)s', '%m-%d %H:%M:%S')
    pi_ager_log_rotatingfilehandler.setFormatter(pi_ager_log_rotatingfilehandler_formatter)

    # Logger fuer die Console
    console_streamhandler = logging.StreamHandler()
    console_streamhandler.setLevel(get_logginglevel(loglevel_console_value))
    console_streamhandler_formatter = logging.Formatter(' %(levelname)-10s: %(name)-8s %(message)s')
    console_streamhandler.setFormatter(console_streamhandler_formatter)
    
    logger = logging.getLogger(pythonfile)
    logger.setLevel(logging.DEBUG)
    logger.addHandler(website_log_filehandler)
    logger.addHandler(pi_ager_log_rotatingfilehandler)
    logger.addHandler(console_streamhandler)
    
    return logger
        
logger = create_logger(__name__)
logger.debug('logging initialised')
