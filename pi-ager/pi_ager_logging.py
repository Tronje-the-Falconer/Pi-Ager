import logging
import pi_ager_names
import pi_ager_paths

def get_logginglevel(loglevelstring):
    if loglevelstring == 'DEBUG':
        loglevel = logging.DEBUG
    elif loglevelstring == 'INFO':
        loglevel = logging.INFO
    elif loglevelstring == 'WARNING':
        loglevel = logging.WARNING
    elif loglevelstring == 'ERROR':
        loglevel = logging.ERROR
    elif loglevelstring == 'CRITICAL':
        loglevel = logging.CRITICAL
    
    return loglevel

def create_logger():
    loglevel_file = pi_ager_names.loglevel_file
    loglevel_console = pi_ager_names.loglevel_console
    
    
    # set up logging to file - see previous section for more details
    logging.basicConfig(level=get_logginglevel(loglevel_file),
                        format='%(asctime)s %(name)-12s %(levelname)-8s %(message)s',
                        datefmt='%m-%d %H:%M',
                        filename=pi_ager_paths.pi_ager_log_file,
                        filemode='w')
    # define a Handler which writes INFO messages or higher to the sys.stderr
    console = logging.StreamHandler()
    console.setLevel(get_logginglevel(loglevel_console))
    # set a format which is simpler for console use
    console_formatter = logging.Formatter('%(name)-12s: %(levelname)-8s %(message)s')
    # tell the handler to use this format
    console.setFormatter(console_formatter)
    # add the handler to the root logger
    logging.getLogger('').addHandler(console)
    
    website_logfile = logging.FileHandler(pi_ager_paths.logfile_txt_file, mode='a', encoding=None, delay=False)
    website_logfile.setLevel(logging.INFO)
    website_formatter = logging.Formatter('%(asctime)s -8s %(message)s')
    website_logfile.setFormatter(website_formatter)
    logging.getLogger('').addHandler(website_logfile)
    
    # Verschiedene Logger um Einzelebenen zu erkennen
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
    logger_scale = logging.getLogger('scale.py')
    logger_scale_loop = logging.getLogger('scale_loop.py')
    

# Beispiele
    # logging.info('Dies landet auf der Konsole. und im File')
    # logger1.debug('landet nur im file')
    # logger1.info('landet auf Konsole und im File mit Info zu logger1 ')
    # logger2.warning('landet auf Konsole unf im File mit Info zu logger2')
    # logger2.error('landet auf Konsole unf im File mit Info zu logger2')