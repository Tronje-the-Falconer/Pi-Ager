#!/usr/bin/python3
# -*- coding: iso-8859-1 -*-
# globals.py
"""
pi-ager global values
"""
import threading

def init():
    global lock
    lock = threading.Lock()
    global hands_off_light_switch
    hands_off_light_switch = False
    nextion_thread = None
    