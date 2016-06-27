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
            url: 'data/d4cf32e8303053a4d7ba0f0859297f83',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: {
                id: $(this).data('var-id')
            },
            dataType: 'json',
            success: function(response) {
                var info = '';

                info = '<table class="table table-bordered table-striped"><tbody>';
                info += '<tr><td class="text-right" width="25%">Call Number:</td><td>' + response['data']['Call_Number'] + '</td></tr>';
                info += '<tr><td class="text-right" width="25%">Title:</td><td>' + response['data']['Title'] + '</td></tr>';
                info += '<tr><td class="text-right" width="25%">Edition:</td><td>' + response['data']['Edition'] + '</td></tr>';
                info += '<tr><td class="text-right" width="25%">Collection Type:</td><td>' + response['data']['Collection_Type'] + '</td></tr>';
                info += '<tr><td class="text-right" width="25%">ISBN:</td><td>' + response['data']['ISBN'] + '</td></tr>';
                info += '</tbody></table>';

                setModalContent('View Book Information', info, '');
            }
        });

        return false;
    });
});