#!/usr/bin/python3
# from Github https://github.com/dcrystalj/hx711py3

import statistics
import time
import RPi.GPIO as gpio


class HX711:
    def __init__(self, gpio_scale_data=24, gpio_scale_sync=25, gain=128, bits_to_read=24, samples=20, spikes=4, sleep=0.1):
        self.gpio_scale_sync = gpio_scale_sync
        self.gpio_scale_data = gpio_scale_data

        gpio.setwarnings(False)
        gpio.setmode(gpio.BCM)
        gpio.setup(self.gpio_scale_sync, gpio.OUT)
        gpio.setup(self.gpio_scale_data, gpio.IN)

        samples = samples
        spikes = spikes
        sleep = sleep
        
        # The value returned by the hx711 that corresponds to your
        # reference unit AFTER dividing by the SCALE.
        self.reference_wheight_value = 1

        self.gain_value = 0
        self.mean_offset_value = 1
        self.lastVal = 0
        self.bits_to_read = bits_to_read
        self.twosComplementThreshold = 1 << (bits_to_read - 1)
        self.twosComplementOffset = -(1 << (bits_to_read))
        self.setGain(gain)
        self.read()

    def isReady(self):
        return gpio.input(self.gpio_scale_data) == 0

    def setGain(self, gain):
        if gain is 128:
            self.gain_value = 1
        elif gain is 64:
            self.gain_value = 3
        elif gain is 32:
            self.gain_value = 2

        gpio.output(self.gpio_scale_sync, False)
        self.read()

    def waitForReady(self):
        while not self.isReady():
            pass

    def correctTwosComplement(self, unsignedValue):
        if unsignedValue >= self.twosComplementThreshold:
            return unsignedValue + self.twosComplementOffset
        else:
            return unsignedValue

    def read(self):
        self.waitForReady()

        unsignedValue = 0
        for i in range(0, self.bits_to_read):
            gpio.output(self.gpio_scale_sync, True)
            bitValue = gpio.input(self.gpio_scale_data)
            gpio.output(self.gpio_scale_sync, False)
            unsignedValue = unsignedValue << 1
            unsignedValue = unsignedValue | bitValue

        # set channel and gain factor for next reading
        for i in range(self.gain_value):
            gpio.output(self.gpio_scale_sync, True)
            gpio.output(self.gpio_scale_sync, False)

        return self.correctTwosComplement(unsignedValue)

    def getValue(self):
        return self.read() - self.mean_offset_value

    def getWeight(self):
        value = self.getValue()
        value /= self.reference_wheight_value
        return value

    def tare(self, times=25):
        tare_wheight_value = self.reference_wheight_value
        self.setReferenceUnit(1)

        # remove spikes
        cut = times//5
        values = sorted([self.read() for i in range(times)])[cut:-cut]
        offset_value = statistics.mean(values)

        self.setOffset(offset_value)

        self.setReferenceUnit(tare_wheight_value)

    def setOffset(self, offset_value):
        self.mean_offset_value = offset_value

    def setReferenceUnit(self, tare_wheight_value):
        self.reference_wheight_value = tare_wheight_value

    # HX711 datasheet states that setting the PDA_CLOCK pin on high
    # for a more than 60 microseconds would power off the chip.
    # I used 100 microseconds, just in case.
    # I've found it is good practice to reset the hx711 if it wasn't used
    # for more than a few seconds.
    def powerDown(self):
        gpio.output(self.gpio_scale_sync, False)
        gpio.output(self.gpio_scale_sync, True)
        time.sleep(0.0001)

    def powerUp(self):
        gpio.output(self.gpio_scale_sync, False)
        time.sleep(0.0001)

    def reset(self):
        self.powerDown()
        self.powerUp()
    ####################
    def newMeasure(self):
        value = self.source.newgetWeight()
        self.history.append(value)
        
    def newgetWeight(self, samples=None):
        """Get weight for once in a while. It clears history first."""
        self.history = []

        [self.newMeasure() for i in range(samples or self.samples)]

        return self.newgetMeasure()
    
    def newgetMeasure(self):
        """Useful for continuous measurements."""
        self.newMeasure()
        # cut to old values
        self.history = self.history[-self.samples:]

        avg = statistics.mean(self.history)
        deltas = sorted([abs(i-avg) for i in self.history])

        if len(deltas) < self.spikes:
            max_permitted_delta = deltas[-1]
        else:
            max_permitted_delta = deltas[-self.spikes]

        valid_values = list(filter(
            lambda val: abs(val - avg) <= max_permitted_delta, self.history
        ))

        avg = statistics.mean(valid_values)

        return avg
