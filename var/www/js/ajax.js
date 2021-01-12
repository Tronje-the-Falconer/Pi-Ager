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
        
        // get values from json
            //timestamps
        // var json_timestamp_temperature = myObj.last_change_temperature;
        // var json_timestamp_humidity = myObj.last_change_humidity;
        // var json_timestamp_scale1 = myObj.last_change_scale1;
        // var json_timestamp_scale2 = myObj.last_change_scale2;
        // var timestamp_unix = myObj.server_time;
            //values
        var temperature_main = myObj.sensor_temperature;
        var humidity_main = myObj.sensor_humidity;
        var dewpoint_main = myObj.sensor_dewpoint;
        var temperature_extern = myObj.sensor_extern_temperature;
        var humidity_extern = myObj.sensor_extern_humidity;
        var dewpoint_extern = myObj.sensor_extern_dewpoint;
        var gr_scale1 = myObj.scale1;
        var gr_scale2 = myObj.scale2;
        var temp_meat1 = myObj.temperature_meat1;
        var temp_meat2 = myObj.temperature_meat2;
        var temp_meat3 = myObj.temperature_meat3;
        var temp_meat4 = myObj.temperature_meat4;
        var str_meat1_sensorname = myObj.meat1_sensor_name;
        var str_meat2_sensorname = myObj.meat2_sensor_name;
        var str_meat3_sensorname = myObj.meat3_sensor_name;
        var str_meat4_sensorname = myObj.meat4_sensor_name;
        var status_piager = myObj.status_piager;
        var status_agingtable = myObj.status_agingtable;
        var status_scale1 = myObj.status_scale1;
        var status_scale2 = myObj.status_scale2;
        
        // timestamp calculating
        // var time_difference_temperature = timestamp_unix - json_timestamp_temperature;
        // var time_difference_humidity = timestamp_unix - json_timestamp_humidity;
        // var time_difference_scale1 = timestamp_unix - json_timestamp_scale1;
        // var time_difference_scale2 = timestamp_unix - json_timestamp_scale2;
        
        //string work
        // var split_temperature = str_temperature.split(".");
        // var split_humidity = str_humidity.split(".");
        
        var str_temperature_main;
        var str_humidity_main;
        var str_dewpoint_main;
        var str_temperature_extern;
        var str_humidity_extern;
        var str_dewpoint_extern;
        var str_gr_scale1;
        var str_gr_scale2;
        var str_meat1;
        var str_meat2;
        var str_meat3;
        var str_meat4; 
        
        if (temperature_main === null) {
            str_temperature_main = '-----';
        }
        else {
            str_temperature_main = temperature_main.toFixed(1);
        }
        if (humidity_main === null) {
            str_humidity_main = '-----';
        }
        else {
            str_humidity_main = humidity_main.toFixed(0);
        }
        // if (dewpoint_main === null) {
            str_dewpoint_main = '-----';
        // }
        // else {
            // str_dewpoint_main = dewpoint_main.toFixed(1);
        // }
        // if (temperature_extern === null) {
            str_temperature_extern = '-----';
        // }
        // else {
            // str_temperature_extern = temperature_extern.toFixed(1);
        // }
        // if (humidity_extern === null) {
            str_humidity_extern = '-----';
        // }
        // else {
            // str_humidity_extern = humidity_extern.toFixed(0);
        // }
        // if (dewpoint_extern === null) {
            str_dewpoint_extern = '-----';
        // }
        // else {
            // str_dewpoint_extern = dewpoint_extern.toFixed(1);
        // }
        if (gr_scale1 === null) {
            str_gr_scale1 = '-----';
        }
        else {
            str_gr_scale1 = gr_scale1.toFixed(0);
        }
        if (gr_scale2 === null) {
            str_gr_scale2 = '-----';
        }
        else {
            str_gr_scale2 = gr_scale2.toFixed(0);
        }
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
            str_meat4 = temp_meat4.toFixed(2);
        }

        //------------------------Setzen der Hauptsensorwerte auf der Webseite
        if (str_temperature_main.substring(0,3) == '---' || status_piager == 0) {
            document.getElementById('json_temperature_main').innerHTML = '-----' + " °C";
        }
        else {
            document.getElementById('json_temperature_main').innerHTML = str_temperature_main + " °C";
        }
        if (str_humidity_main.substring(0,3) == '---' || status_piager == 0) {
            document.getElementById('json_humidity_main').innerHTML = '-----' + " &#37";
        }
        else {
            document.getElementById('json_humidity_main').innerHTML = str_humidity_main + " &#37";
        }
        if (str_dewpoint_main.substring(0,3) == '---' || status_piager == 0) {
            document.getElementById('json_dewpoint_main').innerHTML = '-----' + " °C";
        }
        else {
            document.getElementById('json_dewpoint_main').innerHTML = str_dewpoint_main + " °C";
        }

        //------------------------Setzen der Externsensorwerte auf der Webseite
        if (str_temperature_extern.substring(0,3) == '---' || status_piager == 0) {
            document.getElementById('json_temperature_extern').innerHTML = '-----' + " °C";
        }
        else {
            document.getElementById('json_temperature_extern').innerHTML = str_temperature_extern + " °C";
        }
        if (str_humidity_extern.substring(0,3) == '---' || status_piager == 0) {
            document.getElementById('json_humidity_extern').innerHTML = '-----' + " &#37";
        }
        else {
            document.getElementById('json_humidity_extern').innerHTML = str_humidity_extern + " &#37";
        }
        if (str_dewpoint_extern.substring(0,3) == '---' || status_piager == 0) {
            document.getElementById('json_dewpoint_extern').innerHTML = '-----' + " °C";
        }
        else {
            document.getElementById('json_dewpoint_extern').innerHTML = str_dewpoint_extern + " °C";
        }
        
        //------------------------Setzen der Scale1-Werte auf der Webseite
        if (str_gr_scale1.substring(0,3) == '---' || status_scale1 == 0) {
            document.getElementById('json_scale1').innerHTML = '-----' + " gr";
        }
        else {
            document.getElementById('json_scale1').innerHTML = str_gr_scale1 + " gr";
        }
        if (str_gr_scale2.substring(0,3) == '---' || status_scale2 == 0) {
            document.getElementById('json_scale2').innerHTML = '-----' + " gr";
        }
        else {
            document.getElementById('json_scale2').innerHTML = str_gr_scale2 + " gr";
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
            document.getElementById('json_meat_temperature4').innerHTML = temp_meat4.toFixed(1) + " °C";
        }
    }
}

    var myVar = setInterval(myTimer, 3000);

    function myTimer() {
        var d = new Date();
        loadContent();
    }