#!/usr/bin/python3
# -*- coding: iso-8859-1 -*-
"""
pi-ager dry aging fridge control

this is the control for a self-made dry aging fridge
"""

# Importieren der Module
import os
import time

#Zuerst Datenbank prüfen
import pi_ager_database_check
pi_ager_database_check.check_and_update_database()

import pi_ager_logging
pi_ager_logging.create_logger('main.py')
import pi_ager_loop
import pi_ager_init
import pi_ager_organization
import pi_ager_names
import pi_ager_database
import pi_ager_database_check
import pi_revision
from pi_ager_cl_alarm import cl_fact_alarm
from pi_ager_cl_messenger import cl_fact_messenger

global logger

pi_ager_names.create_json_file()
pi_ager_database_check.check_and_update_database()
pi_ager_init.set_language()
logger = pi_ager_logging.create_logger('main')
logger.debug('logging initialised')

logger.info(pi_ager_names.logspacer)

pi_revision.get_and_write_revision_in_database()

pi_ager_init.setup_GPIO()
pi_ager_init.set_sensortype()
pi_ager_init.set_system_starttime()

os.system('sudo /var/sudowebscript.sh pkillscale &')
time.sleep(2)
os.system('sudo /var/sudowebscript.sh startscale &')
logger.debug('scale restart done')

exception_known = True

try:
    
    pi_ager_loop.autostart_loop()

except KeyboardInterrupt:
    logger.warning(_('KeyboardInterrupt'))
    pass

except Exception as cx_error:
    exception_known = cl_fact_messenger().get_instance(cx_error).send()
    pass

finally:
    if exception_known == False:
        pi_ager_init.loopcounter = 0
        pi_ager_database.write_stop_in_database(pi_ager_names.status_pi_ager_key)
        os.system('sudo /var/sudowebscript.sh pkillscale &')
        os.system('sudo /var/sudowebscript.sh pkillmain &')
        pi_ager_organization.goodbye()
