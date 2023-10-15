//
// process request for current server date and time in index.php
//

async function handleContentDateTime( msg ) {
    myObj = JSON.parse(msg);

    server_date_time = myObj.server_date_time;
    server_ip = myObj.server_ip;
    
    $('#server_date_time_id').html(server_date_time);
    $('#server_ip_id').html(server_ip);
}

// request server date and time and show them on index.php
async function loadContentDateTime() {
    $.ajax({
        method: 'POST',
        url: 'modules/querydatetime.php'
    })
    .done(function( msg ) {
        handleContentDateTime(msg);
    })
    .fail(function(xhr, textStatus) {
        // show_error(textStatus);
        console.log( "querydatetime failed: " + xhr.statusText);
        console.log(textStatus);
    });
    
    return;
}

// timer for data refresh
setInterval(myDateTimeTimer, 5000); // every 5 seconds

function myDateTimeTimer() {
    loadContentDateTime();
}
