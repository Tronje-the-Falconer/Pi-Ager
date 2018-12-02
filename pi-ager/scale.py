#!/usr/bin/python3
"""
    main for scales
    
    
    mainfile for scale handling
"""
import sys
sys.settrace

import scale_loop
import pi_ager_database
import pi_ager_names
import pi_ager_logging 
from pi_ager_cl_alarm import cl_fact_alarm
from pi_ager_cl_messenger import cl_fact_messenger

exception_known = True

try:
    global logger
    logger = pi_ager_logging .create_logger('scale')
    logger.debug('logging initialised')

    #Create factory for alarm, get from factory the instance of the alarm, execute alarm in one line
    #cl_fact_alarm().get_instance().execute()
    
    logger.info("Start scale loop")
    scale_loop.doScaleLoop()

except KeyboardInterrupt:
    logger.warning('KeyboardInterrupt')
    pass

except Exception as cx_error:
    #logger.info("Exception raised: " + type(cx_error).__name__  )
    #logger.exception(cx_error, exc_info = True)  
    #Create factory for alarm, get from factory the instance of the alarm, execute alarm in one line
    #cl_fact_alarm().get_instance().execute()
    
    #Create factory for messanger, get from factory the instance of the messenger, send messages in one line
    exception_known = cl_fact_messenger().get_instance(cx_error).send()
    pi_ager_database.write_current_value(pi_ager_names.calibrate_scale1_key,0)
    pi_ager_database.write_current_value(pi_ager_names.calibrate_scale2_key,0)
    pi_ager_database.write_current_value(pi_ager_names.calibrate_weight_key,0)
    pi_ager_database.write_current_value(pi_ager_names.status_tara_scale1_key,0)
    pi_ager_database.write_current_value(pi_ager_names.status_tara_scale2_key,0)
finally:
    if exception_known == False:
        pi_ager_init.loopcounter = 0
        pi_ager_database.write_stop_in_database(pi_ager_names.status_pi_ager_key)
        os.system('sudo /var/sudowebscript.sh pkillscale &')
        os.system('sudo /var/sudowebscript.sh pkillmain &')

        pi_ager_organization.goodbye()
