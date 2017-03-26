# https://circuitdigest.com/microcontroller-projects/raspberry-pi-weight-sensing-automatic-gate
import RPi.GPIO as gpio
import time
scale1_data = 24
scale1_sync = 25
sample = 0
gpio.setwarnings(False)
gpio.setmode(gpio.BCM)
gpio.setup(scale1_sync, gpio.OUT)

def read_scale_weight():
  i=0
  count=0
 # print count
 # time.sleep(0.001)
  gpio.setup(scale1_data, gpio.OUT)
  gpio.output(scale1_data,1)
  gpio.output(scale1_sync,0)
  gpio.setup(scale1_data, gpio.IN)
  
  
  while gpio.input(scale1_data) == 1:
      i=0
  for i in range(24):
        gpio.output(scale1_sync,1)
        count=count<<1
        gpio.output(scale1_sync,0)
        #time.sleep(0.001)
        if gpio.input(scale1_data) == 0: 
            count=count + 1
            #print count
        
  gpio.output(scale1_sync,1)
  count=count^0x800000
  #time.sleep(0.001)
  gpio.output(scale1_sync,0)
  return count  

sample= read_scale_weight()
while 1:
  count= read_scale_weight()
  w=0
  w=(count - sample)/106
  print w
   
  time.sleep(0.5)