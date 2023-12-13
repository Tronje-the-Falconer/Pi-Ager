#!/usr/bin/python3
"""
    inital settings for pi-ager
    
    setting up inital settings
"""
import Adafruit_DHT
import time
import gettext
import pi_ager_database
import pi_ager_names
import pi_ager_gpio_config
# import pi_ager_logging
import pi_sht1x
from main.pi_ager_cl_logger import cl_fact_logger

global system_starttime
global circulation_air_start
global exhaust_air_start
global uv_starttime
global uv_stoptime
global light_starttime
global light_stoptime
# global logger
global sensortype

# logger = pi_ager_logging.create_logger(__name__)
# logger.debug('logging initialised')
cl_fact_logger.get_instance().debug(('logging initialised __________________________'))

# Function zum Setzen des Sensors
def set_sensortype():
    """
    setting up sensortype
    """
    global sensor
    global sensortype
    global sensorname
    global sensorvalue
    # global logger

    # logger.debug('set_sensortype()')
    cl_fact_logger.get_instance().debug('set_sensortype()')

    # Sensortyp
    sensortype = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.sensortype_key)
    sensorname = cl_fact_main_sensor_type.get_instance().get_sensor_type_ui()
    # logger.info(_('sensortype set to') + ' ' + sensorname)
    cl_fact_logger.get_instance().info(_('sensortype set to') + ' ' + sensorname)

def set_system_starttime():
    """
    setting starttimes
    """
    global system_starttime
    global circulation_air_start
    global exhaust_air_start
    global uv_starttime
    global uv_stoptime
    global light_starttime
    global light_stoptime
    global defrost_cycle_start
    # global logger

    # logger.debug('set_system_starttime()')
    cl_fact_logger.get_instance().debug('set_system_starttime()')
    
    system_starttime=int(time.time())
    circulation_air_start = system_starttime
    exhaust_air_start = system_starttime
    uv_starttime = system_starttime
    uv_stoptime = uv_starttime
    light_starttime = system_starttime
    light_stoptime = light_starttime
    defrost_cycle_start = system_starttime
    
def set_language():
    """
    setting up language
    """
    # global logger
    
    cl_fact_logger.get_instance().debug('set_language()')
    language = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.language_key)
    
    ####   Set up message catalog access
    # translation = gettext.translation('pi_ager', '/var/www/locale', fallback=True)
     # _ = translation.ugettext
    
    if language == 1:
        translation = gettext.translation('pi-ager', '/var/www/locale', languages=['de_DE'], fallback=True)
        cl_fact_logger.get_instance().debug('Language set to de_DE')
        cl_fact_logger.get_instance().debug('translation : ' + str(translation))
        
    elif language == 2:
        translation = gettext.translation('pi-ager', '/var/www/locale', languages=['en_GB'], fallback=True)
        cl_fact_logger.get_instance().debug('Language set to en_GB')        
        cl_fact_logger.get_instance().debug('translation : ' + str(translation))
        
    translation.install()

def setup_GPIO():
    """
    initialise GPIO's and setting default GPIO's
    """
    pi_ager_gpio_config.setupGPIO() # GPIO initialisieren
    pi_ager_gpio_config.defaultGPIO()   

loopcounter = 0                      #  Zaehlt die Durchlaeufe des Mainloops
    
# Einschaltfeuchte
switch_on_humidifier = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.switch_on_humidifier_key)
# Ausschaltfeuchte
switch_off_humidifier = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.switch_off_humidifier_key)
# Luftbefeuchtungsverzoegerung
delay_humidify = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.delay_humidify_key)


