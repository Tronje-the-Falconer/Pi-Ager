//
// process request for current spi-ager status showing on index.php
//

async function handleContentStatus( msg ) {
    console.log('in handleContentStatus');
    var myObj = JSON.parse(msg);
    
    var grepmain = myObj.grepmain;
    var status_piager = myObj.status_piager;
    var grepagingtable = myObj.grepagingtable;
    
    // general 
    if (grepmain == 0) {
        $('#mainstatus_img_onoff').attr('src', 'images/icons/status_off_20x20.png');
        $('#mainstatus_img_mode').attr('src', 'images/icons/operatingmode_fail_42x42.png');
    }
    else if (grepmain != 0 && status_piager == 0) {
        $('#mainstatus_img_onoff').attr('src', 'images/icons/status_off_20x20.png');
        $('#mainstatus_img_mode').attr('src', 'images/icons/operatingmode_42x42.png');
    }
    else {
        $('#mainstatus_img_onoff').attr('src', 'images/icons/status_on_20x20.png');
        $('#mainstatus_img_mode').attr('src', 'images/icons/operating_42x42.gif');
    }
    
    $('#gpio_voltage_img').attr('src', myObj.powersupply_img);
    $('#gpio_voltage_text').html(myObj.powersupply_text);    
    $('#gpio_voltage_text').css('color', myObj.powersupply_text_color);
    
    var main_status_text = $('#main_status_text').html();
    $('#main_status_text').html(myObj.main_status_text);

    $('#gpio_battery_img').attr('src', myObj.battery_img);
    $('#gpio_battery_text').html(myObj.battery_text);    
    $('#gpio_battery_text').css('color', myObj.battery_text_color);
    
    if (grepmain == 0) {
        $('#agingtable_img').attr('src', 'images/icons/agingtable_fail_42x42.gif');
        $('#agingtable_img_status').attr('src', 'images/icons/status_off_20x20.png');
    }
    else if (grepagingtable == 0) {
        $('#agingtable_img').attr('src', 'images/icons/agingtable_42x42.png');
        $('#agingtable_img_status').attr('src', 'images/icons/status_off_20x20.png');
    }
    else {
        $('#agingtable_img').attr('src', 'images/icons/agingtable_42x42.gif');
        $('#agingtable_img_status').attr('src', 'images/icons/status_on_20x20.png');
    }        

    $('#maturity_type_id').html(myObj.maturity_type); 
    
    $('#switch_img').attr('src', myObj.switch_img);
    $('#switch_text').html(myObj.switch_text);
    
    $('#defrost_img').attr('src', myObj.defrost_img);
    $('#defrost_text').html(myObj.defrost_text);
    
    var modus = myObj.modus;
    // temperatures
    $('#mod_type_line1_id').attr('src', myObj.mod_type_line1);
    $('#mod_stat_line1_id').attr('src', myObj.mod_stat_line1);
    $('#mod_name_line1_id').html(myObj.mod_name_line1);
    $('#mod_current_line1_id').html(myObj.mod_current_line1 + ' °C');
    $('#mod_setpoint_line1_id').html(myObj.mod_setpoint_line1 + ' °C');
    $('#mod_on_line1_id').html(myObj.mod_on_line1 + ' °C');
    $('#mod_off_line1_id').html(myObj.mod_off_line1 + ' °C');

    if (modus == 3 || modus == 4) {
        $('#mod_type_line2_id').attr('src', myObj.mod_type_line2);
        $('#mod_stat_line2_id').attr('src', myObj.mod_stat_line2);
        $('#mod_name_line2_id').html(myObj.mod_name_line2);
        $('#mod_current_line2_id').html(myObj.mod_current_line2 + ' °C');
        $('#mod_setpoint_line2_id').html(myObj.mod_setpoint_line2 + ' °C');
        $('#mod_on_line2_id').html(myObj.mod_on_line2 + ' °C');
        $('#mod_off_line2_id').html(myObj.mod_off_line2 + ' °C');
    }
    
    $('#mod_type_line3_id').attr('src', myObj.mod_type_line3);
    $('#mod_stat_line3_id').attr('src', myObj.mod_stat_line3);
    $('#mod_name_line3_id').html(myObj.mod_name_line3);
    $('#mod_current_line3_id').html(myObj.mod_current_line3 + ' %');
    $('#mod_setpoint_line3_id').html(myObj.mod_setpoint_line3 + ' %');
    $('#mod_on_line3_id').html(myObj.mod_on_line3 + ' %');
    $('#mod_off_line3_id').html(myObj.mod_off_line3 + ' %');
    
    if (modus == 4) {
        $('#mod_type_line4_id').attr('src', myObj.mod_type_line4);
        $('#mod_stat_line4_id').attr('src', myObj.mod_stat_line4);
        $('#mod_name_line4_id').html(myObj.mod_name_line4);
        $('#mod_current_line4_id').html(myObj.mod_current_line4 + ' %');
        $('#mod_setpoint_line4_id').html(myObj.mod_setpoint_line4 + ' %');
        $('#mod_on_line4_id').html(myObj.mod_on_line4 + ' %');
        $('#mod_off_line4_id').html(myObj.mod_off_line4 + ' %');
        
        $('#mod_type_line5_id').attr('src', myObj.mod_type_line5);
        $('#mod_stat_line5_id').attr('src', myObj.mod_stat_line5);
        $('#mod_name_line5_id').html(myObj.mod_name_line5);
        $('#mod_current_line5_id').html(myObj.mod_current_line5 + ' %');
        $('#mod_setpoint_line5_id').html(myObj.mod_setpoint_line5 + ' %');
        $('#mod_on_line5_id').html(myObj.mod_on_line5 + ' %');
        $('#mod_off_line5_id').html(myObj.mod_off_line5 + ' %');
        
        $('#mod_stat_line6_id').attr('src', myObj.mod_stat_line6);
        $('#mod_name_line6_id').html(myObj.mod_name_line6);
    }

    // timer circulation air
    $('#timer_type_line1_id').attr('class', myObj.circulation_air_duration_class);
    $('#timer_stat_line1_id').attr('src', myObj.circulating_on_off_png);    
    $('#timer_name_line1_id').html(myObj.timer_name_line1);
    $('#timer_period_line1_id').html(myObj.timer_period_line1);
    $('#timer_dur_line1_id').html(myObj.timer_dur_line1);    
    
    // timer exhausting air
    $('#timer_type_line2_id').attr('class', myObj.exhausting_air_duration_class);
    $('#timer_stat_line2_id').attr('src', myObj.exhausting_on_off_png);    
    $('#timer_name_line2_id').html(myObj.timer_name_line2);
    $('#timer_period_line2_id').html(myObj.timer_period_line2);
    $('#timer_dur_line2_id').html(myObj.timer_dur_line2);
    
    // timer uv-light
    $('#timer_type_line3_id').attr('class', myObj.uv_duration_class);
    $('#timer_stat_line3_id').attr('src', myObj.timer_stat_line3);    
    $('#timer_name_line3_id').html(myObj.timer_name_line3);
    $('#timer_period_line3_id').html(myObj.timer_period_line3);
    $('#timer_dur_line3_id').html(myObj.timer_dur_line3);    

    // timer light
    $('#timer_type_line4_id').attr('class', myObj.light_duration_class);
    $('#timer_stat_line4_id').attr('src', myObj.timer_stat_line4);    
    $('#timer_name_line4_id').html(myObj.timer_name_line4);
    $('#timer_period_line4_id').html(myObj.timer_period_line4);
    $('#timer_dur_line4_id').html(myObj.timer_dur_line4);    

    // scales
    if ($('#scale1_img_id').attr('src').localeCompare(myObj.scale1_img_id) != 0) {
        $('#scale1_img_id').attr('src', myObj.scale1_img_id);
    }
    $('#scale1_onoff_status_id').attr('src', myObj.scale1_onoff_status_id);    
    $('#scale1_status_text_id').html(myObj.scale1_status_text_id);  
    
    if ($('#scale2_img_id').attr('src').localeCompare(myObj.scale2_img_id) != 0) {
        $('#scale2_img_id').attr('src', myObj.scale2_img_id);
    }
    $('#scale2_onoff_status_id').attr('src', myObj.scale2_onoff_status_id);    
    $('#scale2_status_text_id').html(myObj.scale2_status_text_id); 

    if (main_status_text.localeCompare( myObj.main_status_text ) != 0) {   // refresh when mode changed
        window.location.href = "index.php"; 
    }
}

// request current pi-ager status and show it on index.php
async function loadContentStatus() {
    $.ajax({
        method: 'POST',
        url: 'modules/querystatus.php'
    })
    .done(function( msg ) {
        handleContentStatus(msg);
    })
    .fail(function(xhr, textStatus) {
        // show_error(textStatus);
        console.log( "querystatus failed: " + xhr.statusText);
        console.log(textStatus);
    });
    
    return;
}

// timer for page data refresh
var myVarStatus = setInterval(myTimerStatus, 5000);

function myTimerStatus() {
    var loc = location.pathname;
        //console.log('location.pathname :' + loc);
        //if (loc.indexOf('index.php') !== -1)
    if (loc === '/index.php' || loc === '/')
        loadContentStatus();
}
