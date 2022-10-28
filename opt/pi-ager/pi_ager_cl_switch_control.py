#!/usr/bin/python3
"""
    thread for switch control
"""
import pi_ager_names
import pi_ager_gpio_config
import RPi.GPIO as gpio
from abc import ABC
import time
import globals
if __name__ == '__main__':
    import gettext
    # import globals
    # init global threading.lock
    globals.init()
    gpio.setwarnings(False)
    gpio.setmode(pi_ager_gpio_config.board_mode)
    gpio.setup(pi_ager_gpio_config.gpio_uv, gpio.OUT )   
    gpio.setup(pi_ager_gpio_config.gpio_light, gpio.OUT )          
    gpio.setup(pi_ager_gpio_config.gpio_switch, gpio.IN )   # manueller Schalter setzen

from messenger.pi_ager_cl_messenger import cl_fact_logic_messenger
from main.pi_ager_cl_logger import cl_fact_logger
import threading

class cl_switch_control_thread(threading.Thread):

    def __init__(self):
        super().__init__() 
        self.stop_received = False
        
    def run(self):
        try:
            cl_fact_logger.get_instance().info(_('Start switch control loop at') + ' ' + time.strftime('%H:%M:%S', time.localtime()))
            self.do_swich_control_loop()
            
        except Exception as cx_error:
            cl_fact_logic_messenger().get_instance().handle_exception(cx_error)
        
        finally:
            cl_fact_logger.get_instance().info(_('Switch control loop stopped at') + ' ' + time.strftime('%H:%M:%S', time.localtime()))
            
            
    def do_swich_control_loop(self):
        while not self.stop_received and threading.main_thread().is_alive():
            # copy states from global to local to avoid race conditions
            current_switch_state = gpio.input(pi_ager_gpio_config.gpio_switch)
            switch_control_uv_light = globals.switch_control_uv_light
            switch_control_light = globals.switch_control_light
            requested_state_uv_light = globals.requested_state_uv_light
            requested_state_light = globals.requested_state_light
            
            if switch_control_uv_light == 0:    # requested state is output state
                gpio.output(pi_ager_gpio_config.gpio_uv, requested_state_uv_light)
            elif switch_control_uv_light == 1:    # turn off UV light when switch is open else output requested state
                if current_switch_state == True:    # switch open
                    gpio.output(pi_ager_gpio_config.gpio_uv, pi_ager_names.relay_off)
                else:
                    gpio.output(pi_ager_gpio_config.gpio_uv, requested_state_uv_light)
            elif switch_control_uv_light == 2:    # turn off UV light when switch is closed else output requested state
                if current_switch_state == False:    # switch closed
                    gpio.output(pi_ager_gpio_config.gpio_uv, pi_ager_names.relay_off)
                else:
                    gpio.output(pi_ager_gpio_config.gpio_uv, requested_state_uv_light)

            if switch_control_light == 0:    # requested state is output state
                gpio.output(pi_ager_gpio_config.gpio_light, requested_state_light)
            elif switch_control_light == 1:    # turn on light when switch is open else output requested state
                if current_switch_state == True:    # switch open
                    gpio.output(pi_ager_gpio_config.gpio_light, pi_ager_names.relay_on)
                else:
                    gpio.output(pi_ager_gpio_config.gpio_light, requested_state_light)
            elif switch_control_light == 2:    # turn on light when switch is closed else output requested state
                if current_switch_state == False:    # switch closed
                    gpio.output(pi_ager_gpio_config.gpio_light, pi_ager_names.relay_on)
                else:
                    gpio.output(pi_ager_gpio_config.gpio_light, requested_state_light)

            time.sleep(0.4)

class cl_fact_switch_control(ABC):
    __o_instance = None
    
    @classmethod
    def set_instance(self, i_instance):
        """
        Factory method to set the nextion instance
        """
        cl_fact_switch_control.__o_instance = i_instance
        
    @classmethod        
    def get_instance(self):
        """
        Factory method to get the nextion instance
        """
        if cl_fact_switch_control.__o_instance is not None:
            return(cl_fact_switch_control.__o_instance)
        cl_fact_switch_control.__o_instance = cl_switch_control_thread()
        return(cl_fact_switch_control.__o_instance)

    def __init__(self):
        """
        Constructor nextion factory
        """
        pass    
            
def main():
    switch_control_thread = cl_switch_control_thread()
    switch_control_thread.start()
    
    try:
        while True:
            time.sleep(1)
            
    except KeyboardInterrupt:
        print("Ctrl-c received! Sending Stop to thread...")
        switch_control_thread.stop_received = True
        
    switch_control_thread.join()
    print('finis.')
    
if __name__ == '__main__':
    main()