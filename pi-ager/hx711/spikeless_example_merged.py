import sys
import time

import RPi.GPIO as gpio
from hx711 import HX711

# choose pins on rpi (BCM5 and BCM6)
hx = HX711(gpio_scale_data=24, gpio_scale_sync=25, gain=128, bits_to_read=24, samples=20, spikes=4, sleep=0.1)

# HOW TO CALCULATE THE REFFERENCE UNIT
#########################################
# To set the reference unit to 1.
# Call get_weight before and after putting 1000g weight on your sensor.
# Divide difference with grams (1000g) and use it as refference unit.

hx.setReferenceUnit(98)

hx.reset()
hx.tare()

while True:

    try:
        val = hx.newgetMeasure()
        print("{0: 4.4f}".format(val))

    except (KeyboardInterrupt, SystemExit):
        GPIO.cleanup()
        sys.exit()
