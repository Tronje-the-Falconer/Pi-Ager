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

async function handleContentdg( msg ) {
    console.log('in handleContentdg');
    myObj = JSON.parse(msg);
    
    customtime = myObj.customtime;
//    console.log('customtime : ' + customtime);
    
    if (customtime <= 3600) {
        temp_hum_chart.data.datasets[0].pointRadius = 1;
        temp_hum_chart.data.datasets[1].pointRadius = 1;
        temp_hum_chart.data.datasets[2].pointRadius = 1;
        temp_hum_chart.data.datasets[3].pointRadius = 1;
        temp_hum_chart.data.datasets[4].pointRadius = 1;
        scales_chart.data.datasets[0].pointRadius = 1;
        scales_chart.data.datasets[1].pointRadius = 1;
    }
    else {
        temp_hum_chart.data.datasets[0].pointRadius = 0;
        temp_hum_chart.data.datasets[1].pointRadius = 0;
        temp_hum_chart.data.datasets[2].pointRadius = 0;
        temp_hum_chart.data.datasets[3].pointRadius = 0;
        temp_hum_chart.data.datasets[4].pointRadius = 0;
        scales_chart.data.datasets[0].pointRadius = 0;
        scales_chart.data.datasets[1].pointRadius = 0;
    }
    
    temp_timestamps_seconds = myObj.all_sensors_timestamps_axis;
    scale_timestamps_seconds = myObj.all_scales_timestamps_axis;  // seconds since 1970

    temp_timestamps_js = convert_timestamps(temp_timestamps_seconds);
    scale_timestamps_js = convert_timestamps(scale_timestamps_seconds);

    temp_hum_chart.data.labels = temp_timestamps_js;
    temp_hum_chart.data.datasets[0].data = myObj.temperature_dataset;
    temp_hum_chart.data.datasets[1].data = myObj.temperature_avg_dataset;
    temp_hum_chart.data.datasets[2].data = myObj.thermometer1_dataset;
    temp_hum_chart.data.datasets[3].data = myObj.humidity_dataset;
    temp_hum_chart.data.datasets[4].data = myObj.humidity_avg_dataset;
    
    scales_chart.data.labels = scale_timestamps_js;
    scales_chart.data.datasets[0].data = myObj.scale1_dataset;
    scales_chart.data.datasets[1].data = myObj.scale2_dataset;
    
    scales_chart.update(); 
    temp_hum_chart.update();
    console.log('charts updated');
}


// request diagram data every 10s and show them on index.php
async function loadContentdg() {
    $.ajax({
        method: 'POST',
        url: 'modules/querydg.php'
    })
    .done(function( msg ) {
        if (msg == '') {
            console.log('no data from server');
        }
        else {
            handleContentdg(msg);
        }
    })
    .fail(function(xhr, textStatus) {
        console.log( "querydg failed: " + xhr.statusText);
        console.log(textStatus);
    });
    
    return;
}

// timer for page data refresh
var myVardg = setInterval(myTimerdg, 10000);

function myTimerdg() {
    var loc = location.pathname;
        //console.log('location.pathname :' + loc);
        //if (loc.indexOf('index.php') !== -1)
    if (loc === '/index.php' || loc === '/')
        loadContentdg();
}
