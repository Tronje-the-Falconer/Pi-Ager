# -*- coding: utf-8 -*-
 
"""This unit-test class is for testing the sensor type class."""

__author__ = "Claus Fischer"
__copyright__ = "Copyright 2019, The Pi-Ager Project"
__credits__ = ["Claus Fischer"]
__license__ = "GPL"
__version__ = "1.0.0"
__maintainer__ = "Claus Fischer"
__email__ = "DerBurgermeister@pi-ager.org"
__status__ = "Development"

from unittest import TestCase
#from pi_ager_cl_sensor import cl_fact_main_sensor, th_main_sensor
from pi_ager_cl_sensor_type import cl_fact_main_sensor_type, th_main_sensor_type
from pi_ager_cx_exception import *
import pi_ager_cx_exception



class Test_cl_sensor_type(TestCase):
    
    #Check Singleton
    def test_singleton(self):
        o_sensor_type1 =  cl_fact_main_sensor_type.get_instance()
        o_sensor_type2 =  cl_fact_main_sensor_type.get_instance()
        self.assertEqual(o_sensor_type1, o_sensor_type2)
        
    #Check SHT75
    def test_get_type_SHT75(self):
        th_sensor_type = th_main_sensor_type()
        cl_fact_main_sensor_type.set_instance(th_sensor_type)
        th_sensor_type = th_main_sensor_type()
        th_sensor_type._type = 'SHT75'           
                    
        self.assertEqual(th_sensor_type.get_type(), "SHT75")   


    #Check DHT11
    def test_get_type_DHT11(self):
        th_sensor_type = th_main_sensor_type()
        cl_fact_main_sensor_type.set_instance(th_sensor_type)
        th_sensor_type = th_main_sensor_type()
        th_sensor_type._type = 'DHT11'           
                    
        self.assertEqual(th_sensor_type.get_type(), "DHT11")   
        
    #Check DHT11 Error
    def test_get_type_DHT11_error(self):
        th_sensor_type = th_main_sensor_type()
        cl_fact_main_sensor_type.set_instance(th_sensor_type)
        th_sensor_type = th_main_sensor_type()
        th_sensor_type._type = 'SHT1x'           
                  
        self.assertNotEqual(th_sensor_type.get_type(),"DHT11")   

        
    #Check Exception        
    def test_exception(self):
        th_sensor_type = th_main_sensor_type()
        cl_fact_main_sensor_type.set_instance(th_sensor_type)
        th_sensor_type = th_main_sensor_type()
        th_sensor_type.is_valid = False
        
        with self.assertRaises(cx_Sensor_not_defined) as cx_error:
            th_sensor_type.get_type()
        self.assertEqual("This sensor is not defined", str(cx_error.exception))

            
    #Check Main Sensor name            
    def test_get_name(self):
        ret = cl_fact_main_sensor_type.get_instance()
        self.assertEqual(ret.get_name(), "Main_sensor")         
        
    