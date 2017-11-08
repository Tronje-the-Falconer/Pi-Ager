#!/usr/bin/python3
import logging
from logging.handlers import RotatingFileHandler
import pi_ager_names
import pi_ager_paths
import pi_ager_database
from pathlib import Path
import os, stat

def get_logginglevel(loglevelstring):
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
    filepath = pi_ager_paths.get_path_logfile_txt_file()
    website_logfile = Path(filepath)
    if website_logfile.is_file():
        print ('file exists')
    else:
        new_website_logfile = open(pi_ager_paths.get_path_logfile_txt_file(), "wb")
        new_website_logfile.close()
        os.chmod(pi_ager_paths.get_path_logfile_txt_file(), stat.S_IWOTH|stat.S_IWGRP|stat.S_IWUSR|stat.S_IROTH|stat.S_IRGRP|stat.S_IRUSR)
        print ('Logfile created')
        #logger_pi_ager_logging.info('new logfile created')
        
def create_logger(pythonfile):
    check_website_logfile()
    loglevel_file_value = pi_ager_database.get_logging_value('loglevel_file')
    loglevel_console_value = pi_ager_database.get_logging_value('loglevel_console')

    logger = logging.getLogger()
    logger.setLevel(logging.DEBUG)

    #Logger fuer website
    website_log = logging.FileHandler(pi_ager_paths.get_path_logfile_txt_file(), mode='a', encoding=None, delay=False)
    website_log.setLevel(logging.INFO)
    website_log_formatter = logging.Formatter('%(asctime)s %(message)s', '%y-%m-%d %H:%M:%S')
    website_log.setFormatter(website_log_formatter)
    logger.addHandler(website_log)

    #Logger fuer pi-ager debugging
    pi_ager_logger = RotatingFileHandler(pi_ager_paths.get_pi_ager_log_file_path(), mode='a', maxBytes=2097152, backupCount=20, encoding=None, delay=False)
    pi_ager_logger.setLevel(get_logginglevel(loglevel_file_value))
    pi_ager_logger_formatter = logging.Formatter('%(asctime)s %(name)-27s %(levelname)-8s %(message)s', '%m-%d %H:%M:%S')
    pi_ager_logger.setFormatter(pi_ager_logger_formatter)
    logger.addHandler(pi_ager_logger)

    # Logger fuer die Console
    console = logging.StreamHandler()
    console.setLevel(get_logginglevel(loglevel_console_value))
    console_formatter = logging.Formatter(' %(levelname)-10s: %(name)-8s %(message)s')
    console.setFormatter(console_formatter)
    logger.addHandler(console)

    global logger_pi_ager_logging
    logger_pi_ager_logging = logging.getLogger('pi_ager_logging.py')
    
    
    
    # Verschiedene Logger um Einzelebenen zu erkennen
    if pythonfile == 'main.py':
        global logger_main
        global logger_pi_ager_database
        global logger_pi_ager_gpio_config
        global logger_pi_ager_init
        global logger_pi_ager_loop
        global logger_pi_ager_names
        global logger_pi_ager_organisation
        global logger_pi_ager_paths
        global logger_pi_ager_plotting

        logger_main = logging.getLogger('main.py')
        logger_pi_ager_database = logging.getLogger('pi_ager_database.py')
        logger_pi_ager_gpio_config = logging.getLogger('pi_ager_gpio_config.py')
        logger_pi_ager_init = logging.getLogger('pi_ager_init.py')
        logger_pi_ager_loop = logging.getLogger('pi_ager_loop.py')
        logger_pi_ager_names = logging.getLogger('pi_ager_names.py')
        logger_pi_ager_organisation = logging.getLogger('pi_ager_organisation.py')
        logger_pi_ager_paths = logging.getLogger('pi_ager_paths.py')
        logger_pi_ager_plotting = logging.getLogger('pi_ager_plotting.py')
        logger_pi_ager_logging.debug('logging für main initialisiert')
        
    elif pythonfile == 'scale.py':
        global logger_scale
        global logger_scale_loop
        global logger_hx711

        logger_scale = logging.getLogger('scale.py')
        logger_scale_loop = logging.getLogger('scale_loop.py')
        logger_hx711 = logging.getLogger('hx711.py')
        logger_pi_ager_logging.debug('logging für scale initialisiert')
        
    elif pythonfile == 'agingtable.py':
        global logger_agingtable
        global logger_agingtable_loop
        global logger_pi_ager_init
        global logger_pi_ager_database

        logger_agingtable = logging.getLogger('agingtable.py')
        logger_agingtable_loop = logging.getLogger('agingtable_loop.py')
        logger_pi_ager_init = logging.getLogger('pi_ager_init.py')
        logger_pi_ager_database = logging.getLogger('pi_ager_database.py')
        logger_pi_ager_logging.debug('logging für agingtable initialisiert')
        

    logger_pi_ager_logging.debug('logging initialisiert')