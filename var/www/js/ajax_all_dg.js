//
// process request for current values showing on index.php
//

function convert_timestamps( timestamps_seconds ) {
    // convert timestamps array from seconds to js Date strings array
    const timestamps_js = [];
    for (const el of timestamps_seconds) {
        dt = new Date(el * 1000);
        timestamps_js.push(dt);
    }
    return timestamps_js;    
}

async function handleContentAlldg( msg ) {
    console.log('in handleContentAlldg');
    
    myObj = JSON.parse(msg);
    
    diagram_time = myObj.diagram_time;
//    console.log('diagram_time : ' + diagram_time);
//    let dummy_val = 0;
//    if (dummy_val == 0)
//     return;
    
    if (diagram_time <= 3600) {
        temp_hum_chart.data.datasets[0].pointRadius = 1;
        temp_hum_chart.data.datasets[1].pointRadius = 1;
        temp_hum_chart.data.datasets[2].pointRadius = 1;
        temp_hum_chart.data.datasets[3].pointRadius = 1;
        scales_chart.data.datasets[0].pointRadius = 1;
        scales_chart.data.datasets[1].pointRadius = 1;
        dewpoint_humidity_chart.data.datasets[0].pointRadius = 1;
        dewpoint_humidity_chart.data.datasets[1].pointRadius = 1;
        dewpoint_humidity_chart.data.datasets[2].pointRadius = 1;
        dewpoint_humidity_chart.data.datasets[3].pointRadius = 1;
        thermometers_chart.data.datasets[0].pointRadius = 1;
        thermometers_chart.data.datasets[1].pointRadius = 1;
        thermometers_chart.data.datasets[2].pointRadius = 1;
        thermometers_chart.data.datasets[3].pointRadius = 1;
    }
    else {
        temp_hum_chart.data.datasets[0].pointRadius = 0;
        temp_hum_chart.data.datasets[1].pointRadius = 0;
        temp_hum_chart.data.datasets[2].pointRadius = 0;
        temp_hum_chart.data.datasets[3].pointRadius = 0;
        scales_chart.data.datasets[0].pointRadius = 0;
        scales_chart.data.datasets[1].pointRadius = 0;
        dewpoint_humidity_chart.data.datasets[0].pointRadius = 0;
        dewpoint_humidity_chart.data.datasets[1].pointRadius = 0;
        dewpoint_humidity_chart.data.datasets[2].pointRadius = 0;
        dewpoint_humidity_chart.data.datasets[3].pointRadius = 0;
        thermometers_chart.data.datasets[0].pointRadius = 0;
        thermometers_chart.data.datasets[1].pointRadius = 0;
        thermometers_chart.data.datasets[2].pointRadius = 0;
        thermometers_chart.data.datasets[3].pointRadius = 0;
    }
    
    temp_timestamps_seconds = myObj.all_sensors_timestamps_axis;
    scale_timestamps_seconds = myObj.all_scales_timestamps_axis;  // seconds since 1970
    // on_off charts time axis
    timestamps_light_seconds = myObj.light_timestamps_axis;
    timestamps_uv_light_seconds = myObj.uv_light_timestamps_axis;
    timestamps_heater_seconds = myObj.heater_timestamps_axis;                                  
    timestamps_cooler_seconds = myObj.cooler_timestamps_axis;
    timestamps_humidifier_seconds = myObj.humidifier_timestamps_axis;
    timestamps_dehumidifier_seconds = myObj.dehumidifier_timestamps_axis;
    timestamps_exhaust_air_seconds = myObj.exhaust_air_timestamps_axis;
    timestamps_circulate_air_seconds = myObj.circulate_air_timestamps_axis;
       
    temp_timestamps_js = convert_timestamps(temp_timestamps_seconds);
    scale_timestamps_js = convert_timestamps(scale_timestamps_seconds);
  
    timestamps_light_js = convert_timestamps( timestamps_light_seconds );
    timestamps_uv_light_js = convert_timestamps( timestamps_uv_light_seconds );
    timestamps_heater_js = convert_timestamps( timestamps_heater_seconds );
    timestamps_cooler_js = convert_timestamps( timestamps_cooler_seconds );
    timestamps_humidifier_js = convert_timestamps( timestamps_humidifier_seconds );
    timestamps_dehumidifier_js = convert_timestamps( timestamps_dehumidifier_seconds );
    timestamps_exhaust_air_js = convert_timestamps( timestamps_exhaust_air_seconds );
    timestamps_circulate_air_js = convert_timestamps( timestamps_circulate_air_seconds );

    temp_hum_chart.data.labels = temp_timestamps_js;
    temp_hum_chart.data.datasets[0].data = myObj.temperature_dataset;
    temp_hum_chart.data.datasets[1].data = myObj.extern_temperature_dataset;
    temp_hum_chart.data.datasets[2].data = myObj.humidity_dataset;
    temp_hum_chart.data.datasets[3].data = myObj.extern_humidity_dataset;

    dewpoint_humidity_chart.data.labels = temp_timestamps_js;
    dewpoint_humidity_chart.data.datasets[0].data = myObj.dewpoint_dataset;
    dewpoint_humidity_chart.data.datasets[1].data = myObj.extern_dewpoint_dataset;
    dewpoint_humidity_chart.data.datasets[2].data = myObj.humidity_abs_dataset;
    dewpoint_humidity_chart.data.datasets[3].data = myObj.extern_humidity_abs_dataset;
    
    thermometers_chart.data.labels = temp_timestamps_js;
    thermometers_chart.data.datasets[0].data = myObj.thermometer1_dataset;
    thermometers_chart.data.datasets[1].data = myObj.thermometer2_dataset;
    thermometers_chart.data.datasets[2].data = myObj.thermometer3_dataset;
    thermometers_chart.data.datasets[3].data = myObj.thermometer4_dataset;
   
    scales_chart.data.labels = scale_timestamps_js;
    scales_chart.data.datasets[0].data = myObj.scale1_dataset;
    scales_chart.data.datasets[1].data = myObj.scale2_dataset;

    light_chart.data.labels = timestamps_light_js;
    light_chart.data.datasets[0].data = myObj.light_dataset;

    uv_light_chart.data.labels = timestamps_uv_light_js;
    uv_light_chart.data.datasets[0].data = myObj.uv_light_dataset;

    heater_chart.data.labels = timestamps_heater_js;
    heater_chart.data.datasets[0].data = myObj.heater_dataset;

    cooler_chart.data.labels = timestamps_cooler_js;
    cooler_chart.data.datasets[0].data = myObj.cooler_dataset;

    humidifier_chart.data.labels = timestamps_humidifier_js;
    humidifier_chart.data.datasets[0].data = myObj.humidifier_dataset;

    dehumidifier_chart.data.labels = timestamps_dehumidifier_js;
    dehumidifier_chart.data.datasets[0].data = myObj.dehumidifier_dataset;
    
    exhaust_air_chart.data.labels = timestamps_exhaust_air_js;
    exhaust_air_chart.data.datasets[0].data = myObj.exhaust_air_dataset;
    
    circulation_air_chart.data.labels = timestamps_circulate_air_js;
    circulation_air_chart.data.datasets[0].data = myObj.circulate_air_dataset;

    // update all charts
    scales_chart.update(); 
    temp_hum_chart.update();
    dewpoint_humidity_chart.update();    
    thermometers_chart.update(); 
    light_chart.update();
    uv_light_chart.update();
    heater_chart.update();
    cooler_chart.update();
    humidifier_chart.update();
    dehumidifier_chart.update();
    exhaust_air_chart.update();
    circulation_air_chart.update();

    console.log('charts updated');
}


// request diagram data every 10s and show them on index.php
async function loadContentAlldg() {
    $.ajax({
        method: 'POST',
        url: 'modules/query_all_dg.php'
    })
    .done(function( msg ) {
        if (msg == '') {
            console.log('no data from server');
        }
        else {
//            console.log(msg);
            handleContentAlldg(msg);
        }
    })
    .fail(function(xhr, textStatus) {
        console.log( "query_all_dg failed: " + xhr.statusText);
        console.log(textStatus);
    });
    
    return;
}

// timer for page data refresh
var myVarAlldg = setInterval(myTimerAlldg, 5000);

function myTimerAlldg() {
    var loc = location.pathname;
        //console.log('location.pathname :' + loc);
        //if (loc.indexOf('index.php') !== -1)
    if (loc === '/diagrams.php')
        loadContentAlldg();
}
