import pi_ager_init
import pi_ager_paths
import pi_ager_debug
import rrdtool
import pi_ager_logging

#---------------------------------------------------------------------------------- Function zum Plotten der Grafiken
def plotting(plotting_value):
#---------------------------------------------------------------------------------------------------------------- Beschriftung fuer die Grafiken festlegen
    global rrd_dbname
    pi_ager_logging.logger_pi_ager_plotting.debug('in plotingfunction')
    # if pi_ager_debug.debugging == 'on':
        # print("DEBUG: in plotingfunction")
    if plotting_value == 'sensor_temperature':
        title = _('temperature')
        label = 'in C'
    elif plotting_value == 'sensor_humidity':
        title = _('humidity')
        label = 'in %'
    elif plotting_value == "stat_exhaust_air":
        title = _('exhaust air')
        label = _('on or off')
    elif plotting_value == "stat_circulate_air":
        title = _('circulatioon air')
        label = _('on or off')
    elif plotting_value == "stat_heater":
        title = _('heater')
        label = _('on or off')
    elif plotting_value == "stat_coolcompressor":
        title = _('cooling compressor')
        label = _('on or off')
    elif plotting_value == "status_humidifier":
        title = _('humidifier')
        label = _('on or off')
    elif plotting_value == "status_dehumidifier":
        title = _('dehumidifier')
        label = _('on or off')
    elif plotting_value == "status_light":
        title = _('light')
        label = _('on or off')
    elif plotting_value == "status_uv":
        title = _('uv-light')
        label = _('on or off')
    elif plotting_value == "scale1_data":
        title = _('scale1')
        label = _('gr')
    elif plotting_value == "scale2_data":
        title = _('scale2')
        label = _('gr')
#---------------------------------------------------------------------------------------------------------------- Aufteilung in drei Plots
    for plot in ['daily' , 'weekly', 'monthly', 'hourly']:
        pi_ager_logging.logger_pi_ager_plotting.debug('in for schleife daily, weekly, monthly, hourly')
        # if pi_ager_debug.debugging == 'on':
            # print ("DEBUG: in for schleife daily, weekly, monthly, hourly")
        if plot == 'weekly':
            period = 'w'
        elif plot == 'daily':
            period = 'd'
        elif plot == 'monthly':
            period = 'm'
        elif plot == 'hourly':
            period = 'h'
#---------------------------------------------------------------------------------------------------------------- Grafiken erzeugen
        ret = rrdtool.graph("%s%s_%s-%s.png" %(pi_ager_paths.get_path_graphs_website(),pi_ager_init.rrd_dbname,plotting_value,plot),
            "--start",
            "-1%s" % (period),
            "--title=%s (%s)" % (title, plot),
            "--vertical-label=%s" % (label),
            '--watermark=Grillsportverein',
            "-w 400",
            "--alt-autoscale",
            "--slope-mode",
            "DEF:%s=%s:%s:AVERAGE" % (plotting_value, pi_ager_init.rrd_filename, plotting_value),
            "DEF:%s=%s:sensor_temperature:AVERAGE" % (_('durch'), pi_ager_init.rrd_filename),
            "DEF:%s=%s:sensor_humidity:AVERAGE" % (_('durchhum'), pi_ager_init.rrd_filename),
            "GPRINT:%s:AVERAGE:%s\: %%3.2lf C" % (_('durch'), _('Temperatur')),
            "GPRINT:%s:AVERAGE:%s\: %%3.2lf" % (_('durchhum'), _('Luftfeuchtigkeit')), 
            "LINE1:%s#0000FF:%s_%s" % (plotting_value, pi_ager_init.rrd_dbname, plotting_value))

