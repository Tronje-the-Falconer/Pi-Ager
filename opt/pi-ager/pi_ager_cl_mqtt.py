#!/usr/bin/python3
"""
    thread for MQTT client
"""
import pi_ager_names
import pi_ager_gpio_config  
import globals
if __name__ == '__main__':
    # init global threading.lock
    globals.init()
    import RPi.GPIO as gpio
    gpio.setwarnings(False)
    gpio.setmode(pi_ager_gpio_config.board_mode)
    gpio.setup(pi_ager_gpio_config.gpio_power_monitor, gpio.IN )
    gpio.setup(pi_ager_gpio_config.gpio_ups_bat_low, gpio.IN )
    gpio.setup(pi_ager_gpio_config.gpio_switch, gpio.IN )   # manueller Schalter setzen
   
    import gettext
    _ = gettext.gettext
    
import pi_ager_database  
from abc import ABC   
import time
# from messenger.pi_ager_cl_messenger import cl_fact_logic_messenger
from main.pi_ager_cl_logger import cl_fact_logger
import paho.mqtt.client as mqtt
import socket
import threading


class cl_mqtt_thread( threading.Thread ):

    def __init__( self ):
        super().__init__() 
        self.stop_received = False
        self.client = None
        self.topic = "/status"
        
    def get_current_values(self):   # get all current values from db
        current_value_rows = pi_ager_database.get_current(pi_ager_names.current_values_table, True)
        current_values = {}
        for current_row in current_value_rows:
            current_values[current_row[pi_ager_names.key_field]] = current_row[pi_ager_names.value_field]
        return current_values
    
    def run(self):
        cl_fact_logger.get_instance().info(_('Start MQTT loop at') + ' ' + time.strftime('%H:%M:%S', time.localtime()))
        self.client = mqtt.Client()
        
        while not self.stop_received and threading.main_thread().is_alive():
            config_mqtt = pi_ager_database.get_table_row(pi_ager_names.config_mqtt_table, 1)

            broker = config_mqtt[pi_ager_names.broker_address_field]
            port = config_mqtt[pi_ager_names.port_field]
            mqtt_active = config_mqtt[pi_ager_names.mqtt_active_field]
            
            if (mqtt_active == 0 or not broker or not port):
                time.sleep(1)
                continue
                
            username = config_mqtt[pi_ager_names.username_field]
            password = config_mqtt[pi_ager_names.password_field]

            try:
                # try to connect to broker
                # print("Connecting")
                self.client.username_pw_set(username, password)
                self.client.connect(broker, port)
                # print("Connected")
                
            except Exception as e:
                # if __name__ == '__main__':
                #    cl_fact_logger.get_instance().info('MQTT thread in exception ' + str(e))
                # else:
                #    cl_fact_logic_messenger().get_instance().handle_exception(cx_error)
                
                    # if self.stop_received == True:
                    #    cl_fact_logger.get_instance().info(_('MQTT loop stopped at') + ' ' + time.strftime('%H:%M:%S', time.localtime()))
                    #    return      # should stop thread
                time.sleep(1)    
                continue

            # print("Start publish")
            try:
                hostname = socket.gethostname()
                ups_bat_low_temp = pi_ager_gpio_config.check_ups_bat_low()
                UPS_battery = ('ok' if ups_bat_low_temp else 'low')
                
                power_monitor_state = pi_ager_gpio_config.check_power_monitor()
                power_monitor = ('powergood' if power_monitor_state else 'powerfail')
                
                pi_switch_temp = pi_ager_gpio_config.check_switch()
                pi_switch = ('off' if pi_switch_temp else 'on')
                
                current_values = self.get_current_values()

                self.client.loop_start()
                self.client.publish(hostname + self.topic + "/" + pi_ager_names.status_pi_ager_key , str(int(current_values[pi_ager_names.status_pi_ager_key])))
                if (int(current_values[pi_ager_names.status_pi_ager_key]) == 1):
                    self.client.publish(hostname + self.topic + "/" + pi_ager_names.temperature_avg_key , str(current_values[pi_ager_names.temperature_avg_key]))
                    self.client.publish(hostname + self.topic + "/" + pi_ager_names.humidity_avg_key , str(current_values[pi_ager_names.humidity_avg_key]))
                    self.client.publish(hostname + self.topic + "/" + pi_ager_names.humidity_abs_avg_key , str(current_values[pi_ager_names.humidity_abs_avg_key]))
                    
                    tmp = current_values[pi_ager_names.second_sensor_temperature_key]
                    if tmp != None:
                        self.client.publish(hostname + self.topic + "/" + pi_ager_names.second_sensor_temperature_key , str(tmp)) 
                    tmp = current_values[pi_ager_names.second_sensor_humidity_key]
                    if tmp != None:
                        self.client.publish(hostname + self.topic + "/" + pi_ager_names.second_sensor_humidity_key , str(tmp))
                    tmp = current_values[pi_ager_names.second_sensor_humidity_abs_key]
                    if tmp != None:
                        self.client.publish(hostname + self.topic + "/" + pi_ager_names.second_sensor_humidity_abs_key , str(tmp))
                    
                    tmp = current_values[pi_ager_names.temperature_meat1_key]
                    if tmp != None:
                        self.client.publish(hostname + self.topic + "/" + pi_ager_names.temperature_meat1_key , str(tmp))
                    tmp = current_values[pi_ager_names.temperature_meat2_key]
                    if tmp != None:
                        self.client.publish(hostname + self.topic + "/" + pi_ager_names.temperature_meat2_key , str(tmp))
                    tmp = current_values[pi_ager_names.temperature_meat3_key]
                    if tmp != None:
                        self.client.publish(hostname + self.topic + "/" + pi_ager_names.temperature_meat3_key , str(tmp))
                    tmp = current_values[pi_ager_names.temperature_meat4_key]
                    if tmp != None:
                        self.client.publish(hostname + self.topic + "/" + pi_ager_names.temperature_meat4_key , str(tmp))
                
                self.client.publish(hostname + self.topic + "/" + pi_ager_names.status_heater_key , str(int(current_values[pi_ager_names.status_heater_key])))
                self.client.publish(hostname + self.topic + "/" + pi_ager_names.status_cooling_compressor_key , str(int(current_values[pi_ager_names.status_cooling_compressor_key])))
                self.client.publish(hostname + self.topic + "/" + pi_ager_names.status_humidifier_key , str(int(current_values[pi_ager_names.status_humidifier_key])))
                self.client.publish(hostname + self.topic + "/" + pi_ager_names.status_exhaust_air_key , str(int(current_values[pi_ager_names.status_exhaust_air_key])))
                self.client.publish(hostname + self.topic + "/" + pi_ager_names.status_circulating_air_key , str(int(current_values[pi_ager_names.status_circulating_air_key])))
                self.client.publish(hostname + self.topic + "/" + pi_ager_names.status_uv_key , str(int(current_values[pi_ager_names.status_uv_key])))
                self.client.publish(hostname + self.topic + "/" + pi_ager_names.status_light_key , str(int(current_values[pi_ager_names.status_light_key])))
                self.client.publish(hostname + self.topic + "/" + pi_ager_names.status_dehumidifier_key , str(int(current_values[pi_ager_names.status_dehumidifier_key])))
                
                if (int(current_values[pi_ager_names.status_scale1_key]) == 0):
                    pass    # self.client.publish(hostname + self.topic + "/" + pi_ager_names.scale1_key , "None")
                else:
                    self.client.publish(hostname + self.topic + "/" + pi_ager_names.scale1_key , str(current_values[pi_ager_names.scale1_key]))
                    
                if (int(current_values[pi_ager_names.status_scale2_key]) == 0):
                    pass    # self.client.publish(hostname + self.topic + "/" + pi_ager_names.scale2_key , "None")
                else:
                    self.client.publish(hostname + self.topic + "/" + pi_ager_names.scale2_key , str(current_values[pi_ager_names.scale2_key]))
                
                self.client.publish(hostname + self.topic + "/" + 'pi_switch_state', pi_switch)
                self.client.publish(hostname + self.topic + "/" + 'power_monitor_state', power_monitor)
                self.client.publish(hostname + self.topic + "/" + 'UPS_battery_state', UPS_battery)
                
                if (not globals.mqtt_msg_queue.empty()):
                    self.client.publish(hostname + self.topic + "/" + 'event', globals.mqtt_msg_queue.get())
                    
                self.client.loop_stop()
                self.client.disconnect()
            except Exception as e:
                cl_fact_logger.get_instance().info('MQTT thread in exception ' + str(e))
   
            time.sleep(5)
        
        cl_fact_logger.get_instance().info(_('MQTT loop stopped at') + ' ' + time.strftime('%H:%M:%S', time.localtime()))
        
            
class cl_fact_mqtt(ABC):
    __o_instance = None
    
    @classmethod
    def set_instance(self, i_instance):
        """
        Factory method to set the mqtt instance
        """
        cl_fact_mqtt.__o_instance = i_instance
        
    @classmethod        
    def get_instance(self):
        """
        Factory method to get the mqtt instance
        """
        if cl_fact_mqtt.__o_instance is not None:
            return(cl_fact_mqtt.__o_instance)
        cl_fact_mqtt.__o_instance = cl_mqtt_thread()
        return(cl_fact_mqtt.__o_instance)

    def __init__(self):
        """
        Constructor nextion factory
        """
        pass    
            
        
def main():
    mqtt_thread = cl_fact_mqtt().get_instance()
    mqtt_thread.start()
    
    try:
        while True:
            time.sleep(1)

    except KeyboardInterrupt:
        print("Ctrl-c received! Sending Stop to thread...")
        mqtt_thread.stop_received = True
            
    mqtt_thread.join()
    print('thread finished.')
    

if __name__ == '__main__':
    main()
        