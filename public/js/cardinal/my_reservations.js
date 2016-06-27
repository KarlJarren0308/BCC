$(document).ready(function() {
    $('#reservations-table').dataTable({
        aoColumnDefs: [
            { bSearchable: false, bSortable: false, aTargets: [6] }
        ]
    });

    onDataButtonClick('cancel-reservation-button', function() {
        setModalLoader();
        openModal('static');

        $.ajax({
            url: 'opac/cancel_reservation',
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