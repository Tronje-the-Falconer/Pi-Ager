import RPi.GPIO as gpio
import pi_ager_organization
import pi_ager_init
#---------------------------------------------------------------------------------- Function Setup GPIO
def setupGPIO():
    # global board_mode
    # global gpio_heater
    # global gpio_cooling_compressor
    # global gpio_circulating_air
    # global gpio_humidifier
    # global gpio_exhausting_air
    # global gpio_light
    # global gpio_uv
    # global gpio_scale_data
    # global gpio_scale_sync
    # global gpio_sensor_data
    # global gpio_sensor_sync
    # global gpio_dehumidifier
    
    logstring = _('setting up GPIO') + '...'
    pi_ager_organization.write_verbose(logstring, False, False)
    gpio.setwarnings(False)
#---------------------------------------------------------------------------------------------------------------- Board mode wird gesetzt
    gpio.setmode(pi_ager_init.board_mode)
#---------------------------------------------------------------------------------------------------------------- Einstellen der GPIO PINS
#------------------------------------------------------------------------------------------------------------------------------------------ Sensoren etc
    gpio.setup(pi_ager_init.gpio_scale_data, gpio.IN)           # Kabel Data ()
    gpio.setup(pi_ager_init.gpio_scale_sync, gpio.OUT)           # Kabel Sync ()
#------------------------------------------------------------------------------------------------------------------------------------------ Relaisboard
    gpio.setup(pi_ager_init.gpio_heater, gpio.OUT)                # Heizung setzen (config.json)
    #gpio.output(gpio_heater, relay_off)              # Heizung Relais standartmaessig aus
    gpio.setup(pi_ager_init.gpio_cooling_compressor, gpio.OUT)    # Kuehlung setzen (config.json)
    #gpio.output(gpio_cooling_compressor, relay_off)  # Kuehlung Relais standartmaessig aus
    gpio.setup(pi_ager_init.gpio_circulating_air, gpio.OUT)       # Umluft setzen (config.json)
    #gpio.output(gpio_circulating_air, relay_off)     # Umluft Relais standartmaessig aus
    gpio.setup(pi_ager_init.gpio_humidifier, gpio.OUT)            # Befeuchter setzen (config.json)
    #gpio.output(gpio_humidifier, relay_off)          # Befeuchter Relais standartmaessig aus
    gpio.setup(pi_ager_init.gpio_exhausting_air, gpio.OUT)        # Abluft setzen (config.json)
    #gpio.output(gpio_exhausting_air, relay_off)      # Abluft Relais standartmaessig aus
    gpio.setup(pi_ager_init.gpio_light, gpio.OUT)                  # Licht setzen (json.conf)
    #gpio.output(gpio_light, relay_off)               # Licht Relais standartmaessig aus
    gpio.setup(pi_ager_init.gpio_uv, gpio.OUT)               # UV-Licht setzen (json.conf)
    #gpio.output(gpio_uv, relay_off)            # UV-Licht Relais standartmaessig aus
    gpio.setup(pi_ager_init.gpio_dehumidifier, gpio.OUT)              # Reserve setzen (json.conf)
    #gpio.output(gpio_dehumidifier, relay_off)           # Reserve Relais standartmaessig aus
    logstring = _('GPIO setup complete') + '.'
    pi_ager_organization.write_verbose(logstring, False, False)
    
def defaultGPIO():
    gpio.output(pi_ager_init.gpio_heater, pi_ager_init.relay_off)              # Heizung Relais standartmaessig aus
    gpio.output(pi_ager_init.gpio_cooling_compressor, pi_ager_init.relay_off)  # Kuehlung Relais standartmaessig aus
    gpio.output(pi_ager_init.gpio_circulating_air, pi_ager_init.relay_off)     # Umluft Relais standartmaessig aus
    gpio.output(pi_ager_init.gpio_humidifier, pi_ager_init.relay_off)          # Befeuchter Relais standartmaessig aus
    gpio.output(pi_ager_init.gpio_exhausting_air, pi_ager_init.relay_off)      # Abluft Relais standartmaessig aus
    gpio.output(pi_ager_init.gpio_light, pi_ager_init.relay_off)               # Licht Relais standartmaessig aus
    gpio.output(pi_ager_init.gpio_uv, pi_ager_init.relay_off)            # UV-Licht Relais standartmaessig aus
    gpio.output(pi_ager_init.gpio_dehumidifier, pi_ager_init.relay_off)          # Reserve Relais standartmaessig aus
    logstring = _('default GPIO setup complete') + '.'
    pi_ager_organization.write_verbose(logstring, True, False)
