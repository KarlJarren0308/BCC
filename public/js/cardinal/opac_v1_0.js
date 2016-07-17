$(document).ready(function() {
    $('#books-table').dataTable({
        aoColumnDefs: [
            { bSearchable: false, bSortable: false, aTargets: [5] }
        ]
    });

    onDataButtonClick('view-button', function() {
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

    onDataButtonClick('reserve-button', function() {
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

                    location.reload();
                }, 2000);
            }
        });

        return false;
    });
});