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

        var str_temp = myObj.temperatur.toFixed(1);
        var split_temperatur = str_temp.split(".");
        document.getElementById('current_json_temperatur_0').innerHTML = split_temperatur[0];
        document.getElementById('current_json_temperatur_1').innerHTML = split_temperatur[1];

        var str_hum = myObj.luftfeuchtigkeit.toFixed(1);
        var split_luftfeuchtigkeit = str_hum.split(".");
        document.getElementById('current_json_luftfeuchtigkeit_0').innerHTML = split_luftfeuchtigkeit[0];
        document.getElementById('current_json_luftfeuchtigkeit_1').innerHTML = split_luftfeuchtigkeit[1];
        }
}

var myVar = setInterval(myTimer, 2000);

function myTimer() {
    var d = new Date();
    loadContent();
}