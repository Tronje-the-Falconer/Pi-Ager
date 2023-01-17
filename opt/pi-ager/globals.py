#!/usr/bin/python3
# -*- coding: iso-8859-1 -*-
# globals.py
"""
pi-ager global values
"""
import threading
import pi_ager_names

def init():
    global lock
    lock = threading.Lock()
    global hands_off_light_switch
    hands_off_light_switch = False
    global switch_control_uv_light
    switch_control_uv_light = 0 # switch control not active
    global switch_control_light
    switch_control_light = 0    # switch control not active
    global requested_state_uv_light
    requested_state_uv_light = pi_ager_names.relay_off    # relais not active
    global requested_state_light
    requested_state_light = pi_ager_names.relay_off    # relais not active
    