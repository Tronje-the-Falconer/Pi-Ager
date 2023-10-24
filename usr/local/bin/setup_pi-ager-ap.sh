#!/bin/bash
# File: setup_pi-ager-ap.sh
# START NetworkManger commands to setup AccessPoint on wirtual wlan1
# wlan1 must be configured by : iw dev wlan0 interface add wlan1 type __ap
# This command must be put at the end of /etc/rc.local

set -x
trap read debug
 # configure access point with networkmanager
 nmcli con add type wifi ifname wlan1 mode ap con-name PI_AGER_AP ssid pi-ager
 nmcli con modify PI_AGER_AP 802-11-wireless.band bg
 nmcli con modify PI_AGER_AP 802-11-wireless.channel 1
 nmcli con modify PI_AGER_AP 802-11-wireless-security.key-mgmt wpa-psk
 nmcli con modify PI_AGER_AP 802-11-wireless-security.proto rsn
 nmcli con modify PI_AGER_AP 802-11-wireless-security.group ccmp
 nmcli con modify PI_AGER_AP 802-11-wireless-security.pairwise ccmp
 nmcli con modify PI_AGER_AP 802-11-wireless-security.psk 1234567890
 nmcli con modify PI_AGER_AP ipv4.addr 10.0.0.1/24
 nmcli con modify PI_AGER_AP ipv4.method shared
 nmcli con up PI_AGER_AP