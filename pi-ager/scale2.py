#!/usr/bin/python3
# from Github https://github.com/dcrystalj/hx711py3

import statistics
import time
import RPi.GPIO as GPIO

def is_ready():
    return GPIO.input(DOUT) == 0

def setGain(gain):
    if gain is 128:
        GAIN = 1
    elif gain is 64:
        GAIN = 3
    elif gain is 32:
        GAIN = 2

    GPIO.output(PD_SCK, False)
    read()
    
def read():
    while not isReady():        # Überprüfen ob Chip ist startbereit wenn nicht
        pass                    # nichts passiert
    
    unsignedValue = 0
    for i in range(0, bitsToRead):  # clock pin 24 times to read the data
        GPIO.output(PD_SCK, True)
        bitValue = GPIO.input(DOUT)
        GPIO.output(PD_SCK, False)
        unsignedValue = unsignedValue << 1
        unsignedValue = unsignedValue | bitValue

    # set channel and gain factor for next reading
    for i in range(GAIN):
        GPIO.output(PD_SCK, True)
        GPIO.output(PD_SCK, False)

    if unsignedValue >= twosComplementThreshold:
        return unsignedValue + twosComplementOffset
    else:
        return unsignedValue
    
def read_average(times):
    sum = 0
    while i < times:
        if i == 0:
            sum += read()
        else
            pass
    }
    return sum / times

def get_value(times):
    return read_average(times) - OFFSET

def get_units(times)
    return get_value(times)/SCALE

def tare():
    sum = read_average(times)
    set_offset (sum)

def set_scale(float scale):
    SCALE = scale
    
def get_scale():
    return SCALE
    
def setOffset( offset):
    OFFSET = offset

def get_offset():
    return OFFSET

# HX711 datasheet states that setting the PDA_CLOCK pin on high
# for a more than 60 microseconds would power off the chip.
# I used 100 microseconds, just in case.
# I've found it is good practice to reset the hx711 if it wasn't used
# for more than a few seconds.
def powerDown():
    GPIO.output(PD_SCK, False)
    GPIO.output(PD_SCK, True)
    time.sleep(0.0001)

def powerUp():
    GPIO.output(PD_SCK, False)
    time.sleep(0.0001)

    
def calibrate():    # Waage mit Referenzgewicht kalibrieren
    print ('Kalibrieren der Waage')
    zero_average = measure_average()
    print ('Zero raw value')
    print (zero_average)
    input('Bitte Referenzgewicht auflegen...')
    reference_wheight_in_g = input("Wieviel Gramm wiegt das Referenzgewicht? ")
    print (reference_wheight_in_g)
    print ('calibrating ...')
    reference_wheigt_average = measure_average()
    return zero_average , reference_wheigt_average

def measure_average():
    measure_average_count = 0   # counter fuer die anzahl der messungen
    raw_value_average = 0
    while measure_average_count <= 1000     # 1000 mal messen und daraus den Durcschnitt ziehen
        measure_average_count = measure_average_count + 1
        raw_value_average = ((measure_average_count-1)/measure_average_count) * wheight + (1/measure_average_count) * read()
    return raw_value_average
    
def long_time_measure():    
    raw_value = read()  # Rohdatensatz der Waage
    zero = raw_value - zero_average
    raw_value_average = zero
    raw_value_average = 0.5 * raw_value_average + 0.5 * raw_value
    
    wheight_float = (raw_value_average - zero_average) / reference_wheigt_average * reference_wheight_in_g
    wheight = int(round(wheight_float*100))
    print (wheight)

    
powerDown()
powerUp()

zero_average , reference_wheigt_average = calibrate()
while True:
    try:
        long_time_measure()
    except (KeyboardInterrupt, SystemExit):
        GPIO.cleanup()
        sys.exit()
