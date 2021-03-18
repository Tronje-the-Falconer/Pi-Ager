#!/usr/bin/python3
"""
    basic functions
   
"""
import RPi.GPIO as gpio
import pi_ager_names
import pi_ager_gpio_config

from main.pi_ager_cl_logger import cl_fact_logger

# Function goodbye
def goodbye():
    """
    last function for clean up system
    """
    # global logger
    cleanup()
    logstring = _('goodbye') + '!'
    cl_fact_logger.get_instance().info(logstring)

# Function cleanup
def cleanup():
    """
    clean up gpio
    """
    # global logger
    cl_fact_logger.get_instance().check_website_logfile()
    if pi_ager_gpio_config.gpios_are_in_use():
        logstring = _('running cleanup script') + '...'
        cl_fact_logger.get_instance().info(logstring)
        gpio.cleanup() # gpio zuruecksetzen
        logstring = _('cleanup complete') + '.'
    else:
        logstring = _('nothing to cleanup') + '!'

    cl_fact_logger.get_instance().info(logstring)
    cl_fact_logger.get_instance().info(pi_ager_names.logspacer)