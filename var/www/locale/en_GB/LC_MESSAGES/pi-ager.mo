��             +         �  P   �          )     9     R     c     �     �     �     �  %   �     �          !     9     O     c     v     �     �     �     �  "   �                (  !   D     f     �     �     �  |  �  l   K  �  �  N  K  �  �  �  =  �  �  �  �  �  V!  �  �"  �  �&  �  y)  �  ^-  O  R4  �  �6  "  >8  g  a;  �  �<  P  [@     �D  '  �H    �L    R  _  %^  L  �_  �
  �c  �  �n    ww  $  {y  G  �z  a  �  �  J�                                 	                                                                                          
                     Enter MiThermometer device name, e.g. ATC_C4C134, can be found with blescan tool helptext_alarm helptext_backup helptext_circulation_air helptext_defrost helptext_dehumidifier_config helptext_display helptext_email helptext_event helptext_exhausting_air helptext_humidifier_monitoring_config helptext_humidify_config helptext_humidity_setpoint helptext_language_admin helptext_light_config helptext_mailserver helptext_messenger helptext_mqtt helptext_operation_mode helptext_pushover helptext_scale_admin helptext_sensortype_admin helptext_shutdown_on_batlow_config helptext_switch_admin helptext_telegram helptext_temperature_config helptext_temperature_event_config helptext_temperature_setpoint helptext_thermometer_admin helptext_uv_config humidifier monitoring delay Project-Id-Version: Pi-Ager-GB
PO-Revision-Date: 2023-12-12 16:49+0100
Last-Translator: 
Language-Team: 
Language: en_GB
MIME-Version: 1.0
Content-Type: text/plain; charset=UTF-8
Content-Transfer-Encoding: 8bit
Plural-Forms: nplurals=2; plural=(n != 1);
X-Generator: Poedit 3.4.1
X-Poedit-Basepath: ../../../../..
X-Poedit-SearchPath-0: opt/pi-ager
X-Poedit-SearchPath-1: var/www
 Enter MiThermometer device name, e.g. ATC_C4C134. The device name can be found with a Bluetooth scanner tool <br><br>
<b>alarm function:</b>
<br><br>
<br>Here you can define different alarm types for the acoustic alarm. 
<br>The following parameters can be set:
<br><br>
<b>alarm: </b> Here you can enter the name of the alarm configuration.
<br> This name then appears in the respective selection fields in the Messenger or Event areas.
<br><br>
<b>replication: </b> Here you can enter how often an alarm sequence should be repeated.
<br><br>
<b>sleep:</b> Here you can enter the pause time between the repetitions.
<br><br>
<b>high time:</b> Here you can enter the time how long the beeper should be activated during an alarm sequence.
<br><br>
<b>low time:</b> Here you can enter the time for which the beeper should be switched off during an alarm sequence.
<br><br>
<br>If you want to delete an alarm configuration from the list, you can remove it completely from the database by selecting the appropriate ID.
<br><br> <br><br>
<b>backup - script over NFS:</b> 
<br><br>
<br>The system has a function with which a complete image of the Pi-Ager can be stored on a NAS or a NFS mount. 
<br><br>
<b>caution. </b> The larger the SD card, the longer the backup process will take. This is also because the NFS mount is connected via WLAN. With a LAN connection it probably goes faster.
<br><br>
<b>general information:</b> 
<br>About the script pi-ager-main.sh the Pi-Ager is stopped. During this time no Pi-Ager function is active, this means that the relays are all down for this time.
<br>When the backup process is finished, the Pi-Ager starts automatically and runs with the last settings.  
<br><br>
<b>nfs directory: </b> Here you enter the server and NFS share.
<br>z.B.: 192.168.0.111:/srv/backup
<br><br>
<b>subdirectory: </b>Here you can specify a possible subdirectory.
<br>e.g.: pi-ager
<br><br>
<b>nfs mount: </b> This is where the NFS share is mounted on the Raspberry Pi.
<br>e.g.: /home/pi/backup
<br><br>
<b>nfs path: </b> Is composed of the file path on the Pi (nfsmount) and the NFS subdirectory. 
<br>e.g.: /home/pi/backup/pi-ager
<br><br>
<b>Number of backups: </b>Number of last n backups kept.
<br>e.g. 2
<br><br>
<b>backup name: </b> Name of the backup file.
<br>e.g.: pi_ager_backup
<br><br>
<b>nfs option: </b> Options for mounting can be specified here.
<br>Here you can find possible options:
<br>http://manpages.ubuntu.com/manpages/impish/en/man8/mount.8.html
<br><br>
<b>backup active: </b>If a backup is to be performed, this checkbox must be selected. By deselecting it, the backup can be disabled
<br><br> <br><br>
<b>OFF duration: </b> This is used to set the pause time, which waits until the circulation air is switched on again. If the value is 0 (= no pause), the circulation air is permanently switched on. The maximum value is 1440min.
<br><br>
<b>ON duration: </b> This sets the circulation air time during which the fan is running. At 0, the circulation air is permanently switched off. The maximum value is 1440min.
<br><br>
<b>note: </b> The circulation air fan runs independently of the timer settings - also during cooling, heating and humidification.
<br><br>
<b>attention: </b> OFF duration=0 and ON duration=0: circulation air is permanently switched off.
<br><br> <br><br>
<b>Defrost</b>
<br><br>
<br>The defrost mechanism turns on the heater element and melts the frost that emerged mainly on the cooling element.
<br>The resulting water can drain through a duct at the back of the unit.
<br> Defrosting is controlled by applying some parameters:
<br><br>
<b>Defrost temperature offset:</b>The setpoint temperature for defrosting is calculated by the sum of defrost temperature offset and current setpoint temperature. The heater is turned on as long as the temperature in the cabinet has not reached this temperature.
<br><br>
<b>Defrost cycle:</b>For every n hours of Pi-Ager operation the defrost processing is turned on.
<br><br>
<b>Circulating air active:</b>Circulating air is active during defrost processing.
<br><br>
<b>Defrost active:</b>Defrost processing is enabled or disabled.
<br><br>
<br>The defrosting mechanism is only put into operation if the target temperature is less than 8°C. This limit can currently only be modified via the database administration. The name of the table is 'config_defrost' and the limit is defined in the 'temp_limit' column.
<br>During defrost processing the cooler is disabled, the heater is enabled.
<br><br> <br><br>
<b>only exhaust: </b> Because passive dehumidification by exhaust air takes place in this mode, the effectiveness of the dehumidification is very dependent on the ambient air humidity.
<br><br>
<b>exhaust & dehumidifier: </b> In this mode, dehumidification via exhaust air is active together with a separate dehumidifier.
<br><br>
<b>only dehumidifier:</b> In this mode, the dehumidification of the air in the Pi-ager is performed only by a dehumidifier.
<br><br>
<b>abs. humidity check active:</b> This option is only available if an external sensor is defined. If active and operating mode = 4 (automatic with dehumidification and humidification) then the external absolute humidity is checked against the internal absolute humidity. If the external absolute humidity is greater than the internal absolute humidity then the exhaust air fan is deactivated to avoid a more humid air suctioning from outside.
<br><br>
<b>recommendation:</b> The delay time in humidity control is not active on the dehumidifier, it only affects the humidifier..
<br><br>
<b>attention: </b> Since there are very few dehumidifiers on the market that are effective at the low temperatures, you should carefully consider which device you want to use for what.
<br><br> <br><br>
<b>Nextion Display Firmware:</b>
<br><br>
<br>Here you can program the display with the, in the software already stored, firmware.
<br>In this case, you do not need to upload another file to the system ! 
<br><br>
<br>Should someone want to modify the firmware of the display and adapt it to their own needs,
<br>can upload this alternative firmware file here and program the display via the top button. 
<br><br>
<b>Caution! </b>Whoever creates a new firmware, must know very well what he does, because changes can change IDs and these then no longer match the firmware on the Python side. This firmware would then no longer work, adjustments would then be necessary on the Python side. 
<br>Important: The .tft file must be named "pi-ager.tft" so that the upload works. The pi-ager.tft file from the Nextion Design Tool must be compressed with ZIP and then uploaded as pi-ager.zip.  
<br><br> <br><br>
<b>email: </b
<br><br>
<br>Here you can enter email addresses to which notifications should be sent.
<br><br>
<br>With the help of the checkbox "Active" one can activate or deactivate the sending to the corresponding e-mail address.
<br><br>
<br>If you want to delete an e-mail address from the list, you can remove it completely from the database by selecting the corresponding ID.
<br><br> <br><br>
<b>event</b>
<br><br>
<br>The event manager controls the notification to the different services like email, alarm, pushover and telegram. 
<br>Here you can assign to the events where to pass the message and whether to activate audible alarm. 
<br><br>
<b>event: </b>Here the event detected by the system is entered. This column is mostly already predefined.
<br><br>
<b>checkboxes: </b>Here you can activate the appropriate notification service through which the message should be sent.
<br><br>
<b>alarm: </b> Here you can select the type of acoustic alarm ( beeper) on the board. The alarm types can be defined further down in the "Alarm" area accordingly.
<br><br>
<b>event text: </b>Here you can assign each event a text that is then sent as a message via the appropriate service.
<br><br>
<b>active: </b> Here you can select whether this event should be considered at all. If this checkbox is not selected, no notification or logging will occur.
<br><br> <br><br>
<b>OFF duration: </b> This is used to set the pause time, which waits until the exhausting air is switched on again. If the value is 0 (= no pause), the exhausting air is permanently switched on. The maximum value is 1440min.
<br><br>
<b>ON duration: </b> This sets the exhausting air time during which the fan is running. At 0, the exhausting air is permanently switched off. the maximum value is 1440min.
<br><br>
<b>note: </b> The exhausting air fan runs independently of the timer settings - also during dehumidification in the automatic mode with humidification and dehumidification.
<br><br>
<b>attention: </b> OFF duration=0 and ON duration=0: exhausting air is permanently switched off.
<br><br> <br><br>
<img src="./images/icons/humidifier_monitor.png" alt="">
<br><br>
<b>Monitoring delay: </b>The correct function of the humidifier is monitored. The humidifier is part of the humidity control in dry aging mode 1...4. In these operating modes, the difference between the humidity setpoint and average value is continuously monitored. If the measured value is constantly smaller than (setpoint - monitoring tolerance) during the set delay time, an alarm/event (humidifier failure) is issued. The timer for the delay time is reset each time the dry-aging mode changes and when the delay time changes. The timer is also reset as soon as the average value has reached the tolerance range below the setpoint.
<br><br>
<b>Monitoring tolerance: </b> (setpoint - monitoring tolerance) determine a limit value that is constantly compared with the average humidity value during the set delay time.
<br><br>
<b>Activate monitoring: </b> Humidifier monitoring can be activated or deactivated.
<br><br> <br><br>
<b>Switching hysteresis:</b>
<br><br>
<img src="./images/icons/humidity-control.jpg" alt="">
<br>
<b>setpoint: </b> Setpoint of the humidity control
<br><br>
<b>hysteresis humidification: </b> Defines the switching hysteresis for humidification control. Recommended value: 3%
<br><br>
<b>hysteresis dehumidification: </b> Defines the switching hysteresis for controlling dehumidification. Recommended value: 5%
<br><br>
<b>hysteresis offset: </b> Defines the offset of the switching hysteresis from the control setpoint. This allows the control deviation of the humidity setpoint to the mean value to be minimized. Recommended value: -2.5%
<br><br>
<b>Delay: </b>The delay time is set here, which waits until the humidifier switches on when the humidity is too low. This can be used to avoid switching on the humidifier too early if the humidity drops quickly (caused by cooling), otherwise too much water can be introduced into the system.
<br><br>
<b>Example humidification: </b><i> Setpoint humidity: 75% ; Hysteresis humidification: 3% ; Hysteresis offset: -2.5%</i>
<br>Switching on the humidifier phi-low = setpoint - hysteresis/2 + offset = 71%
<br>Switching off the humidifier phi-high = setpoint + hysteresis/2 + offset = 79%
<br><br>
<b>Example of automatic operation with humidification and dehumidification: </b> In automatic operation, the humidity is regulated fully automatically. First, the current humidity is determined, then a decision is made as to which method (humidification and dehumidification) is suitable to achieve the desired target humidity. This also means that the switching values ​of the hysteresis must not be too close together, otherwise humidification and dehumidification could always be switched on and off alternately.
<br><br> <br><br>
<b>target humidity: </b> the desired humidity is set here.
<br>The minimum value is theoretically 0% and a maximum of 99%. these values will be never reached normally. The circulating air is always active during humidification. 
<br>The effectiveness of the dehumidification (automatic mode with with humidification and dehumidification) is dependent on the ambient air humidity, since only a passive dehumidification by exhaust air takes place.
<br><br>
<b>recommendation: </b> check the stored values in the logfile!
<br><br>
<b>attention! </b> se only positive integers!
<br><br> <br><br>
<b>Language: </b> Here you can set the desired languages for the system.
<br><br>
<br><b>Attention! </b> For the language settings to work, the appropriate language pack must be installed on the Rasperry Pi with sudo raspi-config using a terminal program, e.g. PuTTY :
<br> Select 'Localisation Options'
<br> Select 'Change Locale'
<br> Select 'en_GB.UTF-8 UTF8'
<br> Select 'de_DE.UTF-8 UTF8'
<br><br> <br><br>
<b>OFF duration: </b> This is used to set the pause time, which waits until the light is switched on again. If the value is 0 (= no pause), the light is permanently switched on. The maximum value is 1440min.
<br><br>
<b>ON duration: </b> This sets light time during which the light is switched on. At 0, the light is permanently switched off. The maximum value is 1440min.
<br><br>
<b>timestamp: </b> As an alternative to the cycle, a fixed time can be set here at which the light is switched on for the set ON duration.
<br><br>
<b>note: </b> You can manually switch on the light permanently via a switch on the webcam page. In this case, the timer can no longer turn off the light.
<br><br>
<b>attention: </b> ON duration=0 and OFF duration=0: the light is permanently switched off.
<br><br> <br><br>
<b>mail server:</b>
<br><br>
<b>server: </b> Here you enter the corresponding SMTP server from your provider, e.g. securesmtp.t-online.de
<br><br>
<b>user: </b> Here you enter the corresponding user name for the SMTP server.
<br><br>
<b>password:</b> Password for the user.
<br><br>
<b>port:</b> Here you select the port for the SMTP server.
<br><br> <br><br>
<b>System error</b>
<br><br>
<br>The exception manager / messenger controls the notification to the individual services such as email, alarm, pushover and Telegram. 
<br>Here individual errors or exceptions can be assigned, where the message is passed. 
<br>If a system error occurs, the Pi-Ager program will be shut down after the notifications are complete. Details about the system error that occurred also appear in the log.
<br><br>
<b>!!! Important !!!: </b>If changes are made in this area, the system must be restarted at present (reboot) so that these changes become active.
<br><br>
<b>Checkboxes: </b>Here you can activate the appropriate notification service through which the message should be sent.
<br><br>
<b>Alarm: </b> Here you can select the type of acoustic alarm ( signal generator on the board). One can define the alarm types further down in the range "alarm" accordingly.
<br><br> <br><br>
<b>MQTT</b>
<br><br>
<b>Broker address: </b>IP address of the broker in the local network or address of a public MQTT broker
<br><br>
<b>Port: </b> Port number for communication between MQTT broker and client. Default is 1883
<br><br>
<b>User name: </b> User name for logging in to the broker. Only if authentication is required, otherwise leave blank.
<br><br>
<b>Password: </b> Only if authentication with the broker is required, otherwise leave blank.
<br><br>
<b>MQTT active: </b> Activate to start MQTT on the Pi-Ager.
<br><br>
<b>The following data is published by Pi-Ager:</b>
<br>MQTT Topics are structured as follows: '{hostname}/status/{topic}' :
<br>
<b> hostname: </b> hostname as defined in the setup.txt file, default is 'pi-ager'.
<br>
<b> topic: </b> Names from the Pi-Ager Database table 'current_values'.
<br><br>
 <img src="./images/icons/pi-ager-mqtt-publish-topics.png" alt=""></img><br>
<br><br>
<b>The following events will be published under the topic '{hostname}/status/event': </b>
<br><br>
<img src="./images/icons/mqtt_published_events.png" alt=""></img><br>
<br><br> <br><br>
<b>0 - cooling: </b> It is cooled to the set temperature with circulating air.
<br><br>
<b>1 - cooling with humidification: </b> It is cooled to the set temperature with circulating air and humidification is on, the heating is never controlled.
<br><br>
<b>2 - heating with humidification: </b> It is heated to the set temperature with circulating air and humidification is on, the cooling is never controlled.
<br><br>
<b>3 - automatic with humidification: </b> The pi-ager cools or heats with circulating air, depending on the set value and humidification is on.
<br><br>
<b>4 - automatic with dehumidification and humidification </b>Like automatic with humidification, additionally: If the humidity is exceeded, the exhaust air or/and the dehumidifier switches on until the humidity setpoint is reached again. Since the mode "only exhaust air" is a passive dehumidification, the minimum achievable humidity depends on the dryness of the ambient air. to avoid a wild switching on and off, the humidification should be delayed (5-10min)!
<br><br> <br><br>
<b>Pushover:</b>
<br><br>
<br>To use Pushover, one must download and install the APP for the appropriate system (Android / IOS) and create an account.
<br>After registration, you receive an email through to confirm the registration.
<br><br>
<br>To generate the <b>User-Key </b>and <b>API Token/Key </b>one must log in here: https://pushover.net/
<br>On the page you can then find your own user key after login. 
<br><br>
<br>Next you have to register an application: </b><i> Create an Application/API Token </i>
<br>You can then enter "Pi-Ager" as the name, and if you want, you can also add an icon.
<br>Once this process is complete, an API token/key is generated.  
<br>These 2 strings (<b>User-Key</b>and <b>API Token/Key</b>) must be entered into the corresponding fields on the Pi-Ager "Notification" page.
<br>(Don't forget to save)
<br><br>
<br>If the APP is already installed on your phone and you have logged in with your account,
<br>you can send a test message via the Pi-Ager page.
<br>If this arrives, everything is set correctly.
<br><br> <br><br>
<b><u>Load cell setting parameters: </u></b>
<br><br>
<b>Reference Unit: </b> The specific characteristic value of the load cell used is entered here.
<br>On the settings page, the exact value for the reference unit of the load cell used can be determined with the help of the scale wizard.
<br><br>
<b>Offset: </b> The specific characteristic value of the load cell is entered here.
<br>On the settings page, the exact value for the offset of the load cell used can be determined with the help of the scale wizard. 
<br><br>
<b>Storage cycle: </b>
<br>Here you can set the cycle in which you save the values to the database table for showing the weight data in the scale diagrams.
<br>Recommended value is 300 seconds.
<br><br>
<b>Typical Ref. Unit values: </b>
<br>10KG China Zelle: 205
<br>20kg China Zelle: 102
<br>50kg Edelstahl Zelle: 74
<br>20kg Edelstahl Zelle: 186
<br><br>
<b>Starting weights: </b> To determine how much weight meat or sausages lost during the ripening process, the starting weights can be entered here. The starting weights are also shown in dashed lines in the scale diagram. The difference between the starting weight and the current weight can then be read there. These values are also displayed on the MONITOR page as numerical values in grams and percent.
<br><br> <br><br>
<b>Sensor intern: </b> Here you can select the sensor to control the temperature and humidity in the Pi-Ager.
The older 1-Wire sensors DHT11/22 and SHT75 as well as the newer I2C sensors (Bus Adr.0x44) and AHT2x can be used.
<br><br>
<b>Sensor extern: </b>It is possible to connect another sensor to the system. The external sensor can be used to monitor the external environmental conditions of the Pi-Ager. The external temperature and humidity can be used as a source of information, especially for the control of humidity in the Pi-Ager.
<br>It should be noted that currently only the SHT3x sensors can have their I2C bus address changed. Therefore only these sensors, the AHT2x and the Bluetooth sensor 'Mi Temperature and Humidity Monitor 2' can be used as external sensors.
<br>With the help of an external circuit, e.g. with a 10kOhm resistor, the bus address of the SHT3x sensor can be changed to the required address 0x45. Some SHT3X sensor boards are also equipped with a corresponding jumper.
<br> The AHT2x sensor has a different I2C address, so that it can be combined with SHT3x and SHT85 internal sensors.
<br><br>
<br><img src="./images/icons/MiTemperatureSensor2.png" alt="">
<br>The MiThermometer2 can be used together with all internal sensors.
<br>This sensor can only be used with a custom firmware. To flash this sensor it is required to use an Android-based smartphone or tablet and open this page in the browser:
<br><a href="https://pvvx.github.io/ATC_MiThermometer/TelinkMiFlasher.html" target="_blank">TelinkMiFlasher</a>
<br>In the Telink Flasher Page: Press Connect: The browser should open a popup with visible Bluetooth devices. Choose the according target device (i.e. LYWSD03MMC) to pair.
<br>After connection is established a Do Acivation button appears. Press this button to start the decryption key process.
<br>Now you can press the Custom Firmware ver x.x button to directly flash the custom firmware. Alternatively you can choose a specific firmware binary (i.e. the original firmware) via the file chooser
<br>Press Start Flashing.
<br>
<b>Attention:</b> After flashing, make a note of the MiThermometer device name that is displayed. The format of the name is ATC_xxxxxx, with xxxxxx as the last 3 bytes of the 6 byte MAC address.
<br>The first three bytes are always the same (A4:C1:38) and are therefore not displayed in the name. The device name must be entered in the admin page.
<br>The device name of the Bluetooth thermometer can also be evaluated with a Bluetooth scanner app, e.g. nRF Connect or BLE Scanner.
<br><img src="./images/icons/telink_connect.jpg" alt="">
<br><br>
<b>Remark:</b>For the connection of the sensors always shielded cables must be used, e.g. USB cables or Ethernet cables. The length of the sensor cable should not exceed 1.5m. It is important that the shield of the sensor lines is always connected on the Pi-Ager board side.
<br><br>
<b>Attention: </b> The connection of a sensor may only be done in a de-energized state, otherwise there is a risk of short circuit and the Raspberry can be damaged.
<br><br> <br><br>
<b>UPS battery:</b>The Pi-Ager board can be equipped with an UPS module from Adafruit (PowerBoost 1000C). This module activates the attached LiPo accu on powerfail and monitors continuously the accu voltage. When this voltage drops below about 3.6V then the Pi-Ager can be shutdown to prevent further discharge and damage of the accu
<br><br> <br><br>
<b><u>Switch control parameter: </u></b>
<br><br>
<b>Light control: </b> Control the light relais depending on the switch input state. Options are:
<br>not active: the light is under control of the associated timer or manual button
<br>turn on if switch is open: the light is turned on if switch is open. If switch is closed, timer or manual button are active
<br>turn on if switch is closed: the light is turned on if switch is closed. If switch is open, timer or manual button are active
<br><br>
<b>UV light control: </b> Control the UV light relais depending on the switch input state. Options are:
<br>not active: the UV light is under the control of the associated timer or manual button
<br>turn off if switch is open: the UV light is turned off if switch is open. If switch is closed, timer or manual button are active
<br>turn off if switch is closed: the UV light is turned off if switch is closed. If switch is open, timer or manual button are active 
<br><br><img src="./images/icons/switch-board.jpg" alt="">
<br><br><img src="./images/icons/switch-circuit.jpg" alt="">
<br><br> <br><br>
<b>Telegram:</b>
<br><br>
<br>To use Telegram, you have to download and install the APP for the appropriate system (Android / IOS).
<br>When that is shot down, one enters "BotFather" in the top search box and then selects the chat.
<br><br>
<br>Now you can create a bot:
<br>For this, one simply sends the user BotFather the messages:
<br>/newbot
<br>You will then be asked to specify the bot name and the bot user name (The user name must necessarily end in <b>bot</b>). 
<br>e.g. :
<br><b>Bot Name:</b></b><i> Pi-Ager </i>or</b><i> Matured Cupboard </i>or</b><i> My-Pi-Ager</i>.
<br><b>User Name:</b></b><i> Name_bot </i>or</b><i> any Name_bot</i>.
<br>Finally you get the so called token, a longer string that uniquely identifies your bot. 
<br>For example: </b><i> 123456787:ABcdEFGhijklm-aTchoFJ_pb6oZKxzx8Zw</i>
<br>It is best to copy the token code directly into a text file .
<br>Optionally you can set up a user picture for the bot etc - just send commands like /setuserpic, /help etc in the Telegram client. 
<br><br>
<br><b>Important: </b>You have to start the bot now first and send a first message, otherwise the next step will not work.
<br><br>
<br>To start the bot, click on the link in the reply, it will look something like this:</b><i>t.me/Name_bot</i>.
<br>It opens a chat window, in which you can then start the bot. 
<br>This can be done (depending on the version) via a field or you send the command / message :
<br>/start
<br><br>
<br>Now you have to find out the chat id.
<br>It is really important to type something in a chat via the smartphone for example: "Hello Test", because otherwise it won't work.
<br>Next you call the following URL in the browser: https://api.telegram.org/bot[HTTP-TOKEN]/getUpdates
<br>[HTTP-TOKEN] you have to replace with the determined string.
<br>For example: 
<br>https://api.telegram.org/bot<b>123456787:ABcdEFGhijklm-aTchoFJ_pb6oZKxzx8Zw</b>/getUpdates
<br>The response will be something like this: 
<br>{"ok":true,"result":[{"update_id":277706798,
<br>"message":{"message_id":3,"from":{"id":<b>374628888</b>,"first_name":"Frank","last_name":"Mustermann"},"chat":{"id":374628888,"first_name":"Frank","last_name":"Mustermann","type":"private"},"date":1499083767,"text":"Hallo Test"}}]}
<br>What we need is the number/string after "id", so here that would be <b>374628888</b>.
<br><br>
<br>Then you have everything you need for the pi agers:
<br><b>bot token (HTTP token):</b> 123456787:ABcdEFGhijklm-aTchoFJ_pb6oZKxzx8Zw
<br><b>bot chatID:</b> 374628888
<br><br>
<br>These 2 strings must be entered into the appropriate fields on the Pi-Ager "Notification" page.
<br>(Don't forget to save)
<br>Now you can send a test message via the Pi-Ager page.
<br>If this arrives, everything is set correctly.
<br><br> <br><br>
<img src="./images/icons/temperature-control.jpg" alt="">
<br><br>
<b>Primary hysteresis of the temperature control: </b> 
<br>Hysteresis for the two-point control of the temperature in the maturing cabinet. (Value limit: 0.5 to +8° C).
<br><br>
<b>Secondary hysteresis of the temperature control: </b> 
<br>Alternative hysteresis for the two-point control of the temperature in the maturing cabinet. (Value limit: 0.5 to +8° C).
<br><br>
<b>Delay:</b> 
<br>This value sets the minimum delay in seconds between refrigeration compressor OFF and ON. (Values: 0 to 120 seconds).
<br><br>
<b>Recommendation:</b> 
<br>To avoid wild switching on and off, the hysteresis values should not be too small.
<br>Usually the primary hysteresis is used to control cooling and the secondary hysteresis to control heating. 
<br>Both values are <b>different</b> to set.
<br>Primary hysteresis is responsible for temperature control when only cooling or heating is required. 
<br>This is the case when operating mode 0 or 1 or 2 is preselected.
<br>
<br>In automatic mode, i.e. mode 3 or 4, the secondary hysteresis comes into play:
<br>If the main purpose is cooling, the primary hysteresis is activated during cooling, 
<br>while the secondary hysteresis is activated during heating. In order to minimize the switching processes,
<br>it is recommended to set the secondary hysteresis larger than the primary hysteresis.
<br>Conversely, if the main purpose is to heat in automatic mode (e.g. salami), 
<br>it is recommended to set the secondary hysteresis smaller than the primary hysteresis.
<br>
<br>If there is an external temperature sensor in the system, the hysteresis can be activated automatically in automatic mode:
<br>If the measured outside temperature is higher than the desired inside temperature, 
<br>cooling is required. The primary hysteresis is automatically used for this and the secondary hysteresis for heating.
<br>Conversely, if the measured outside temperature is lower than the desired inside temperature, 
<br>heating is required. The primary hysteresis is automatically used for heating and the secondary hysteresis for cooling
<br>
<br>The default values are:
<br>Primary hysteresis : 2°C
<br>Secondary hysteresis : 4°C
<br><br> <br><br>
<b>Low limit: </b> If the temperature falls below this limit, an alarm is activated or a message is sent.
<br><br>
<b>High limit: </b> If the temperature rises above this limit, an alarm is activated or a message is sent.
<br><br>
<b>Hysteresis:</b> To avoid chattering alarms, you can enter a hysteresis value here. The actual value must be in the good range minus this hysteresis value, so that the alarm goes away again and triggers a new alarm at the next under / exceeding of the limit value.
<br><br> <br><br>
<b>Set temperature:</b>
The desired temperature is set here. The minimum value and the maximum value are limited. 
<br>For technical reasons, not all values can be approached in every operating mode. The circulating air is always active during the cooling or heating phases.
<br><br> <br><br>
<b><u>Analog sensors: </u></b>
<br>A 4-channel AD converter was implemented on the Pi-Ager board and integrated into the software. With this it is now possible to operate various other analog sensors on the system. (especially NTC temperature sensors)
<br>
<br>If sensor channels are not used, please select ------.
<br><br>
<b>Temperature sensors: </b> For example, if you want to monitor the temperature in the meat at different points, this is possible with the appropriate meat temperature sensors. Due to the lower temperatures, the 100K sensors are recommended because of the better resolution. However, the 1000kOhm NTC sensors also work without problems. 
<br>Nearly all temperature sensors can be used, which are also used in the Wlanthermo project.
<br><br>
<img src="./images/icons/current_sensor2.png" alt="">
<img src="./images/icons/current_input_circuit.png" alt="">
<img src="./images/icons/terminalblock_3.5mm.png" alt="">
<br>
<b>Current sensor: </b> It is possible to connect a current sensor to channel 4. So it is possible to monitor the current of the system.
<br> Supported sensor type: LEM HO 6-P/SP33 (It is important to use the version with the 3.3V power supply).
<br> For correct values to be displayed, 3 windings (turns) must be passed through the sensor. 
<br><br>
<br><b>Attention! </b> It is necessary to count the turns that are passed through the feedthrough of the sensor. At this point you can easily make a mistake.
<br><br>
<b>Annotation: </b>Up to board version 2.2 all 4 inputs were prepared for NTC sensors and equipped with 2.5 mm stereo jack sockets. From board version 2.3 on, channel 4 has been prepared for connection of a current sensor (3.5 mm stereo jack socket) and can be used immediately. All boards with version 2.2 and smaller can be modified to connect a current sensor. But you have to use a 2.5 mm jack plug in this case.
<br><br>
<b>Parameters for current monitoring when switching the compressor relay:</b>
<br><br>
<b>Compressor relay monitoring:</b> Checkbox for switching the compressor relay function monitoring on/off.
<br>
<b>Current Threshold:</b> Comparison value for the current measurement. Depending on the switching process, an error message is output as an event if this value is exceeded or not reached.<br>
<b>Event messages every:</b> If a faulty compressor relay is detected, an error message is output as an event every n minutes.<br>
<br><br>
<b>Note on how it works:</b><br>
<br>When the compressor is switched on, an increased current flow can normally be observed. The current sensor measures this current and compares it to the current threshold. If this threshold value is exceeded, the relay switching process was successful. This will not be the case with burned relay contacts or a defective relay and an event message will be issued. It is also checked whether the current flow has fallen below the current threshold value after the compressor has been switched off. This will not be the case with 'sticking' or welded relay contacts and an event message will be issued. Event notifications can be configured on the 'Notifications' page.
<br><br> <br><br>
<b>UV light mode: </b>
<br>ON/OFF duration: the ON/OFF cycle can be set by defining ON- and OFF -duration in minutes.
<br>ON duration and timestamp: set time of day when UV light should be turned on for a duration in minutes.
<br>OFF: UV light is permanently turned off.
<br><br>
<b>UV OFF duration: </b> This is used to set the pause duration, which waits until the UV light is switched on again. If the value is 0 (= no pause), the UV light is permanently switched on. The maximum value is 1440min.
<br><br>
<b>UV ON duration: </b> This sets the UV light duration during which the light is switched on. At 0, the UV light is permanently switched off. The maximum value is 1440min.
<br><br>
<b>timestamp: </b> As an alternative to the cycle, a fixed time can be set here at which the UV light is switched on for the set UV light duration.
<br><br>
<b>set/reset uptime: </b> UV light uptime can be set to any start value in hours.
<br><br>
<b>activate circulating air: </b> The circulating air fan can be activated every time when the UV light is switched on.
<br><br>
<b>note: </b> For security reasons, you can manually switch off the UV light permanently via a switch on the admin page. In this case, the timer can no longer turn on the UV light.
<br><br>
<b>attention: </b> UV ON duration=0 and UV OFF duration=0: the UV light is permanently switched off.
<br><br> <br><br>
<img src="./images/icons/humidifier_monitor.png" alt="">
<br><br>
<b>monitoring start delay:</b> When humidification is enabled by setting up dry aging mode to 1...4 humidifier monitoring start is delayed to let settle the humidification controller. Whenever this value is changed, the timer is reset.
<br><br>
<b>monitoring hysteresis:</b> If the measured humidity falls below the lower limit, the 'humidifier failure' event is generated. Before a new event is generated again, the measured humidity must first have reached the setpoint. If the measured value then falls below the lower limit again, an event is generated.
<br><br> 