#!/usr/bin/python3
import RPi.GPIO as gpio
import pi_ager_paths
import pi_ager_logging

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
    logstring = _('running cleanup script') + '...'
    logger.info(logstring)
    gpio.cleanup() # GPIO zuruecksetzen
    logstring = _('cleanup complete') + '.'
    logger.info(logstring)
    logger.info(pi_ager_init.logspacer)