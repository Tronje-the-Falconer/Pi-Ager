#!/usr/bin/python3
import scale_loop
import pi_ager_database
import pi_ager_names
import pi_ager_logging 

try:
    global logger
    logger = pi_ager_logging .create_logger('scale')
    logger.debug('logging initialised')
    scale_loop.doScaleLoop()

except KeyboardInterrupt:
    logger.warning(_('KeyboardInterrupt'))
    pass

except Exception as e:
    pi_ager_database.write_current_value(pi_ager_names.calibrate_scale1_key,0)
    pi_ager_database.write_current_value(pi_ager_names.calibrate_scale2_key,0)
    pi_ager_database.write_current_value(pi_ager_names.calibrate_weight_key,0)
    pi_ager_database.write_current_value(pi_ager_names.status_tara_scale1_key,0)
    pi_ager_database.write_current_value(pi_ager_names.status_tara_scale2_key,0)
    logstring = _('exception occurred') + '!!!'
    logger.exception(logstring, exc_info = True)
    pass
