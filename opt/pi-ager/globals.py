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
    
    