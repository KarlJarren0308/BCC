$(document).ready(function() {
    $('#books-table').dataTable({
        aoColumnDefs: [
            { bSearchable: false, bSortable: false, aTargets: [5] }
        ]
    });

    onDataButtonClick('loan-book-button', function() {
        var dataVarID = $(this).data('var-id');
        
        setModalLoader();
        openModal();

        $.ajax({
            url: '',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: {
                id: dataVarID
            },
            dataType: 'json',
            success: function(response) {
                setModalContent('Reserved Book', response['message'], '');

                setTimeout(function() {
                    closeModal();

                    location.reload();
                }, 2000);
            },
            error: function(arg0, arg1, arg2) {
                console.log(arg0.responseText);
            }
        });

        return false;
    });
});