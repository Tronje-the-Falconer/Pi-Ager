#!/usr/bin/python3
"""
    GPIO handling
    
    handling GPIO's
"""
import RPi.GPIO as gpio
import pi_ager_names
# import pi_ager_logging
from main.pi_ager_cl_logger import cl_fact_logger

# hardcoded values
# Pinbelegung
# Pinleiste vertikal Pin 1 oben links pin 2 oben rechts
board_mode = gpio.BCM              # GPIO board mode (BCM = Broadcom SOC channel number - numbers after GPIO Bsp. GPIO12=12 [GPIO.BOARD = Pin by number Bsp: GPIO12=32])

# linke Pinleiste:
# 3 V
gpio_notinuse_0 = 2                 
gpio_heater = 3                    # GPIO fuer Heizkabel
gpio_cooling_compressor = 4        # GPIO fuer Kuehlschrankkompressor
# Ground
gpio_sensor_data = 17              # GPIO fuer Data Temperatur/Humidity Sensor
gpio_sensor_sync = 27              # GPIO fuer Sync Temperatur/Humidity Sensor
gpio_switch = 22                   # GPIO fuer manuellen Schalter
# 3 V
gpio_scale2_data = 10               # GPIO fuer Waage2 Data
gpio_scale2_sync = 9                # GPIO fuer Waage2 Sync
gpio_ups_bat_low = 11               # GPIO fuer UPS Batterie low signal
# Ground
gpio_notinuse_2 =  0
gpio_scale1_data = 5                # GPIO fuer Waage1 Data
gpio_scale1_sync = 6                # GPIO fuer Waage1 Sync
gpio_alarm = 13                     # GPIO fuer Piezzo
gpio_temperature_meat_MISO = 19     # GPIO fuer A/D Wandler Fleischtemperatursensoren
gpio_power_monitor = 26             # GPIO für Powersignal
# Ground

# rechte Pinleiste:#
# 5 V
# 5 V
# Ground
gpio_display_txd = 14            # GPIO fuer Display
gpio_display_rxd = 15            # GPIO fuer Diplay
gpio_humidifier = 18               # GPIO fuer Luftbefeuchter
# Ground
gpio_exhausting_air = 23           # GPIO fuer Austauschluefter
gpio_circulating_air = 24          # GPIO fuer Umluftventilator
# Ground
gpio_uv = 25                       # GPIO fuer UV Licht
gpio_light = 8                     # GPIO fuer Licht
gpio_dehumidifier = 7              # GPIO fuer Entfeuchter
gpio_notinuse_4 = 1
# Ground
gpio_notinuse_5 = 12
# Ground
gpio_temperature_meat_CSO = 16     # GPIO fuer A/D Wandler Fleischtemperatursensoren
gpio_temperature_meat_MOSI = 20    # GPIO fuer A/D Wandler Fleischtemperatursensoren
gpio_temperature_meat_SCLK = 21    # GPIO fuer A/D Wandler Fleischtemperatursensoren Sync



# global logger
# logger = pi_ager_logging.create_logger(__name__)
# logger.debug('logging initialised')

# Function Setup GPIO
def setupGPIO():
    """
    setting up GPIO's (boardmode, in/out)
    """
    # global logger
    logstring = 'setupGPIO()'
    # logger.debug(logstring)
    cl_fact_logger.get_instance().debug(logstring)
    gpio.setwarnings(False)
    
    # Board mode wird gesetzt
    gpio.setmode(board_mode)
    
    # Einstellen der GPIO PINS
    # Pinleiste vertikal Pin 1 oben links pin 2 oben rechts
    
    # linke Pinleiste:
    # 3,3 V
    # gpio.setup(pi_ager_names.gpio_notinuse_0, gpio. )            
    gpio.setup(gpio_heater, gpio.OUT)                # Heizung setzen
    gpio.setup(gpio_cooling_compressor, gpio.OUT)    # Kuehlung setzen
    # Ground
    # gpio.setup(pi_ager_names.gpio_sensor_data, gpio. )           # wird ueber Bibliothek gesetzt
    # gpio.setup(pi_ager_names.gpio_sensor_sync, gpio. )           # wird ueber Bibliothek gesetzt
    gpio.setup(gpio_switch, gpio.IN )                # manueller Schalter setzen
    # 3,3 V
    gpio.setup(gpio_scale2_data, gpio.IN)             # Scale2 Data setzen
    gpio.setup(gpio_scale2_sync, gpio.OUT)            # Scale2 Sync setzen
    # gpio.setup(pi_ager_names.gpio_ups_bat_low, gpio.IN)           # UPS Bat LOW signal
    # Ground
    # gpio.setup(pi_ager_names.gpio_notinuse_2, gpio. )
    gpio.setup(gpio_scale1_data, gpio.IN)             # Scale1 Data setzen
    gpio.setup(gpio_scale1_sync, gpio.OUT)            # Scale1 Sync setzen
    gpio.setup(gpio_alarm, gpio.OUT )                 # Piezzo setzen
    # gpio.setup(pi_ager_names.gpio_temperature_meat_MISO, gpio. )
    gpio.setup(gpio_power_monitor, gpio.IN)        # Monitor Power auswertung
    # Ground

    # rechte Pinleiste:
    # 5 V
    # 5 V
    # Ground
    # gpio.setup(pi_ager_names.gpio_display_txd, gpio. )
    # gpio.setup(pi_ager_names.gpio_display_rxd, gpio. )
    gpio.setup(gpio_humidifier, gpio.OUT)            # Befeuchter setzen
    # Ground
    gpio.setup(gpio_exhausting_air, gpio.OUT)        # Abluft setzen
    gpio.setup(gpio_circulating_air, gpio.OUT)       # Umluft setzen
    # Ground
    gpio.setup(gpio_uv, gpio.OUT)                    # UV-Licht setzen
    gpio.setup(gpio_light, gpio.OUT)                 # Licht setzen
    gpio.setup(gpio_dehumidifier, gpio.OUT)          # Dehumidifier setzen
    # gpio.setup(pi_ager_names.gpio_notinuse_4, gpio. )
    # Ground
    # gpio.setup(pi_ager_names.gpio_notinuse_5, gpio. )
    # Ground
    # gpio.setup(pi_ager_names.gpio_temperature_meat_CSO, gpio. )
    # gpio.setup(pi_ager_names.gpio_temperature_meat_MOSI, gpio. )
    # gpio.setup(pi_ager_names.gpio_temperature_meat_SCLK, gpio. )

def defaultGPIO():
    """
    setting up default gpio (1/0)
    """
    # global logger
    logstring = 'defaultGPIO()'
    # logger.debug(logstring)
    cl_fact_logger.get_instance().debug(logstring)
    
    gpio.output(gpio_heater, pi_ager_names.relay_off)              # Heizung Relais standardmaessig aus
    gpio.output(gpio_cooling_compressor, pi_ager_names.relay_off)  # Kuehlung Relais standardmaessig aus
    gpio.output(gpio_circulating_air, pi_ager_names.relay_off)     # Umluft Relais standardmaessig aus
    gpio.output(gpio_humidifier, pi_ager_names.relay_off)          # Befeuchter Relais standardmaessig aus
    gpio.output(gpio_exhausting_air, pi_ager_names.relay_off)      # Abluft Relais standardmaessig aus
    gpio.output(gpio_light, pi_ager_names.relay_off)               # Licht Relais standardmaessig aus
    gpio.output(gpio_uv, pi_ager_names.relay_off)                  # UV-Licht Relais standardmaessig aus
    gpio.output(gpio_dehumidifier, pi_ager_names.relay_off)        # Entfeuchter Relais standardmaessig aus
    gpio.output(gpio_alarm, pi_ager_names.pin_without_voltage)
    # gpio.output(pi_ager_names.gpio_switch, pi_ager_names.pin_without_voltage)  # ist Eingang
    # gpio.output(pi_ager_names.gpio_power_monitor, pi_ager_names.pin_without_voltage) # ist Eingang
    # gpio.output(pi_ager_names.gpio_ups_bat_low, pi_ager_names.pin_without_voltage) # ist Eingang
    # gpio.output(pi_ager_names.gpio_notinuse_2, pi_ager_names.pin_without_voltage)
    # gpio.output(pi_ager_names.gpio_notinuse_3, pi_ager_names.pin_without_voltage)
    # gpio.output(pi_ager_names.gpio_notinuse_4, pi_ager_names.pin_without_voltage)
    # gpio.output(pi_ager_names.gpio_notinuse_5, pi_ager_names.pin_without_voltage)
    
def gpios_are_in_use():
    """
    setting up gpio_xxx_used variables
    """
    gpio_heater_used = gpio_is_in_use(gpio_heater)
    gpio_cooling_compressor_used = gpio_is_in_use(gpio_cooling_compressor)
    gpio_circulating_air_used = gpio_is_in_use(gpio_circulating_air)
    gpio_humidifier_used = gpio_is_in_use(gpio_humidifier)
    gpio_exhausting_air_used = gpio_is_in_use(gpio_exhausting_air)
    gpio_light_used = gpio_is_in_use(gpio_light)
    gpio_uv_used = gpio_is_in_use(gpio_uv)
    gpio_dehumidifier_used = gpio_is_in_use(gpio_dehumidifier)
    gpio_alarm_used = gpio_is_in_use(gpio_alarm)
    # gpio_switch_used = gpio_is_in_use(pi_ager_names.gpio_switch)   # ist Eingang
    # gpio_power_monitor_used = gpio_is_in_use(pi_ager_names.gpio_power_monitor) # ist Eingang
    # gpio_gpio_ups_bat_low_used = gpio_is_in_use(pi_ager_names.gpio_ups_bat_low) # ist Eingang
    gpio_notinuse_2_used = gpio_is_in_use(gpio_notinuse_2)
    gpio_notinuse_3_used = gpio_is_in_use(gpio_notinuse_3)
    gpio_notinuse_4_used = gpio_is_in_use(gpio_notinuse_4)
    gpio_notinuse_5_used = gpio_is_in_use(gpio_notinuse_5)

    if gpio_heater_used or gpio_cooling_compressor_used or gpio_circulating_air_used or gpio_humidifier_used or gpio_exhausting_air_used or gpio_light_used or gpio_uv_used or gpio_dehumidifier_used or gpio_alarm_used or gpio_switch_used or gpio_power_monitor_used or gpio_ups_bat_low_used or gpio_notinuse_2_used or gpio_notinuse_3_used or gpio_notinuse_4_used or gpio_notinuse_5_used:
        return True
    else:
        return False

def gpio_is_in_use(gpio):
    """
    check if GPIO is in use
    """
    gpio_used = gpio.gpio_function(gpio)
    
    if gpio_used == -1:
        return False
    else:
        return True
