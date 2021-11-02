#!/usr/bin/python3
"""
   file for the hx711 modules written for python 3
   
   originaly file from hx711py3 written by dcrystalj https://github.com/dcrystalj/hx711py3/blob/master/hx711.py
   Timing controlled by performance counter to detect hx711 reset by clk high for more than 60 us
"""

from statistics import mean as pi_mean
from statistics import stdev as pi_stdev
import time
import RPi.GPIO as GPIO

class cl_sensor_hx711:
    def __init__(self, dout=10, pd_sck=9, gain=128, bitsToRead=24):
        self.PD_SCK = pd_sck
        self.DOUT = dout

        GPIO.setwarnings(False)
        GPIO.setmode(GPIO.BCM)
        GPIO.setup(self.PD_SCK, GPIO.OUT)
        GPIO.setup(self.DOUT, GPIO.IN)

        # The value returned by the hx711 that corresponds to your
        # reference unit AFTER dividing by the SCALE.
        self.REFERENCE_UNIT = 1

        self.GAIN = 0
        self.OFFSET = 0
        self.lastVal = 0
        self.bitsToRead = bitsToRead
        self.twosComplementThreshold = 1 << (bitsToRead-1)
        self.twosComplementOffset = -(1 << (bitsToRead))
        self.setGain(gain)
#        self.read()

    def isReady(self):
        return GPIO.input(self.DOUT) == 0

    def setGain(self, gain):
        if gain is 128:
            self.GAIN = 1
        elif gain is 64:
            self.GAIN = 3
        elif gain is 32:
            self.GAIN = 2

        GPIO.output(self.PD_SCK, False)
        self.read()
        time.sleep(0.4)  # 400ms settling time after gain change
        
    def waitForReady(self):
        while not self.isReady():
            pass

    def correctTwosComplement(self, unsignedValue):
        if unsignedValue >= self.twosComplementThreshold:
            return unsignedValue + self.twosComplementOffset
        else:
            return unsignedValue

    def read(self):
        read_repeat_counter = 0
        read_repeat_counter_max = 10
        while (read_repeat_counter <= read_repeat_counter_max):
            read_repeat_counter += 1
            self.reset()                      # start with reset
            ready_counter = 0
            ready_counter_max = 20
            while (not self.isReady() and ready_counter <= ready_counter_max):
                time.sleep(0.01)  # sleep for 10 ms because data is not ready
                ready_counter += 1
            
            if ready_counter == ready_counter_max:  # if counter reached max value then try again
                continue

            unsignedValue = 0
            for i in range(0, self.bitsToRead):
                timing_error = False
                start_counter = time.perf_counter()
                GPIO.output(self.PD_SCK, True)
                bitValue = GPIO.input(self.DOUT)
                GPIO.output(self.PD_SCK, False)
                end_counter = time.perf_counter()
                if ((end_counter - start_counter) >= 0.00010):  # choose 100 us, zero not precise enough, check if the hx 711 did not turn off...
                    timing_error = True
#                    print("Timing error read data")
#                    self.reset()
                    break
                unsignedValue = unsignedValue << 1
                unsignedValue = unsignedValue | bitValue

            if timing_error == True:    # retry
#                print("Timing error read data")
                continue
                
        # set channel and gain factor for next reading
            for i in range(self.GAIN):
                timing_error = False
                start_counter = time.perf_counter()
                GPIO.output(self.PD_SCK, True)
                GPIO.output(self.PD_SCK, False)
                end_counter = time.perf_counter()
                if end_counter - start_counter >= 0.00006:  # check if the hx 711 did not turn off...
                    timing_error = True
#                    print("timing error set gain")
                    break
            
            if timing_error == True:    # retry
                continue
               
#            time.sleep(0.1)    # next conversion ends after 100ms at a conversion rate of 10 Hz               
            value = self.correctTwosComplement(unsignedValue)
            if (value == -1):  # some time 0xffffff is read from hx711, reading failed
                continue

            return value
            
        return 0     # error, no valid conversion after read_repeat_counter_max retries

    def getRawWeight(self):
        value = self.read()
        value /= self.REFERENCE_UNIT
        value -= self.OFFSET
        return value

    def setOffset(self, offset):
        self.OFFSET = offset

    def setReferenceUnit(self, reference_unit):
        if reference_unit == 0:
            self.REFERENCE_UNIT = 1
        else:
            self.REFERENCE_UNIT = reference_unit

    # HX711 datasheet states that setting the PDA_CLOCK pin on high
    # for a more than 60 microseconds would power off the chip.
    # I used 100 microseconds, just in case.
    # I've found it is good practice to reset the hx711 if it wasn't used
    # for more than a few seconds.
    def powerDown(self):
        GPIO.output(self.PD_SCK, False)
        GPIO.output(self.PD_SCK, True)
        time.sleep(0.0001)

    def powerUp(self):
        GPIO.output(self.PD_SCK, False)
        time.sleep(0.4)    # 0.0001s to0 short, after reset 400 ms settling time needed

    def reset(self):
        self.powerDown()
        self.powerUp()

class cl_scale(cl_sensor_hx711):
    def __init__(self, samples=20, dout=10, pd_sck=9, gain=128, bitsToRead=24):
        super().__init__(dout, pd_sck, gain, bitsToRead)
        self.gain = gain
        self.dout = dout
        self.pd_sck = pd_sck
        self.bitsToRead = bitsToRead
        self.samples = samples
        self.history = []

    def getWeightAppendHistory(self):
        value = self.getRawWeight()
        self.history.append(value)

    def getWeightFiltered(self):
        """Useful for continuous measurements."""
        while len(self.history) < 3:
            self.getWeightAppendHistory()
        self.getWeightAppendHistory()
        # cut to old values
        self.history = self.history[-self.samples:]

        # if more than 3 samples in history, sort values by magnitude
        # and remove min and max value
        if (len(self.history) > 3):
             temp_hist = sorted(self.history)
             temp_hist = temp_hist[1:len(temp_hist) - 1]
        else:
             temp_hist = self.history
             
        avg = pi_mean(temp_hist)
        if (len(temp_hist) < 2):
            sigma = 0
        else:
            sigma = pi_stdev(temp_hist)

        if (len(temp_hist) < 2):
           valid_values = temp_hist
        else:
           valid_values = list(filter(
               lambda val: abs(val - avg) <= sigma, temp_hist
           ))

#        if (len(valid_values) < self.samples):
#           print("valid_values : %d" % len(valid_values))

        if len(valid_values) == 0:
           return avg

#        print(*temp_hist)
#        print(*valid_values)

        avg = pi_mean(valid_values)

        return avg

    def getWeightFilteredFlushingHistory(self, samples=None):
        """Get weight for once in a while. It clears history first."""
        self.history = []
        [self.getWeightAppendHistory() for i in range(samples or self.samples)]
        return self.getWeightFiltered()

    def setSamples(self, samples):
        self.samples = samples
