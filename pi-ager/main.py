#!/usr/bin/python3
# -*- coding: iso-8859-1 -*-
# Importieren der Module
import os
import subprocess
import time
import rrdtool
import pi_ager_logging
pi_ager_logging.create_logger('main.py')
import pi_ager_debug
import pi_ager_loop
import pi_ager_init
import pi_ager_organization
import pi_ager_plotting

os.system('clear') # Bildschirm loeschen
pi_ager_logging.logger_main.info(pi_ager_init.logspacer)

# RRD-Datenbank anlegen, wenn nicht vorhanden
try:
    pi_ager_logging.logger_main.debug('begin try')

    with open(pi_ager_init.rrd_filename): pass
    logstring = _("database file found") + ": " + pi_ager_init.rrd_filename
    pi_ager_logging.logger_main.debug(logstring)
    os.system('sudo /var/sudowebscript.sh pkillscale &')
    pi_ager_logging.logger_main.debug('pkillscale done')
    time.sleep(2)
    os.system('sudo /var/sudowebscript.sh startscale &')
    pi_ager_logging.logger_main.debug('startscale done')

except IOError:
    logstring = _("creating a new database") + ": " + pi_ager_init.rrd_filename
    pi_ager_logging.logger_main.debug(logstring)
    ret = rrdtool.create("%s" %(pi_ager_init.rrd_filename),
        "--step","%s" %(pi_ager_init.measurement_time_interval),
        "--start",'0',
        "DS:sensor_temperature:GAUGE:2000:U:U",
        "DS:sensor_humidity:GAUGE:2000:U:U",
        "DS:stat_exhaust_air:GAUGE:2000:U:U",
        "DS:stat_circulate_air:GAUGE:2000:U:U",
        "DS:stat_heater:GAUGE:2000:U:U",
        "DS:stat_coolcompressor:GAUGE:2000:U:U",
        "DS:status_humidifier:GAUGE:2000:U:U",
        "DS:status_dehumidifier:GAUGE:2000:U:U",
        "DS:status_light:GAUGE:2000:U:U",
        "DS:status_uv:GAUGE:2000:U:U",
        "DS:scale1_data:GAUGE:2000:U:U",
        "DS:scale2_data:GAUGE:2000:U:U",
        "RRA:AVERAGE:0.5:1:2160",
        "RRA:AVERAGE:0.5:5:2016",
        "RRA:AVERAGE:0.5:15:2880",
        "RRA:AVERAGE:0.5:60:8760",)

pi_ager_logging.logger_main.info(pi_ager_init.logspacer)
pi_ager_init.set_sensortype()
pi_ager_init.set_system_starttime()

try:
    pi_ager_loop.autostart_loop()
except KeyboardInterrupt:
    pi_ager_logging.logger_main.critical('KeyboardInterrupt')
    pass

except Exception as e:
    logstring = _('exception occurred') + '!!!'
    pi_ager_logging.logger_main.critical(logstring)
    pi_ager_logging.logger_main.critical(str(e))
    pass
pi_ager_organization.goodbye()