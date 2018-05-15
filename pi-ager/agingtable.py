#!/usr/bin/python3
"""
    startscript for agingtable
    
    the agingtable writes defined values from the DB to the control table of the pi-ager.
    this allows a time-defined program flow
"""
import pi_ager_logging
import agingtable_loop
import pi_ager_database
import pi_ager_names

global logger
logger = pi_ager_logging.create_logger('agingtable')
logger.debug('logging initialised')

try:
    agingtable_loop.doAgingtableLoop()
    
except KeyboardInterrupt:
    pi_ager_database.write_current_value(pi_ager_names.agingtable_period_key, 0)
    logger.warning(_('KeyboardInterrupt'))
    pass

except Exception as e:
    logstring = _('exception occurred') + '!!!'
    logger.exception(logstring, exc_info = True)
    pass
    
finally:
    pi_ager_database.write_stop_in_database(pi_ager_names.status_agingtable_key)
