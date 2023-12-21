# -*- coding: utf-8 -*-

"""This abstract classes is for handling the main sensor(s) for the Pi-Ager."""

from abc import ABC, abstractmethod

class cl_ab_sensor(ABC):
    def __init__(self):
        pass
        
    @abstractmethod
    def get_dewpoint(self, temperature, humidity):
        pass       
