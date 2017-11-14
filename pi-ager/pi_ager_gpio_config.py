#!/usr/bin/python3
import RPi.GPIO as gpio
import pi_ager_organization
import pi_ager_init
import pi_ager_logging

global logger
logger = pi_ager_logging.create_logger(__name__)
logger.debug('logging initialised')

# Function Setup GPIO
def setupGPIO():
    global logger
    logstring = 'setupGPIO()'
    logger.debug(logstring)
    gpio.setwarnings(False)
    
    # Board mode wird gesetzt
    gpio.setmode(pi_ager_init.board_mode)
    
    # Einstellen der GPIO PINS
    # Sensoren etc
    gpio.setup(pi_ager_init.gpio_scale_data, gpio.IN)           # Kabel Data ()
    gpio.setup(pi_ager_init.gpio_scale_sync, gpio.OUT)           # Kabel Sync ()
    
    # Relaisboard
    gpio.setup(pi_ager_init.gpio_heater, gpio.OUT)                # Heizung setzen (config.json)
    gpio.setup(pi_ager_init.gpio_cooling_compressor, gpio.OUT)    # Kuehlung setzen (config.json)
    gpio.setup(pi_ager_init.gpio_circulating_air, gpio.OUT)       # Umluft setzen (config.json)
    gpio.setup(pi_ager_init.gpio_humidifier, gpio.OUT)            # Befeuchter setzen (config.json)
    gpio.setup(pi_ager_init.gpio_exhausting_air, gpio.OUT)        # Abluft setzen (config.json)
    gpio.setup(pi_ager_init.gpio_light, gpio.OUT)                  # Licht setzen (json.conf)
    gpio.setup(pi_ager_init.gpio_uv, gpio.OUT)               # UV-Licht setzen (json.conf)
    gpio.setup(pi_ager_init.gpio_dehumidifier, gpio.OUT)              # Reserve setzen (json.conf)


def defaultGPIO():
    global logger
    logstring = 'defaultGPIO()'
    logger.debug(logstring)
    
    gpio.output(pi_ager_init.gpio_heater, pi_ager_init.relay_off)              # Heizung Relais standardmaessig aus
    gpio.output(pi_ager_init.gpio_cooling_compressor, pi_ager_init.relay_off)  # Kuehlung Relais standardmaessig aus
    gpio.output(pi_ager_init.gpio_circulating_air, pi_ager_init.relay_off)     # Umluft Relais standardmaessig aus
    gpio.output(pi_ager_init.gpio_humidifier, pi_ager_init.relay_off)          # Befeuchter Relais standardmaessig aus
    gpio.output(pi_ager_init.gpio_exhausting_air, pi_ager_init.relay_off)      # Abluft Relais standardmaessig aus
    gpio.output(pi_ager_init.gpio_light, pi_ager_init.relay_off)               # Licht Relais standardmaessig aus
    gpio.output(pi_ager_init.gpio_uv, pi_ager_init.relay_off)                  # UV-Licht Relais standardmaessig aus
    gpio.output(pi_ager_init.gpio_dehumidifier, pi_ager_init.relay_off)        # Reserve Relais standardmaessig aus
