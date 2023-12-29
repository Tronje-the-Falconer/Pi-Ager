# -*- coding: utf-8 -*-
 
"""This class is for handling the AHT1x sensor """

import inspect
from main.pi_ager_cl_logger import cl_fact_logger
from smbus2 import i2c_msg
import time

from abc import ABC, abstractmethod
from main.pi_ager_cx_exception import *
# from messenger.pi_ager_cl_messenger import cl_fact_logic_messenger
from sensors.pi_ager_cl_sensor_aht import cl_sensor_aht

class cl_sensor_aht1x(cl_sensor_aht):
    # i_active_sensor : 'MAIN' or 'SECOND'
    # i_sensor_type   : class cl_main_sensor_type or cl_second_sensor_type
    # i_address       : i2c address
    def __init__(self, i_sensor_type, i_active_sensor, i_address):
        # cl_fact_logger.get_instance().debug("i2c address is : " + hex(i_address) + ". active sensor is : " + i_active_sensor + ". i_sensor_type is : " + i_sensor_type.get_sensor_type_ui())
        cl_fact_logger.get_instance().debug("i2c address is : " + str(i_address) + ". active sensor is : " + str(i_active_sensor) + ". i_sensor_type is : " + str(i_sensor_type))
        self.o_sensor_type = i_sensor_type
        self.o_address     = i_address
        
        super().__init__(self.o_sensor_type, i_active_sensor, self.o_address)
        self.check_init_status()
        
    def check_init_status(self):
        # check if sensor status after power on is ok, if not, raise exception
        status = self.get_status()
        if ((status & 0x08) != 0x08):
            self.cmd_initialize()
            status = self.get_status()
            if ((status & 0x08) != 0x08):
                raise cx_measurement_error (_('Error initializing AHTxx sensor!'))

    def cmd_initialize(self):
        # Send the command to initialize (calibrate)
        write = i2c_msg.write(self.o_address, self.AHT10_CMD_INITIALIZE)
        self._i2c_bus.i2c_rdwr(write)
        time.sleep(0.02)
        
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
                if ((buf[0] & 0x80) != 0):  # check busy
                    repeat_count += 1
                    continue
                    
                humidity_raw = ((buf[1]) << 16) | ((buf[2]) << 8) | buf[3]     # set the humidity 
                humidity_raw = (humidity_raw) >> 4                             # right shift 4
                humidity_s = (humidity_raw / 1048576.0 * 100.0)           # convert the humidity 
    
                temperature_raw = ((buf[3]) << 16) | ((buf[4]) << 8) | buf[5]  # set the temperature 
                temperature_raw = temperature_raw & 0xFFFFF                    # cut the temperature part 
                temperature_s = temperature_raw / 1048576.0 * 200.0 - 50.0  

                cl_fact_logger.get_instance().debug(f"AHT1x temperature : {temperature_s:.2f}")
                cl_fact_logger.get_instance().debug(f"AHT1x humidity : {humidity_s:.1f}")
                dewpoint = super().get_dewpoint(temperature_s, humidity_s)
                (temperature_dewpoint, humidity_absolute) = dewpoint
        
                self.measured_data = (temperature_s, humidity_s, temperature_dewpoint, humidity_absolute)
                return(self.measured_data)
                
            except Exception as cx_error:
                repeat_count += 1
                if (repeat_count == 1):
                    cl_fact_logger.get_instance().exception(cx_error)
                else:
                    cl_fact_logger.get_instance().error(f"Retry getting measurement from I2C device AHT1x. Current retry count : {repeat_count}, max retry count : {repeat_count_max}")      

        raise cx_measurement_error (_('Too many measurement errors occurred!'))

    
class cl_fact_sensor_aht1x(ABC): 
    __o_instance = None
    __ot_instances = {}
    
    @classmethod        
    def get_instance(self, i_sensor_type, i_active_sensor, i_address):
        cl_fact_logger.get_instance().debug("cl_fact_sensor_aht1x.get_instance")
        cl_fact_logger.get_instance().debug("Old aht1x __ot_instances = " + str(cl_fact_sensor_aht1x.__ot_instances))

        cl_fact_sensor_aht1x.__o_instance = cl_fact_sensor_aht1x.__ot_instances.get(i_active_sensor)
        if  cl_fact_sensor_aht1x.__o_instance is not None :
            cl_fact_logger.get_instance().debug("aht1x  __ot_instance = " + str(cl_fact_sensor_aht1x.__o_instance)+ " Returning")
            return(cl_fact_sensor_aht1x.__o_instance)
       
        cl_fact_sensor_aht1x.__o_instance = cl_sensor_aht1x(i_sensor_type, i_active_sensor, i_address)
        cl_fact_logger.get_instance().debug("aht1x __ot_instance " + i_active_sensor + str(cl_fact_sensor_aht1x.__o_instance) + " created for " )
        line = {i_active_sensor:cl_fact_sensor_aht1x.__o_instance}
        cl_fact_sensor_aht1x.__ot_instances.update(line)   
        cl_fact_logger.get_instance().debug("New aht1x __ot_instances = " + str(cl_fact_sensor_aht1x.__ot_instances))
        return(cl_fact_sensor_aht1x.__o_instance)

    @classmethod
    def set_instance(self, i_active_sensor, i_instance):
        cl_fact_sensor_aht1x.__o_instance = i_instance
        
    def __init__(self):
        pass    

