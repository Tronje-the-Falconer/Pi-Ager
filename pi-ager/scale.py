#!/usr/bin/python3
import sys
import scale_loop
import pi_ager_logging

try:
    pi_ager_logging.create_logger('scale.py')
    scale_loop.doScaleLoop()

except (KeyboardInterrupt, SystemExit):
    pi_ager_logging.logger_scale.critical('KeyboardInterrupt or System Exit')
    sys.exit()
