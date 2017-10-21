import logging
from logging.handlers import RotatingFileHandler
import pi_ager_names
import pi_ager_paths
import pi_ager_database


def get_logginglevel(loglevelstring):
    # CRITICAL	50
    # ERROR	40
    # WARNING	30
    # INFO	20
    # DEBUG	10
    # NOTSET
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

def create_logger():
    #loglevel_file = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.loglevel_file_key)
    #loglevel_console = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.loglevel_console_key)
    loglevel_file_value = pi_ager_database.get_logging_value('loglevel_file')
    loglevel_console_value = pi_ager_database.get_logging_value('loglevel_console')
    
    # Logger fuer logfile auf website
    logging.basicConfig(level=logging.DEBUG)
                        #format='%(asctime)s -8s %(message)s',
                        #datefmt='%m-%d %H:%M',
                        #filename=pi_ager_paths.logfile_txt_file,
                        #filemode='a')
    

    #Logger fuer website
    website_log = logging.FileHandler(pi_ager_paths.logfile_txt_file, mode='a', encoding=None, delay=False)
    # website_log = RotatingFileHandler(pi_ager_paths.logfile_txt_file, mode='a', maxBytes=5242880, backupCount=4, encoding=None, delay=False)
    website_log.setLevel(get_logginglevel(loglevel_file_value))
    website_log_formatter = logging.Formatter('%(asctime)s %(name)-27s %(levelname)-8s %(message)s', '%m-%d %H:%M')
    website_log.setFormatter(website_log_formatter)
    logging.getLogger('').addHandler(website_log)
    
    # Logger fuer die Console
    console = logging.StreamHandler()
    console.setLevel(get_logginglevel(loglevel_console_value))
    # set a format which is simpler for console use
    console_formatter = logging.Formatter(' %(levelname)-10s: %(name)-8s %(message)s')
    # tell the handler to use this format
    console.setFormatter(console_formatter)
    # add the handler to the root logger
    logging.getLogger('').addHandler(console)
    
    #Logger fuer pi-ager debugging
    # pi_ager_logger = logging.FileHandler(pi_ager_paths.pi_ager_log_file, mode='a', encoding=None, delay=False)
    pi_ager_logger = RotatingFileHandler(pi_ager_paths.pi_ager_log_file, mode='a', maxBytes=5242880, backupCount=4, encoding=None, delay=False)
    pi_ager_logger.setLevel(get_logginglevel(loglevel_file_value))
    pi_ager_logger_formatter = logging.Formatter('%(asctime)s %(name)-27s %(levelname)-8s %(message)s', '%m-%d %H:%M')
    pi_ager_logger.setFormatter(pi_ager_logger_formatter)
    logging.getLogger('').addHandler(pi_ager_logger)
    
    
    # Verschiedene Logger um Einzelebenen zu erkennen
    global logger_agingtable
    global logger_hx711
    global logger_main
    global logger_pi_ager_database
    global logger_pi_ager_gpio_config
    global logger_pi_ager_init
    global logger_pi_ager_loop
    global logger_pi_ager_names
    global logger_pi_ager_organisation
    global logger_pi_ager_paths
    global logger_pi_ager_plotting
    global logger_pi_ager_logging
    global logger_scale
    global logger_scale_loop
    
    logger_agingtable = logging.getLogger('agingtable.py')
    logger_hx711 = logging.getLogger('hx711.py')
    logger_main = logging.getLogger('main.py')
    logger_pi_ager_database = logging.getLogger('pi_ager_database.py')
    logger_pi_ager_gpio_config = logging.getLogger('pi_ager_gpio_config.py')
    logger_pi_ager_init = logging.getLogger('pi_ager_init.py')
    logger_pi_ager_loop = logging.getLogger('pi_ager_loop.py')
    logger_pi_ager_names = logging.getLogger('pi_ager_names.py')
    logger_pi_ager_organisation = logging.getLogger('pi_ager_organisation.py')
    logger_pi_ager_paths = logging.getLogger('pi_ager_paths.py')
    logger_pi_ager_plotting = logging.getLogger('pi_ager_plotting.py')
    logger_pi_ager_logging = logging.getLogger('pi_ager_logging.py')
    logger_scale = logging.getLogger('scale.py')
    logger_scale_loop = logging.getLogger('scale_loop.py')
    
    
    logger_pi_ager_logging.debug('logging initialisiert')
# Beispiele
    # logging.info('Dies landet auf der Konsole. und im File')
    # logger1.debug('landet nur im file')
    # logger1.info('landet auf Konsole und im File mit Info zu logger1 ')
    # logger2.warning('landet auf Konsole unf im File mit Info zu logger2')
    # logger2.error('landet auf Konsole unf im File mit Info zu logger2')