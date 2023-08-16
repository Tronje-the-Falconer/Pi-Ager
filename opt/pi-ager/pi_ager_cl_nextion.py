#!/usr/bin/python3
"""
    thread for nextion HMI display

    mainfile for communication between Pi-Ager and HMI Display via serial port
    Serial port has to be enabled and login disabled using raspi-config
    
"""
import globals
if __name__ == '__main__':
    # import globals
    # init global threading.lock
    globals.init()

from abc import ABC   
import subprocess
import time
import asyncio
import signal
# import logging
import random
from collections import namedtuple
import RPi.GPIO as gpio

import pi_ager_database
import pi_ager_names
import pi_ager_gpio_config

# from messenger.pi_ager_cl_messenger import cl_fact_logic_messenger
# from main.pi_ager_cl_logger import cl_fact_logger
from pi_ager_nextion.client import Nextion, EventType
from main.pi_ager_cl_logger import cl_fact_logger

import threading

class Timer:
    def __init__(self, timeout, callback):
        self._timeout = timeout
        self._callback = callback
        self._task = None

    async def _job(self):
        await asyncio.sleep(self._timeout)
        await self._callback()

    def start(self):
        self._task = asyncio.ensure_future(self._job())
        
    def cancel(self):
        self._task.cancel()

class cl_nextion( threading.Thread ):

    def __init__( self ):
        super().__init__() 
        
        self.client = None
        self.waiter_task = None
        self.wakeup_task = None
        self.stop_event = None
        self.button_event = None
        self.wakeup_event = None
        self.reconnect_event = None
        # self.original_sigint_handler = signal.getsignal(signal.SIGINT)
        self.data = None
        self.type_ = None
        self.loop = None
        self.current_page_id = None
        self.current_theme = 'fridge'
        self.test_flag = False
        self.light_status = False
        self.light_timer = Timer(600, self.turn_off_light)
        
    def nextion_event_handler(self, type_, data):

        if type_ == EventType.STARTUP:
            cl_fact_logger.get_instance().debug('We have booted up!')
        elif type_ == EventType.TOUCH:
            cl_fact_logger.get_instance().debug('A button (id: %d) was touched on page %d' % (data.component_id, data.page_id))
            self.data = data
            self.type_ = type_
            self.current_page_id = data.page_id
            self.loop.call_soon_threadsafe(self.button_event.set)
        elif type_ == EventType.AUTO_WAKE:
            cl_fact_logger.get_instance().debug('nextion event handler found AUTO_WAKE')
            self.loop.call_soon_threadsafe(self.wakeup_event.set)
        else:    
            cl_fact_logger.get_instance().debug('Event %s data: %s', type_, str(data))
        
    async def update_light_val(self):
        if self.light_status == False:      # turn off
            await self.client.set('values.status_light.val', 0)
            if self.current_theme == 'fridge':
                await self.client.set('btn_light.pic', 12) 
            else:
                await self.client.set('btn_light.pic', 39)
        else:
            await self.client.set('values.status_light.val', 1)
            if self.current_theme == 'fridge':
                await self.client.set('btn_light.pic', 13)
            else:
                await self.client.set('btn_light.pic', 41) 
                
    async def turn_off_light(self):
        cl_fact_logger.get_instance().info(_('Light turned off after 10 minutes timeout'))
        # gpio.output(pi_ager_gpio_config.gpio_light, True)
        globals.requested_state_light = pi_ager_names.relay_off
        globals.hands_off_light_switch = False
        self.light_status = False       # turn off
        # pi_ager_database.update_value_in_table(pi_ager_names.current_values_table, pi_ager_names.status_light_key, 0)
        if self.current_theme == 'fridge':
            await self.client.set('btn_light.pic', 12) 
        else:
            await self.client.set('btn_light.pic', 39)
        await self.client.set('values.status_light.val', 0) 
        
    async def control_light_status(self):
        #light_status = await self.client.get('values.status_light.val')  
        if self.light_status == True:
            cl_fact_logger.get_instance().info(_('Light turned off'))
            self.light_status = False       # turn off
            if self.current_theme == 'fridge':
                await self.client.set('btn_light.pic', 12) 
            else:
                await self.client.set('btn_light.pic', 39)
            self.light_timer.cancel()                
            # gpio.output(pi_ager_gpio_config.gpio_light, True)
            globals.requested_state_light = pi_ager_names.relay_off
            globals.hands_off_light_switch = False
            await self.client.set('values.status_light.val', 0) 
            # pi_ager_database.update_value_in_table(pi_ager_names.current_values_table, pi_ager_names.status_light_key, 0)
            
        else:
            cl_fact_logger.get_instance().info(_('Light turned on'))
            self.light_status = True       # turn on
            if self.current_theme == 'fridge':
                await self.client.set('btn_light.pic', 13)
            else:
                await self.client.set('btn_light.pic', 41)
            self.light_timer.start()
            globals.hands_off_light_switch = True 
            globals.requested_state_light = pi_ager_names.relay_on
            await self.client.set('values.status_light.val', 1)
            # gpio.output(pi_ager_gpio_config.gpio_light, False)
            # pi_ager_database.update_value_in_table(pi_ager_names.current_values_table, pi_ager_names.status_light_key, 1)
    
    async def control_piager_start_stop(self):  # start/stop pi-ager
        button_state = await self.client.get('btn_piager.val')
        if button_state == 0:
            pi_ager_database.update_value_in_table(pi_ager_names.current_values_table, pi_ager_names.status_agingtable_key, 0)
            pi_ager_database.update_value_in_table(pi_ager_names.current_values_table, pi_ager_names.status_pi_ager_key, 0)
        else:
            pi_ager_database.update_value_in_table(pi_ager_names.current_values_table, pi_ager_names.status_pi_ager_key, 1)
    
    async def init_page_17_19(self):  # initialize values  
        temp_soll = round(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.setpoint_temperature_key))
        humitidy_soll = round(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.setpoint_humidity_key))
        modus = int(pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.modus_key))
        
        await self.client.set('n_temp_soll.val', temp_soll)
        await self.client.set('n_hum_soll.val', humitidy_soll)
        await self.client.set('n_mod.val',modus)

    async def save_page_17_19_values(self):
        #get values and check if within limits
        temp_soll = await self.client.get('n_temp_soll.val')
        hum_soll = await self.client.get('n_hum_soll.val')
        modus = await self.client.get('n_mod.val')
    
        if (temp_soll < -11):
            temp_soll = -11
            await self.client.set('n_temp_soll.val', temp_soll)
        elif (temp_soll > 70):
            temp_soll = 70
            await self.client.set('n_temp_soll.val', temp_soll)
            
        if (hum_soll < 0):
            hum_soll = 0
            await self.client.set('n_hum_soll.val', hum_soll)
        elif (hum_soll > 99):
            hum_soll = 99
            await self.client.set('n_hum_soll.val', hum_soll)
            
        if (modus < 0):
            modus = 0
            await self.client.set('n_mod.val', modus)
        elif (modus > 4):
            modus = 4
            await self.client.set('n_mod.val', modus)  
            
        pi_ager_database.update_value_in_table(pi_ager_names.config_settings_table, pi_ager_names.setpoint_temperature_key, temp_soll)    
        pi_ager_database.update_value_in_table(pi_ager_names.config_settings_table, pi_ager_names.setpoint_humidity_key, hum_soll)    
        pi_ager_database.update_value_in_table(pi_ager_names.config_settings_table, pi_ager_names.modus_key, modus)    

    async def wakeup_waiter(self, event):   # process touch screen wakeup event
        try:
            while True:
                await self.wakeup_event.wait()
                cl_fact_logger.get_instance().debug('wakeup_waiter: wakeup event happened')
                await self.update_light_val()
                self.wakeup_event.clear()
                cl_fact_logger.get_instance().debug('wakeup event processed')
        except Exception as e:
            cl_fact_logger.get_instance().error(_('wakeup_waiter stopped ') + str(e))
            pass
            
    async def button_waiter(self, event):   # process touch screen button events
        try:
            while True:
#                print('waiting for button pressed ...')
                await self.button_event.wait()
#                print('... got it!')
                cl_fact_logger.get_instance().debug('button_waiter: page_id = %d, component_id = %d' % (self.data.page_id, self.data.component_id))
                if self.data.component_id == -1:    # component_id = -1 to signal powergood event happened. Activate last active page_id
                    # await self.client.set('sleep', 0)
                    cmd = 'page ' + str(self.data.page_id)
                    await self.client.command(cmd)
                    self.current_page_id = self.data.page_id
                elif self.data.page_id == 0:
                    # await self.client.set('sleep', 0)
                    await self.client.command('page 1')
                    self.current_page_id = 1
                elif self.data.page_id == 8:    # boot_steak
                    await self.client.command('page 9')
                    self.current_page_id = 9    # main_steak
                elif self.data.page_id == 1 and self.data.component_id == 8:    # main_fridge, btn_light
                    await self.control_light_status()
                elif self.data.page_id == 1 and self.data.component_id == 7:    # main_fridge, btn_menu
                    await self.client.command('page 2')
                    self.current_page_id = 2
                elif self.data.page_id == 1 and self.data.component_id == 9:    # main_fridge, btn_wifi
                    await self.client.command('page 6')
                    self.current_page_id = 6                                    # info_fridge
                elif self.data.page_id == 2 and self.data.component_id == 6:    # menu_fridge, btn_light
                    await self.control_light_status()    
                elif self.data.page_id == 2 and self.data.component_id == 1:    # menu_fridge, btn_home
                    await self.client.command('page 1')
                    self.current_page_id = 1                    
                elif self.data.page_id == 2 and self.data.component_id == 2:    # menu_fridge, btn_states
                    await self.client.command('page 3')
                    self.current_page_id = 3  
                elif self.data.page_id == 2 and self.data.component_id == 3:    # menu_fridge, btn_values
                    await self.client.command('page 4')
                    self.current_page_id = 4  
                elif self.data.page_id == 2 and self.data.component_id == 4:    # menu_fridge, btn_settings
                    await self.client.command('page 7')
                    self.current_page_id = 7  
                elif self.data.page_id == 2 and self.data.component_id == 5:    # menu_fridge, btn_info
                    await self.init_info_page_values()
                    await self.client.command('page 6')
                    self.current_page_id = 6  
                elif self.data.page_id == 2 and self.data.component_id == 7:    # menu_fridge, btn_themes
                    if (self.current_theme == 'steak'):
                        self.current_theme = 'fridge'
                        await self.client.command('page 2')
                        self.current_page_id = 2 
                    else:
                        self.current_theme = 'steak'
                        await self.client.command('page 10')
                        self.current_page_id = 10
                elif self.data.page_id == 2 and self.data.component_id == 8:    # menu_fridge, btn_control
                    await self.client.command('page 17')
                    self.current_page_id = 17
                    await self.init_page_17_19()
                elif self.data.page_id == 2 and self.data.component_id == 9:    # menu_fridge, btn_wifi
                    await self.client.command('page 6')
                    self.current_page_id = 6 
                elif self.data.page_id == 3 and self.data.component_id == 1:    # states_fridge, btn_light
                    await self.control_light_status()
                elif self.data.page_id == 3 and self.data.component_id == 10:   # states_fridge, btn_menu
                    await self.client.command('page 2')
                    self.current_page_id = 2                    
                elif self.data.page_id == 3 and self.data.component_id == 11:   # states_fridge, btn_home
                    await self.client.command('page 1')
                    self.current_page_id = 1
                elif self.data.page_id == 3 and self.data.component_id == 12:   # states_fridge, btn_wifi
                    await self.client.command('page 6')
                    self.current_page_id = 6     
                elif self.data.page_id == 4 and self.data.component_id == 1:    # values_fridge, btn_light
                    await self.control_light_status()
                elif self.data.page_id == 4 and self.data.component_id == 2:    # values_fridge, btn_menu
                    await self.client.command('page 2')
                    self.current_page_id = 2
                elif self.data.page_id == 4 and self.data.component_id == 11:   # values_fridge, btn_home
                    await self.client.command('page 1')
                    self.current_page_id = 1
                elif self.data.page_id == 4 and self.data.component_id == 12:   # values_fridge, btn_wifi
                    await self.client.command('page 6')
                    self.current_page_id = 6
                elif self.data.page_id == 6 and self.data.component_id == 1:    # info_fridge, btn_light
                    await self.control_light_status()
                elif self.data.page_id == 6 and self.data.component_id == 2:    # info_fridge, btn_menu
                    await self.client.command('page 2')
                    self.current_page_id = 2
                elif self.data.page_id == 6 and self.data.component_id == 7:    # info_fridge, btn_home
                    await self.client.command('page 1')
                    self.current_page_id = 1
                elif self.data.page_id == 7 and self.data.component_id == 1:    # setting_fridge, btn_light
                    await self.control_light_status()
                elif self.data.page_id == 7 and self.data.component_id == 2:    # setting_fridge, btn_menu
                    await self.client.command('page 2')
                    self.current_page_id = 2
                elif self.data.page_id == 7 and self.data.component_id == 7:    # setting_fridge, btn_home
                    await self.client.command('page 1')
                    self.current_page_id = 1
                elif self.data.page_id == 7 and self.data.component_id == 12:   # states_fridge, btn_wifi
                    await self.client.command('page 6')
                    self.current_page_id = 6     
                elif self.data.page_id == 9 and self.data.component_id == 7:    # menn_steak, btn_menu
                    await self.client.command('page 10')
                    self.current_page_id = 10
                elif self.data.page_id == 9 and self.data.component_id == 8:    # main_steak, btn_light
                    await self.control_light_status()
                elif self.data.page_id == 9 and self.data.component_id == 18:   # main_steak, btn_wifi
                    await self.client.command('page 14')
                    self.current_page_id = 14     
                elif self.data.page_id == 10 and self.data.component_id == 6:   # menu_steak, btn_light
                    await self.control_light_status()
                elif self.data.page_id == 10 and self.data.component_id == 1:   # menu_steak, btn_home, goto main_steak
                    await self.client.command('page 9')
                    self.current_page_id = 9
                elif self.data.page_id == 10 and self.data.component_id == 2:   # menu_steak, btn_values, goto values_steak
                    await self.client.command('page 12')
                    self.current_page_id = 12
                elif self.data.page_id == 10 and self.data.component_id == 5:   # menu_steak, btn_themes, goto menu_steak or menu_fridge
                    if (self.current_theme == 'steak'):
                        self.current_theme = 'fridge'
                        await self.client.command('page 2')
                        self.current_page_id = 2 
                    else:
                        self.current_theme = 'steak'
                        await self.client.command('page 10')
                        self.current_page_id = 10
                elif self.data.page_id == 10 and self.data.component_id == 3:   # menu_steak, btn_settings, goto setting_steak
                    await self.client.command('page 15')
                    self.current_page_id = 15
                elif self.data.page_id == 10 and self.data.component_id == 4:   # menu_steak, btn_info, goto info_steak
                    await self.init_info_page_values()
                    await self.client.command('page 14')
                    self.current_page_id = 14
                elif self.data.page_id == 10 and self.data.component_id == 7:   # menu_steak, btn_control, goto control_steak
                    await self.client.command('page 19')
                    self.current_page_id = 19 
                    await self.init_page_17_19()
                elif self.data.page_id == 10 and self.data.component_id == 8:   # menu_steak, btn_wifi
                    await self.client.command('page 14')
                    self.current_page_id = 14     
                elif self.data.page_id == 12 and self.data.component_id == 1:   # values_steak, btn_menu
                    await self.client.command('page 10')
                    self.current_page_id = 10                     
                elif self.data.page_id == 12 and self.data.component_id == 10:  # values_steak, btn_home
                    await self.client.command('page 9')
                    self.current_page_id = 9                     
                elif self.data.page_id == 12 and self.data.component_id == 11:  # values_steak, btn_light
                    await self.control_light_status()
                elif self.data.page_id == 12 and self.data.component_id == 12:  # menu_steak, btn_wifi
                    await self.client.command('page 14')
                    self.current_page_id = 14     
                elif self.data.page_id == 14 and self.data.component_id == 1:   # info_steak, btn_menu
                    await self.client.command('page 10')
                    self.current_page_id = 10            
                elif self.data.page_id == 14 and self.data.component_id == 6:   # info_steak, btn_home
                    await self.client.command('page 9')
                    self.current_page_id = 9            
                elif self.data.page_id == 14 and self.data.component_id == 7:   # info_steak, btn_light
                    await self.control_light_status()
                elif self.data.page_id == 15 and self.data.component_id == 7:   # settings_steak, btn_menu
                    await self.client.command('page 10')
                    self.current_page_id = 10
                elif self.data.page_id == 15 and self.data.component_id == 5:   # settings_steak, btn_home
                    await self.client.command('page 9')
                    self.current_page_id = 9            
                elif self.data.page_id == 15 and self.data.component_id == 6:   # settings_steak, btn_light
                    await self.control_light_status()
                elif self.data.page_id == 15 and self.data.component_id == 12:  # settings_steak, btn_wifi
                    await self.client.command('page 14')
                    self.current_page_id = 14                         
                elif self.data.page_id == 17 and self.data.component_id == 3:   # control_fridge, btn_menu
                    await self.client.command('page 2')
                    self.current_page_id = 2
                elif self.data.page_id == 17 and self.data.component_id == 4:   # control_fridge, btn_home
                    await self.client.command('page 1')
                    self.current_page_id = 1   
                elif self.data.page_id == 17 and self.data.component_id == 2:   # control_fridge, btn_light
                    await self.control_light_status()
                elif self.data.page_id == 17 and self.data.component_id == 5:   # control_fridge, btn_piager,  start/stop
                    await self.control_piager_start_stop()
                elif self.data.page_id == 17 and self.data.component_id == 9:   # control_fridge, btn_ok, button save new Temp/Hum. values
                    await self.save_page_17_19_values()
                elif self.data.page_id == 17 and self.data.component_id == 10:  # control_fridge, btn_wifi
                    await self.client.command('page 6')
                    self.current_page_id = 6     
                elif self.data.page_id == 19 and self.data.component_id == 2:   # control_steak, btn_menu
                    await self.client.command('page 10')
                    self.current_page_id = 10
                elif self.data.page_id == 19 and self.data.component_id == 3:   # control_steak, btn_home
                    await self.client.command('page 9')
                    self.current_page_id = 9   
                elif self.data.page_id == 19 and self.data.component_id == 4:   # control_steak, btn_light
                    await self.control_light_status()
                elif self.data.page_id == 19 and self.data.component_id == 8:   # control_steak, btn_piager, pi-ager start/stop
                    await self.control_piager_start_stop()
                elif self.data.page_id == 19 and self.data.component_id == 1:   # control_steak, btn_ok,  save new Temp/Hum. values
                    await self.save_page_17_19_values()
                elif self.data.page_id == 19 and self.data.component_id == 9:   # control_steak, btn_wifi
                    await self.client.command('page 14')
                    self.current_page_id = 14    

                self.button_event.clear()
                cl_fact_logger.get_instance().debug('button pressed processed')
        except Exception as e:
            cl_fact_logger.get_instance().error(_('button_waiter stopped ') + str(e))
            pass    
    
    def get_pi_model(self):
        try:
            process = subprocess.run(['cat', '/proc/device-tree/model'], check=True, text=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
            output = process.stdout
            return output
        except Exception as e:
            return 'unknown'

    def get_wifi_ssid(self):
        try:
            process = subprocess.run(['iwgetid'], check=True, text=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
            output = process.stdout
            if output == '':
                return ''
            else:
                return (output.split('"')[1]).strip()
        except Exception as e:
            return ''

    def get_ip_address(self):
        try:
            process = subprocess.run(['hostname', '-I'], check=True, text=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
            return (process.stdout).rstrip()
        except Exception as e:
            return ''
        
    async def init_info_page_values(self):
        version = pi_ager_database.get_table_value(pi_ager_names.system_table, pi_ager_names.pi_ager_version_key )
        display_type = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.tft_display_type_key )
        
        display_name = ''
        if display_type == 1:
            display_name = 'NX3224K028'
        elif display_type == 2:
            display_name = 'NX3224F028'
        else:
            display_name = 'NX3224T028'

        await self.client.set('values.displ_version.txt', display_name)
        await self.client.set('values.sw_version.txt', version)
        
        model = self.get_pi_model()
#        print('pi model: ' + model)
        await self.client.set('values.pi_model.txt', model)
        
#        wifi_ssid = self.get_wifi_ssid()
#        await self.client.set('txt_wifi_conn.txt', wifi_ssid)
        
#        ip_address = self.get_ip_address()
#        await self.client.set('txt_ip_address.txt', ip_address)
        
        await self.client.set('values.status_light.val', 0)
        
    
    async def init_display_values(self):
        await self.init_info_page_values()

        self.current_page_id = 1
        await self.client.set('sleep', 0)
        await self.client.command('page 1') 
        await self.client.set('thsp', 60)
        
    def db_get_base_values(self):
        status_piager = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_pi_ager_key )
        
        temp_ist = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.sensor_temperature_key)
        humidity_ist = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.sensor_humidity_key)
        dewpoint_ist = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.sensor_dewpoint_key)
        humabs = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.sensor_humidity_abs_key)
        temp_soll = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.setpoint_temperature_key)
        humitidy_soll = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.setpoint_humidity_key)
    
        values = dict()
        values['status_piager'] = status_piager
        values['temp_ist'] = temp_ist
        values['humidity_ist'] = humidity_ist
        values['dewpoint_ist'] = dewpoint_ist
        values['humabs'] = humabs
        values['temp_soll'] = temp_soll
        values['humitidy_soll'] = humitidy_soll
                         
        return values
        
    def db_get_extended_values(self):
        status_piager = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_pi_ager_key )
        status_scale1 = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_scale1_key )
        status_scale2 = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_scale2_key )
        secondsensortype = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.sensorsecondtype_key)  # disabled if 0
        
        temp_meat1 = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.temperature_meat1_key)
        temp_meat2 = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.temperature_meat2_key)
        temp_meat3 = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.temperature_meat3_key)
        temp_meat4 = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.temperature_meat4_key)
        scale1 = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.scale1_key)
        scale2 = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.scale2_key)
        temp_ext = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.second_sensor_temperature_key)
        humid_ext = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.second_sensor_humidity_key)
        dewp_ext = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.second_sensor_dewpoint_key)
        humabs_ext = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.second_sensor_humidity_abs_key)
        
        values = dict()
        values['status_piager'] = status_piager
        values['status_scale1'] = status_scale1
        values['status_scale2'] = status_scale2
        values['status_secondsensor'] = secondsensortype
        values['temp_meat1'] = temp_meat1
        values['temp_meat2'] = temp_meat2        
        values['temp_meat3'] = temp_meat3        
        values['temp_meat4'] = temp_meat4        
        values['scale1'] = scale1        
        values['scale2'] = scale2
        values['temp_ext'] = temp_ext
        values['humid_ext'] = humid_ext        
        values['dewp_ext'] = dewp_ext        
        values['humabs_ext'] = humabs_ext
        
        return values
    
    def db_get_states(self):
        values = dict()
        values['circulating_air'] = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_circulating_air_key)
        values['compressor'] = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_cooling_compressor_key)
        values['exhaust_air'] = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_exhaust_air_key)
        values['heater'] = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_heater_key)
        values['light'] = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_light_key)
        values['uv'] = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_uv_key)
        values['humidifier'] = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_humidifier_key)
        values['dehumidifier'] = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_dehumidifier_key)
    
        return values
        
    async def update_states(self):
        values = self.db_get_states()
        
        if values['circulating_air'] == 0:
            await self.client.set('led_circulate.pic', 10) 
        else:
            await self.client.set('led_circulate.pic', 47) 

        if values['compressor'] == 0:
            await self.client.set('led_compressor.pic', 10) 
        else:
            await self.client.set('led_compressor.pic', 47) 

        if values['exhaust_air'] == 0:
            await self.client.set('led_exhaust.pic', 10) 
        else:
            await self.client.set('led_exhaust.pic', 47) 

        if values['heater'] == 0:
            await self.client.set('led_heater.pic', 10) 
        else:
            await self.client.set('led_heater.pic', 47) 
            
        if values['light'] == 0:
            await self.client.set('led_light.pic', 10) 
        else:
            await self.client.set('led_light.pic', 47) 
            
        if values['uv'] == 0:
            await self.client.set('led_uv.pic', 10) 
        else:
            await self.client.set('led_uv.pic', 47) 
            
        if values['humidifier'] == 0:
            await self.client.set('led_humid.pic', 10) 
        else:
            await self.client.set('led_humid.pic', 47) 
            
        if values['dehumidifier'] == 0:
            await self.client.set('led_dehumid.pic', 10) 
        else:
            await self.client.set('led_dehumid.pic', 47)  
    
    async def update_base_values(self):
        values = self.db_get_base_values()
        cl_fact_logger.get_instance().debug('Update_base_values')
        await self.client.set('txt_temp_set.txt', "%.1f" % (values['temp_soll']))
        await self.client.set('txt_humid_set.txt', "%.1f" % (values['humitidy_soll']))        
        if values['status_piager'] == 0:
            await self.client.set('txt_temp.txt', '--.-')
            await self.client.set('txt_humid.txt', '--.-')
            await self.client.set('txt_humabs.txt', '--.-')
        else:
            await self.client.set('txt_temp.txt', "%.1f" % (values['temp_ist']))
            await self.client.set('txt_humid.txt', "%.1f" % (values['humidity_ist']))        
            await self.client.set('txt_humabs.txt', "%.1f" % (values['humabs']))      
    
    async def update_info_values(self):
        wifi_ssid = self.get_wifi_ssid()
        await self.client.set('txt_wifi_conn.txt', wifi_ssid)
        
        ip_address = self.get_ip_address()
        await self.client.set('txt_ip_address.txt', ip_address)
            
    async def update_extended_values(self):
        values = self.db_get_extended_values()
        if values['status_piager'] == 0:
            await self.client.set('txt_temp_ext.txt', '--.-') 
            await self.client.set('txt_humid_ext.txt', '--.-')
            await self.client.set('txt_humabs_ext.txt', '--.-')
            await self.client.set('txt_temp_meat1.txt', '--.-')
            await self.client.set('txt_temp_meat2.txt', '--.-')
            await self.client.set('txt_temp_meat3.txt', '--.-')
        
        else:
            if values['status_secondsensor'] != 0:
                if values['temp_ext'] == None:
                    await self.client.set('txt_temp_ext.txt', '--.-')
                else:
                    await self.client.set('txt_temp_ext.txt', "%.1f" % (values['temp_ext']))
                if values['humid_ext'] == None:
                    await self.client.set('txt_humid_ext.txt', '--.-')
                else:
                    await self.client.set('txt_humid_ext.txt',"%.1f" % (values['humid_ext']))
                if values['humabs_ext'] == None:
                    await self.client.set('txt_humabs_ext.txt', '--.-')
                else:
                    await self.client.set('txt_humabs_ext.txt', "%.1f" % (values['humabs_ext']))
            else:
                await self.client.set('txt_temp_ext.txt', '--.-') 
                await self.client.set('txt_humid_ext.txt', '--.-')
                await self.client.set('txt_humabs_ext.txt', '--.-')    
                
            if values['temp_meat1'] == None:
                await self.client.set('txt_temp_meat1.txt', '--.-')
            else:
                await self.client.set('txt_temp_meat1.txt', "%.1f" % (values['temp_meat1']))
                
            if values['temp_meat2'] == None:
                await self.client.set('txt_temp_meat2.txt', '--.-')
            else:
                await self.client.set('txt_temp_meat2.txt', "%.1f" % (values['temp_meat2']))
                
            if values['temp_meat3'] == None:
                await self.client.set('txt_temp_meat3.txt', '--.-')
            else:
                await self.client.set('txt_temp_meat3.txt', "%.1f" % (values['temp_meat3']))

        if values['status_scale1'] == 0:
            await self.client.set('txt_scale1.txt', '--.-')
        else:
            await self.client.set('txt_scale1.txt', "%.0f" % (values['scale1']))

        if values['status_scale2'] == 0:
            await self.client.set('txt_scale2.txt', '--.-')
        else:
            await self.client.set('txt_scale2.txt', "%.0f" % (values['scale2'])) 

    async def update_page9_values(self):
        status_piager = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_pi_ager_key )
        secondsensortype = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.sensorsecondtype_key)  # disabled if 0
        
        temp_ist = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.sensor_temperature_key)
        humidity_ist = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.sensor_humidity_key)

        temp_soll = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.setpoint_temperature_key)
        humitidy_soll = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.setpoint_humidity_key)

        humabs_ext = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.second_sensor_humidity_abs_key)
        humabs = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.sensor_humidity_abs_key)
        
        await self.client.set('txt_temp_set.txt', "%.1f" % (temp_soll))
        await self.client.set('txt_humid_set.txt', "%.1f" % (humitidy_soll))
        
        if status_piager == 0:
            await self.client.set('txt_temp.txt', '--.-')
            await self.client.set('txt_humid.txt', '--.-')
            await self.client.set('txt_humabs_ext.txt', '--.-') 
            await self.client.set('txt_humabs.txt', '--.-')
        else:
            await self.client.set('txt_temp.txt', "%.1f" % (temp_ist))
            await self.client.set('txt_humid.txt', "%.1f" % (humidity_ist))
            await self.client.set('txt_humabs.txt', "%.1f" % (humabs))
            if secondsensortype != 0:
                # await self.client.set('txt_dewp.txt', "%.1f" % (dewpoint))
                if humabs_ext == None:
                    await self.client.set('txt_humabs_ext.txt', '--.-')
                else:
                    await self.client.set('txt_humabs_ext.txt',"%.1f" % (humabs_ext))
            else:
                # await self.client.set('txt_dewp.txt', '--.-') 
                await self.client.set('txt_humabs_ext.txt', '--.-')
        
    async def update_page_17_19_values(self):
        status_piager = int(pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_pi_ager_key ))

        await self.client.set('btn_piager.val', status_piager)

    async def process_page1(self):
        await self.update_base_values()

    async def process_page3(self):
        await self.update_states()
            
    async def process_page4(self):
        await self.update_extended_values()
        
    async def process_page_6_14(self):
        await self.update_info_values()
            
    async def process_page9(self):
        await self.update_states()
        # await self.update_base_values()
        await self.update_page9_values()
        
    async def process_page12(self):
        await self.update_extended_values()
        
    async def process_page_17_19(self):
        await self.update_page_17_19_values()
    
    async def process_wifi_btn(self):
        len_wifi_ssid = len(self.get_wifi_ssid())
        len_ip_address = len(self.get_ip_address())
        # cl_fact_logger.get_instance().info('len(wifi_ssid) :' + str(len_wifi_ssid) + ' len(ip_address) :' + str(len_ip_address))
        
        if (len_wifi_ssid == 0 and len_ip_address == 0):      # no WLAN
            await self.client.set('btn_wifi.pic', 55)
            await self.client.set('btn_wifi.pic2', 55)
        elif (len_wifi_ssid != 0 and len_ip_address != 0):    # client mode, standard
            await self.client.set('btn_wifi.pic', 44)
            await self.client.set('btn_wifi.pic2', 44)           
        elif (len_wifi_ssid == 0 and len_ip_address != 0 ):   # AP mode
            await self.client.set('btn_wifi.pic', 54)
            await self.client.set('btn_wifi.pic2', 54)           

        
    async def run_client(self):
        self.button_event = asyncio.Event()
        self.wakeup_event = asyncio.Event()
        self.stop_event = asyncio.Event()
        self.reconnect_event = asyncio.Event()
        # self.waiter_task = asyncio.create_task(self.button_waiter(self.button_event))
        # self.waiter_task = self.loop.create_task(self.button_waiter(self.button_event))   
        
        self.client = Nextion('/dev/serial0', 9600, self.nextion_event_handler, self.loop)
        cl_fact_logger.get_instance().info(_('Client instance generated'))
        try:
            await self.client.connect()
#            cl_fact_logger.get_instance().info('client connected')
            cl_fact_logger.get_instance().info(_('Nextion client connected'))
            self.waiter_task = self.loop.create_task(self.button_waiter(self.button_event))
            self.wakeup_task = self.loop.create_task(self.wakeup_waiter(self.wakeup_event))            
        except Exception as e:
            cl_fact_logger.get_instance().error('run_client exception1: ' + str(e))
            cl_fact_logger.get_instance().error(_('Could not connect to Nextion client. Possible HMI display not connected'))
            while not self.stop_event.is_set():
                await asyncio.sleep(1)
            return                                                                                                              

        cl_fact_logger.get_instance().debug('sleep:' + str(await self.client.get('sleep')))
        
        # init internal display values
        await self.init_display_values()

        while not self.stop_event.is_set():
            try:
                # await self.update_light_val()   # update status in nextion values, needed when display was turned off caused by light timeout
                if self.current_page_id == 1:
                    await self.process_page1()
                elif self.current_page_id == 3:
                    await self.process_page3()
                elif self.current_page_id == 4:
                    await self.process_page4()
                elif self.current_page_id == 5:
                    await self.show_offline()
                elif self.current_page_id == 6:
                    await self.process_page_6_14()
                elif self.current_page_id == 9:
                    await self.process_page9() 
                elif self.current_page_id == 12:
                    await self.process_page12()
                elif self.current_page_id == 14:
                    await self.process_page_6_14()
                elif self.current_page_id == 17:
                    await self.process_page_17_19()
                elif self.current_page_id == 19:
                    await self.process_page_17_19()
                elif self.current_page_id == 13:
                    await self.show_offline()    
            
                if not(self.current_page_id == 0 or self.current_page_id  == 5 or self.current_page_id  == 8 or self.current_page_id == 13):
                    await self.process_wifi_btn()
                    
            except Exception as e:
                cl_fact_logger.get_instance().debug('run_client exception2: ' + str(e))

            if self.reconnect_event.is_set():
                await self.client.reconnect()
                self.reconnect_event.clear()
                
            await asyncio.sleep(3)

        cl_fact_logger.get_instance().info(_('Nextion client run-loop finished'))
        
    def inner_ctrl_c_signal_handler(self, sig, frame):
        self.stop_event.set()
        # signal.signal(signal.SIGINT, self.original_sigint_handler)
        
    def init_gpio(self):
        gpio.setwarnings(False)
        gpio.setmode(gpio.BCM)
        gpio.setup(pi_ager_gpio_config.gpio_light, gpio.OUT)   
        
    def run(self):
#        logging.basicConfig(
#            format='%(asctime)s - %(module)s - %(levelname)s - %(message)s',
#            level=logging.DEBUG,
#            handlers=[
#                logging.StreamHandler()
#            ])
        
        self.init_gpio()
    
        self.loop = asyncio.new_event_loop()
        asyncio.set_event_loop(self.loop)
        asyncio.ensure_future(self.run_client())
    
        try:
            self.loop.run_forever()
        
        # except KeyboardInterrupt:
        #    print('ctrl+C pressed')
        # tasks = [task for task in asyncio.all_tasks(self.loop) if not task.done()]
        # for task in tasks:
        #     task.cancel()
            cl_fact_logger.get_instance().debug('Nextion run_forever stopped')
            tasks = asyncio.all_tasks(self.loop)
            count = len(tasks)
            cl_fact_logger.get_instance().debug('Elements in tasks list : ' + str(count))                                                                        
            for t in [t for t in tasks if not (t.done() or t.cancelled())]:
                self.loop.run_until_complete(t)

        # self.loop.run_until_complete(asyncio.gather(*tasks, loop=self.loop))
        # for task in tasks:
        #     if not task.cancelled() and task.exception() is not None: 
        #         self.loop.call_exception_handler({
        #         'message': 'Unhandled exception during Client.run shutdown.',
        #         'exception': task.exception(),
        #         'task': task
        #         })
        except Exception as e:
            cl_fact_logger.get_instance().info('Nextion thread in exception ' + str(e))
            
        finally:
            #cl_fact_logger.get_instance().info('after finally')
            cl_fact_logger.get_instance().info(_('Nextion client stopped'))
            self.loop.close()
    
    async def show_offline(self):
        if self.client != None:
            await self.client.set('sleep', 0)
            if self.current_theme == 'fridge':
                await self.client.command('page 5')
            else:
                await self.client.command('page 13')
            await self.client.set('thsp', 0)
            self.current_page_id = None
            
    def prep_show_offline(self):
        if self.current_theme == 'fridge':
            self.current_page_id = 5
        else:    
            self.current_page_id = 13
        time.sleep(4)
    
    def reset_page_after_powergood(self):
        if self.client != None:
            self.loop.call_soon_threadsafe(self.reconnect_event.set)
            time.sleep(4)
            cl_fact_logger.get_instance().info('Nextion client after powergood. Page 1 now active page')
            if self.data == None:   # assume current page = 1, cause no touch event happened
#                print('simulate touch event with page_id = 0, component_id = 1 and touch_event = 1 to enter main_fridge (page 1)')
                Touch = namedtuple("Touch", "page_id component_id touch_event")
                self.data = Touch(0, 1, 1)
#                print('set Display page 1 active')
            else:
#                print('To force showing page 1, simulate button id = -1 on current page %d' % (self.current_page_id))
                Touch = namedtuple("Touch", "page_id component_id touch_event")
                # self.data = Touch(self.current_page_id, -1, 1)            
                self.data = Touch(1, -1, 1)
                
            self.loop.call_soon_threadsafe(self.button_event.set)
        
    def stop_loop(self):
        cl_fact_logger.get_instance().debug('in stop_loop')
        tasks = asyncio.all_tasks(self.loop)
        count = len(tasks)
        cl_fact_logger.get_instance().debug('Elements in task list : ' + str(count))        
        for t in tasks:
            name = getattr(t, 'name', f'Task-{id(t)}')
            cl_fact_logger.get_instance().debug('Task name : ' + name)    
            t.cancel()
        self.loop.call_soon_threadsafe(self.loop.stop)
        cl_fact_logger.get_instance().debug('after self.loop.stop')

class cl_fact_nextion(ABC):
    __o_instance = None
    
    @classmethod
    def set_instance(self, i_instance):
        """
        Factory method to set the nextion instance
        """
        cl_fact_nextion.__o_instance = i_instance
        
    @classmethod        
    def get_instance(self):
        """
        Factory method to get the nextion instance
        """
        if cl_fact_nextion.__o_instance is not None:
            return(cl_fact_nextion.__o_instance)
        cl_fact_nextion.__o_instance = cl_nextion()
#        cl_fact_logger.get_instance().debug('nextion factory done')
        return(cl_fact_nextion.__o_instance)

    def __init__(self):
        """
        Constructor nextion factory
        """
        pass    
            
        
def main():
    # signal.signal(signal.SIGINT, nextion_thread.inner_ctrl_c_signal_handler)
    cl_fact_nextion.get_instance().start()
    i = 0
    try:
        while i < 60:
            i = i + 1
            time.sleep(1)
            
        # simulate powerfail, set last active page  
        # cl_fact_nextion.get_instance().reset_page_after_powergood()
        
        while True:
            time.sleep(1)

    except KeyboardInterrupt:
        print("Ctrl-c received! Sending Stop to thread...")
        # show offline state on display
        cl_fact_nextion.get_instance().prep_show_offline()
        # set stop event
        cl_fact_nextion.get_instance().loop.call_soon_threadsafe(cl_fact_nextion.get_instance().stop_event.set)
        # time.sleep(1)
        cl_fact_nextion.get_instance().stop_loop()
            
    cl_fact_nextion.get_instance().join()
    print('thread finished.')
    

if __name__ == '__main__':
    main()
        