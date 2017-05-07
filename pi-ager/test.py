#!/usr/bin/python3
import sys
import RPi.GPIO as GPIO
from hx711 import Scale

scale = Scale(gain=128) #32, 64, 128

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

while True:

    try:
        val = scale.getMeasure()
        print("{0: 4.4f}".format(val))

    except (KeyboardInterrupt, SystemExit):
        GPIO.cleanup()
        sys.exit()