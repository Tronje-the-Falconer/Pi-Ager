var xmlHttpObject = false;
if (typeof XMLHttpRequest != 'undefined') {
    xmlHttpObject = new XMLHttpRequest();
    }
if (!xmlHttpObject) {
    try {
        xmlHttpObject = new ActiveXObject("Msxml2.XMLHTTP");
    }
    catch(e) {
        try {
            xmlHttpObject = new ActiveXObject("Microsoft.XMLHTTP");
        }
        catch(e) {
            xmlHttpObject = null;
        }
    }
}

function loadContent() {
    xmlHttpObject.open('get','config/current.json');
    xmlHttpObject.onreadystatechange = handleContent;
    xmlHttpObject.send(null);
    return false;
}

function handleContent() {
    if (xmlHttpObject.readyState==4 && xmlHttpObject.status==200) {
        myObj = JSON.parse(this.responseText);

        var json_timestamp = myObj.last_change;
        var timestamp = new Date();
        var timestamp_unix_milliseconds = timestamp.getTime();
        var timestamp_unix = Math.round(timestamp_unix_milliseconds / 1000);
        var time_difference = timestamp_unix - json_timestamp;
        var str_temperature = myObj.sensor_temperature.toFixed(1);
        var split_temperature = str_temperature.split(".");
        var str_humidity = myObj.sensor_humidity.toFixed(1);
        var split_humidity = str_humidity.split(".");
//        document.getElementById('json_timestamp').innerHTML = json_timestamp;
//        document.getElementById('timestamp').innerHTML = timestamp_unix;
        if (time_difference >= 25 && time_difference <= 119){
            document.getElementById('values_older_25_sec').innerHTML = "<img src='images/icons/attention_42x42.png'>";
        }
        if (time_difference<=120){
            if (split_temperature[0] < 10){
                split_temperature[0] = "0"+split_temperature[0];
            }
            document.getElementById('current_json_temperature_0').innerHTML = split_temperature[0];
            document.getElementById('current_json_temperature_1').innerHTML = split_temperature[1];
            if (split_humidity[0] < 10){
                split_humidity[0] = "0"+split_humidity[0];
            }
            document.getElementById('current_json_humidity_0').innerHTML = split_humidity[0];
            document.getElementById('current_json_humidity_1').innerHTML = split_humidity[1];
        }
        else {
            document.getElementById('current_json_temperature_0').innerHTML = '--';
            document.getElementById('current_json_temperature_1').innerHTML = '-';
            document.getElementById('current_json_humidity_0').innerHTML = '--';
            document.getElementById('current_json_humidity_1').innerHTML = '-';
        }
    }
}

var myVar = setInterval(myTimer, 2000);

function myTimer() {
    var d = new Date();
    loadContent();
}