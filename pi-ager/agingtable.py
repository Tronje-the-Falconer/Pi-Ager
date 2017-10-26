#!/usr/bin/python3
import sys
import pi_ager_logging
pi_ager_logging.create_logger('agingtable.py')
import agingtable_loop

try:
    agingtable_loop.doAgingtableLoop()

except (KeyboardInterrupt, SystemExit):
    pi_ager_logging.logger_agingtable.critical('KeyboardInterrupt or System Exit')
    sys.exit()
