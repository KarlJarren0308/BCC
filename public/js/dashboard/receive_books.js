$(document).ready(function() {
    $('#books-table').dataTable({
        aoColumnDefs: [
            { bSearchable: false, bSortable: false, aTargets: [7] },
            { bVisible: false, aTargets: [8] }
        ]
    });

    onDynamicDataButtonClick('receive-book-button', function() {
        var dataVarID = $(this).data('var-id');
        var dataVarPenalty = $(this).data('var-penalty');

        setModalLoader();
        openModal('static');

        $.ajax({
            url: '/receive_books',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: {
                id: dataVarID,
                penalty: dataVarPenalty
            },
            dataType: 'json',
            success: function(response) {
                setModalContent('Receive Book', response['message'], '');

                setTimeout(function() {
                    closeModal();

                    location.reload();
                }, 2000);
            }
        });

        return false;
    });
});