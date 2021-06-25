#!/usr/bin/python3
#
#       MCP3204/MCP3208 sample program for Raspberry Pi
#
#       how to setup /dev/spidev?.?
#               in /boot/config.txt : 
#                  dtoverlay=spi1-1cs,cs0_pin=16
#
#       how to setup spidev
#               $ sudo apt-get install python3-dev python3-pip
#               $ sudo pip3 install spidev
#
import spidev
import time
import sys
import logging

class MCP3204:
        def __init__(self, spi_device=1, spi_channel=0):
            self.conn = None
            self.spi_device= spi_device
            self.spi_channel = spi_channel
            self.conn = spidev.SpiDev(spi_device, spi_channel)
            self.conn.max_speed_hz = 100000 # 0,1MHz

        def __enter__(self):
             return self

        def __exit__(self, exc_type, exc_value, exc_traceback):
            self.close()

        def __del__( self ):
            self.close()

        def close(self):
            if self.conn != None:
                self.conn.close()
                self.conn = None

        def bitstring(self, n):
            s = bin(n)[2:]
            return '0'*(8-len(s)) + s

        def read(self, adc_channel=0):
# build command
#            cmd  = 128 # start bit
#            cmd +=  64 # single end / diff
#            if (adc_channel % 2) == 1:
#               cmd += 8
#            if ((adc_channel/2) % 2) == 1:
#                cmd += 16
#            if ((adc_channel/4) % 2) == 1:
#                cmd += 32

            # send & receive data
#            reply_bytes = self.conn.xfer2([cmd, 0, 0, 0])

            #
#            reply_bitstring = ''.join(self.bitstring(n) for n in reply_bytes)
            # print(reply_bitstring)

            # see also... http://akizukidenshi.com/download/MCP3204.pdf (page.20)
#            reply = reply_bitstring[7:19]
#            return int(reply, 2)

            cmd1 = 4 | 2 | ((adc_channel & 4) >> 2)
            cmd2 = (adc_channel & 3) << 6
            reply_bytes = self.conn.xfer2([cmd1,cmd2, 0])
            reply = ((reply_bytes[1] & 15) << 8) + reply_bytes[2]
            return reply
            
        def readAverage(self, adc_channel=0, nAverage=10, bDebug=0):
            raw = 0
            for i in range(nAverage):
                raw += self.read(adc_channel)
            fMean = raw / nAverage

            if bDebug:
                print('nAverage={:d}: mean value={:7.2f}ticks'.format(nAverage, fMean))
            return fMean

if __name__ == '__main__':
        logger = logging.getLogger('mcplogger')
        logger.setLevel(logging.DEBUG)
        try:
            with MCP3204(spi_device=1, spi_channel=0) as mcp:
          
                try:

                    while True:
                        a0 = mcp.readAverage(0, nAverage=20)
                        a1 = mcp.readAverage(1)
                        a2 = mcp.readAverage(2)
                        a3 = mcp.readAverage(3)

                        print('rawValues: {:7.2f} {:7.2f} {:7.2f} {:7.2f}'.format(a0, a1, a2, a3))
                        time.sleep(0.5)

                except KeyboardInterrupt:
                    print("interrupted")

        except Exception as e:
            logger.exception(e)
            pass