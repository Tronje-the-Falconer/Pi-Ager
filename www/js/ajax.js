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
    xmlHttpObject.open('get','current.json');
    xmlHttpObject.onreadystatechange = handleContent;
    xmlHttpObject.send(null);
    return false;
}

function handleContent() {
    if (xmlHttpObject.readyState==4 && xmlHttpObject.status==200) {
        myObj = JSON.parse(this.responseText);

        var str_temperature = myObj.temperature.toFixed(1);
        var split_temperature = str_temperature.split(".");
        if (split_temperature[0] < 10)
        split_temperature[0] = "0"+split_temperature[0];
        document.getElementById('current_json_temperature_0').innerHTML = split_temperature[0];
        document.getElementById('current_json_temperature_1').innerHTML = split_temperature[1];

        var str_humidity = myObj.luftfeuchtigkeit.toFixed(1);
        var split_humidity = str_humidity.split(".");
        if (split_humidity[0] < 10)
        split_humidity[0] = "0"+split_humidity[0];
        document.getElementById('current_json_humidity_0').innerHTML = split_humidity[0];
        document.getElementById('current_json_humidity_1').innerHTML = split_humidity[1];
        }
}

var myVar = setInterval(myTimer, 2000);

function myTimer() {
    var d = new Date();
    loadContent();
}