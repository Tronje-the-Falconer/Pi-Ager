#!/usr/bin/python3
# -*- coding: iso-8859-1 -*-
"""
pi-ager dry aging fridge control

this is the control for a self-made dry aging fridge
"""

# Importieren der Module
import sys
# sys.modules[__name__].__dict__.clear()
import os
import time

import globals
# init global threading.lock
globals.init()

#Zuerst Datenbank prüfen
import pi_ager_database_check
pi_ager_database_check.check_and_update_database()

#import pi_ager_logging
from main.pi_ager_cl_logger import cl_fact_logger
#pi_ager_logging.create_logger('main.py')
import pi_ager_loop
import pi_ager_init
import pi_ager_organization
import pi_ager_names
import pi_ager_database
import pi_ager_database_check
import pi_revision
from messenger.pi_ager_cl_alarm import cl_fact_logic_alarm
from messenger.pi_ager_cl_messenger import cl_fact_logic_messenger
from sensors.pi_ager_cl_sensor_type import cl_fact_main_sensor_type

import signal
import threading
import pi_ager_cl_scale
import pi_ager_cl_agingtable
import pi_ager_cl_nextion

# catch signal.SIGTERM and signal.SIGINT when killing main to gracefully shutdown system
def signal_handler(signum, frame):
    cl_fact_logger.get_instance().debug('SIGTERM or SIGINT issued')
    pi_ager_loop.do_system_shutdown()
    # sys.exit(0) # raises SystemExit exception
   
pi_ager_names.create_json_file()
pi_ager_database_check.check_and_update_database()
pi_ager_init.set_language()

cl_fact_logger.get_instance().debug(('logging initialised __________________________'))
cl_fact_logger.get_instance().info((pi_ager_names.logspacer))

pi_revision.get_and_write_revision_in_database()

pi_ager_init.setup_GPIO()
#pi_ager_init.set_sensortype()
cl_fact_main_sensor_type().get_instance()._is_valid()
pi_ager_init.set_system_starttime()

# os.system('sudo /var/sudowebscript.sh pkillscale &')
# time.sleep(2)
# os.system('sudo /var/sudowebscript.sh startscale &')
cl_fact_logger.get_instance().debug('Starting scale threads ' + time.strftime('%H:%M:%S', time.localtime()))
scale1_thread = pi_ager_cl_scale.cl_scale_thread(0)
scale1_thread.start()
scale2_thread = pi_ager_cl_scale.cl_scale_thread(1)
scale2_thread.start() 
cl_fact_logger.get_instance().debug('Starting scale threads done ' + time.strftime('%H:%M:%S', time.localtime()))

cl_fact_logger.get_instance().debug('Starting agingtable thread ' + time.strftime('%H:%M:%S', time.localtime()))
agingtable_thread = pi_ager_cl_agingtable.cl_aging_thread()
agingtable_thread.start()
cl_fact_logger.get_instance().debug('Starting agingtable thread done' + time.strftime('%H:%M:%S', time.localtime()))

cl_fact_logger.get_instance().debug('Starting nextion display thread ' + time.strftime('%H:%M:%S', time.localtime()))
nextion_thread = pi_ager_cl_nextion.pi_ager_cl_nextion()
nextion_thread.start()
cl_fact_logger.get_instance().debug('Starting nextion display thread done' + time.strftime('%H:%M:%S', time.localtime()))

exception_known = True

# now enable signal handler
signal.signal(signal.SIGTERM, signal_handler)
signal.signal(signal.SIGINT, signal_handler)

# Send a start message
try:
    cl_fact_logic_messenger().get_instance().handle_event('Pi-Ager_started', None) #if the second parameter is empty, the value is take from the field envent_text in table config_messenger_event 
except Exception as cx_error:
    exception_known = cl_fact_logic_messenger().get_instance().handle_exception(cx_error)
    pass

try:
    pi_ager_loop.autostart_loop()

except KeyboardInterrupt:
    #logger.warning(_('KeyboardInterrupt'))
    cl_fact_logger.get_instance().debug(_('KeyboardInterrupt'))
    pass

except Exception as cx_error:
    cl_fact_logger.get_instance().debug('main.exception')
    exception_known = cl_fact_logic_messenger().get_instance().handle_exception(cx_error)
    # pass

finally:
    cl_fact_logger.get_instance().debug('main.finally')
    #if exception_known == False:
    pi_ager_init.loopcounter = 0
    pi_ager_database.write_stop_in_database(pi_ager_names.status_pi_ager_key)
    pi_ager_database.write_stop_in_database(pi_ager_names.status_scale1_key)
    pi_ager_database.write_stop_in_database(pi_ager_names.status_scale2_key)
        # os.system('sudo /var/sudowebscript.sh pkillscale &')
        # os.system('sudo /var/sudowebscript.sh pkillmain &')
    cl_fact_logger.get_instance().debug('waiting for all threads terminating...')   
    scale1_thread.stop_received = True
    scale2_thread.stop_received = True
    agingtable_thread.stop_received = True
    nextion_thread.prep_show_offline()
    nextion_thread.loop.call_soon_threadsafe(nextion_thread.stop_event.set)
    nextion_thread.stop_loop()
    
    scale1_thread.join()
    scale2_thread.join()
    agingtable_thread.join()
    nextion_thread.join()  
    
    cl_fact_logger.get_instance().debug('threads terminated')   
    pi_ager_organization.goodbye()
