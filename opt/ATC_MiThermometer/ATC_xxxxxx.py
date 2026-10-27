#!/usr/bin/env python3
from __future__ import print_function
import argparse
import binascii
import os
import sys
from bluepy import btle
import schedule
import time
import signal
import sqlite3
import ATC_db_helper
import syslog
import traceback
from datetime import datetime

# catch signal.SIGTERM and signal.SIGINT when killing main to gracefully shutdown system
def signal_handler(signum, frame):
    do_cancel_job()
  
if os.getenv('C', '1') == '0':
    ANSI_RED = ''
    ANSI_GREEN = ''
    ANSI_YELLOW = ''
    ANSI_CYAN = ''
    ANSI_WHITE = ''
    ANSI_OFF = ''
else:
    ANSI_CSI = "\033["
    ANSI_RED = ANSI_CSI + '31m'
    ANSI_GREEN = ANSI_CSI + '32m'
    ANSI_YELLOW = ANSI_CSI + '33m'
    ANSI_CYAN = ANSI_CSI + '36m'
    ANSI_WHITE = ANSI_CSI + '37m'
    ANSI_OFF = ANSI_CSI + '0m'


def dump_services(dev):
    services = sorted(dev.services, key=lambda s: s.hndStart)
    for s in services:
        print ("\t%04x: %s" % (s.hndStart, s))
        if s.hndStart == s.hndEnd:
            continue
        chars = s.getCharacteristics()
        for i, c in enumerate(chars):
            props = c.propertiesToString()
            h = c.getHandle()
            if 'READ' in props:
                val = c.read()
                if c.uuid == btle.AssignedNumbers.device_name:
                    string = ANSI_CYAN + '\'' + \
                        val.decode('utf-8') + '\'' + ANSI_OFF
                elif c.uuid == btle.AssignedNumbers.device_information:
                    string = repr(val)
                else:
                    string = '<s' + binascii.b2a_hex(val).decode('utf-8') + '>'
            else:
                string = ''
            print ("\t%04x:    %-59s %-12s %s" % (h, c, props, string))

            while True:
                h += 1
                if h > s.hndEnd or (i < len(chars) - 1 and h >= chars[i + 1].getHandle() - 1):
                    break
                try:
                    val = dev.readCharacteristic(h)
                    print ("\t%04x:     <%s>" %
                           (h, binascii.b2a_hex(val).decode('utf-8')))
                except btle.BTLEException:
                    break


class ScanPrint(btle.DefaultDelegate):

    def __init__(self, opts):
        btle.DefaultDelegate.__init__(self)
        self.opts = opts
        self.device_name = ATC_db_helper.get_table_value_from_field('atc_device_name', 'name')
        if (self.device_name == None):
            self.device_name = '          '
        self.address = ("a4:c1:38:" + self.device_name[4:6] + ":" + self.device_name[6:8] + ":" + self.device_name[8:]).lower()
#        print(self.address)

    def handleDiscovery(self, dev, isNewDev, isNewData):
        if isNewDev:
            status = "new"
        elif isNewData:
            if self.opts.new:
                return
            status = "update"
        else:
            if not self.opts.all:
                return
            status = "old"

        if dev.rssi < self.opts.sensitivity:
            return

        # filter desired device, addresses must match
        if (dev.addr != self.address):
            return
            
#        print ('    Device (%s): %s (%s), %d dBm %s' %
#               (status,
#                   ANSI_WHITE + dev.addr + ANSI_OFF,
#                   dev.addrType,
#                   dev.rssi,
#                  ('' if dev.connectable else '(not connectable)'))
#               )
        device_name = '          '
        advertise_data = ''
        for (sdid, desc, val) in dev.getScanData():
            if sdid in [8, 9]:
#                print ('\t' + desc + ': \'' + ANSI_CYAN + val + ANSI_OFF + '\'')
                device_name = val
            else:
#                print ('\t' + desc + ': <' + val + '>')
                advertise_data = val
        
#        print("device name : " + device_name + ". data : " + advertise_data)
        temperature = int.from_bytes(bytearray.fromhex(advertise_data[16:20]),byteorder='little',signed=True) / 100.
        humidity = int.from_bytes(bytearray.fromhex(advertise_data[20:24]),byteorder='little',signed=False) / 100.
        batteryVoltage = int.from_bytes(bytearray.fromhex(advertise_data[24:28]),byteorder='little',signed=False) / 1000.
        batteryPercent =  int.from_bytes(bytearray.fromhex(advertise_data[28:30]),byteorder='little',signed=False)
        timestamp = ATC_db_helper.get_current_time()
        # formatted_time = datetime.fromtimestamp(timestamp)
        # syslog.syslog(syslog.LOG_INFO, f'Temperature: {temperature:.2f}. Humidity: {humidity:3.1f}. Battery voltage: {batteryVoltage:.2f}. Battery percent: {batteryPercent}. Timestamp: {formatted_time}')
        ATC_db_helper.write_atc_data( temperature, humidity, batteryVoltage, batteryPercent, timestamp)
        
#        if not dev.scanData:
#            print ('\t(no data)')
#        print

def job():
    parser = argparse.ArgumentParser()
    parser.add_argument('-i', '--hci', action='store', type=int, default=0,
                        help='Interface number for scan')
    parser.add_argument('-t', '--timeout', action='store', type=int, default=6,
                        help='Scan delay, 0 for continuous')
    parser.add_argument('-s', '--sensitivity', action='store', type=int, default=-128,
                        help='dBm value for filtering far devices')
    parser.add_argument('-d', '--discover', action='store_true',
                        help='Connect and discover service to scanned devices')
    parser.add_argument('-a', '--all', action='store_true',
                        help='Display duplicate adv responses, by default show new + updated')
    parser.add_argument('-n', '--new', action='store_true',
                        help='Display only new adv responses, by default show new + updated')
    parser.add_argument('-v', '--verbose', action='store_true',
                        help='Increase output verbosity')
    arg = parser.parse_args(sys.argv[1:])

    btle.Debugging = arg.verbose

    try:
        scanner = btle.Scanner(arg.hci).withDelegate(ScanPrint(arg))

#    print (ANSI_RED + "Scanning for devices..." + ANSI_OFF)
        devices = scanner.scan(arg.timeout, passive=True)
#        assert False, 'testing assertion'
        
    except Exception as exc:
        error = traceback.format_exc()
        syslog.syslog(syslog.LOG_ERR, error)
        
    if arg.discover:
        print (ANSI_RED + "Discovering services..." + ANSI_OFF)

        for d in devices:
            if not d.connectable or d.rssi < arg.sensitivity:

                continue

            print ("    Connecting to", ANSI_WHITE + d.addr + ANSI_OFF + ":")

            dev = btle.Peripheral(d)
            dump_services(dev)
            dev.disconnect()
            print

# if __name__ == "__main__":
#     main()

def do_cancel_job():
#    print("sigterm or sigint received")
    schedule.cancel_job(job)
    syslog.syslog(syslog.LOG_INFO, "ATC_xxxxxx.py stopped") 
    sys.exit(0)

syslog.syslog(syslog.LOG_INFO, "ATC_xxxxxx.py started")

# now enable signal handler
signal.signal(signal.SIGTERM, signal_handler)
signal.signal(signal.SIGINT, signal_handler)
ATC_db_helper.empty_table('atc_data')       # clear mi-data table

schedule.every(2).seconds.do(job)

while True:
    schedule.run_pending()
    time.sleep(1)
        

