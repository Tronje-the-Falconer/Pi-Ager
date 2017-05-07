#!/usr/bin/python3
import sys
import RPi.GPIO as GPIO
from hx711 import Scale

scale = Scale(gain=128)

scale.setReferenceUnit(60)

scale.reset()
scale.tare()

while True:

    try:
        val = scale.getMeasure()
        print("{0: 4.4f}".format(val))

    except (KeyboardInterrupt, SystemExit):
        GPIO.cleanup()
        sys.exit()