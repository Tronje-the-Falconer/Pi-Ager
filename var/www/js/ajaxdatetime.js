//
// process request for current server date and time in index.php
//

async function handleContentDateTime( msg ) {
    myObj = JSON.parse(msg);

    server_date_time = myObj.server_date_time;
    $('#server_date_time_id').html(server_date_time);
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
setInterval(myDateTimeTimer, 1000); // every second

function myDateTimeTimer() {
    loadContentDateTime();
}
