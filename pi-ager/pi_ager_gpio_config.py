#!/usr/bin/python3
import RPi.GPIO as gpio
import pi_ager_names
import pi_ager_logging

global logger
logger = pi_ager_logging.create_logger(__name__)
logger.debug('logging initialised')

# Function Setup GPIO
def setupGPIO():
    global logger
    logstring = 'setupGPIO()'
    logger.debug(logstring)
    gpio.setwarnings(False)
    
    # Board mode wird gesetzt
    gpio.setmode(pi_ager_names.board_mode)
    
    # Einstellen der GPIO PINS
    
    # gpio.setup(pi_ager_names.gpio_notinuse_0, gpio. )
    gpio.setup(pi_ager_names.gpio_heater, gpio.OUT)                # Heizung setzen
    gpio.setup(pi_ager_names.gpio_cooling_compressor, gpio.OUT)    # Kuehlung setzen
    # gpio.setup(pi_ager_names.gpio_sensor_data, gpio. )
    # gpio.setup(pi_ager_names.gpio_sensor_sync, gpio. )
    # gpio.setup(pi_ager_names.gpio_switch, gpio. )
    gpio.setup(pi_ager_names.gpio_scale2_data, gpio.IN)
    gpio.setup(pi_ager_names.gpio_scale2_sync, gpio.OUT)
    # gpio.setup(pi_ager_names.gpio_notinuse_1, gpio. )
    # gpio.setup(pi_ager_names.gpio_notinuse_2, gpio. )
    gpio.setup(pi_ager_names.gpio_scale1_data, gpio.IN)
    gpio.setup(pi_ager_names.gpio_scale1_sync, gpio.OUT)
    # gpio.setup(pi_ager_names.gpio_alarm, gpio. )
    # gpio.setup(pi_ager_names.gpio_temperature_meat_MISO, gpio. )
    # gpio.setup(pi_ager_names.gpio_notinuse_3, gpio. )

    # gpio.setup(pi_ager_names.gpio_display_txd, gpio. )
    # gpio.setup(pi_ager_names.gpio_display_rxd, gpio. )
    gpio.setup(pi_ager_names.gpio_humidifier, gpio.OUT)            # Befeuchter setzen
    gpio.setup(pi_ager_names.gpio_exhausting_air, gpio.OUT)        # Abluft setzen
    gpio.setup(pi_ager_names.gpio_circulating_air, gpio.OUT)       # Umluft setzen
    gpio.setup(pi_ager_names.gpio_uv, gpio.OUT)                    # UV-Licht setzen
    gpio.setup(pi_ager_names.gpio_light, gpio.OUT)                 # Licht setzen
    gpio.setup(pi_ager_names.gpio_dehumidifier, gpio.OUT)          # Dehumidifier setzen
    # gpio.setup(pi_ager_names.gpio_notinuse_4, gpio. )
    # gpio.setup(pi_ager_names.gpio_notinuse_5, gpio. )
    # gpio.setup(pi_ager_names.gpio_temperature_meat_CSO, gpio. )
    # gpio.setup(pi_ager_names.gpio_temperature_meat_MOSI, gpio. )
    # gpio.setup(pi_ager_names.gpio_temperature_meat_SCLK, gpio. )

def defaultGPIO():
    global logger
    logstring = 'defaultGPIO()'
    logger.debug(logstring)
    
    gpio.output(pi_ager_names.gpio_heater, pi_ager_names.relay_off)              # Heizung Relais standardmaessig aus
    gpio.output(pi_ager_names.gpio_cooling_compressor, pi_ager_names.relay_off)  # Kuehlung Relais standardmaessig aus
    gpio.output(pi_ager_names.gpio_circulating_air, pi_ager_names.relay_off)     # Umluft Relais standardmaessig aus
    gpio.output(pi_ager_names.gpio_humidifier, pi_ager_names.relay_off)          # Befeuchter Relais standardmaessig aus
    gpio.output(pi_ager_names.gpio_exhausting_air, pi_ager_names.relay_off)      # Abluft Relais standardmaessig aus
    gpio.output(pi_ager_names.gpio_light, pi_ager_names.relay_off)               # Licht Relais standardmaessig aus
    gpio.output(pi_ager_names.gpio_uv, pi_ager_names.relay_off)                  # UV-Licht Relais standardmaessig aus
    gpio.output(pi_ager_names.gpio_dehumidifier, pi_ager_names.relay_off)        # Reserve Relais standardmaessig aus
    gpio.output(pi_ager_names.gpio_alarm, pi_ager_names.relay_off)
    # gpio.output(pi_ager_names.gpio_switch, pi_ager_names.relay_off)
    # gpio.output(pi_ager_names.gpio_notinuse_0, pi_ager_names.relay_off)
    # gpio.output(pi_ager_names.gpio_notinuse_1, pi_ager_names.relay_off)
    # gpio.output(pi_ager_names.gpio_notinuse_2, pi_ager_names.relay_off)
    # gpio.output(pi_ager_names.gpio_notinuse_3, pi_ager_names.relay_off)
    # gpio.output(pi_ager_names.gpio_notinuse_4, pi_ager_names.relay_off)
    # gpio.output(pi_ager_names.gpio_notinuse_5, pi_ager_names.relay_off)
    
def gpios_are_in_use():
    gpio_heater_usage = gpio_is_in_use(pi_ager_names.gpio_heater)
    gpio_cooling_compressor_usage = gpio_is_in_use(pi_ager_names.gpio_cooling_compressor)
    gpio_circulating_air_usage = gpio_is_in_use(pi_ager_names.gpio_circulating_air)
    gpio_humidifier_usage = gpio_is_in_use(pi_ager_names.gpio_humidifier)
    gpio_exhausting_air_usage = gpio_is_in_use(pi_ager_names.gpio_exhausting_air)
    gpio_light_usage = gpio_is_in_use(pi_ager_names.gpio_light)
    gpio_uv_usage = gpio_is_in_use(pi_ager_names.gpio_uv)
    gpio_dehumidifier_usage = gpio_is_in_use(pi_ager_names.gpio_dehumidifier)
    gpio_alarm_usage = gpio_is_in_use(pi_ager_names.gpio_alarm)
    # gpio_switch_usage = gpio_is_in_use(pi_ager_names.gpio_switch)
    # gpio_notinuse_0_usage = gpio_is_in_use(pi_ager_names.gpio_notinuse_0)
    # gpio_notinuse_1_usage = gpio_is_in_use(pi_ager_names.gpio_notinuse_1)
    # gpio_notinuse_2_usage = gpio_is_in_use(pi_ager_names.gpio_notinuse_2)
    # gpio_notinuse_3_usage = gpio_is_in_use(pi_ager_names.gpio_notinuse_3)
    # gpio_notinuse_4_usage = gpio_is_in_use(pi_ager_names.gpio_notinuse_4)
    # gpio_notinuse_5_usage = gpio_is_in_use(pi_ager_names.gpio_notinuse_5)
    
    if gpio_uv_usage or gpio_light_usage or gpio_humidifier_usage or gpio_heater_usage or gpio_dehumidifier_usage or gpio_cooling_compressor_usage or gpio_circulating_air_usage or gpio_exhausting_air_function or gpio_alarm_usage:
        return True
    else:
        return False

def gpio_is_in_use(gpio):
    gpio_usage = gpio.gpio_function(gpio)
    
    if gpio_usage == -1:
        return True
    else:
        return False