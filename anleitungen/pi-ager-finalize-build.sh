#!/bin/bash
 
 echo "enable and activate setup.txt after reboot"
 systemctl enable setup_pi-ager.service
 echo "rebooting" 
 sync
 reboot