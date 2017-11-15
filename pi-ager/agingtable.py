#!/usr/bin/python3
import sys
from pi_ager_logging import create_logger
import agingtable_loop
import pi_ager_database
import pi_ager_names

global logger
logger = create_logger('agingtable')
logger.debug('logging initialised')

try:
    agingtable_loop.doAgingtableLoop()
    
except KeyboardInterrupt:
    pi_ager_database.write_current_value(pi_ager_names.agingtable_period_key, 0)
    logger.warning('KeyboardInterrupt')
    pass

except Exception as e:
    logstring = _('exception occurred') + '!!!'
    logger.exception(logstring, exc_info = True)
    pass
    
finally:
    pi_ager_database.write_stop_in_database(pi_ager_names.status_agingtable_key)
