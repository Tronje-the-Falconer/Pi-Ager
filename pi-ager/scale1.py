#!/usr/bin/python3
import sys
import RPi.GPIO as GPIO
import time
import datetime
from hx711 import Scale
import pi_ager_database
import pi_ager_names

samples_scale1 = int(pi_ager_database.get_table_value(pi_ager_names.scale1_settings_table,pi_ager_names.sample_key))
spikes_scale1 = int(pi_ager_database.get_table_value(pi_ager_names.scale1_settings_table,pi_ager_names.spikes_key))
sleep_scale1 = pi_ager_database.get_table_value(pi_ager_names.scale1_settings_table,pi_ager_names.sleep_key)
gpio_scale1_data = int(pi_ager_database.get_table_value(pi_ager_names.scale1_settings_table,pi_ager_names.gpio_data_key))
gpio_scale_sync = int(pi_ager_database.get_table_value(pi_ager_names.scale1_settings_table,pi_ager_names.gpio_sync))
gain_scale1 = int(pi_ager_database.get_table_value(pi_ager_names.scale1_settings_table,pi_ager_names.gain_key))  #32[Channel B], 64 [Channel A], 128 [Channel A]
bitsToRead_scale1 = int(pi_ager_database.get_table_value(pi_ager_names.scale1_settings_table,pi_ager_names.bits_to_read_key))
referenceunit_scale1 = pi_ager_database.get_table_value(pi_ager_names.config_settings_table,pi_ager_names.referenceunit_scale1_key)
# 10KG China Zelle:
#   Gain 32 -> 60
#   Gain 128 -> 205
# 20kg China Zelle:
#   Gain 32 -> 
#   Gain 128 -> 102
# 50kg Edelstahl Zelle:
#   Gain 32 -> 
#   Gain 128 -> 74
# 20kg Edelstahl Zelle:
#   Gain 32 -> 
#   Gain 128 -> 186


# Standard Aufruf Parameter Scale (self, source=None, samples=20, spikes=4, sleep=0.1, dout=10, pd_sck=9, gain=128, bitsToRead=24)
scale = Scale(source=None, samples=samples_scale1, spikes=spikes_scale1, sleep=sleep_scale1, dout=gpio_scale1_data, pd_sck=gpio_scale_sync, gain=gain_scale1, bitsToRead=bitsToRead_scale1)

scale.setReferenceUnit(referenceunit_scale1)

scale.reset()
scale.tare()

tsstart = time.time()
last_hour = datetime.datetime.fromtimestamp(tsstart).strftime('%H')
last_hour = str(int(last_hour)-1)


while True:

    try:
        value = scale.getMeasure()
        #formated_value = ("{0: 4.4f}".format(value))
        formated_value = round(value, 3)
        timestamp = time.time()
        current_hour = datetime.datetime.fromtimestamp(timestamp).strftime('%H')
        
        if current_hour != last_hour:
            pi_ager_database.write_scale(pi_ager_names.scale1_table,value)
            last_hour = current_hour
            time.sleep(10800)

    except (KeyboardInterrupt, SystemExit):
        GPIO.cleanup()
        sys.exit()