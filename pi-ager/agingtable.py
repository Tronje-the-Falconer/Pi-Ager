#!/usr/bin/python3
import sys
import agingtable_loop
import pi_ager_logging

try:
    pi_ager_logging.create_logger('agingtable.py')
    agingtable_loop.doAgingtableLoop()

except (KeyboardInterrupt, SystemExit):
    pi_ager_logging.logger_scale.critical('KeyboardInterrupt or System Exit')
    sys.exit()
