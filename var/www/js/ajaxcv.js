//
// process request for current values showing on index.php
//

async function handleContent( msg ) {
//    console.log('in handleContent');

    myObj = JSON.parse(msg);

    var temperature_main = myObj.temperature_avg[0];
    var humidity_main = myObj.humidity_avg[0];
    var humidity_abs_main = myObj.humidity_abs_avg[0];        
    var dewpoint_main = myObj.sensor_dewpoint[0];
    var temperature_extern = myObj.sensor_extern_temperature[0];
    var humidity_extern = myObj.sensor_extern_humidity[0];
    var humidity_extern_abs = myObj.sensor_extern_humidity_abs[0];
    var dewpoint_extern = myObj.sensor_extern_dewpoint[0];
    var gr_scale1 = myObj.scale1[0];
    var gr_scale2 = myObj.scale2[0];
    var temp_meat1 = myObj.temperature_meat1[0];
    var temp_meat2 = myObj.temperature_meat2[0];
    var temp_meat3 = myObj.temperature_meat3[0];
    var temp_meat4 = myObj.temperature_meat4[0];
    var str_meat1_sensorname = myObj.meat1_sensor_name;
    var str_meat2_sensorname = myObj.meat2_sensor_name;
    var str_meat3_sensorname = myObj.meat3_sensor_name;
    var str_meat4_sensorname = myObj.meat4_sensor_name;
    var status_piager = myObj.status_piager[0];
    var status_agingtable = myObj.status_agingtable[0];
    var status_scale1 = myObj.status_scale1[0];
    var status_scale2 = myObj.status_scale2[0];
    var grepmain = myObj.grepmain;
    
    var MiSensor_battery = myObj.MiSensor_battery[0];
    var sensorsecondtype = myObj.sensorsecondtype;
    var sensorbus = myObj.sensorbus;
    
    // console.log('temperature_extern = ', temperature_extern);
    
    var str_temperature_main;
    var str_humidity_main;
    var str_humidity_abs_main;
    var str_dewpoint_main;
    var str_temperature_extern;
    var str_humidity_extern;
    var str_humidity_abs_extern;
    var str_dewpoint_extern;
    var str_gr_scale1;
    var str_gr_scale2;
    var str_meat1;
    var str_meat2;
    var str_meat3;
    var str_meat4; 
    var str_MiSensor_battery;
    
    if (MiSensor_battery == null) {
        str_MiSensor_battery = '----';
    }
    else {
        str_MiSensor_battery = MiSensor_battery.toFixed(2);
    }
    
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
        str_humidity_main = humidity_main.toFixed(1);
    }
    if (dewpoint_main === null) {
        str_dewpoint_main = '-----';
    }
    else {
        str_dewpoint_main = dewpoint_main.toFixed(1);
    }
    if (humidity_abs_main === null) {
        str_humidity_abs_main = '-----';
    }
    else {
        str_humidity_abs_main = humidity_abs_main.toFixed(1);
    }
    if (temperature_extern === null) {
        str_temperature_extern = '-----';
    }
    else {
        str_temperature_extern = temperature_extern.toFixed(1);
    }
    if (humidity_extern === null) {
        str_humidity_extern = '-----';
    }
    else {
        str_humidity_extern = humidity_extern.toFixed(0);
    }
    if (dewpoint_extern === null) {
        str_dewpoint_extern = '-----';
    }
    else {
        str_dewpoint_extern = dewpoint_extern.toFixed(1);
    }
    if (humidity_extern_abs === null) {
        str_humidity_abs_extern = '-----';
    }
    else {
        str_humidity_abs_extern = humidity_extern_abs.toFixed(1);
    }
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
    
    //colouring main values
    if (temperature_main === null){
        color_temp = '#888888';
    } 
    else if (temperature_main < 1){
        color_temp = '#0119F0';
    } else if(temperature_main >=1 && temperature_main < 8){
        color_temp = '#00FAD0';
    }
    else if(temperature_main >=8 && temperature_main < 14){
        color_temp = '#E0D909';
    }
    else if(temperature_main >=14 && temperature_main < 21){
        color_temp = '#FA9E02';
    }
    else if(temperature_main >=21 ){
        color_temp = '#F01A00';
    }
    
    if (humidity_main === null){
        color_hum = '#888888';
    } 
    else if (humidity_main < 45){
        color_hum = '#C87308';
    }
    else if(humidity_main >=45 && humidity_main < 65){
        color_hum = '#AD657C';
    }
    else if(humidity_main >=65 && humidity_main < 75){
        color_hum = '#8D54AF';
    }
    else if(humidity_main >=75 && humidity_main < 90){
        color_hum = '#643fd6';
    }
    else if(humidity_main >=90 ){
        color_hum = '#011EF7';
    }
    
    if (humidity_abs_main === null){
        color_humidity_abs = '#888888';
    } 
    else if (humidity_abs_main < 1){
        color_humidity_abs = '#0119F0';
    } 
    else if(humidity_abs_main >=1 && humidity_abs_main < 8){
        color_humidity_abs = '#00FAD0';
    }
    else if(humidity_abs_main >=8 && humidity_abs_main < 14){
        color_humidity_abs = '#E0D909';
    }
    else if(humidity_abs_main >=14 && humidity_abs_main < 21){
        color_humidity_abs = '#FA9E02';
    }
    else if(humidity_abs_main >=21 ){
        color_humidity_abs = '#F01A00';
    }
    //text-shadow:0 0 5px #888888
    td_styling_pre = '0 0 5px ';


    //------------------------Setzen der Hauptsensorwerte auf der Webseite
    if (str_temperature_main.substring(0,3) == '---' || status_piager == 0 || grepmain == 0) {
        $('#json_temperature_main').html('-----' + " °C");
        $('#json_temperature_main').css('textShadow', td_styling_pre + '#888888');
    }
    else {
        $('#json_temperature_main').fadeOut(500, function(){
            $(this).html(str_temperature_main + " °C").fadeIn(500);
        });
        $('#json_temperature_main').css('textShadow', td_styling_pre + color_temp);
    }
    if (str_humidity_main.substring(0,3) == '---' || status_piager == 0 || grepmain == 0) {
        $('#json_humidity_main').html('-----' + " &#37");
        $('#json_humidity_main').css('textShadow', td_styling_pre + '#888888');
    }
    else {
        $('#json_humidity_main').fadeOut(500, function(){
            $(this).html(str_humidity_main + " &#37").fadeIn(500);
        });
        $('#json_humidity_main').css('textShadow', td_styling_pre + color_hum);
    }
    
    if (str_humidity_abs_main.substring(0,3) == '---' || status_piager == 0 || grepmain == 0) {
        $('#json_hum_abs_main').html('-----' + " g/m³");
        $('#json_hum_abs_main').css('textShadow', td_styling_pre + '#888888');
    }
    else {
        $('#json_hum_abs_main').fadeOut(500, function(){
            $(this).html(str_humidity_abs_main + " g/m³").fadeIn(500);
        });
        $('#json_hum_abs_main').css('textShadow', td_styling_pre + color_humidity_abs);
    }

    //------------------------Setzen der Externsensorwerte auf der Webseite
    if (str_temperature_extern.substring(0,3) == '---' || status_piager == 0 || grepmain == 0) {
        $('#json_temperature_extern').html('-----' + " °C");
    }
    else {
        $('#json_temperature_extern').html(str_temperature_extern + " °C");
    }
    if (str_humidity_extern.substring(0,3) == '---' || status_piager == 0 || grepmain == 0) {
        $('#json_humidity_extern').html('-----' + " &#37");
    }
    else {
        $('#json_humidity_extern').html(str_humidity_extern + " &#37");
    }
    
    if (str_humidity_abs_extern.substring(0,3) == '---' || status_piager == 0 || grepmain == 0) {
        $('#json_hum_abs_extern').html('-----' + " g/m³");
    }
    else {
        $('#json_hum_abs_extern').html(str_humidity_abs_extern + " g/m³");
    }
    if (sensorsecondtype == 6) {
        $('#secondsensorname_id').html('(MiThermometer, battery: ' + str_MiSensor_battery + 'V)');
    }
    
    //------------------------Setzen der Scale1-Werte auf der Webseite
    if (str_gr_scale1.substring(0,3) == '---' || status_scale1 == 0 || grepmain == 0) {
        $('#json_scale1').html('-----' + " g");
    }
    else {
        $('#json_scale1').html(str_gr_scale1 + " g");
    }
    if (str_gr_scale2.substring(0,3) == '---' || status_scale2 == 0 || grepmain == 0) {
        $('#json_scale2').html('-----' + " g");
    }
    else {
        $('#json_scale2').html(str_gr_scale2 + " g");
    }
    
    //------------------------ Setzen der Meat Thermometer Werte

    if (str_meat1_sensorname.substring(0,3) == '---' || status_piager == 0 || grepmain == 0) {
        $('#json_meat_temperature1').html('-----' + " °C");
    }
    else {
        $('#json_meat_temperature1').html(str_meat1 + " °C");
    }
    
    if (str_meat2_sensorname.substring(0,3) == '---' || status_piager == 0 || grepmain == 0) {
        $('#json_meat_temperature2').html('-----' + " °C");
    }
    else {
        $('#json_meat_temperature2').html(str_meat2 + " °C");
    }
    
    if (str_meat3_sensorname.substring(0,3) == '---' || status_piager == 0 || grepmain == 0) {
        $('#json_meat_temperature3').html('-----' + " °C");
    }
    else {
        $('#json_meat_temperature3').html(str_meat3 + " °C");
    }
                            
    if (str_meat4_sensorname.substring(0,3) == '---' || status_piager == 0 || grepmain == 0) {
        $('#json_meat_temperature4').html('-----' + " °C");
    }
    else if (str_meat4_sensorname.substring(0,3) == 'LEM') {
        $('#json_meat_temperature4').html(str_meat4 + " A");
    }
    else {
        $('#json_meat_temperature4').html(temp_meat4.toFixed(1) + " °C");
    }
}

// request current values data every 3s and show them on index.php
async function loadContent() {
    $.ajax({
        method: 'POST',
        url: 'modules/querycv.php'
    })
    .done(function( msg ) {
        if (msg == '') {
            console.log('no data from server');
        }
        else {
            //console.log('First ajax done');
            handleContent(msg);
        }
    })
    .fail(function(xhr, textStatus) {
        console.log( "querycv failed: " + xhr.statusText);
        console.log(textStatus);
    });
    
    return;
}

// timer for page data refresh
var myVar = setInterval(myTimer, 6000);

function myTimer() {
    var loc = location.pathname;
        //console.log('location.pathname :' + loc);
        //if (loc.indexOf('index.php') !== -1)
    if (loc === '/index.php' || loc === '/')
        loadContent();
}

    