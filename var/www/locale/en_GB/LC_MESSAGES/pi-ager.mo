��          �   %   �      0     1     L     [     k     �     �     �     �     �     �          #     9     M     `     x     �     �     �     �     �             o  3  �  �  �  �  ;    �  Y  !  �  �    �  �  �  w  �  &   O  �&  �   )    �*  g  �-  �  -/     �4  '  9  {  ?=  :  �@  �
  �F  �  �Q  o	  �S  �  )]  2  �c                                   
                                                             	                               helptext_agingtable_config helptext_alarm helptext_backup helptext_circulation_air helptext_dehumidifier_config helptext_email helptext_event helptext_exhausting_air helptext_humidify_config helptext_humidity_setpoint helptext_language_admin helptext_light_config helptext_mailserver helptext_messenger helptext_operation_mode helptext_pushover helptext_scale_admin helptext_sensortype_admin helptext_telegram helptext_temperature_config helptext_temperature_setpoint helptext_thermometer_admin helptext_uv_config Project-Id-Version: Pi-Ager-EN
PO-Revision-Date: 2021-06-21 14:13+0200
Last-Translator: 
Language-Team: 
Language: en_GB
MIME-Version: 1.0
Content-Type: text/plain; charset=UTF-8
Content-Transfer-Encoding: 8bit
X-Generator: Poedit 3.0
X-Poedit-Basepath: ../../###GITHUB/branches/entwicklung/var/www
Plural-Forms: nplurals=2; plural=(n != 1);
X-Poedit-SearchPath-0: .
 <br><br>
<b>failure humidity delta: </b> The difference between current and setpoint humidity must be less then this value to continue operating the aging table after a power failure.
<br>Otherwise operating the aging table is stopped.
<br><br>
<b>failure temperature delta: </b> The difference between current and setpoint temperature must be less then this value to continue operating the aging table after a power failure.
<br>Otherwise operating the aging table is stopped.
<br><br> <br><br>
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
<br>The progress of the backup process can be monitored using a terminal window. Any problems occurring during backup are also logged.
<br>In the terminal window enter the following commands:
<br> cd /var/log
<br> cat pi-ager_backup.log
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
<b>period: </b> this is used to set the pause time, which waits until the exhausting is switched on again. if the value is 0 (= no pause), the exhausting air is permanently switched on. the maximum value is 1440min.
<br><br>
<b>duration: </b> this sets the exhausting time during which the fan is running. at 0, the exhausting air timer function is switched off. the maximum value is 1440min.
<br><br>
<b>note: </b> The exhaust air fan runs independently of the timer settings - also during dehumidification in the "automatic mode with humidification and dehumidification.
<br><br>
<b>attention: </b> period=0 und duration=0 is not useful and not allowed.
<br><br> <br><br>
<b>only exhaust: </b> Because passive dehumidification by exhaust air takes place in this mode, the effectiveness of the dehumidification is very dependent on the ambient air humidity.
<br><br>
<b>exhaust & dehumidifier: </b> In this mode, dehumidification via exhaust air is active together with a separate dehumidifier.
<br><br>
<b>only dehumidifier:</b> In this mode, the dehumidification of the air in the Pi-ager is performed only by a dehumidifier.
<br><br>
<b>recommendation:</b> The delay time in humidity control is not active on the dehumidifier, it only affects the humidifier..
<br><br>
<b>attention: </b> Since there are very few dehumidifiers on the market that are effective at the low temperatures, you should carefully consider which device you want to use for what.
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
<b>period: </b> This is used to set the pause time, which waits until the exhausting is switched on again. If the value is 0 (= no pause), the exhausting air is permanently switched on. the maximum value is 1440min.
<br><br>
<b>duration: </b> This sets the exhausting time during which the fan is running. If the value is 0, the exhausting air timer function is switched off. The maximum value is 1440min.
<br><br>
<b>note: </b> The exhaust air fan runs independently of the timer settings - also during dehumidification in the "automatic mode with humidification and dehumidification.
<br><br>
<b>attention: </b> period=0 und duration=0 is not useful and not allowed.
<br><br> <br><br>
<b>switching hysteresis:</b>
<br><br>
<b>switch-on value: </b> is the value at which the control becomes active (value: 0-30%)
<b>switch-off value: </b>  is the value at which the control becomes inactive (value: 0-30%)
<br>the values may not be the same in order to avoid a wild switching on and off.
<br><br>
<b>delay: </b>here the delay time is set until the humidifier turns on if the humidity is too low. this can be used to blast out the rapidly falling air humidity during "cooling", "timer exhaust" or "dehumidification". The minimum value is 0 minutes, the maximum 60 minutes.
<br><br>
<b>example </b></b><i> target humidity: 75% ; switch-on value: 5% ; switch-off value: 1%</i>
<br>switch-on humidity = target humidity - switch-on value --> 75% - 5% = 70%
<br>switch-off humidity = target humidity - switch-off value--> 75% - 1% = 74%
<br>delay = 5 minutes
<br>so if 70% relative humidity are reached, the control waits for 5 minutes. only then does the pi-ager humidify the air to 74% and then switch off humidification again.
<br><br>
<b>example automatic mode with with humidification and dehumidification: </b> in this automatic mode, the humidity is completely automatically controlled. the current humidity is determined first. it is then decided which method (humidification and dehumidification) is suitable for achieving the desired set-point humidity. this also means that the switching values of the hysteresis must not be too close together. otherwise, humidification and dehumidification could always be switched on and off alternately.
<br><br>
<b>recommendation:</b> check the stored values in the logfile!
<br><br>
<b>attention: </b> use only positive integers!
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
<b>period: </b> This is used to set the pause time, which waits until the light is switched on again. If the value is 0 (= no pause), the light is permanently switched on. The maximum value is 1440min.
<br><br>
<b>duration: </b> This sets light time during which the light is switched on. At 0, the light timer function is switched off. The maximum value is 1440min.
<br><br>
<b>timestamp: </b> As an alternative to the cycle, a fixed time can be set here at which the light is switched on for the set duration.
<br><br>
<b>note: </b>  You can manually switch on the light permanently via a switch on the webcam page. In this case, the timer can no longer turn off the light.
<br><br>
<b>attention: </b> period=0 und duration=0 is not useful and not allowed.
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
<br>The error manager / messenger controls the notification to the individual services such as email, alarm, pushover and Telegram. 
<br>Here individual errors or exceptions can be assigned, where the message is passed and whether a stop of the system should be activated. 
<br><br>

<b>It is strongly recommended to set ALL errors so that they ALWAYS trigger a stop of the system (exception) when they occur. Failure to do so may result in damage to the hoop cabinet</b>.
<br><br>
<b>Error: </b>The error that the system detects is entered here. This column is mostly already predefined.
<br><br>
<b>Checkboxes: </b>Here you can activate the appropriate notification service through which the message should be sent.
<br><br>
<b>Alarm: </b> Here you can select the type of acoustic alarm ( signal generator on the board). One can define the alarm types further down in the range "alarm" accordingly.
<br><br>
<b>Trigger stop: </b> By selecting this checkbox, you can define whether the system should be stopped when this error occurs. <b>(THIS IS STRONGLY RECOMMENDED!!!)</b>. If the checkbox is not selected, there will only be a notification about the selected service.
<br><br>
<b>Active: </b> Here you can select whether this error should be considered at all. If this checkbox is not selected, there is no stop of the system and there is also no notification or logging.
<br><br>

Translated with www.DeepL.com/Translator (free version) <br><br>
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
<br><br> <br><br>
<b>Sensor intern: </b> Here you can select the sensor to control the temperature and humidity in the Pi-Ager.
The older 1-Wire sensors DHT11/22 and SHT75 as well as the newer I2C sensors (Bus Adr.44) can be used.
<br><br>
<b>Sensor extern: </b>It is possible to connect another sensor to the system. The external sensor can be used to monitor the external environmental conditions of the Pi-Ager. The external temperature and humidity can be used as a source of information, especially for the control of humidity in the Pi-Ager.
<br>It should be noted that currently only the SHT3X sensors can have their I2C bus address changed. Therefore only these sensors can be used as external sensors.
<br>With the help of an external circuit, e.g. with a 10kOhm resistor, the bus address of the SHT3x sensor can be changed to the required address 45. Some SHT3X sensor boards are also equipped with a corresponding jumper.
<br><br>
<b>Remark: </b> The connection of 2 sensors is only possible if you use the new I2C sensors. The external sensor is connected in parallel to the internal sensor to the corresponding terminals of the Pi-Ager board.
<br>For the connection of the sensors always shielded cables must be used, e.g. USB cables or Ethernet cables. The length of the sensor cable should not exceed 1.5m. It is important that the shield of the sensor lines is always connected on the Pi-Ager board side.
<br><br>
<b>Attention: </b> The connection of a sensor may only be done in a de-energized state, otherwise there is a risk of short circuit and the Raspberry can be damaged.
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
<b>switch-on value: </b>  is the value at which the control becomes active (value limit: 0-10 ° C). This value must always be greater than the switch-off value.
<br><br>
<b>switch-off value: </b> is the value at which the control becomes inactive (value: 0-10 ° C). The values may not be the same in order to avoid a wild switching on and off.
<br><br>
<b>recommendation:</b> check the stored values in the logfile!
<br><br>
<b>attention: </b> use only positive integers!
<br><br> <br><br>
<b>setpoint temperature:</b> The desired temperature is set here. The min and the max value are limited. 
<br> For technical reasons, not all values can be approached in any operating mode. the circulating air is always active during the cooling or heating phases.
<br><br>
<b>example of cooling:</b>
<i>setpoint temperature: 12°C; switch-on value: 3°C; switch-off value: 1°C</i>
<br>switch-on temperature = setpoint temperature + switch-on value --> 12°C + 3°C = 15°C
<br>switch-off temperature = setpoint temperature + switch-off value --> 12°C + 1°C = 13°C
<br>So, if 15 degrees are exceeded, the pi-ager cools down to 13 ° C and then switches off to avoid excessive cooling. 
<br>The entire behavior is different from pi-ager to pi-ager and therefore to be determined individually.
<br><br>
<b>example of heating:</b>
</b><i>setpoint temperature: 22°C; switch-on value: 3°C; switch-off value 1°C</i>
<br>switch-on temperature = setpoint temperature - switch-on value --> 22°C - 3°C = 19°C
<br>switch-off temperature = setpoint temperature - switch-off value --> 22°C - 1°C = 21°C
<br>So, if the temperature drops below 19 degrees, the pi-ager heats up to 21 ° C and then switches off to avoid excessive heating.
<br>The entire behavior is different from pi-ager to pi-ager and therefore to be determined individually.
<br><br>
<b>automatic mode:</b> in every automatic mode, the temperature is fully automatically controlled. 
first, the current temperature is determined. Then decide which method (cooling or heating) is suitable to reach the setpoint temperature set. this also means that the switching values of the hysteresis must not be too close together. Otherwise, cooling and heating could be switched on and off alternately.
<br><br>
<b>example of automatic:</b> setpoint temperature: 15°C; switch-on value: 5°C; switch-off value 3°C
<br>1st case: sensor temperature >= (setpoint temperature + switch-on value [=20°C]) = cooling on
<br>2st case: sensor temperature <= (setpoint temperature + switch-off value [=18°C]) = cooling off
<br>3st case: sensor temperature >= (setpoint temperature - switch-on value [=10°C]) = heating on
<br>4st case: sensor temperature <= (setpoint temperature - switch-off value [=12°C]) = heating off
<br><br>
<b>recommendation:</b> check the stored values in the logfile!
<br><br>
<b>attention:</b> use only positive integers!
<br><br> <br><br>
<b><u>Analog sensors: </u></b>
<br>A 4-channel AD converter was implemented on the Pi-Ager board and integrated into the software. With this it is now possible to operate various other analog sensors on the system. (especially NTC temperature sensors)
<br>
<br>If sensor channels are not used, please select ------.
<br><br>
<b>Temperature sensors: </b> For example, if you want to monitor the temperature in the meat at different points, this is possible with the appropriate meat temperature sensors. Due to the lower temperatures, the 100K sensors are recommended because of the better resolution. However, the 1000kOhm NTC sensors also work without problems. 
<br>Nearly all temperature sensors can be used, which are also used in the Wlanthermo project.
<br><br>
<b>Current sensor: </b> It is possible to connect a current sensor to channel 4. So it is possible to monitor the current of the system.
<br> Supported sensor type: LEM HO 6-P/SP33 (It is important to use the version with the 3.3V power supply).
<br> For correct values to be displayed, 3 windings (turns) must be passed through the sensor. 
<br><br>
<br><b>Attention! </b> It is necessary to count the turns that are passed through the feedthrough of the sensor. At this point you can easily make a mistake.
<br><br>
<b>Annotation: </b>Up to board version 2.2 all 4 inputs were prepared for NTC sensors and equipped with 2.5 mm stereo jack sockets. From board version 2.3 on, channel 4 has been prepared for connection of a current sensor (3.5 mm stereo jack socket) and can be used immediately. All boards with version 2.2 and smaller can be modified to connect a current sensor. But you have to use a 2.5 mm jack plug in this case.
<br><br> <br><br>
<b>period: </b> This is used to set the pause time, which waits until the UV light is switched on again. If the value is 0 (= no pause), the UV light is permanently switched on. The maximum value is 1440min.
<br><br>
<b>duration: </b> This sets UV light time during which the light is switched on. At 0, the UV light timer function is switched off. The maximum value is 1440min.
<br><br>
<b>timestamp: </b> As an alternative to the cycle, a fixed time can be set here at which the UV light is switched on for the set duration.
<br><br>
<b>note: </b> For security reasons, you can manually switch off the UV light permanently via a switch on the admin page.  In this case, the timer can no longer turn on the UV light.
<br><br>
<b>attention: </b> period=0 und duration=0 is not useful and not allowed.
<br><br> 