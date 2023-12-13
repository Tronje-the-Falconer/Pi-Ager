# -*- coding: utf-8 -*-
 
"""This exception class is for all Exceptions in this project ."""

__author__ = "Claus Fischer"
__copyright__ = "Copyright 2019, The Pi-Ager Project"
__credits__ = ["Claus Fischer"]
__license__ = "GPL"
__version__ = "1.0.0"
__maintainer__ = "Claus Fischer"
__email__ = "DerBurgermeister@pi-ager.org"
__status__ = "Production" 

class Error(Exception):
    """Base class for other exceptions"""
    pass

class cx_Sensor_not_defined(Error):
    pass

class cx_direct_call(Error):
    def __init__(self, message, errors):

        # Call the base class constructor with the parameters it needs
        super().__init__(message)

        # Now for your custom code...
        self.errors = errors

class cx_no_email_server_config_found(Error):
    pass

class cx_no_email_recipient_config_found(Error):
    pass

class cx_i2c_sht_temperature_crc_error(Error):
    pass

class cx_i2c_sht_humidity_crc_error(Error):
    pass
    
class cx_i2c_bus_error(Error):
    pass

class cx_adafruit_error(Error):
    pass

class cx_measurement_error(Error):
    pass
    
class cx_i2c_aht_temperature_crc_error(Error):
    pass

class cx_i2c_aht_humidity_crc_error(Error):
    pass    