#!/usr/bin/python3
import Adafruit_DHT
import time
import gettext
import pi_ager_database
import pi_ager_names
import pi_ager_gpio_config
import pi_ager_logging
import pi_sht1x

global system_starttime
global circulation_air_start
global exhaust_air_start
global uv_starttime
global uv_stoptime
global light_starttime
global light_stoptime
global logger
global sensortype

logger = pi_ager_logging.create_logger(__name__)
logger.debug('logging initialised')

# Function zum Setzen des Sensors
def set_sensortype():
    global sensor
    global sensortype
    global sensorname
    global sensorvalue
    global logger

    logger.debug('set_sensortype()')

    # Sensortyp
    sensortype = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.sensortype_key)
    if sensortype == 1: #DHT
        sensor = Adafruit_DHT.DHT11
        sensorname = 'DHT11'
        sensorvalue = 1
    elif sensortype == 2: #DHT22
        sensor = Adafruit_DHT.DHT22
        sensorname = 'DHT22'
        sensorvalue = 2
    elif sensortype == 3: #SHT
        #sensor = Adafruit_DHT.AM2302
        sensor = 'SHT'
        sensorname = 'SHT'
        sensorvalue = 3
    check_sensor(sensorname, sensor)
    logger.info(_('sensortype set to') + ' ' + sensorname)
        
def check_sensor(sensorname, sensor):
    global sensortype
    logger.debug('check_sensor()')
    try:
        if sensorname == 'SHT':
            sensor_sht = pi_sht1x.SHT1x(pi_ager_names.gpio_sensor_data, pi_ager_names.gpio_sensor_sync, gpio_mode=pi_ager_names.board_mode)
            sensor_sht.read_temperature()
            sensor_sht.read_humidity()
            value_sht_temperature = sensor_sht.temperature_celsius
            value_sht_humidity = sensor_sht.humidity
            
            if value_sht_temperature > 100:
                raise Exception
        else:
            value_dht_humidity, value_temperature = Adafruit_DHT.read_retry(sensor, pi_ager_names.gpio_sensor_data)
            
            if value_temperature > 100:
                raise Exception
    except:
        if sensorname == 'SHT':
            sensortype = 2
        elif sensorname == 'DHT22':
            sensortype = 1
        else:
            sensortype = 3
        pi_ager_database.update_value_in_table(pi_ager_names.config_settings_table, pi_ager_names.sensortype_key, sensortype)
        logger.info(_('wrong sensortype in settings'))
        set_sensortype()

def set_system_starttime():
    global system_starttime
    global circulation_air_start
    global exhaust_air_start
    global uv_starttime
    global uv_stoptime
    global light_starttime
    global light_stoptime
    global logger

    logger.debug('set_system_starttime()')
    
    system_starttime=int(time.time())
    circulation_air_start = system_starttime
    exhaust_air_start = system_starttime
    uv_starttime = system_starttime
    uv_stoptime = uv_starttime
    light_starttime = system_starttime
    light_stoptime = light_starttime

def set_language():
    global logger
    
    logger.debug('set_language()')
    # Sprache der Textausgabe
    language = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.language_key)
    
    ####   Set up message catalog access
    # translation = gettext.translation('pi_ager', '/var/www/locale', fallback=True)
     # _ = translation.ugettext
    
    if language == 1:
        translation = gettext.translation('pi_ager', '/var/www/locale', languages=['en'], fallback=True)
    elif language == 2:
        translation = gettext.translation('pi_ager', '/var/www/locale', languages=['de'], fallback=True)

    translation.install()

def setup_GPIO():
    pi_ager_gpio_config.setupGPIO() # GPIO initialisieren
    pi_ager_gpio_config.defaultGPIO()   

loopcounter = 0                      #  Zaehlt die Durchlaeufe des Mainloops
    
# Einschalttemperatur
switch_on_cooling_compressor = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.switch_on_cooling_compressor_key)
# Ausschalttemperatur
switch_off_cooling_compressor = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.switch_off_cooling_compressor_key)
# Einschaltfeuchte
switch_on_humidifier = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.switch_on_humidifier_key)
# Ausschaltfeuchte
switch_off_humidifier = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.switch_off_humidifier_key)
# Luftbefeuchtungsverzoegerung
delay_humidify = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.delay_humidify_key)


