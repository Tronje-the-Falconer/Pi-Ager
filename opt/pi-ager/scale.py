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
#import pi_ager_logging 
from messenger.pi_ager_cl_alarm import cl_fact_logic_alarm
from messenger.pi_ager_cl_messenger import cl_fact_logic_messenger
from main.pi_ager_cl_logger import cl_fact_logger

exception_known = True

try:
    cl_fact_logger.get_instance().debug('logging initialised __________________________')

    # logger.info("Start scale loop")
    cl_fact_logger.get_instance().info("Start scale loop")
 
    scale_loop.doScaleLoop()

except KeyboardInterrupt:
    logger.warning('KeyboardInterrupt')
    pass

except Exception as cx_error:
    
    #Create factory for messanger, get from factory the instance of the messenger, send messages in one line
    exception_known = cl_fact_logic_messenger().get_instance(cx_error).send()
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
