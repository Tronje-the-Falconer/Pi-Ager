#!/usr/bin/python3
import sys
import RPi.GPIO as GPIO
import time
from hx711 import Scale

scale = Scale(gain=128) #32[Channel B], 64 [Channel A], 128 [Channel A]

scale.setReferenceUnit(60)
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

scale.reset()
scale.tare()
last_minute = datetime.datetime.fromtimestamp(ts).strftime('%M')

while True:

    try:
        value = scale.getMeasure()
        timestamp = datetime.datetime.fromtimestamp(ts).strftime('%Y-%m-%d %H:%M:%S')
        current_minute = datetime.datetime.fromtimestamp(ts).strftime('%M')
        print("{0: 4.4f}".format(val))
        
        if current_minute = last_minute:
            with open("scale.txt", "a") as fh:
	            fh.write(timestamp + ' ; ' + str(value))
            last_minute = current_minute
        
    except (KeyboardInterrupt, SystemExit):
        GPIO.cleanup()
        sys.exit()
