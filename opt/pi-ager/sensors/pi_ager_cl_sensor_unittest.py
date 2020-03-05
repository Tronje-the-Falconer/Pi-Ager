# -*- coding: utf-8 -*-
 
"""This unit-test class is for testing the sensor class."""

__author__ = "Claus Fischer"
__copyright__ = "Copyright 2019, The Pi-Ager Project"
__credits__ = ["Claus Fischer"]
__license__ = "GPL"
__version__ = "1.0.0"
__maintainer__ = "Claus Fischer"
__email__ = "DerBurgermeister@pi-ager.org"
__status__ = "Development"

from unittest import TestCase
from sensors.pi_ager_cl_sensor import cl_fact_main_sensor, th_main_sensor
from sensors.pi_ager_cl_sensor_type import cl_fact_main_sensor_type, th_main_sensor_type
from main.pi_ager_cx_exception import cx_Sensor_not_defined



class Test_cl_sensor(TestCase):
    
    def setUp(self):                                                             
        pass
    #Check Singleton
    def test_singleton(self):
        o_sensor1 =  cl_fact_main_sensor.get_instance()
        o_sensor2 =  cl_fact_main_sensor.get_instance()
        self.assertEqual(o_sensor1, o_sensor2)
                      
    def test_get_sensor_type_ok(self):
        th_sensor_type = th_main_sensor_type()
        cl_fact_main_sensor_type.set_instance(th_sensor_type)
        th_sensor = th_main_sensor()
        th_sensor_type._type = 'SHT75'           

        result = th_sensor.get_sensor_type().get_type()                                     
        expected = "SHT75"                                                     
        self.assertEqual(result, expected)   
        
    def test_get_sensor_type_error(self):
        th_sensor_type = th_main_sensor_type()
        cl_fact_main_sensor_type.set_instance(th_sensor_type)
        th_sensor = th_main_sensor()
        th_sensor_type._type = 'SHT73'           

        result = th_sensor.get_sensor_type().get_type()                                     
        expected = "SHT75"                                                     
        self.assertNotEqual(result, expected)   
     
   
        
                