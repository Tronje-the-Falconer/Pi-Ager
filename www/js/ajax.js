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
        var timestamp = new Date();
        var timestamp_unix_milliseconds = timestamp.getTime();
        var timestamp_unix = Math.round(timestamp_unix_milliseconds / 1000);
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
        if (time_difference_scale1 >= 36000 && time_difference_scale1 < 43200) {
            document.getElementById('scale1_values_old').innerHTML = "<img src='images/icons/attention_42x42.png'>";
        }
        if (time_difference_scale1 <= 43200 && status_scale1 == 1) {
        // if (time_difference_scale1 <= 43200) {
            document.getElementById('scale_json_scale1').innerHTML = str_scale1 + " gr.";
        } else {
            document.getElementById('scale_json_scale1').innerHTML = '----- gr.';
        }
        //------------------------Setzen der Scale2-Werte auf der Webseite
        if (time_difference_scale2 >= 36000 && time_difference_scale2 <= 43200) {
            document.getElementById('scale1_values_old').innerHTML = "<img src='images/icons/attention_42x42.png'>";
        }
        if (time_difference_scale2 <= 43200 && status_scale2 == 1) {
        // if (time_difference_scale2 <= 43200) {
            document.getElementById('scale_json_scale2').innerHTML = str_scale2 + " gr.";
        } else {
            document.getElementById('scale_json_scale2').innerHTML = '----- gr.';
        }
    }
}

    var myVar = setInterval(myTimer, 10000);

    function myTimer() {
        var d = new Date();
        loadContent();
    }