#!/usr/bin/python3
import sys
from pi_ager_logging import create_logger
import agingtable_loop

global logger
logger = create_logger('agingtable')
logger.debug('logging initialised')

try:
    agingtable_loop.doAgingtableLoop()
    
except KeyboardInterrupt:
    logger.warning('KeyboardInterrupt')
    pass

except Exception as e:
    pi_ager_database.write_stop_in_database(pi_ager_names.status_agingtable_key)
    logstring = _('exception occurred') + '!!!'
    logger.exception(logstring, exc_info = True)
    pass
