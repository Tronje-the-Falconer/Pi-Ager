from messenger.pi_ager_cl_messenger import cl_fact_logic_messenger
from main.pi_ager_cx_exception import *
#import pi_ager_logging.
#import logging
from main.pi_ager_cl_logger import cl_fact_logger
from messenger.pi_ager_cl_pushover import cl_fact_logic_pushover, cl_logic_pushover
try:

    logger = cl_fact_logger.get_instance()
    logger.debug('logging initialised')

    raise cx_Sensor_not_defined
except Exception as cx_error:
    cl_fact_logic_messenger().get_instance().handle_exception(cx_error)
try:
    cl_fact_logic_pushover.get_instance().execute('Hallo', 'Pushover')
except IndexError as cx_error:
    logger.debug('Pushover settings not maintained')

try:
    cl_fact_logic_messenger().get_instance().send_mail('Hallo', 'Message_test')
except IndexError as cx_error:
    logger.debug('E-Mail settings not maintained')
try:
    cl_fact_logic_messenger().get_instance().alarm(alarm = 'short')
except IndexError as cx_error:
    logger.debug('Alarm settings not maintained')

