# -*- coding: utf-8 -*-

"""This class is for handling the SHT sensors SHT4x-A/B/C with i2c bus interface from sensirion.""" 

#from abc import ABC, abstractmethod
from main.pi_ager_cl_logger import cl_fact_logger
from smbus2 import i2c_msg
import time
from sensors.pi_ager_cl_i2c_bus import cl_fact_i2c_bus_logic
from main.pi_ager_cx_exception import *
from sensors.pi_ager_cl_sensor import cl_sensor


class cl_sensor_sht4(cl_sensor):
    SHT_CMD_SOFTRESET = [0x94]
    SHT_CMD_MEASURE = [0xfd]

    # Initial value. Equal to bit negation the first data
    CRC_INIT = 0xFF
    # CRC polynomial G(x) = x8 + x5 + x4 + 1
    CRC_POLY = 0x31
    
    # i_active_sensor : 'MAIN' or 'SECOND'
    # i_sensor_type   : class cl_main_sensor_type or cl_second_sensor_type
    # i_address       : i2c address        
    def __init__(self, i_sensor_type, i_active_sensor, i_address):
        # cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        if i_address is not None:
            cl_fact_logger.get_instance().debug("i2c address is " + hex(i_address))
                    
        self.repeat_count_max = 3
        
        self.o_address = i_address
        self.o_sensor_type = i_sensor_type
        self.o_active_sensor= i_active_sensor
        
        super().__init__(self.o_sensor_type)

        self._i2c_bus = cl_fact_i2c_bus_logic.get_instance().get_i2c_bus()
        self.cmd_soft_reset()
        time.sleep(0.02)
#        cl_fact_logger.get_instance().debug(self._i2c_bus)
#        self._i2c_sensor = cl_fact_i2c_sensor_sht.get_instance(self.o_active_sensor, self._i2c_bus, self.o_address)

    
    def sht_calc_crc(self, data, len):
        crc = self.CRC_INIT
    
        for byte in range(len):     # len times 
            crc ^= data[byte]       # xor byte
            for i in range(8):      # one byte
                if ((crc & 0x80) != 0): # if high
                    crc = (crc << 1) ^ self.CRC_POLY     # xor 0x31 
                else:
                    crc = crc << 1      # skip 
            
        return crc & 0xff
        
    def cmd_measure(self):
        # Send the command to measure
        write = i2c_msg.write(self.o_address, self.SHT_CMD_MEASURE)
        self._i2c_bus.i2c_rdwr(write)
        time.sleep(0.02) #This is so the sensor has time to perform the measurement and write its registers before you read it
            
    def get_measure(self):
        # Get the full measure
        self.cmd_measure()
        read = i2c_msg.read(self.o_address, 6)
        self._i2c_bus.i2c_rdwr(read)
        buf = list(read)
        return buf
        
    def get_current_data(self):
        # cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        repeat_count = 0
        repeat_count_max = 2
        while repeat_count < repeat_count_max:
            try:
                buf = self.get_measure()
            
                if (self.sht_calc_crc(buf, 2) != buf[2]):
                    repeat_count += 1
                    time.sleep(0.02)
                    continue
                
                hum_buf = buf[3:6]                
                if (self.sht_calc_crc(hum_buf, 2) != hum_buf[2]):
                    repeat_count += 1
                    time.sleep(0.02)
                    continue
                
                hum_offset = self.get_humidity_offset()    
                humidity_raw = (hum_buf[0] << 8) | hum_buf[1]     # set the humidity 
                humidity_s = (humidity_raw / 65535.0 * 125.0 - 6.0)     # convert the humidity 
                humidity_s += hum_offset
                if (humidity_s > 100.0):
                    humidity_s = 100.0
                if (humidity_s < 0.0):
                    humidity_s = 0.0
                    
                temperature_raw = (buf[0] << 8) | buf[1]          # set the temperature 
                temperature_s = 175.0 * temperature_raw / 65535.0 - 45.0 

                cl_fact_logger.get_instance().debug(f"sht temperature : {temperature_s:.2f}")
                cl_fact_logger.get_instance().debug(f"sht humidity : {humidity_s:.1f}")
                dewpoint = super().get_dewpoint(temperature_s, humidity_s)
                (temperature_dewpoint, humidity_absolute) = dewpoint
        
                self.measured_data = (temperature_s, humidity_s, temperature_dewpoint, humidity_absolute)
                return(self.measured_data)
                
            except Exception as cx_error:
                repeat_count += 1
                if (repeat_count == 1):
                    cl_fact_logger.get_instance().exception(cx_error)
                else:
                    cl_fact_logger.get_instance().error(f"Retry getting measurement from I2C device SHT. Current retry count : {repeat_count}, max retry count : {repeat_count_max}")      

        raise cx_measurement_error (_('Too many SHT measurement errors occurred!'))

    def cmd_soft_reset(self):
        # Send the command to soft reset
        write = i2c_msg.write(self.o_address, self.SHT_CMD_SOFTRESET)
        self._i2c_bus.i2c_rdwr(write)
        time.sleep(0.02)

