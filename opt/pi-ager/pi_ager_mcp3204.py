#!/usr/bin/python3
#
#       MCP3204/MCP3208 Evaluate temperature depending
#       on different temperature sensors.
#       Using class mcp3204.py to get raw data and
#       convert it depending on specific temperature sensors.
#       Designed to work on Raspberry Pi for Pi-Ager project
#
#
import mcp3204
import math
import sys
import statistics
import time

class CONVERT_MCP:

    def __init__(self):
        self.messWiderstand = 47
        self.sensorName = ''

    def __enter__(self):
        return self

    def __exit__(self, exc_type, exc_value, exc_traceback):
        pass

    def __del__( self ):
        pass

    def getValue(self, sensorParameters, adcChannel):
        value = None
        unit = None
            
        # get sensor name
        self.sensorName = sensorParameters['name']
        
        # Read sensortype parameter
        if (self.sensorName[:3] == '---' ):
            return value, unit
            
        if (adcChannel < 0 or adcChannel > 3):
            return value, unit

        # check if sensor is attached, only valid for temperature sensors
        unit = 'T'
        if self.sensorName[:3] != 'LEM':
            # get raw data from mcp
            with mcp3204.MCP3204(spi_device=1, spi_channel=0) as mcp:
                rawVal = mcp.readAverage(adcChannel)

            if (rawVal < 15) or (rawVal > 4080):
                return value, unit
        
            rawVal = 4095 - rawVal
            Rtheta = self.messWiderstand*((4096.0/rawVal) - 1)
            a = sensorParameters['a']
            b = sensorParameters['b']
            c = sensorParameters['c']
            Rn = sensorParameters['Rn']
            try: 
                v = math.log(Rtheta/Rn)
                value = (1/(a + b*v + c*v*v)) - 273
                value = round(value, 2)
            except: #bei unsinnigen Werten (z.B. ein- ausstecken des Sensors im Betrieb) Wert 999.9
                value = None
                pass
        else:
            # LEM current transducers, perform rectifier in software
            unit = 'A'
            mode = sensorParameters['Mode']
            if (mode == 'AC'):
            # AC measurement
                nAverage = sensorParameters['nAverage']  
                RefVoltage = sensorParameters['RefVoltage'] 
                Sensitivity = sensorParameters['Sensitivity']
                Turns = sensorParameters['Turns']
            
            # get raw data from mcp, scale with RefVoltage, Sensitivity and Turns
                vValues = []
                with mcp3204.MCP3204(spi_device=1, spi_channel=0) as mcp:
                    for i in range(nAverage):
                        rawVal = mcp.read(adcChannel)
                        if (rawVal < 15) or (rawVal > 4080):
                           return value, unit
                        vVal = rawVal * 3.3/4095.0
                        vVal = vVal - RefVoltage
                        vVal = vVal / Sensitivity / Turns * 1000.0
                        vValues.append( vVal )

            # compute RMS
                ms = 0.0
                for i in range(nAverage):
                    ms += (vValues[i] * vValues[i])
                ms /= nAverage
                value = round(math.sqrt(ms), 2)
#            avg = statistics.mean(vValues)
#            value = round(avg, 2)
            else:
            # DC Measurement
                nAverage = sensorParameters['nAverage']  
                RefVoltage = sensorParameters['RefVoltage'] 
                Sensitivity = sensorParameters['Sensitivity']
                Turns = sensorParameters['Turns']
            # get raw data from mcp, scale with RefVoltage, Sensitivity and Turns
                with mcp3204.MCP3204(spi_device=1, spi_channel=0) as mcp:
                    rawVal = mcp.readAverage(adcChannel,nAverage)
                    if (rawVal < 15) or (rawVal > 4080):
                       return value, unit
                    vVal = rawVal * 3.3/4095.0
                    
                vVal = vVal - RefVoltage
                value = round(vVal / Sensitivity / Turns * 1000.0 , 2)
                
        return value, unit

if __name__ == '__main__':


        config_iGrill2 = {'name' : 'iGrill2' , 'a' : 3.3562424e-03, 'b' : 2.5319218e-04, 'c' : 2.7988397e-06, 'Rn' : 99.61}
        config_LEM_HO6P_AC = {'name' : 'LEM-HO6P-AC' , 'Mode' : "AC", 'RefVoltage' : 1.6672, 'Sensitivity' : 76.67, 'Turns' : 2, 'nAverage' : 100}
        config_LEM_HO6P_DC = {'name' : 'LEM-HO6P-DC' , 'Mode' : "DC", 'RefVoltage' : 1.6672, 'Sensitivity' : 76.67, 'Turns' : 2, 'nAverage' : 50}
      
        with CONVERT_MCP() as convertMCP:
        
            print('Cancel output with CTRL C')
            try:
                while True:
                    adcChannel = 0
                    value, unit = convertMCP.getValue(config_iGrill2, adcChannel)
                    if (value == None):
                        print('sensor not attached')
                    else:
                        if unit == 'T': 
                            print('sensor {} at channel {} = {} °C or {} °F'.format( convertMCP.sensorName, adcChannel, value, round(value * 1.8 + 32, 2)))
                        else:
                            print('sensor {} at channel {} = {} A'.format( convertMCP.sensorName, adcChannel, value ))

                    adcChannel = 3
                    value, unit = convertMCP.getValue(config_LEM_HO6P_AC, adcChannel)
                    if (value == None):
                        print('sensor not attached')
                    else:
                        if unit == 'T': 
                            print('sensor {} at channel {} = {} °C or {} °F'.format( convertMCP.sensorName, adcChannel, value, round(value * 1.8 + 32, 2)))
                        else:
                            print('sensor {} at channel {} = {} A'.format( convertMCP.sensorName, adcChannel, value ))

                    adcChannel = 3
                    value, unit = convertMCP.getValue(config_LEM_HO6P_DC, adcChannel)
                    if (value == None):
                        print('sensor not attached')
                    else:
                        if unit == 'T': 
                            print('sensor {} at channel {} = {} °C or {} °F'.format( convertMCP.sensorName, adcChannel, value, round(value * 1.8 + 32, 2)))
                        else:
                            print('sensor {} at channel {} = {} A'.format( convertMCP.sensorName, adcChannel, value ))


                    time.sleep(0.2)
            except KeyboardInterrupt:
                print("interrupted") 

        
        
            
        
        