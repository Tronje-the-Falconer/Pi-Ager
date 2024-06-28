#!/bin/bash
# restart NetworkManager when wlan0 disappeared

if [ "$(nmcli -g GENERAL.STATE dev show wlan0)" = "30 (disconnected)" ]; then
	echo "wlan0 disconnected" | systemd-cat
	systemctl restart NetworkManager
fi

