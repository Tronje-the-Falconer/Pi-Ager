import RPi.GPIO as gpio

#---------------------------------------------------------------------------------- Function goodbye
def goodbye():
    cleanup()
    logstring = _('goodbye') + '!'
    write_verbose(logstring, False, False)

#---------------------------------------------------------------------------------- Function cleanup
def cleanup():
    logstring = _('running cleanup script') + '...'
    write_verbose(logstring, False, False)
    gpio.cleanup() # GPIO zuruecksetzen
    logstring = _('cleanup complete') + '.'
    write_verbose(logstring, True, False)

#---------------------------------------------------------------------------------- Function write verbose
def write_verbose(logstring, newLine=False, print_in_logfile=False):
    global verbose
    
    print(logstring)
    if(newLine is True):
        print('')
    if (print_in_logfile is True):
        logfile_txt = open(logfile_txt_file, 'a')           # Variable target = logfile.txt oeffnen
        logfile_txt.write(logstring)
        logfile_txt.close
