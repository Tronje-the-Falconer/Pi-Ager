# -*- coding: utf-8 -*-

"""This class is for handling the AHT sensors AHT10, AHT20, AHT21, AHT30 with i2c bus interface from ASAIR.""" 
""" Default is setup fpr AHT2x and AHT30 """

import inspect
from main.pi_ager_cl_logger import cl_fact_logger
from smbus2 import i2c_msg
import time
from sensors.pi_ager_cl_i2c_bus import cl_fact_i2c_bus_logic
# from sensors.pi_ager_cl_i2c_sensor_aht import cl_fact_i2c_sensor_aht
from main.pi_ager_cx_exception import *
from sensors.pi_ager_cl_sensor import cl_sensor

class cl_sensor_aht(cl_sensor):
    AHTxx_I2CADDR = 0x38
    AHTxx_CMD_SOFTRESET = [0xBA]
    AHT10_CMD_INITIALIZE = [0xE1, 0x08, 0x00]
    AHT20_CMD_INITIALIZE = [0xBE, 0x08, 0x00]
    AHTxx_CMD_MEASURE = [0xAC, 0x33, 0x00]

    # Initial value. Equal to bit negation the first data (status of AHT20)
    CRC_INIT = 0xFF
    # CRC polynomial G(x) = x8 + x5 + x4 + 1
    CRC_POLY = 0x31

#---------------------------------------------------------------------------------------------------------------------------------------------#

    def __init__(self, i_sensor_type, i_active_sensor, i_address):
        # i_active_sensor : 'MAIN' or 'SECOND'
        # i_sensor_type   : class cl_main_sensor_type or cl_second_sensor_type
        # i_address       : i2c address
        cl_fact_logger.get_instance().debug("i2c address is " + hex(i_address) + ". active sensor is : " + i_active_sensor + ". i_sensor_type is : " + i_sensor_type.get_sensor_type_ui())

        self.o_address = i_address
        self.o_sensor_type = i_sensor_type
        self.o_active_sensor = i_active_sensor
        
        super().__init__(self.o_sensor_type)

        self._i2c_bus = cl_fact_i2c_bus_logic.get_instance().get_i2c_bus()
        cl_fact_logger.get_instance().debug("i2c bus object is : " + str(self._i2c_bus))
        # self._i2c_sensor = cl_fact_i2c_sensor_aht.get_instance(self.o_active_sensor, self._i2c_bus, self.o_address)
        
    def check_init_status(self):
        # check if sensor status after power on is ok, if not, raise exception
        status = self.get_status()
        if ((status & 0x18) != 0x18):
            self.cmd_initialize()
            status = self.get_status()
            if ((status & 0x18) != 0x18):
                raise cx_measurement_error (_('Error initializing AHTxx sensor!'))

    def cmd_soft_reset(self):
        # Send the command to soft reset
        write = i2c_msg.write(self.o_address, self.AHTxx_CMD_SOFTRESET)
        self._i2c_bus.i2c_rdwr(write)
        time.sleep(0.02)

    def cmd_initialize(self):
        # Send the command to initialize (calibrate)
        write = i2c_msg.write(self.o_address, self.AHT20_CMD_INITIALIZE)
        self._i2c_bus.i2c_rdwr(write)
        time.sleep(0.02)

    def get_status(self):
        # Get the full status byte
        read = i2c_msg.read(self.o_address, 1)
        self._i2c_bus.i2c_rdwr(read)
        buf = list(read)
        time.sleep(0.01)
        return buf[0]

    def cmd_measure(self):
        # Send the command to measure
        write = i2c_msg.write(self.o_address, self.AHTxx_CMD_MEASURE)
        self._i2c_bus.i2c_rdwr(write)
        time.sleep(0.08)    # Wait 80 ms after measure
            
    def get_measure(self):
        # Get the full measure
        self.cmd_measure()
        read = i2c_msg.read(self.o_address, 7)
        self._i2c_bus.i2c_rdwr(read)
        buf = list(read)
        return buf
    
    def a_aht_calc_crc(self, data, len):
        crc = self.CRC_INIT
    
        for byte in range(len):     # len times 
            crc ^= data[byte]       # xor byte
            for i in range(8):      # one byte
                if ((crc & 0x80) != 0): # if high
                    crc = (crc << 1) ^ self.CRC_POLY     # xor 0x31 
                else:
                    crc = crc << 1      # skip 
            
        return crc & 0xff
    
    def get_current_data(self):
        # cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        
        repeat_count = 0
        repeat_count_max = 2
        while repeat_count < repeat_count_max:
            try:
                buf = self.get_measure()
                if ((buf[0] & 0x80) != 0):  # check busy
                    repeat_count += 1
                    continue
            
                if (self.a_aht_calc_crc(buf, 6) != buf[6]):
                    repeat_count += 1
                    continue
                
                hum_offset = self.get_humidity_offset()
                humidity_raw = ((buf[1]) << 16) | ((buf[2]) << 8) | buf[3]     # set the humidity 
                humidity_raw = (humidity_raw) >> 4                             # right shift 4
                humidity_s = (humidity_raw / 1048576.0 * 100.0)           # convert the humidity 
                humidity_s += hum_offset
                if (humidity_s > 100.0):
                    humidity_s = 100.0
                if (humidity_s < 0.0):
                    humidity_s = 0.0
                    
                temperature_raw = ((buf[3]) << 16) | ((buf[4]) << 8) | buf[5]  # set the temperature 
                temperature_raw = temperature_raw & 0xFFFFF                    # cut the temperature part 
                temperature_s = temperature_raw / 1048576.0 * 200.0 - 50.0  

                cl_fact_logger.get_instance().debug(f"AHT2x/30 temperature : {temperature_s:.2f}")
                cl_fact_logger.get_instance().debug(f"AHT2x/30 humidity : {humidity_s:.1f}")
                dewpoint = super().get_dewpoint(temperature_s, humidity_s)
                (temperature_dewpoint, humidity_absolute) = dewpoint
        
                self.measured_data = (temperature_s, humidity_s, temperature_dewpoint, humidity_absolute)
                return(self.measured_data)
                
            except Exception as cx_error:
                repeat_count += 1
                if (repeat_count == 1):
                    cl_fact_logger.get_instance().exception(cx_error)
                else:
                    cl_fact_logger.get_instance().error(f"Retry getting measurement from I2C device AHT2x/3x. Current retry count : {repeat_count}, max retry count : {repeat_count_max}")      

        raise cx_measurement_error (_('Too many measurement errors occurred!'))
