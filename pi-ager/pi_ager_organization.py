import RPi.GPIO as gpio
import pi_ager_paths
import pi_ager_logging

#---------------------------------------------------------------------------------- Function goodbye
def goodbye():
    cleanup()
    logstring = _('goodbye') + '!'
    pi_ager_logging.logger_pi_ager_organisation.debug(logstring)
    #write_verbose(logstring, False, False)

#---------------------------------------------------------------------------------- Function cleanup
def cleanup():
    logstring = _('running cleanup script') + '...'
    pi_ager_logging.logger_pi_ager_organisation.debug(logstring)
    #write_verbose(logstring, False, False)
    gpio.cleanup() # GPIO zuruecksetzen
    logstring = _('cleanup complete') + '.'
    pi_ager_logging.logger_pi_ager_organisation.debug(logstring)
    #write_verbose(logstring, True, False)

#---------------------------------------------------------------------------------- Function write verbose
def write_verbose(logstring, newLine=False, print_in_logfile=False):
    global verbose
    
    print(logstring)
    if(newLine is True):
        print('')
    if (print_in_logfile is True):
        logfile_txt = open(pi_ager_paths.logfile_txt_file, 'a')           # Variable target = logfile.txt oeffnen
        logfile_txt.write(logstring)
        logfile_txt.close
