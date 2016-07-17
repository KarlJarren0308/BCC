$(document).ready(function() {
    onDataFormSubmit('search-form', function() {
        if($(window).width() > 768) {
            $('.presentation-header').animate({
                'position': 'relative',
                'top': '76px',
                'left': '250px',
                'margin-top': '0',
                'margin-left': '0'
            }, 250);

            $('.presentation-header > form').animate({
                'position': 'relative',
                'margin-left': '-225px'
            }, 250);
        }

        $('.presentation-loader').fadeIn(250);

        $.ajax({
            url: '/opac/search',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                var output = '';

                output += '<table class="table table-bordered table-striped">';
                output += '<thead>';
                output += '<tr>';
                output += '<th>Call Number</th>';
                output += '<th>Title</th>';
                output += '<th>Edition</th>';
                output += '<th>Copyright Year</th>';
                output += '<th>Author(s)</th>';
                output += '<th width="15%"></th>';
                output += '</tr>';
                output += '</thead>';
                output += '<tbody>';

                var isFirst;
                var books = response['data']['books'];
                var bounds = response['data']['bounds'];

                for(var i = 0; i < books.length; i++) {
                    output += '<tr>';
                    output += '<td>' + books[i]['Call_Number'] + '</td>';
                    output += '<td>' + books[i]['Title'] + '</td>';
                    output += '<td>' + books[i]['Edition'] + '</td>';
                    output += '<td>' + books[i]['Copyright_Year'] + '</td>';
                    output += '<td>';

                    isFirst = true;

                    for(var j = 0; j < bounds.length; j++) {
                        if(bounds[j]['Book_ID'] == books[i]['Book_ID']) {
                            if(isFirst) {
                                isFirst = false;
                            } else {
                                output += '<br>';
                            }

                            if(bounds[j]['Middle_Name'].length > 1) {
                                output += bounds[j]['First_Name'] + ' ' + bounds[j]['Middle_Name'].substring(0, 1) + '. ' + bounds[j]['Last_Name'];
                            } else {
                                output += bounds[j]['First_Name'] + ' ' + bounds[j]['Last_Name'];
                            }
                        }
                    }

                    output += '</td>';
                    output += '<td class="text-center">';
                    output += '<button data-button="view-button" data-var-id="' + books[i]['Book_ID'] + '" class="btn btn-primary btn-xs">View Info</button>&nbsp;<button data-button="reserve-button" data-var-id="' + books[i]['Book_ID'] + '" class="btn btn-danger btn-xs">Reserve</button>';
                    output += '</td>';
                    output += '</tr>';
                }

                output += '</tbody>';
                output += '</table>';

                $('.presentation-loader').fadeOut(250).promise().done(function() {
                    $('#presentation-content').html(output);
                });
            },
            error: function(arg0, arg1, arg2) {
                console.log(arg0)
            }
        });

        return false;
    });

    onDynamicDataButtonClick('view-button', function() {
        setModalLoader();
        openModal();

        $.ajax({
            url: '/data/d4cf32e8303053a4d7ba0f0859297f83',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: {
                id: $(this).data('var-id')
            },
            dataType: 'json',
            success: function(response) {
                var info = '';

                if(response['status'] == 'Success') {
                    info = '<table class="table table-bordered table-striped"><tbody>';
                    info += '<tr><td class="text-right" width="25%">Call Number:</td><td>' + response['data']['book']['Call_Number'] + '</td></tr>';
                    info += '<tr><td class="text-right" width="25%">Title:</td><td>' + response['data']['book']['Title'] + '</td></tr>';
                    info += '<tr><td class="text-right" width="25%">Edition:</td><td>' + response['data']['book']['Edition'] + '</td></tr>';
                    info += '<tr><td class="text-right" width="25%">Collection Type:</td><td>' + response['data']['book']['Collection_Type'] + '</td></tr>';
                    info += '<tr><td class="text-right" width="25%">ISBN:</td><td>' + response['data']['book']['ISBN'] + '</td></tr>';
                    info += '<tr><td class="text-right" width="25%">Author(s):</td><td>';

                    for(var i = 0; i < response['data']['authors'].length; i++) {
                        if(response['data']['authors'][i]['Middle_Name'].length > 1) {
                            info += response['data']['authors'][i]['First_Name'] + ' ' + response['data']['authors'][i]['Middle_Name'].substring(0, 1) + '. ' + response['data']['authors'][i]['Last_Name'];
                        } else {
                            info += response['data']['authors'][i]['First_Name'] + ' ' + response['data']['authors'][i]['Last_Name'];
                        }

                        info += '<br>';
                    }

                    info += '</td></tr>';
                    info += '</tbody></table>';
                } else {
                    info = response['message'];
                }

                setModalContent('View Book Information', info, '');
            }
        });

        return false;
    });

    onDynamicDataButtonClick('reserve-button', function() {
        setModalLoader();
        openModal('static');

        $.ajax({
            url: 'opac/reserve',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: {
                id: $(this).data('var-id')
            },
            dataType: 'json',
            success: function(response) {
                setModalContent('Reservation Status', response['message'], '');

                setTimeout(function() {
                    closeModal();
                }, 2000);
            }
        });

        return false;
    });
});