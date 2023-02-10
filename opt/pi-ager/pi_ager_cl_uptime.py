#!/usr/bin/python3
"""
    thread for counting active hours
"""
import RPi.GPIO as gpio
import pi_ager_names
import pi_ager_gpio_config

if __name__ == '__main__':
    # init global threading.lock
    import globals
    globals.init()
    import gettext
    _ = gettext.gettext    
    gpio.setwarnings(False)
    gpio.setmode(pi_ager_gpio_config.board_mode)
    gpio.setup(pi_ager_gpio_config.gpio_uv, gpio.OUT )
    
import pi_ager_database
from abc import ABC
import time
import datetime
from messenger.pi_ager_cl_messenger import cl_fact_logic_messenger
from main.pi_ager_cl_logger import cl_fact_logger
import threading

class cl_uptime_thread(threading.Thread):
    def __init__(self):
        super().__init__() 
        self.stop_received = False
        self.last_time = int(time.time())
        self.uv_light_uptime = 0
        self.pi_ager_uptime = 0
        self.loop_count = 0
        
    def run(self):
        try:
            cl_fact_logger.get_instance().info(_('Start uptime loop at') + ' ' + time.strftime('%H:%M:%S', time.localtime()))
            # here init active_seconds from db
            row = pi_ager_database.get_table_row(pi_ager_names.time_meter_table, 1)
            self.uv_light_uptime = row[pi_ager_names.uv_light_seconds_field]
            self.pi_ager_uptime = row[pi_ager_names.pi_ager_seconds_field]
            self.last_time = int(time.time())
            self.do_uptime_loop()
            
        except Exception as cx_error:
            cl_fact_logic_messenger().get_instance().handle_exception(cx_error)
        
        finally:
            cl_fact_logger.get_instance().info(_('Uptime loop stopped at') + ' ' + time.strftime('%H:%M:%S', time.localtime()))
            
    def do_uptime_loop(self):
        while not self.stop_received and threading.main_thread().is_alive():
            self.loop_count += 1
            if (self.loop_count > 10):  # take timestamp every 10 seconds
                self.loop_count = 0
                new_time_stamp = int(time.time())
                if gpio.input(pi_ager_gpio_config.gpio_uv) == False:    # uv light is active
                    time_diff = new_time_stamp - self.last_time
                    # add here time-div to active hours_s
                    row = pi_ager_database.get_table_row(pi_ager_names.time_meter_table, 1)
                    self.uv_light_uptime = row[pi_ager_names.uv_light_seconds_field] + time_diff
                    pi_ager_database.update_table_field(pi_ager_names.time_meter_table, pi_ager_names.uv_light_seconds_field, self.uv_light_uptime)
            
                status_pi_ager = int(pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_pi_ager_key))
                if (status_pi_ager == True):                            # Pi-Ager running?
                    time_diff = new_time_stamp - self.last_time
                    row = pi_ager_database.get_table_row(pi_ager_names.time_meter_table, 1)
                    self.pi_ager_uptime = row[pi_ager_names.pi_ager_seconds_field] + time_diff
                    pi_ager_database.update_table_field(pi_ager_names.time_meter_table, pi_ager_names.pi_ager_seconds_field, self.pi_ager_uptime)
                 
                self.last_time = new_time_stamp
                # print("uv light uptime : " + str(datetime.timedelta(seconds=int(self.uv_light_uptime))) + " pi_ager uptime : " + str(datetime.timedelta(seconds=int(self.pi_ager_uptime))))
            time.sleep(1)

class cl_fact_uptime(ABC):
    __o_instance = None
    
    @classmethod
    def set_instance(self, i_instance):
        """
        Factory method to set the instance
        """
        cl_fact_uptime.__o_instance = i_instance
        
    @classmethod        
    def get_instance(self):
        """
        Factory method to get the instance
        """
        if cl_fact_uptime.__o_instance is not None:
            return(cl_fact_uptime.__o_instance)
        cl_fact_uptime.__o_instance = cl_uptime_thread()
        return(cl_fact_uptime.__o_instance)

    def __init__(self):
        """
        Constructor factory
        """
        pass    
            
def main():
    uptime_thread = cl_fact_uptime().get_instance()
    uptime_thread.start()
    
    try:
        while True:
            time.sleep(1)
            
    except KeyboardInterrupt:
        print("Ctrl-c received! Sending Stop to thread...")
        uptime_thread.stop_received = True
        
    uptime_thread.join()
    print('finis.')
    
if __name__ == '__main__':
    main()