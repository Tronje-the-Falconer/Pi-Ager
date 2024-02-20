#!/bin/bash
# call: sudo updatessid 'ssid' 'password'
	#check for blank in return
#	echo "$1"
#	echo ""
	if [ "$1" = "Cancel" ] || [ "$1" = "" ] ; then
#		menu
		exit
	fi

	IFS="," wpassid=$(awk '/ssid="/{ print $0 }' /etc/wpa_supplicant/wpa_supplicant.conf | awk -F'ssid=' '{ print $2 }' | sed 's/\r//g'| awk 'BEGIN{ORS=","} {print}' | sed 's/\"/''/g' | sed 's/,$//')
	ssids=($wpassid)
	if [[ ! " ${ssids[@]} " =~ " $1 " ]]; then
#		echo "Add New Wifi Network"
#		echo "Selection SSID: $1"
#		echo ""
#		echo "Enter password for Wifi"
#		read ssidpw
        echo -e "\nnetwork={\n\tssid=\x22$1\x22\n\tpsk=\x22$2\x22\n\tkey_mgmt=WPA-PSK\n}" >> /etc/wpa_supplicant/wpa_supplicant.conf
#		echo -e "\nnetwork={\n\tssid=\x22$1\x22\n\tpsk=\x22$2\x22\n\tkey_mgmt=WPA-PSK\n}"
	else
		f=0
#		echo "Change Password for Selected Wifi"
		while IFS= read -r ln || [[ -n "$ln" ]] <&3; do
			if [[ "$ln" == *"psk="* ]] && [ $f -eq 1 ] ;then
				break
			elif [[ "$ln" == *"$1"* ]] ; then
				f=1
			fi
		done < /etc/wpa_supplicant/wpa_supplicant.conf
#		echo "Change Wifi Network Password"
#		echo "Selected SSID: $1"
#       echo ""
#		echo "Enter password for Wifi"
#		read chgpw
		newpsk=$'\tpsk=\x22'$2$'\x22\n'
#       echo "The entry will be" $newpsk
#		echo "To be Replaced $ln"
		sed -i '/'"$ln"'/c\'"$newpsk" /etc/wpa_supplicant/wpa_supplicant.conf
		f=0
	fi