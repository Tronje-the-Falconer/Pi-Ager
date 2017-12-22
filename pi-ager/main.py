#!/usr/bin/python3
# -*- coding: iso-8859-1 -*-
# Importieren der Module
import os
import time
import pi_ager_logging
pi_ager_logging.create_logger('main.py')
import pi_ager_loop
import pi_ager_init
import pi_ager_organization
import pi_ager_names
import pi_ager_database
import pi_revision

global logger

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

try:
    pi_ager_loop.autostart_loop()

except KeyboardInterrupt:
    logger.warning(_('KeyboardInterrupt'))
    pass

except Exception as e:
    logstring = _('exception occurred') + '!!!'
    logger.exception(logstring, exc_info = True)
    pass

finally:
    pi_ager_init.loopcounter = 0
    pi_ager_database.write_stop_in_database(pi_ager_names.status_pi_ager_key)
    os.system('sudo /var/sudowebscript.sh pkillscale &')
    pi_ager_organization.goodbye()