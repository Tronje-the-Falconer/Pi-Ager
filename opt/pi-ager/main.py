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

#Zuerst Datenbank pr�fen
# import pi_ager_database_check
# pi_ager_database_check.check_and_update_database()

from main.pi_ager_cl_logger import cl_fact_logger
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
from pi_ager_cl_nextion import cl_fact_nextion
from pi_ager_cl_switch_control import cl_fact_switch_control
from pi_ager_cl_uptime import cl_fact_uptime

import signal
import threading
import pi_ager_cl_scale
import pi_ager_cl_agingtable

def kill_mi_thermometer():
    # check if /home/pi/MiTemperature2/LYWSD03MMC.py is running and then kill this process
    stream = os.popen('pgrep -lf python3 | grep LYWSD03MMC.py | wc -l')
    output = stream.read().rstrip('\n')
    cl_fact_logger.get_instance().debug('Killing /home/pi/MiTemperature2/LYWSD03MMC.py')
    if (output != '0'):
        os.system("pgrep -lf LYWSD03MMC.py | grep LYWSD03MMC.py | awk '{print $1}' | xargs kill")
        cl_fact_logger.get_instance().debug('LYWSD03MMC.py terminated')
        
# catch signal.SIGTERM and signal.SIGINT when killing main to gracefully shutdown system
def signal_handler(signum, frame):
    cl_fact_logger.get_instance().debug('SIGTERM or SIGINT issued---------------------------------------------------------')
    pi_ager_loop.do_system_shutdown()
    # sys.exit(0) # raises SystemExit exception
   
# pi_ager_names.create_json_file()
# pi_ager_database_check.check_and_update_database()
pi_ager_init.set_language()

cl_fact_logger.get_instance().debug(('logging initialised __________________________'))
cl_fact_logger.get_instance().info('\n\n')
cl_fact_logger.get_instance().info(pi_ager_names.logspacer)
cl_fact_logger.get_instance().info(_('Pi-Ager Main started'))
cl_fact_logger.get_instance().info(pi_ager_names.logspacer)
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
cl_fact_nextion.get_instance().start()
cl_fact_logger.get_instance().debug('Starting nextion display thread done' + time.strftime('%H:%M:%S', time.localtime()))

cl_fact_logger.get_instance().debug('Starting switch control thread ' + time.strftime('%H:%M:%S', time.localtime()))
cl_fact_switch_control.get_instance().start()
cl_fact_logger.get_instance().debug('Starting switch control thread done' + time.strftime('%H:%M:%S', time.localtime()))

cl_fact_logger.get_instance().debug('Starting uptime thread ' + time.strftime('%H:%M:%S', time.localtime()))
cl_fact_uptime.get_instance().start()
cl_fact_logger.get_instance().debug('Starting uptime thread done' + time.strftime('%H:%M:%S', time.localtime()))

exception_known = True

# now enable signal handler
signal.signal(signal.SIGTERM, signal_handler)
signal.signal(signal.SIGINT, signal_handler)

# Send a start message/event
try:
    cl_fact_logic_messenger().get_instance().handle_event('Pi-Ager_started') #if the second parameter is empty, the value is take from the field envent_text in table config_messenger_event 
except Exception as cx_error:
    exception_known = cl_fact_logic_messenger().get_instance().handle_exception(cx_error)
    pass

# init defrost state off
pi_ager_database.write_startstop_status_in_database(pi_ager_names.status_defrost_key, 0)

try:
    pi_ager_loop.autostart_loop()
    cl_fact_logger.get_instance().debug('in main try at end -------------------------------------------------------------------')
except KeyboardInterrupt:
    #logger.warning(_('KeyboardInterrupt'))
    cl_fact_logger.get_instance().debug(_('KeyboardInterrupt'))
    pass

except Exception as cx_error:
    cl_fact_logger.get_instance().debug('main.exception ------------------------------------------------------------------')
    exception_known = cl_fact_logic_messenger().get_instance().handle_exception(cx_error)
    # pass

finally:
    cl_fact_logger.get_instance().debug('main.finally --------------------------------------------------------------------')
    #if exception_known == False:
    pi_ager_init.loopcounter = 0

    cl_fact_logger.get_instance().debug('waiting for all threads terminating...')   
    scale1_thread.stop_received = True
    scale2_thread.stop_received = True
    agingtable_thread.stop_received = True
    cl_fact_switch_control.get_instance().stop_received = True
    cl_fact_uptime.get_instance().stop_received = True
    
    cl_fact_nextion.get_instance().prep_show_offline()
    cl_fact_nextion.get_instance().loop.call_soon_threadsafe(cl_fact_nextion.get_instance().stop_event.set)
    cl_fact_nextion.get_instance().stop_loop()
    
    scale1_thread.join()
    scale2_thread.join()
    agingtable_thread.join()
    cl_fact_switch_control.get_instance().join()
    cl_fact_uptime.get_instance().join()
    cl_fact_nextion.get_instance().join()
    
    cl_fact_logger.get_instance().debug('threads terminated')
    
    # kill mi_thermometer process if running
    kill_mi_thermometer()

    # Send shutdown message/event
    try:
        cl_fact_logic_messenger().get_instance().handle_event('Pi-Ager_offline') #if the second parameter is empty, the value is taken from the field envent_text in table config_messenger_event 
    except Exception as cx_error:
        exception_known = cl_fact_logic_messenger().get_instance().handle_exception(cx_error)
        pass

    # release GPIO resources, send message to log
    pi_ager_organization.goodbye()
