//
// process request for current aging table phase showing on index.php
//

async function handleContentat( msg ) {
//    console.log('in handleContentat');
    myObj = JSON.parse(msg);

    maturity_type = myObj.maturity_type;
    grepagingtable = myObj.grepagingtable;
    current_period = myObj.current_period;
    current_period_day = myObj.current_period_day;
    data_modus = myObj.data_modus;
    data_setpoint_humidity = myObj.data_setpoint_humidity;
    data_setpoint_temperature = myObj.data_setpoint_temperature;
    data_circulation_air_duration = myObj.data_circulation_air_duration;   
    data_circulation_air_period = myObj.data_circulation_air_period;    
    data_exhaust_air_duration = myObj.data_exhaust_air_duration;   
    data_exhaust_air_period = myObj.data_exhaust_air_period;   
    data_hours = myObj.data_hours;
    agingtable_comment = myObj.agingtable_comment;
//    console.log('current period:' + current_period + "!");
    
    $('#aging_table_header_id').html(maturity_type);
    $('#current_period_head_index').html(current_period);
    $('#current_period_day_head_index').html(current_period_day);
    $('#current_period_index').html(current_period);
    $('#data_modus_index').html(data_modus);
    $('#data_setpoint_humidity_index').html(data_setpoint_humidity);
    $('#data_setpoint_temperature_index').html(data_setpoint_temperature);
    $('#data_circulation_air_duration_index').html(data_circulation_air_duration);
    $('#data_circulation_air_period_index').html(data_circulation_air_period);
    $('#data_exhaust_air_duration_index').html(data_exhaust_air_duration);
    $('#data_exhaust_air_period_index').html(data_exhaust_air_period);
    $('#data_hours_index').html(data_hours);
    $('#agingtable_comment_with_carriage_return').html(agingtable_comment);
       
}

// request aging table current phase data every 10s and show them on index.php
async function loadContentat() {
    $.ajax({
        method: 'POST',
        url: 'modules/queryat.php'
    })
    .done(function( msg ) {
        handleContentat(msg);
    })
    .fail(function(xhr, textStatus) {
        // show_error(textStatus);
        console.log( "queryat failed: " + xhr.statusText);
        console.log(textStatus);
    });
    
    return;
}

// timer for page data refresh
var myVarat = setInterval(myTimerat, 5000);

function myTimerat() {
    var loc = location.pathname;
        //console.log('location.pathname :' + loc);
        //if (loc.indexOf('index.php') !== -1)
    if (loc === '/index.php' || loc === '/')
        loadContentat();
}
