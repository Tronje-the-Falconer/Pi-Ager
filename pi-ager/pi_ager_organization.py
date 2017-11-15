#!/usr/bin/python3
import RPi.GPIO as gpio
import pi_ager_logging
import pi_ager_names

global logger
logger = pi_ager_logging.create_logger(__name__)
logger.debug('logging initialised')

# Function goodbye
def goodbye():
    global logger
    cleanup()
    logstring = _('goodbye') + '!'
    logger.info(logstring)

# Function cleanup
def cleanup():
    global logger
    
    
    gpio_exhausting_air_usage = gpio.gpio_function(pi_ager_names.gpio_exhausting_air)
    print (gpio_exhausting_air_function)
    gpio_circulating_air_usage = gpio.gpio_function(pi_ager_names.gpio_circulating_air)
    gpio_cooling_compressor_usage = gpio.gpio_function(pi_ager_names.gpio_cooling_compressor)
    gpio_dehumidifier_usage = gpio.gpio_function(pi_ager_names.gpio_dehumidifier)
    gpio_heater_usage = gpio.gpio_function(pi_ager_names.gpio_heater)
    gpio_humidifier_usage = gpio.gpio_function(pi_ager_names.gpio_humidifier)
    gpio_light_usage = gpio.gpio_function(pi_ager_names.gpio_light)
    gpio_uv_usage = gpio.gpio_function(pi_ager_names.gpio_uv)
    
    if (gpio_uv_usage != -1 or gpio_light_usage != -1 or gpio_humidifier_usage != -1 or gpio_heater_usage != -1 or gpio_dehumidifier_usage != -1 or gpio_cooling_compressor_usage != -1 or gpio_circulating_air_usage != -1 or gpio_exhausting_air_function != -1):
        logstring = _('running cleanup script') + '...'
        logger.info(logstring)
        gpio.cleanup() # gpio zuruecksetzen
        logstring = _('cleanup complete') + '.'
    else:
        logstring = _('nothing to cleanup') + '.'
    logger.info(logstring)
    logger.info(pi_ager_names.logspacer)