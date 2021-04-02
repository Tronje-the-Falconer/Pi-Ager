# -*- coding: utf-8 -*-

"""This class is the class for pi-ager pushover notifications. """
 
__author__ = "Claus Fischer"
__copyright__ = "Copyright 2021, The Pi-Ager Project"
__credits__ = ["Claus Fischer"]
__license__ = "GPL"
__version__ = "1.0.0"
__maintainer__ = "Claus Fischer"
__email__ = "DerBurgermeister@pi-ager.org"
__status__ = "Productive"

import globals
# init global threading.lock
globals.init()

from messenger.pi_ager_cl_messenger import cl_fact_logic_messenger
from main.pi_ager_cx_exception import *
from main.pi_ager_cl_logger import cl_fact_logger
try:

    logger = cl_fact_logger.get_instance()
    logger.debug('logging initialised')
except Exception as cx_error:
    cl_fact_logic_messenger().get_instance().handle_exception(cx_error)
    
try:
    cl_fact_logic_messenger().get_instance().send_telegram('Test message from Pi-Ager: ', 'Telegram settings are ok.')
except IndexError as cx_error:
    logger.debug('Telegram settings not maintained')
