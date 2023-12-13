# -*- coding: utf-8 -*-

"""This class is for handling the SHT sensors SHT3x, SHT21, SHT85 with i2c bus interface from sensirion.""" 

__author__ = "Claus Fischer"
__copyright__ = "Copyright 2019, The Pi-Ager Project"
__credits__ = ["Claus Fischer"]
__license__ = "GPL"
__version__ = "1.0.1"
__maintainer__ = "Claus Fischer"
__email__ = "DerBurgermeister@pi-ager.org"
__status__ = "Production"

#from abc import ABC, abstractmethod
import inspect
# import pi_ager_logging
from main.pi_ager_cl_logger import cl_fact_logger
import time
# from sensors.pi_ager_cl_sensor_type import cl_fact_main_sensor_type
from sensors.pi_ager_cl_i2c_bus import cl_fact_i2c_bus_logic
from sensors.pi_ager_cl_i2c_sensor_sht import cl_fact_i2c_sensor_sht
from main.pi_ager_cx_exception import *
# from messenger.pi_ager_cl_messenger import cl_fact_logic_messenger
from sensors.pi_ager_cl_sensor import cl_sensor
# from sensors.pi_ager_cl_ab_sensor import cl_ab_sensor


class cl_sensor_sht(cl_sensor):
    
    _RESET = 0x30A2
    _HEATER_ON = 0x306D
    _HEATER_OFF = 0x3066
    _STATUS = 0xF32D
    _TRIGGER = 0x2C06
    _STATUS_BITS_MASK = 0xFFFC
    
    def __init__(self, i_active_sensor, i_sensor_type, i_address):
        # cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        if i_address is not None:
            cl_fact_logger.get_instance().debug("i2c address is " + hex(i_address))
                    
        self._max_errors = 1
        self._old_temperature = 0
        self._current_temperature = 0
        self._temperature_dewpoint = 0
        self._humidity_absolute = 0
        self._old_humidity = 0
        self._current_humidity = 0
        
        self.o_address = i_address
        

        self._alert_pending = False
        self._heater_status = False
        self._humidity_tracking_alert = False
        self._temperature_tracking_alert = False
        self._system_reset_detected = False
        self._command_status_successfully = False

        
        self.o_sensor_type = i_sensor_type
        super().__init__(self.o_sensor_type)
        self.o_active_sensor= i_active_sensor
        self._i2c_bus = cl_fact_i2c_bus_logic.get_instance().get_i2c_bus()
        cl_fact_logger.get_instance().debug(self._i2c_bus)
        self._i2c_sensor = cl_fact_i2c_sensor_sht.get_instance(self.o_active_sensor, self._i2c_bus, self.o_address)


 
    def _send_i2c_start_command(self):
        # cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        
        msb_data = 0x24
        lsb_data = 0x00
        
        #Write the sensor data
        self._i2c_bus.write_byte_data(self._i2c_sensor.get_address(), msb_data, lsb_data)

        time.sleep(0.01) #This is so the sensor has time to perform the measurement and write its registers before you read it
        
        
        msb_data = 0x21
        lsb_data = 0x30
        #Write the sensor data
        self._i2c_bus.write_byte_data(self._i2c_sensor.get_address(), msb_data, lsb_data)

        time.sleep(0.01) #This is so the sensor has time to perform the measurement and write its registers before you read it
   
    
    def _read_data(self):
        # cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
                     
        self._send_i2c_start_command()
        self.data0 = self._i2c_bus.read_i2c_block_data(self._i2c_sensor.get_address(), 0x00, 8)

        self.t_val = (self.data0[0]<<8) + self.data0[1] #convert the data
        self.h_val = (self.data0[3] <<8) + self.data0[4]     # Convert the data
        
        "CRC Values"
        t_crc_calc = self._i2c_sensor.calculate_checksum(self.t_val)
        h_crc_calc = self._i2c_sensor.calculate_checksum(self.h_val)
        
        t_crc = self.data0[2]
        h_crc = self.data0[5]

        localtime = time.asctime( time.localtime(time.time()) )
        if hex(t_crc_calc) != hex(t_crc):
            cl_fact_logger.get_instance().debug("Local current time :", localtime)    
            cl_fact_logger.get_instance().debug("Temperature CRC calc is : %x " %t_crc_calc)
            cl_fact_logger.get_instance().debug("Temperature CRC real is : %x " %t_crc) 
            cl_fact_logger.get_instance().error("CRC Error")
            if (self._system_reset_detected == True):
                cl_fact_main_sensor().set_instance(None)
            else:
                self.soft_reset()
                self.clear_status_register()

            raise cx_i2c_sht_temperature_crc_error
        
        if hex(h_crc_calc) != hex(h_crc):
            cl_fact_logger.get_instance().debug("Local current time :", localtime)    
            cl_fact_logger.get_instance().debug("Humidity CRC calc is : %x " %h_crc_calc)
            cl_fact_logger.get_instance().debug("Humidity CRC real is : %x " %h_crc) 
            cl_fact_logger.get_instance().error("CRC Error")   
            if (self._system_reset_detected == True):
                 cl_fact_main_sensor().set_instance(None)
            else:
                self.soft_reset()
                self.clear_status_register()
                
            raise cx_i2c_sht_humidity_crc_error
            
   

    def clear_status_register(self):
        # cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        msb_data = 0x30
        lsb_data = 0x41
        
        #Write the sensor data
        self._i2c_bus.write_byte_data(self._i2c_sensor.get_address(), msb_data, lsb_data)

        time.sleep(0.01) #This is so the sensor has ime to perform the measurement and write its registers before you read it
        self.read_status_register()
        
    def read_status_register(self):
        # cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        msb_data = 0xF3
        lsb_data = 0x2D
        
        #Write the sensor data
        self._i2c_bus.write_byte_data(self._i2c_sensor.get_address(), msb_data, lsb_data)

        time.sleep(0.01) #This is so the sensor has tme to preform the mesurement and write its registers before you read it
        
        self.data0 = self._i2c_bus.read_i2c_block_data(self._i2c_sensor.get_address(), 0x00, 16)

        self.status_register = (self.data0[0]<<8) + self.data0[1]
        cl_fact_logger.get_instance().debug("Status register is : %x " %self.status_register)
        
        if self.is_set(self.status_register, 15): self._alert_pending = True 
        else: self._alert_pending = False
        if self.is_set(self.status_register, 13): self._heater_status = True 
        else: self._heater_status = False
        if self.is_set(self.status_register, 11): self._humidity_tracking_alert = True 
        else: self._humidity_tracking_alert = False
        if self.is_set(self.status_register, 10): self._temperature_tracking_alert = True 
        else: self._temperature_tracking_alert = False
        if self.is_set(self.status_register, 4): self._system_reset_detected = True
        else: self._system_reset_detected = False
        if self.is_set(self.status_register, 1): self._command_status_successfully = True
        else: self._command_status_successfully = False
        
        cl_fact_logger.get_instance().debug("Alert pending: %r" % self._alert_pending)
        cl_fact_logger.get_instance().debug("Heater status: %r" % self._heater_status)
        cl_fact_logger.get_instance().debug("Humidity tracking alert: %r" % self._humidity_tracking_alert)
        cl_fact_logger.get_instance().debug("Temperature tracking alert: %r" % self._temperature_tracking_alert)
        cl_fact_logger.get_instance().debug("System reset detected: %r" % self._system_reset_detected)
        cl_fact_logger.get_instance().debug("Command status successfully %r" % self._command_status_successfully)

        # CRC Values
         
        status_register_crc = self._i2c_sensor.calculate_checksum(self.status_register)
        localtime = time.asctime( time.localtime(time.time()) )
        sensor_crc = self.data0[2]
        if hex(status_register_crc) != hex(sensor_crc):
            cl_fact_logger.get_instance().debug("Local current time :", localtime)    
            cl_fact_logger.get_instance().debug("Status Register CRC calc is : %x " %status_register_crc)
            cl_fact_logger.get_instance().debug("Status Register CRC real is : %x " %sensor_crc) 
            cl_fact_logger.get_instance().error("Status Register CRC Error")
            
    def is_set(self, x, n):
        # cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        #return x & 2 ** n != 0 
    
        # a more bitwise- and performance-friendly version:
        return (x & 1 << n != 0) 
        
    def get_current_data(self):
        # cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        
        repeat_count = 0
        repeat_count_max = 5
        while repeat_count < repeat_count_max:
            try:
                self._read_data()
                    
                self._current_temperature = self._get_current_temperature()
                self._current_humidity    = self._get_current_humidity()

                cl_fact_logger.get_instance().debug("sht temperature : " + str(self._current_temperature))
                cl_fact_logger.get_instance().debug("sht humidity : " + str(self._current_humidity))
                self._dewpoint = super().get_dewpoint(self._current_temperature, self._current_humidity)
                (temperature_dewpoint, humidity_absolute) = self._dewpoint
        
                self.measured_data = (self._current_temperature, self._current_humidity, temperature_dewpoint, humidity_absolute)
                return(self.measured_data)
                
            except Exception as cx_error:
                repeat_count = repeat_count + 1
                if (repeat_count == 1):
                    cl_fact_logger.get_instance().exception(cx_error)
                else:
                    cl_fact_logger.get_instance().error(f"Retry getting measurement from I2C device. Current retry count : {repeat_count}, max retry count : {repeat_count_max}")      

        raise cx_measurement_error (_('Too many measurement errors occurred!'))
        
    
    def _get_current_temperature(self):
        # cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())

        t_val = (self.data0[0]<<8) + self.data0[1] #convert the data
        
        Temperature_Celsius    = (175.72 * self.t_val) / (2**16 - 1 ) - 45 #do the maths from datasheet
        Temperature_Fahrenheit = (315.0  * self.t_val) / (2**16 - 1 ) - 49 #do the maths from datasheet

        cl_fact_logger.get_instance().debug("Temperature in Celsius is : %.2f C" %Temperature_Celsius)
        cl_fact_logger.get_instance().debug("Temperature in Fahrenheit is : %.2f F" %Temperature_Fahrenheit)
            
        return(Temperature_Celsius)
  
    def _get_current_humidity(self):
        # cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())

        h_val = (self.data0[3] <<8) + self.data0[4]     # Convert the data
        
        Humidity = (100.0 * self.h_val) / (2**16 - 1 )

        cl_fact_logger.get_instance().debug("Relative Humidity is : %.2f %%RH" %Humidity)

        return(Humidity)

    def soft_reset(self):
        """Performs Soft Reset on SHT chip"""
        # cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())

        msb_data = 0x30
        lsb_data = 0xA2
        #Write the sensor data
        self._i2c_bus.write_byte_data(self._i2c_sensor.get_address(), msb_data, lsb_data)   
        self._send_i2c_start_command()
 
    def set_heading_on(self):
        """Switch the heading on the sensor on"""
        # cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        self._i2c_bus.write(self._HEATER_ON)
        pass
    
    def set_heading_off(self):
        """Switch the heading on the sensor off"""
        # cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        self._i2c_bus.write(self._HEATER_OFF)
        pass

