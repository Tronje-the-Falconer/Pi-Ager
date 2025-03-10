#!/bin/bash

 echo "Enable oi-ager service in rc.local after next reboot"
 sed -i 's/#systemctl/systemctl/' /etc/rc.local
 
 echo "enable and activate setup.txt after reboot"
 systemctl enable setup_pi-ager.service
 echo "rebooting" 
 sync
 reboot