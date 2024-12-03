#!/usr/bin/python3
# -*- coding: utf-8 -*-
"""
    pi-ager helper functions
    with access to database
"""
import pi_ager_names
import pi_ager_database
from main.pi_ager_cl_logger import cl_fact_logger

# Read humidity parameter table
# find delay and offset for humidifier control by linear interpolation
# as a function of setpoint temperature
# store delay and offset into database
def eval_humidifier_delay_offset( setp_temp ):
    # get humidifier parameter from DB
    humidity_parms = pi_ager_database.get_current(pi_ager_names.humidifier_params_table, True)
    
    i = -1
    count = len(humidity_parms)
    for parms in humidity_parms:
        if (setp_temp < parms[1]):
            break
        i = i + 1

    if (i == -1):
        i = 0
    if (i > (count - 2)):
        i = count - 2

    index0 = i
    index1 = i + 1

    d = (setp_temp - humidity_parms[index0][1]) / (humidity_parms[index1][1] - humidity_parms[index0][1])  # setpoint temperature
    delay = humidity_parms[index0][2] + d * (humidity_parms[index1][2] - humidity_parms[index0][2])        # delay
    offset = humidity_parms[index0][3] + d * (humidity_parms[index1][3] - humidity_parms[index0][3])       # offset
    
    # save new delay and offset into database
    pi_ager_database.update_table_val(pi_ager_names.config_settings_table, pi_ager_names.delay_humidify_key, delay)
    pi_ager_database.update_table_val(pi_ager_names.config_settings_table, pi_ager_names.humidifier_hysteresis_offset_key, offset)
    cl_fact_logger.get_instance().debug("setpoint temp. : " + str(setp_temp) + " delay : " + str(delay) + " offset : " + str(offset))
    
# Read temperature control table
# find cooler and heater hysterese offsets by linear interpolation
# as a function of setpoint temperature
# store cooler and heater hysteresis offsets into database
def eval_cooler_heater_offsets( setp_temp ):
    # get temperature_control_params_table  from DB
    temperature_control_params = pi_ager_database.get_current(pi_ager_names.temperature_control_params_table, True)
    
    i = -1
    count = len(temperature_control_params)
    for parms in temperature_control_params:
        if (setp_temp < parms[1]):
            break
        i = i + 1

    if (i == -1):
        i = 0
    if (i > (count - 2)):
        i = count - 2

    index0 = i
    index1 = i + 1

    d = (setp_temp - temperature_control_params[index0][1]) / (temperature_control_params[index1][1] - temperature_control_params[index0][1])  # setpoint temperature
    cooler_offset = temperature_control_params[index0][2] + d * (temperature_control_params[index1][2] - temperature_control_params[index0][2])        # cooler offset
    heater_offset = temperature_control_params[index0][3] + d * (temperature_control_params[index1][3] - temperature_control_params[index0][3])       # heater offset
    
    # save new cooler and heater hysteresis offsets into database
    pi_ager_database.update_table_val(pi_ager_names.config_settings_table, pi_ager_names.cooling_hysteresis_offset_key, cooler_offset)
    pi_ager_database.update_table_val(pi_ager_names.config_settings_table, pi_ager_names.heating_hysteresis_offset_key, heater_offset)
    cl_fact_logger.get_instance().debug("setpoint temp. : " + str(setp_temp) + " cooler offset : " + str(cooler_offset) + " heater offset : " + str(heater_offset))
    