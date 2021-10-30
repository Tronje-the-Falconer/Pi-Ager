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
    
import subprocess
import time
import asyncio
import signal
import logging
import random
from collections import namedtuple
import RPi.GPIO as gpio

import pi_ager_database
import pi_ager_names
import pi_ager_gpio_config

from messenger.pi_ager_cl_messenger import cl_fact_logic_messenger
from main.pi_ager_cl_logger import cl_fact_logger
from nextion import Nextion, EventType

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

class pi_ager_cl_nextion( threading.Thread ):

    def __init__( self ):
        super().__init__() 
        
        self.client = None
        self.waiter_task = None
        self.stop_event = None
        self.button_event = None
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
            print('We have booted up!')
        elif type_ == EventType.TOUCH:
            print('A button (id: %d) was touched on page %d' % (data.component_id, data.page_id))
            self.data = data
            self.type_ = type_
            self.current_page_id = data.page_id
            self.loop.call_soon_threadsafe(self.button_event.set)
            
        # self.button_event.set()
        logging.info('Event %s data: %s', type_, str(data))
    
    async def turn_off_light(self):
        logging.info('Light turn off timeout')
        gpio.output(pi_ager_gpio_config.gpio_light, True)
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
            self.light_status = False       # turn off
            if self.current_theme == 'fridge':
                await self.client.set('btn_light.pic', 12) 
            else:
                await self.client.set('btn_light.pic', 39)
            self.light_timer.cancel()                
            gpio.output(pi_ager_gpio_config.gpio_light, True)
            globals.hands_off_light_switch = False
            # pi_ager_database.update_value_in_table(pi_ager_names.current_values_table, pi_ager_names.status_light_key, 0)
            
        else:
            self.light_status = True       # turn on
            if self.current_theme == 'fridge':
                await self.client.set('btn_light.pic', 13)
            else:
                await self.client.set('btn_light.pic', 41)
            self.light_timer.start()
            globals.hands_off_light_switch = True   
            gpio.output(pi_ager_gpio_config.gpio_light, False)
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
        elif (temp_soll > 30):
            temp_soll = 30
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
            
    async def button_waiter(self, event):
        try:
            while True:
                print('waiting for button pressed ...')
                await self.button_event.wait()
                print('... got it!')

                if self.data.page_id == 1 and self.data.component_id == 8:
                    await self.control_light_status()
                elif self.data.page_id == 1 and self.data.component_id == 7:
                    await self.client.command('page 2')
                    self.current_page_id = 2
                elif self.data.page_id == 2 and self.data.component_id == 6:
                    await self.control_light_status()    
                elif self.data.page_id == 2 and self.data.component_id == 1:
                    await self.client.command('page 1')
                    self.current_page_id = 1                    
                elif self.data.page_id == 2 and self.data.component_id == 2:
                    await self.client.command('page 3')
                    self.current_page_id = 3  
                elif self.data.page_id == 2 and self.data.component_id == 3:
                    await self.client.command('page 4')
                    self.current_page_id = 4  
                elif self.data.page_id == 2 and self.data.component_id == 4:
                    await self.client.command('page 7')
                    self.current_page_id = 7  
                elif self.data.page_id == 2 and self.data.component_id == 5:
                    await self.client.command('page 6')
                    self.current_page_id = 6  
                elif self.data.page_id == 2 and self.data.component_id == 7:
                    if (self.current_theme == 'steak'):
                        self.current_theme = 'fridge'
                        await self.client.command('page 2')
                        self.current_page_id = 2 
                    else:
                        self.current_theme = 'steak'
                        await self.client.command('page 10')
                        self.current_page_id = 10
                elif self.data.page_id == 2 and self.data.component_id == 9:
                    await self.client.command('page 17')
                    self.current_page_id = 17
                    await self.init_page_17_19()
                elif self.data.page_id == 3 and self.data.component_id == 1:
                    await self.control_light_status()
                elif self.data.page_id == 3 and self.data.component_id == 10:
                    await self.client.command('page 2')
                    self.current_page_id = 2                    
                elif self.data.page_id == 3 and self.data.component_id == 11:
                    await self.client.command('page 1')
                    self.current_page_id = 1                    
                elif self.data.page_id == 4 and self.data.component_id == 1:
                    await self.control_light_status()
                elif self.data.page_id == 4 and self.data.component_id == 2:
                    await self.client.command('page 2')
                    self.current_page_id = 2
                elif self.data.page_id == 4 and self.data.component_id == 11:
                    await self.client.command('page 1')
                    self.current_page_id = 1
                elif self.data.page_id == 6 and self.data.component_id == 1:
                    await self.control_light_status()
                elif self.data.page_id == 6 and self.data.component_id == 2:
                    await self.client.command('page 2')
                    self.current_page_id = 2
                elif self.data.page_id == 6 and self.data.component_id == 7:
                    await self.client.command('page 1')
                    self.current_page_id = 1
                elif self.data.page_id == 7 and self.data.component_id == 1:
                    await self.control_light_status()
                elif self.data.page_id == 7 and self.data.component_id == 2:
                    await self.client.command('page 2')
                    self.current_page_id = 2
                elif self.data.page_id == 7 and self.data.component_id == 7:
                    await self.client.command('page 1')
                    self.current_page_id = 1
                elif self.data.page_id == 9 and self.data.component_id == 7:
                    await self.client.command('page 10')
                    self.current_page_id = 10
                elif self.data.page_id == 9 and self.data.component_id == 8:
                    await self.control_light_status()
                elif self.data.page_id == 10 and self.data.component_id == 6:
                    await self.control_light_status()
                elif self.data.page_id == 10 and self.data.component_id == 1:   # goto main_steak
                    await self.client.command('page 9')
                    self.current_page_id = 9
                elif self.data.page_id == 10 and self.data.component_id == 2:   # goto values_steak
                    await self.client.command('page 12')
                    self.current_page_id = 12
                #elif self.data.page_id == 10 and self.data.component_id == 2:
                #    await self.client.command('page 11')
                #    self.current_page_id = 11
                elif self.data.page_id == 10 and self.data.component_id == 5:   # goto menu_steak or menu_fridge
                    if (self.current_theme == 'steak'):
                        self.current_theme = 'fridge'
                        await self.client.command('page 2')
                        self.current_page_id = 2 
                    else:
                        self.current_theme = 'steak'
                        await self.client.command('page 10')
                        self.current_page_id = 10
                elif self.data.page_id == 10 and self.data.component_id == 3:   # goto setting_steak
                    await self.client.command('page 15')
                    self.current_page_id = 15
                elif self.data.page_id == 10 and self.data.component_id == 4:   # goto info_steak
                    await self.client.command('page 14')
                    self.current_page_id = 14
                elif self.data.page_id == 10 and self.data.component_id == 8:   # goto control_steak
                    await self.client.command('page 19')
                    self.current_page_id = 19 
                    await self.init_page_17_19()                    
#                elif self.data.page_id == 11 and self.data.component_id == 18:
#                    await self.client.command('page 9')
#                    self.current_page_id = 9                                             
#                elif self.data.page_id == 11 and self.data.component_id == 9:
#                    await self.control_light_status()   
                elif self.data.page_id == 12 and self.data.component_id == 1:
                    await self.client.command('page 10')
                    self.current_page_id = 10                     
                elif self.data.page_id == 12 and self.data.component_id == 10:
                    await self.client.command('page 9')
                    self.current_page_id = 9                     
                elif self.data.page_id == 12 and self.data.component_id == 11:
                    await self.control_light_status()   
                elif self.data.page_id == 14 and self.data.component_id == 1:
                    await self.client.command('page 10')
                    self.current_page_id = 10            
                elif self.data.page_id == 14 and self.data.component_id == 6:
                    await self.client.command('page 9')
                    self.current_page_id = 9            
                elif self.data.page_id == 14 and self.data.component_id == 7:
                    await self.control_light_status()   
                elif self.data.page_id == 15 and self.data.component_id == 7:
                    await self.client.command('page 10')
                    self.current_page_id = 10            
                elif self.data.page_id == 15 and self.data.component_id == 5:
                    await self.client.command('page 9')
                    self.current_page_id = 9            
                elif self.data.page_id == 15 and self.data.component_id == 6:
                    await self.control_light_status()   
                elif self.data.page_id == 17 and self.data.component_id == 3:   # button menu
                    await self.client.command('page 2')
                    self.current_page_id = 2
                elif self.data.page_id == 17 and self.data.component_id == 4:   # button home
                    await self.client.command('page 1')
                    self.current_page_id = 1   
                elif self.data.page_id == 17 and self.data.component_id == 2:   # button light
                    await self.control_light_status()
                elif self.data.page_id == 17 and self.data.component_id == 6:   # button pi-ager start/stop
                    await self.control_piager_start_stop()
                elif self.data.page_id == 17 and self.data.component_id == 9:  # button save new Temp/Hum. values
                    await self.save_page_17_19_values()
                elif self.data.page_id == 19 and self.data.component_id == 2:   # button menu
                    await self.client.command('page 10')
                    self.current_page_id = 10
                elif self.data.page_id == 19 and self.data.component_id == 3:   # button home
                    await self.client.command('page 9')
                    self.current_page_id = 9   
                elif self.data.page_id == 19 and self.data.component_id == 4:   # button light
                    await self.control_light_status()
                elif self.data.page_id == 19 and self.data.component_id == 9:   # button pi-ager start/stop
                    await self.control_piager_start_stop()
                elif self.data.page_id == 19 and self.data.component_id == 1:  # button save new Temp/Hum. values
                    await self.save_page_17_19_values()
                                        
                self.button_event.clear()
                logging.info('button pressed processed')
        except Exception as e:
            logging.error('button_waiter stopped ' + str(e))
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
                return output.split('"')[1]
        except Exception as e:
            return ''
    
    async def init_display_values(self):
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
        print('pi model: ' + model)
        await self.client.set('values.pi_model.txt', model)
        
        wifi_ssid = self.get_wifi_ssid()
        await self.client.set('values.wifi_conn.txt', wifi_ssid)
        
        await self.client.set('values.status_light.val', 0)
        
        self.current_page_id = 1
        await self.client.set('sleep', 0)
        await self.client.command('page 1') 
        await self.client.set('thsp', 0)
        
    def db_get_base_values(self):
        status_piager = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_pi_ager_key )
        
        temp_ist = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.sensor_temperature_key)
        humidity_ist = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.sensor_humidity_key)
        dewpoint_ist = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.sensor_dewpoint_key)
        temp_soll = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.setpoint_temperature_key)
        humitidy_soll = pi_ager_database.get_table_value(pi_ager_names.config_settings_table, pi_ager_names.setpoint_humidity_key)
    
        values = dict()
        values['status_piager'] = status_piager
        values['temp_ist'] = temp_ist
        values['humidity_ist'] = humidity_ist
        values['dewpoint_ist'] = dewpoint_ist
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
        
        await self.client.set('txt_temp_set.txt', "%.1f" % (values['temp_soll']))
        await self.client.set('txt_humid_set.txt', "%.1f" % (values['humitidy_soll']))        
        if values['status_piager'] == 0:
            await self.client.set('txt_temp.txt', '--.-')
            await self.client.set('txt_humid.txt', '--.-')
            await self.client.set('txt_dew.txt', '--.-')
        else:
            await self.client.set('txt_temp.txt', "%.1f" % (values['temp_ist']))
            await self.client.set('txt_humid.txt', "%.1f" % (values['humidity_ist']))        
            await self.client.set('txt_dew.txt', "%.1f" % (values['dewpoint_ist']))      
    
    async def update_extended_values(self):
        values = self.db_get_extended_values()
        if values['status_piager'] == 0:
            await self.client.set('txt_temp_ext.txt', '--.-') 
            await self.client.set('txt_humid_ext.txt', '--.-')
            await self.client.set('txt_dewp_ext.txt', '--.-')
            await self.client.set('txt_temp_meat1.txt', '--.-')
            await self.client.set('txt_temp_meat2.txt', '--.-')
            await self.client.set('txt_temp_meat3.txt', '--.-')
        
        else:
            if values['status_secondsensor'] != 0:
                await self.client.set('txt_temp_ext.txt', "%.1f" % (values['temp_ext']))
                await self.client.set('txt_humid_ext.txt',"%.1f" % (values['humid_ext']))
                await self.client.set('txt_dewp_ext.txt', "%.1f" % (values['dewp_ext']))
            else:
                await self.client.set('txt_temp_ext.txt', '--.-') 
                await self.client.set('txt_humid_ext.txt', '--.-')
                await self.client.set('txt_dewp_ext.txt', '--.-')    
                
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

        temp_ext = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.second_sensor_temperature_key)
        humid_ext = pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.second_sensor_humidity_key)
        
        await self.client.set('txt_temp_set.txt', "%.1f" % (temp_soll))
        await self.client.set('txt_humid_set.txt', "%.1f" % (humitidy_soll))
        
        if status_piager == 0:
            await self.client.set('txt_temp.txt', '--.-')
            await self.client.set('txt_humid.txt', '--.-')
            await self.client.set('txt_temp_ext.txt', '--.-') 
            await self.client.set('txt_humid_ext.txt', '--.-')
        else:
            await self.client.set('txt_temp.txt', "%.1f" % (temp_ist))
            await self.client.set('txt_humid.txt', "%.1f" % (humidity_ist)) 
            if secondsensortype != 0:
                await self.client.set('txt_temp_ext.txt', "%.1f" % (temp_ext))
                await self.client.set('txt_humid_ext.txt',"%.1f" % (humid_ext))
            else:
                await self.client.set('txt_temp_ext.txt', '--.-') 
                await self.client.set('txt_humid_ext.txt', '--.-')
        
    async def update_page_17_19_values(self):
        status_piager = int(pi_ager_database.get_table_value(pi_ager_names.current_values_table, pi_ager_names.status_pi_ager_key ))

        await self.client.set('btn_piager.val', status_piager)

    async def process_page1(self):
        await self.update_base_values()

    async def process_page3(self):
        await self.update_states()
            
    async def process_page4(self):
        await self.update_extended_values()
            
    async def process_page9(self):
        await self.update_states()
        # await self.update_base_values()
        await self.update_page9_values()
        
    async def process_page12(self):
        await self.update_extended_values()
        
    async def process_page_17_19(self):
        await self.update_page_17_19_values()
        
    async def run_client(self):
        self.button_event = asyncio.Event()
        self.stop_event = asyncio.Event()
        # self.waiter_task = asyncio.create_task(self.button_waiter(self.button_event))
        # self.waiter_task = self.loop.create_task(self.button_waiter(self.button_event))   
        
        self.client = Nextion('/dev/serial0', 9600, self.nextion_event_handler, self.loop)
        logging.info('client generated')
        try:
            await self.client.connect()
            logging.info('client connected')
            cl_fact_logger.get_instance().info('Nextion client connected')
            self.waiter_task = self.loop.create_task(self.button_waiter(self.button_event))                                                                                              
        except Exception as e:
            logging.error('run_client exception1: ' + str(e))
            cl_fact_logger.get_instance().info('Could not connect to Nextion client. Possible HDMI display not connected')
            while not self.stop_event.is_set():
                await asyncio.sleep(1)
            return                                                                                                              

        logging.info('sleep:' + str(await self.client.get('sleep')))
        
        # init internal display values
        await self.init_display_values()

        while not self.stop_event.is_set():
            # test what happens, when object is not in page
            #try:
            #    await self.client.set('txt_temp.txt', "%.1f" % (random.randint(0, 1000) / 10))
            #except Exception as e:
            #    logging.error(str(e))
            #    pass 
            try:
                if self.current_page_id == 1:
                    await self.process_page1()
                elif self.current_page_id == 3:
                    await self.process_page3()
                elif self.current_page_id == 4:
                    await self.process_page4()
                elif self.current_page_id == 5:
                    await self.show_offline()                        
                elif self.current_page_id == 9:
                    await self.process_page9() 
                elif self.current_page_id == 12:
                    await self.process_page12()  
                elif self.current_page_id == 17:
                    await self.process_page_17_19()
                elif self.current_page_id == 19:
                    await self.process_page_17_19()
                elif self.current_page_id == 13:
                    await self.show_offline()    
                    
            except Exception as e:
                logging.error('run_client exception2: ' + str(e))
            
            # await self.client.set('values.temp_cur.txt', "%.1f" % (random.randint(0, 1000) / 10))
            # await self.client.set('values.humidity_cur.txt', "%.1f" % (random.randint(0, 1000) / 10))
            # await self.client.set('values.dewpoint_cur.txt', "%.1f" % (random.randint(0, 1000) / 10))
            # await self.client.set('values.temp_set.txt', "%.1f" % (random.randint(0, 1000) / 10))
            # await self.client.set('values.humidity_set.txt', "%.1f" % (random.randint(0, 1000) / 10))
            # await self.client.set('values.dewpoint_set.txt', "%.1f" % (random.randint(0, 1000) / 10))
            # await self.client.set('values.status_uv.val', 1)
            # await self.client.command('page dp')
            await asyncio.sleep(3)

        logging.info('run_client finished')
        
    def inner_ctrl_c_signal_handler(self, sig, frame):
        self.stop_event.set()
        # signal.signal(signal.SIGINT, self.original_sigint_handler)
        
    def init_gpio(self):
        gpio.setwarnings(False)
        gpio.setmode(gpio.BCM)
        gpio.setup(pi_ager_gpio_config.gpio_light, gpio.OUT)   
        
    def run(self):
        logging.basicConfig(
            format='%(asctime)s - %(module)s - %(levelname)s - %(message)s',
            level=logging.DEBUG,
            handlers=[
                logging.StreamHandler()
            ])
        
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
            logging.info('run_forever stopped')
            tasks = asyncio.all_tasks(self.loop)
            count = len(tasks)
            logging.info('Elements in tasks list : ' + str(count))                                                                        
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
            logging.info('Nextion thread in exception ' + str(e))
            
        finally:
            #logging.info('after finally')
            cl_fact_logger.get_instance().info('Nextion client stopped')
            self.loop.close()
    
    async def show_offline(self):
        if self.client != None:
            await self.client.set('sleep', 0)
            if self.current_theme == 'fridge':
                await self.client.command('page 5')
            else:
                await self.client.command('page 13')
            await self.client.set('thsp', 0)
            
    def prep_show_offline(self):
        if self.current_theme == 'fridge':
            self.current_page_id = 5
        else:    
            self.current_page_id = 13
        time.sleep(4)
    
    def reset_page_after_powergood(self):
        if self.client != None:
            if self.data == None:
                # simulate touch event with page_id = 1, component_id = 7 and touch_event = 0x65
                Touch = namedtuple("Touch", "page_id component_id touch_event")
                self.data = Touch(1, 7, 1)
            else:
                print('Simulate button (id: %d) was touched on page %d' % (self.data.component_id, self.data.page_id))
            self.loop.call_soon_threadsafe(self.button_event.set)
        
    def stop_loop(self):
        logging.info('in stop_loop')
        tasks = asyncio.all_tasks(self.loop)
        count = len(tasks)
        logging.info('Elements in task list : ' + str(count))        
        for t in tasks:
            t.cancel()
        self.loop.stop()
        logging.info('after self.loop.stop')

        
        
def main():
    nextion_thread = pi_ager_cl_nextion()
    # signal.signal(signal.SIGINT, nextion_thread.inner_ctrl_c_signal_handler)
    nextion_thread.start()
    i = 0
    try:
        while True:
            time.sleep(1)

    except KeyboardInterrupt:
        print("Ctrl-c received! Sending Stop to thread...")
        # show offline state on display
        nextion_thread.prep_show_offline()
        # set stop event
        nextion_thread.loop.call_soon_threadsafe(nextion_thread.stop_event.set)
        # time.sleep(1)
        nextion_thread.stop_loop()
            
    nextion_thread.join()
    print('thread finished.')
    

if __name__ == '__main__':
    main()
        