from messenger.pi_ager_cl_messenger import cl_fact_logic_messenger, cl_logic_messenger
from main.pi_ager_cx_exception import *
from main.pi_ager_cl_logger import cl_fact_logger

try:

    cl_fact_logger.get_instance().debug('logging initialised')


    raise cx_Sensor_not_defined
except Exception as cx_error:
    cl_fact_logic_messenger().get_instance().handle_exception(cx_error)


#cl_fact_logic_messenger().get_instance().send_mail('Hallo', 'Message_test')
cl_fact_logic_messenger().get_instance().alarm('long')

