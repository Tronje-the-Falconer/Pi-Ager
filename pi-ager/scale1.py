#!/usr/bin/python3
import sys
import RPi.GPIO as GPIO
import json
import time
import datetime
from hx711 import Scale
import json_interfaces

config_json_file = '/var/www/config/config.json'
data_configjsonfile = json_interfaces.read_config_json(config_json_file)

samples_scale1 = 20
spikes_scale1 = 4
sleep_scale1 = 0.1
gpio_scale1_data = 10
gpio_scale_sync = 9
gain_scale1 = 128  #32[Channel B], 64 [Channel A], 128 [Channel A]
bitsToRead_scale1 = 24
referenceunit_scale1 = data_configjsonfile ['referenceunit_scale1']
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
last_minute = datetime.datetime.fromtimestamp(tsstart).strftime('%M')


while True:

    try:
        value = scale.getMeasure()
        #formated_value = ("{0: 4.4f}".format(value))
        formated_value =round(value, 3)
        timestamp = time.time()
        current_minute = datetime.datetime.fromtimestamp(timestamp).strftime('%M')
        
        if current_minute != last_minute:
            with open('/var/www/config/scales.json', 'r+') as scalesjsonfile:
                scales_json_data = json.load(scalesjsonfile)
                scales_json_data['scale1_data'] = value
                scales_json_data['scale1_date'] = int(time.time())
                scalesjsonfile.seek(0)
                scalesjsonfile.write(json.dumps(scales_json_data))
                scalesjsonfile.truncate()
            last_minute = current_minute
            time.sleep(10800)

    except (KeyboardInterrupt, SystemExit):
        GPIO.cleanup()
        sys.exit()