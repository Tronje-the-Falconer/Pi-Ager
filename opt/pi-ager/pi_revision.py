#!/usr/bin/python3
"""
    getting revision for pi-ager
    
    
"""
import pi_ager_names
import pi_ager_database
import pi_ager_logging

global logger
logger = pi_ager_logging.create_logger(__name__)

def get_and_write_revision_in_database():
    """
    read revision from database
    """
    # Extract board revision from cpuinfo file
    # Overvolted 100 prefix bsp. 1000002
    myrevision = "0000"
    try:
        f = open('/proc/cpuinfo','r')
        for line in f:
          if line[0:8]=='Revision':
            length=len(line)
            myrevision = line[11:length-1]
        f.close()
    except:
        myrevision = "0000"
    
    logger.debug('pi revision: ' + myrevision)
    pi_ager_database.update_value_in_table(pi_ager_names.system_table, pi_ager_names.pi_revision_key, myrevision)
    
    return myrevision

