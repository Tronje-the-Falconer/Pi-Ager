from messenger.pi_ager_cl_messenger import cl_fact_logic_messenger
from main.pi_ager_cx_exception import *
from main.pi_ager_cl_logger import cl_fact_logger
"""
try:

    logger = cl_fact_logger.get_instance()
    logger.debug('logging initialised')

    raise cx_Sensor_not_defined
except Exception as cx_error:
    cl_fact_logic_messenger().get_instance().handle_exception(cx_error)

"""
cl_fact_logic_messenger().get_instance().handle_event('Pi-Ager_started', 'Pi-Ager was started')
#if the second parameter is None, the value is take from the field envent_text in table config_messenger_event 
cl_fact_logic_messenger().get_instance().handle_event('Pi-Ager_started', None) 
"""
try:
    cl_fact_logic_messenger.get_instance().send_pushover('Hallo', 'Pushover Hello')
except IndexError as cx_error:
    logger.debug('Pushover settings not maintained')

try:
    cl_fact_logic_messenger().get_instance().send_mail('Hallo', 'Mail Message_test')
except IndexError as cx_error:
    logger.debug('E-Mail settings not maintained')
try:
    cl_fact_logic_messenger().get_instance().alarm(alarm = 'short')
except IndexError as cx_error:
    logger.debug('Alarm settings not maintained')

"""