# -*- coding: utf-8 -*-

"""This class is for handling the AHT sensors AHT10, AHT20, AHT21, AHT30 with i2c bus interface from ASAIR.""" 

#from abc import ABC, abstractmethod
import inspect
# import pi_ager_logging
from main.pi_ager_cl_logger import cl_fact_logger
import time
# from abc import ABC, abstractmethod
from sensors.pi_ager_cl_i2c_bus import cl_fact_i2c_bus_logic
from sensors.pi_ager_cl_i2c_sensor_aht import cl_fact_i2c_sensor_aht
from main.pi_ager_cx_exception import *
# from messenger.pi_ager_cl_messenger import cl_fact_logic_messenger
from sensors.pi_ager_cl_sensor import cl_sensor

class cl_sensor_aht(cl_sensor):
    AHT20_I2CADDR = 0x38
    AHT20_CMD_SOFTRESET = [0xBA]
    AHT20_CMD_INITIALIZE = [0xBE, 0x08, 0x00]
    AHT20_CMD_MEASURE = [0xAC, 0x33, 0x00]
    AHT20_STATUSBIT_BUSY = 7                    # The 7th bit is the Busy indication bit. 1 = Busy, 0 = not.
    AHT20_STATUSBIT_CALIBRATED = 3              # The 3rd bit is the CAL (calibration) Enable bit. 1 = Calibrated, 0 = not
    # AHT20 crc8 checker. 
    # A total of 6 * 8 bits data need to check. G(x) = x8 + x5 + x4 + 1 -> 0x131(0x31), Initial value = 0xFF. No XOROUT. 
    # Author: XU Zifeng. 
    # Email: zifeng.xu@foxmail.com
    N_DATA = 6
    # 1 * 8 bits CRC
    N_CRC = 1
    # Initial value. Equal to bit negation the first data (status of AHT20)
    INIT = 0xFF
    # Useful value to help calculate
    LAST_8_bit = 0xFF

    # Devide number retrieve from CRC-8 MAXIM G(x) = x8 + x5 + x4 + 1
    CRC_DEVIDE_NUMBER = 0x131

    # Data and CRC taken from AHT20, use this for testing?
    TEST_DATA = [[28, 184, 245, 165, 156, 208, 163], [28, 185, 16, 149, 156, 83, 112], [
    28, 184, 249, 85, 156, 114, 213], [28, 185, 9, 53, 156, 54, 45], [28, 185, 70, 117, 156, 189, 33], [28, 185, 64, 165, 156, 61, 209]]

#---------------------------------------------------------------------------------------------------------------------------------------------#

    def __init__(self, i_active_sensor, i_sensor_type, i_address):
        
        cl_fact_logger.get_instance().debug("i2c address is " + hex(i_address) + ". active sensor is : " + i_active_sensor + ". i_sensor_type is : " + i_sensor_type.get_sensor_type_ui())

        self.o_address = i_address
        self.o_sensor_type = i_sensor_type
        self.o_active_sensor = i_active_sensor
        
        super().__init__(self.o_sensor_type)

        self._i2c_bus = cl_fact_i2c_bus_logic.get_instance().get_i2c_bus()
        cl_fact_logger.get_instance().debug("i2c bus object is : " + str(self._i2c_bus))
        self._i2c_sensor = cl_fact_i2c_sensor_aht.get_instance(self.o_active_sensor, self._i2c_bus, self.o_address)

        self.cmd_soft_reset()
        # Check for calibration, if not done then do and wait 10 ms
        if not self.get_status_calibrated() == 1:
            self.cmd_initialize()
            inner_repeat_count = 0
            inner_repeat_count_max = 2
            while (not self.get_status_calibrated() == 1 and (inner_repeat_count < inner_repeat_count_max)):
                time.sleep(0.01)
                inner_repeat_count += 1
                    
            if (inner_repeat_count == inner_repeat_count_max):
                raise cx_measurement_error (_('Error initializing AHT2x sensor!'))
        return

    def cmd_soft_reset(self):
        # Send the command to soft reset
        self._i2c_bus.write_i2c_block_data(self.o_address, 0x0, self.AHT20_CMD_SOFTRESET)
        time.sleep(0.04)    # Wait 40 ms after poweron
        return True

    def cmd_initialize(self):
        # Send the command to initialize (calibrate)
        self._i2c_bus.write_i2c_block_data(self.o_address, 0x0 , self.AHT20_CMD_INITIALIZE)
        return True

    def get_status(self):
        # Get the full status byte
        return self._i2c_bus.read_i2c_block_data(self.o_address, 0x0, 1)[0]
        
    def get_status_calibrated(self):
        # Get the calibrated bit
        return self.get_normalized_bit(self.get_status(), self.AHT20_STATUSBIT_CALIBRATED)
    
    def get_status_busy(self):
        # Get the busy bit
        return self.get_normalized_bit(self.get_status(), self.AHT20_STATUSBIT_BUSY)
            
    def get_normalized_bit(self, value, bit_index):
        # Return only one bit from value indicated in bit_index
        return (value >> bit_index) & 1

    def cmd_measure(self):
        # Send the command to measure
        self._i2c_bus.write_i2c_block_data(self.o_address, 0, self.AHT20_CMD_MEASURE)
        time.sleep(0.08)    # Wait 80 ms after measure
        return True

            
    def get_measure(self):
        # Get the full measure

        # Command a measure
        self.cmd_measure()

        # Check if busy bit = 0, otherwise wait 80 ms and retry
        count = 0
        count_max = 4
        while self.get_status_busy() == 1:
            count += 1
            if (count >= countMax):
                raise cx_measurement_error (_('Cant read status from AHT2x!'))
            time.sleep(0.08) # Wait 80 ns
        
        # TODO: do CRC check

        # Read data and return it
        return self._i2c_bus.read_i2c_block_data(self.o_address, 0x0, 7)

    def get_measure_CRC8(self):
        """
        This function will calculate crc8 code with G(x) = x8 + x5 + x4 + 1 -> 0x131(0x31), Initial value = 0xFF. No XOROUT.
        return: all_data (1 bytes status + 2.5 byes humidity + 2.5 bytes temperature + 1 bytes crc8 code), isCRC8_pass
        """
        all_data = self.get_measure()
        isCRC8_pass = self.AHT20_crc8_check(all_data)

        return all_data, isCRC8_pass
        

    def mod2_division_8bits(self, a, b, number_of_bytes, init_value):
        "calculate mod2 division in 8 bits. a mod b. init_value is for crc8 init value."
        head_of_a = 0x80
        # Processiong a
        a = a << 8
        # Preprocessing head_of_a
        for i in range(0, number_of_bytes):
            head_of_a = head_of_a << 8
            b = b << 8
            init_value = init_value << 8
        a = a ^ init_value
        while (head_of_a > 0x80):
            # Find a 1
            if (head_of_a & a):
                head_of_a = head_of_a >> 1
                b = b >> 1
                a = a ^ b
            else:
                head_of_a = head_of_a >> 1
                b = b >> 1
            # This will show calculate the remainder
            # print("a:{0}\thead of a:{1}\tb:{2}".format(
            #     bin(a), bin(head_of_a), bin(b)))
        return a


    def AHT20_crc8_calculate(self, all_data_int):
        init_value = self.INIT
        # Preprocess all the data and CRCCode from AHT20
        data_from_AHT20 = 0x00
        # Preprocessing the first data (status)
        # print(bin(data_from_AHT20))
        for i_data in range(0, len(all_data_int)):
            data_from_AHT20 = (data_from_AHT20 << 8) | all_data_int[i_data]
        # print(bin(data_from_AHT20))
        mod_value = self.mod2_division_8bits(data_from_AHT20, self.CRC_DEVIDE_NUMBER, len(all_data_int), init_value)
        # print(mod_value)
        return mod_value


    def AHT20_crc8_check(self, all_data_int):
        """
        The input data shoule be:
        Status Humidity0 Humidity1 Humidity2|Temperature0 Temperature1 Temperature2 CRCCode.
        In python's int64.
        """
        mod_value = self.AHT20_crc8_calculate(all_data_int[:-1])
        if (mod_value == all_data_int[-1]):
            return True
        else:
            return False



    def CRC8_check(self, all_data_int, init_value=0x00):
        divider = 0x107
        DATA_FOR_CHECK = all_data_int[0]
        for data in all_data_int[1:-1]:
            DATA_FOR_CHECK = (DATA_FOR_CHECK << 8) | data
        remainder = self.mod2_division_8bits( DATA_FOR_CHECK, divider, len(all_data_int) - 1, init_value)
        if (remainder == all_data_int[-1]):
            return True
        else:
            return False


    def get_temperature(self):
        # Get a measure, select proper bytes, return converted data
        measure = self.get_measure()
        measure = ((measure[3] & 0xF) << 16) | (measure[4] << 8) | measure[5]
        measure = measure / (pow(2,20))*200-50
        return measure
        
    def get_temperature_crc8(self):
        isCRC8Pass = False
        while (not isCRC8Pass): 
            measure, isCRC8Pass = self.get_measure_CRC8()
            time.sleep(80 * 10**-3)
            
        measure = ((measure[3] & 0xF) << 16) | (measure[4] << 8) | measure[5]
        measure = measure / (pow(2,20))*200-50
        return measure

    def get_humidity(self):
        # Get a measure, select proper bytes, return converted data
        measure = self.get_measure()
        measure = (measure[1] << 12) | (measure[2] << 4) | (measure[3] >> 4)
        measure = measure * 100 / pow(2,20)
        return measure

    def get_humidity_crc8(self):
        isCRC8Pass = False
        while (not isCRC8Pass): 
            measure, isCRC8Pass = self.get_measure_CRC8()
            time.sleep(80 * 10**-3)
        measure = (measure[1] << 12) | (measure[2] << 4) | (measure[3] >> 4)
        measure = measure * 100 / pow(2,20)
        return measure

    def get_temp_hum_crc8(self):
        # get both temperature an humidity
        # isCRC8Pass = False
        # while (not isCRC8Pass): 
        #     measure, isCRC8Pass = self.get_measure_CRC8()
        #     time.sleep(80 * 10**-3)
        measure, isCRC8Pass = self.get_measure_CRC8() 
        if (not isCRC8Pass):
            time.sleep(80 * 10**-3)
            raise cx_i2c_aht_crc_error(_('CRC error reading  AHT2x!'))
            
        temperature = ((measure[3] & 0xF) << 16) | (measure[4] << 8) | measure[5]
        temperature = temperature / (pow(2,20))*200-50

        humidity = (measure[1] << 12) | (measure[2] << 4) | (measure[3] >> 4)
        humidity = humidity * 100 / pow(2,20)
        return temperature,humidity 
        
    def get_current_data(self):
        # cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        
        repeat_count = 0
        repeat_count_max = 2
        while repeat_count < repeat_count_max:
            try:
                temperature, humidity =  self.get_temp_hum_crc8()

                cl_fact_logger.get_instance().debug("aht temperature : " + str(temperature))
                cl_fact_logger.get_instance().debug("aht humidity : " + str(humidity))
                dewpoint = super().get_dewpoint(temperature, humidity)
                (temperature_dewpoint, humidity_absolute) = dewpoint
        
                self.measured_data = (temperature, humidity, temperature_dewpoint, humidity_absolute)
                return(self.measured_data)
                
            except Exception as cx_error:
                repeat_count += 1
                if (repeat_count == 1):
                    cl_fact_logger.get_instance().exception(cx_error)
                else:
                    cl_fact_logger.get_instance().error(f"Retry getting measurement from I2C device AHT2x. Current retry count : {repeat_count}, max retry count : {repeat_count_max}")      

        raise cx_measurement_error (_('Too many measurement errors occurred!'))
        

    def soft_reset(self):
        pass
        
    def set_heading_on(self):
        pass
        
    def set_heading_off(self):
        pass
