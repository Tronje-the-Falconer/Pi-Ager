#!/usr/bin/python3
import sys
import scale_loop
from pi_ager_logging import create_logger

try:
    global logger
    logger = create_logger('scale')
    logger.debug('logging initialised')
    scale_loop.doScaleLoop()

except KeyboardInterrupt:
    logger.warning('KeyboardInterrupt')
    pass

except Exception as e:
    logstring = _('exception occurred') + '!!!'
    logger.exception(logstring, exc_info = True)
    pass
