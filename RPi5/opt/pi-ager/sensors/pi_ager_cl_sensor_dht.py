# -*- coding: utf-8 -*-
 
"""This class is for handling the dht_adafruit sensor from sensirion."""

__author__ = "Claus Fischer"
__copyright__ = "Copyright 2019, The Pi-Ager Project"
__credits__ = ["Claus Fischer"]
__license__ = "GPL"
__version__ = "1.0.0"
__maintainer__ = "Claus Fischer"
__email__ = "DerBurgermeister@pi-ager.org"
__status__ = "Production"

import os
# import inspect
from main.pi_ager_cl_logger import cl_fact_logger
import time

import pi_ager_gpio_config

from sensors.pi_ager_cl_sensor_type import cl_fact_main_sensor_type
from main.pi_ager_cx_exception import *
# from messenger.pi_ager_cl_messenger import cl_fact_logic_messenger
from sensors.pi_ager_cl_sensor import cl_sensor
# from sensors.pi_ager_cl_ab_sensor import cl_ab_sensor
import lgpio as sbc

class cl_sensor_dht(cl_sensor):
    DHTAUTO=0
    DHT11=1
    DHTXX=2

    DHT_GOOD=0
    DHT_BAD_CHECKSUM=1
    DHT_BAD_DATA=2
    DHT_TIMEOUT=3
    
    def __init__(self):
#        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
  
        super().__init__(cl_fact_main_sensor_type.get_instance())
        # remove i2c_gpio kernel driver to release GPIO17 and GPIO27 pins
        # so that rpi-lgpio can access these pins
        # Using a RPi zero W is not recommended due to poor performance
        os.system('rmmod i2c_gpio')
        
        # check Pi model
        stream = os.popen('cat /proc/device-tree/model')
        output = stream.read().rstrip('\n')
        if ('Raspberry Pi 5' in output):
            cl_fact_logger.get_instance().debug("Pi5 detected")
            self._chip = sbc.gpiochip_open(4)       # 4 is for Pi5
        else:
            cl_fact_logger.get_instance().debug("Other Pis detected")
            self._chip = sbc.gpiochip_open(0)   # 0 is fo all other Pi devices

        self._gpio = pi_ager_gpio_config.gpio_sensor_data
        self._model = self._sensor_dht

        self._new_data = False

        self._bits = 0
        self._code = 0
        self._last_edge_tick = 0

        self._timestamp = time.time()
        self._status = self.DHT_TIMEOUT
        self._temperature = 0.0
        self._humidity = 0.0
        
        sbc.gpio_set_watchdog_micros(self._chip, self._gpio, 1000) # watchdog after 1 ms
        self._cb = sbc.callback(self._chip, self._gpio, sbc.RISING_EDGE, self._rising_edge) 
        

    def _datum(self):
        return ((self._timestamp, self._gpio, self._status, self._temperature, self._humidity))

    def _validate_DHT11(self, b1, b2, b3, b4):
        t = b2
        h = b4
        if (b1 == 0) and (b3 == 0) and (t <= 60) and (h >= 9) and (h <= 90):
            valid = True
        else:
            valid = False
        return (valid, t, h)

    def _validate_DHTXX(self, b1, b2, b3, b4):
        if b2 & 128:
            div = -10.0
        else:
            div = 10.0
        t = float(((b2&127)<<8) + b1) / div
        h = float((b4<<8) + b3) / 10.0
        if (h <= 110.0) and (t >= -50.0) and (t <= 135.0):
            valid = True
        else:
            valid = False
        return (valid, t, h)

    def _decode_dhtxx(self):
        """
            +-------+-------+
            | DHT11 | DHTXX |
            +-------+-------+
      Temp C| 0-50  |-40-125|
            +-------+-------+
      RH%   | 20-80 | 0-100 |
            +-------+-------+

               0      1      2      3      4
            +------+------+------+------+------+
      DHT11 |check-| 0    | temp |  0   | RH%  |
            |sum   |      |      |      |      |
            +------+------+------+------+------+
      DHT21 |check-| temp | temp | RH%  | RH%  |
      DHT22 |sum   | LSB  | MSB  | LSB  | MSB  |
      DHT33 |      |      |      |      |      |
      DHT44 |      |      |      |      |      |
            +------+------+------+------+------+
        """
        b0 =  self._code        & 0xff
        b1 = (self._code >>  8) & 0xff
        b2 = (self._code >> 16) & 0xff
        b3 = (self._code >> 24) & 0xff
        b4 = (self._code >> 32) & 0xff
      
        chksum = (b1 + b2 + b3 + b4) & 0xFF

        if chksum == b0:
            if self._model == self.DHT11:
                valid, t, h = self._validate_DHT11(b1, b2, b3, b4)
            elif self._model == self.DHTXX:
                valid, t, h = self._validate_DHTXX(b1, b2, b3, b4)
            else: # AUTO
                # Try DHTXX first.
                valid, t, h = self._validate_DHTXX(b1, b2, b3, b4)
                if not valid:
                    # try DHT11.
                    valid, t, h = self._validate_DHT11(b1, b2, b3, b4)
            if valid:
                self._timestamp = time.time()
                self._temperature = t
                self._humidity = h
                self._status = self.DHT_GOOD
            else:
                self._status = self.DHT_BAD_DATA
        else:
            self._status = self.DHT_BAD_CHECKSUM
        self._new_data = True

    def _rising_edge(self, chip, gpio, level, tick):
        if level != sbc.TIMEOUT:
            edge_len = tick - self._last_edge_tick
            self._last_edge_tick = tick
            if edge_len > 2e8: # 0.2 seconds
                self._bits = 0
                self._code = 0
            else:
                self._code <<= 1
                if edge_len > 1e5: # 100 microseconds, so a high bit
                    self._code |= 1
                self._bits += 1
        else: # watchdog
            if self._bits >= 30:
                self._decode_dhtxx()

    def _trigger(self):
        sbc.gpio_claim_output(self._chip, self._gpio, 0)
        if self._model != self.DHTXX:
            time.sleep(0.015)
        else:
            time.sleep(0.001)
        self._bits = 0
        self._code = 0
        sbc.gpio_claim_alert(self._chip, self._gpio, sbc.RISING_EDGE)

    def cancel(self):
        """
        """
        if self._cb is not None:
            self._cb.cancel()
            self._cb = None

    def read(self):
        """
        """
        self._new_data = False
        self._status = self.DHT_TIMEOUT
        self._trigger()
        for i in range(20): # timeout after 1 seconds.
            time.sleep(0.05)
            if self._new_data:
                break
        if not self._new_data:
            cl_fact_logger.get_instance().debug("DHT11/DHT22 data timeout")
        datum = self._datum()
        return datum

    def get_current_data(self):
#        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        self._error_counter = 0
        self._max_errors = 5
        
        while self._error_counter < self._max_errors:
            try:
                # Try to grab a sensor reading.  Use the read_retry method which will retry up
                # to <retries> times to get a sensor reading (waiting <delay_seconds> seconds between each retry).
                cl_fact_logger.get_instance().debug("Try to read from Sensor DHT%s Pin %d" % (self._sensor_dht, self._gpio))
                data = self.read()
                if (data[2] != self.DHT_GOOD):
                    self._error_counter += 1
                    time.sleep(1)
                    continue
                    
                current_humidity = data[4]
                hum_offset = self.get_humidity_offset()
                current_humidity += hum_offset
                if (current_humidity > 100.0):
                    current_humidity = 100.0
                if (current_humidity < 0.0):
                    current_humidity = 0.0
                current_temperature = data[3]
                
                cl_fact_logger.get_instance().debug("Temperature in Celsius is : %.2f °C" %current_temperature)
                cl_fact_logger.get_instance().debug("Relative Humidity is : %.2f %%RH" %current_humidity)
                dewpoint = super().get_dewpoint(current_temperature, current_humidity)
        
                (temperature_dewpoint, humidity_absolute) = dewpoint
                self.measured_data = (current_temperature, current_humidity, temperature_dewpoint, humidity_absolute)
                return(self.measured_data)                    
            
            except Exception as cx_error:
                self._error_counter = self._error_counter + 1
                if (self._error_counter == 1):
                    cl_fact_logger.get_instance().exception(cx_error)
                else:
                    cl_fact_logger.get_instance().error(f"Retry getting measurement from DHT device. Current retry count : {self._error_counter}, max retry count : {self._max_errors}")        
                time.sleep(1)
        
        cl_fact_logger.get_instance().debug(_('Too many measurement errors occurred!'))
        raise cx_measurement_error (_('Too many measurement errors occurred!'))    


