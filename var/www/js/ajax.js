var xmlHttpObject = false;
if (typeof XMLHttpRequest != 'undefined') {
    xmlHttpObject = new XMLHttpRequest();
}
if (!xmlHttpObject) {
    try {
        xmlHttpObject = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
        try {
            xmlHttpObject = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (e) {
            xmlHttpObject = null;
        }
    }
}

function loadContent() {
    // xmlHttpObject.open('get','config/current.json');
    xmlHttpObject.open('GET', 'modules/monitor_query.php');
    xmlHttpObject.onreadystatechange = handleContent;
    xmlHttpObject.send(null);
    // xmlHttpObject.open('get','config/scales.json');
    // xmlHttpObject.onreadystatechange = handleContentScales;
    // xmlHttpObject.send(null);
    return false;
}

function handleContent() {
    if (xmlHttpObject.readyState == 4 && xmlHttpObject.status == 200) {
        myObj = JSON.parse(xmlHttpObject.responseText);

        var json_timestamp_temperature = myObj.last_change_temperature;
        var json_timestamp_humidity = myObj.last_change_humidity;
        var json_timestamp_scale1 = myObj.last_change_scale1;
        var json_timestamp_scale2 = myObj.last_change_scale2;
//      var timestamp = new Date();
//      var timestamp_unix_milliseconds = timestamp.getTime();
//      var timestamp_unix = Math.round(timestamp_unix_milliseconds / 1000);
        var timestamp_unix = myObj.server_time;
        var time_difference_temperature = timestamp_unix - json_timestamp_temperature;
        var time_difference_humidity = timestamp_unix - json_timestamp_humidity;
        var time_difference_scale1 = timestamp_unix - json_timestamp_scale1;
        var time_difference_scale2 = timestamp_unix - json_timestamp_scale2;
        var str_temperature = myObj.sensor_temperature.toFixed(1);
        var split_temperature = str_temperature.split(".");
        var str_humidity = myObj.sensor_humidity.toFixed(1);
        var split_humidity = str_humidity.split(".");
        var str_scale1 = myObj.scale1.toFixed(0);
        var str_scale2 = myObj.scale2.toFixed(0);
        var temp_meat1 = myObj.temperature_meat1;
        var temp_meat2 = myObj.temperature_meat2;
        var temp_meat3 = myObj.temperature_meat3;
        var temp_meat4 = myObj.temperature_meat4;
        var str_meat1;
        var str_meat2;
        var str_meat3;
        var str_meat4;       
        if (temp_meat1 === null) {
            str_meat1 = '-----';
        }
        else {
            str_meat1 = temp_meat1.toFixed(1);
        }
        if (temp_meat2 === null) {
            str_meat2 = '-----';
        }
        else {
            str_meat2 = temp_meat2.toFixed(1);
        }        
        if (temp_meat3 === null) {
            str_meat3 = '-----';
        }
        else {
            str_meat3 = temp_meat3.toFixed(1);
        }
        if (temp_meat4 === null) {
            str_meat4 = '-----';
        }
        else {
            str_meat4 = temp_meat4.toFixed(1);
        }        

        var str_meat1_sensorname = myObj.meat1_sensor_name;
        var str_meat2_sensorname = myObj.meat2_sensor_name;
        var str_meat3_sensorname = myObj.meat3_sensor_name;
        var str_meat4_sensorname = myObj.meat4_sensor_name;
        var status_piager = myObj.status_piager;
        var status_agingtable = myObj.status_agingtable;
        var status_scale1 = myObj.status_scale1;
        var status_scale2 = myObj.status_scale2;
        //        document.getElementById('json_timestamp').innerHTML = json_timestamp;
        //        document.getElementById('timestamp').innerHTML = timestamp_unix;

        //------------------------Setzen der Temperatur-Werte auf der Webseite
        if (time_difference_temperature >= 25 && time_difference_temperature <= 119) {
            document.getElementById('temperature_values_old').innerHTML = "<img src='images/icons/attention_42x42.png'>";
        }
        if (time_difference_temperature <= 120 && status_piager == 1) {
        // if (time_difference_temperature <= 120) {
            if (split_temperature[0] < 10) {
                split_temperature[0] = "0" + split_temperature[0];
            }
            document.getElementById('current_json_temperature_0').innerHTML = split_temperature[0];
            document.getElementById('current_json_temperature_1').innerHTML = split_temperature[1];
        } else {
            document.getElementById('current_json_temperature_0').innerHTML = '--';
            document.getElementById('current_json_temperature_1').innerHTML = '-';
        }

        //------------------------Setzen der Feuchtigkeits-Werte auf der Webseite
        if (time_difference_humidity >= 25 && time_difference_humidity <= 119) {
            document.getElementById('humidity_values_old').innerHTML = "<img src='images/icons/attention_42x42.png'>";
        }

        if (time_difference_humidity <= 120 && status_piager == 1) {
        // if (time_difference_humidity <= 120) {
            if (split_humidity[0] < 10) {
                split_humidity[0] = "0" + split_humidity[0];
            }
            document.getElementById('current_json_humidity_0').innerHTML = split_humidity[0];
            document.getElementById('current_json_humidity_1').innerHTML = split_humidity[1];
        } else {
            document.getElementById('current_json_humidity_0').innerHTML = '--';
            document.getElementById('current_json_humidity_1').innerHTML = '-';
        }
        //------------------------Setzen der Scale1-Werte auf der Webseite
        if (time_difference_scale1 >= 36000 && time_difference_scale1 <= 43200) {
        //    document.getElementById('scale1_values_old').innerHTML = "<img src='images/icons/attention_42x42.png'>";
            document.getElementById('scale1_icon').innerHTML = "<img src='images/icons/scale_fail_42x42.gif' style='padding-top: 10px;'>" + "1"; 
        }
        if (time_difference_scale1 <= 43200 && status_scale1 == 1) {
        // if (time_difference_scale1 <= 43200) {
            document.getElementById('scale_json_scale1').innerHTML = str_scale1 + " gr.";
        } else {
            document.getElementById('scale_json_scale1').innerHTML = '----- gr.';
        }
        //------------------------Setzen der Scale2-Werte auf der Webseite
        if (time_difference_scale2 >= 36000 && time_difference_scale2 <= 43200) {
        //    document.getElementById('scale2_values_old').innerHTML = "<img src='images/icons/attention_42x42.png'>";
            document.getElementById('scale2_icon').innerHTML = "<img src='images/icons/scale_fail_42x42.gif' style='padding-top: 10px;'>" + "2";          
        }
        if (time_difference_scale2 <= 43200 && status_scale2 == 1) {
        // if (time_difference_scale2 <= 43200) {
            document.getElementById('scale_json_scale2').innerHTML = str_scale2 + " gr.";
        } else {
            document.getElementById('scale_json_scale2').innerHTML = '----- gr.';
        }
        
        //------------------------ Setzen der Meat Thermometer Werte

        if (str_meat1_sensorname.substring(0,3) == '---' || status_piager == 0) {
            document.getElementById('json_meat_temperature1').innerHTML = '-----' + " °C";
        }
        else {
            document.getElementById('json_meat_temperature1').innerHTML = str_meat1 + " °C";
        }
        
        if (str_meat2_sensorname.substring(0,3) == '---' || status_piager == 0) {
            document.getElementById('json_meat_temperature2').innerHTML = '-----' + " °C";
        }
        else {
            document.getElementById('json_meat_temperature2').innerHTML = str_meat2 + " °C";
        }
        
        if (str_meat3_sensorname.substring(0,3) == '---' || status_piager == 0) {
            document.getElementById('json_meat_temperature3').innerHTML = '-----' + " °C";
        }
        else {
            document.getElementById('json_meat_temperature3').innerHTML = str_meat3 + " °C";
        }
                                
        if (str_meat4_sensorname.substring(0,3) == '---' || status_piager == 0) {
            document.getElementById('json_meat_temperature4').innerHTML = '-----' + " °C";
        }
        else if (str_meat4_sensorname.substring(0,3) == 'LEM') {
            document.getElementById('json_meat_temperature4').innerHTML = str_meat4 + " A";
        }
        else {
            document.getElementById('json_meat_temperature4').innerHTML = str_meat4 + " °C";
        }
    }
}

    var myVar = setInterval(myTimer, 3000);

    function myTimer() {
        var d = new Date();
        loadContent();
    }