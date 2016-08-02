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
            dataType: 'json',
            success: function(response) {
                setModalContent('Reserved Book', '', '');
            }
        });

        return false;
    });
});