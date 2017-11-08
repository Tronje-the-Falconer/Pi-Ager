#!/usr/bin/python3
import Adafruit_DHT
import RPi.GPIO as gpio
import time
import gettext
import pi_ager_database
import pi_ager_names
import pi_ager_logging


global logspacer
global board_mode
global system_starttime
global system_starttime
global circulation_air_start
global exhaust_air_start
global uv_starttime
global uv_stoptime
global light_starttime
global light_stoptime

# Function zum Setzen des Sensors
def set_sensortype():
    global sensor
    global sensorname
    global sensorvalue

    pi_ager_logging.logger_pi_ager_init.debug('begin set_sensortype')

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

    pi_ager_logging.logger_pi_ager_init.debug('end set_sensortype')

def set_system_starttime():
    global system_starttime
    global circulation_air_start
    global exhaust_air_start
    global uv_starttime
    global uv_stoptime
    global light_starttime
    global light_stoptime

    pi_ager_logging.logger_pi_ager_init.debug('begin set_system_starttime')

    system_starttime=int(time.time())
    circulation_air_start = system_starttime
    exhaust_air_start = system_starttime
    uv_starttime = system_starttime
    uv_stoptime = uv_starttime
    light_starttime = system_starttime
    light_stoptime = light_starttime

    pi_ager_logging.logger_pi_ager_init.debug('end set_system_starttime')

def set_language():

    ####   Set up message catalog access
    # translation = gettext.translation('pi_ager', '/var/www/locale', fallback=True)
    # _ = translation.ugettext

    pi_ager_logging.logger_pi_ager_init.debug('begin set_language')
    
    if language == 1:
        translation = gettext.translation('pi_ager', '/var/www/locale', languages=['en'], fallback=True)
    elif language == 2:
        translation = gettext.translation('pi_ager', '/var/www/locale', languages=['de'], fallback=True)

    translation.install()

    pi_ager_logging.logger_pi_ager_init.debug('end set_language')

logspacer = "***********************************************"
logspacer2 = '-------------------------------------------------------'

# delay = 4                      # Wartezeit in der Schleife
# counter_humidify = 0           # Zaehler fuer die Verzoegerung der Befeuchtung

# status_exhaust_fan = False     # Variable fuer die "Evakuierung" zur Feuchtereduzierung durch (Abluft-)Luftaustausch
# verbose = True                 # Dokumentiert interne Vorgaenge wortreich

# Allgemeingueltige Werte aus config.json
# Sensortyp
sensortype = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.sensortype_key)
# Sprache der Textausgabe
language = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.language_key)
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

# Sainsmart Relais Vereinfachung 0 aktiv
relay_on = False               # negative Logik!!! des Relay's, Schaltet bei 0 | GPIO.LOW  | False  ein
relay_off = (not relay_on)     # negative Logik!!! des Relay's, Schaltet bei 1 | GPIO.High | True aus

# RRD-Tool
rrd_dbname = 'pi-ager'                   # Name fuer Grafiken etc
rrd_filename = rrd_dbname + '.rrd'   # Dateinamen mit Endung
measurement_time_interval = 10       # Zeitintervall fuer die Messung in Sekunden
loopcounter = 0                      #  Zaehlt die Durchlaeufe des Mainloops

# Pinbelegung
board_mode = gpio.BCM              # GPIO board mode (BCM = Broadcom SOC channel number - numbers after GPIO Bsp. GPIO12=12 [GPIO.BOARD = Pin by number Bsp: GPIO12=32])
gpio_cooling_compressor = 4        # GPIO fuer Kuehlschrankkompressor
gpio_heater = 3                    # GPIO fuer Heizkabel
gpio_humidifier = 18               # GPIO fuer Luftbefeuchter
gpio_circulating_air = 24          # GPIO fuer Umluftventilator
gpio_exhausting_air = 23           # GPIO fuer Austauschluefter
gpio_uv = 25                       # GPIO fuer UV Licht
gpio_light = 8                     # GPIO fuer Licht
gpio_dehumidifier = 7              # GPIO fuer Entfeuchter
gpio_sensor_data = 17              # GPIO fuer Data Temperatur/Humidity Sensor
gpio_sensor_sync = 27              # GPIO fuer Sync Temperatur/Humidity Sensor
gpio_scale_data = 10               # GPIO fuer Waage Data
gpio_scale_sync = 9                # GPIO fuer Waage Sync
gpio_recerved1 = 2                 # GPIO Reserve 1
gpio_recerved2 = 11                # GPIO Reserve 2

# Sprache
set_language()
