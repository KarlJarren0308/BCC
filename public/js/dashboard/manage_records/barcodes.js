$(document).ready(function() {
    $('#books-table').dataTable({
        aoColumnDefs: [
            { bSearchable: false, bSortable: false, aTargets: [0] },
            { bSearchable: false, bSortable: false, aTargets: [2] }
        ]
    });

    onDataButtonClick('add-button', function() {
        setModalContent('Add Accession Number', '<form data-form="add-barcode-form"><input type="hidden" name="id" value="' + $(this).data('var-id') + '"><div class="form-group"><label for="">Book Title:</label><input type="text" class="form-control" value="' + $(this).data('var-title') + '" disabled></div><div class="form-group"><label for="numberOfCopies">Number of Copies to be Added:</label><input type="number" min="1" name="numberOfCopies" id="numberOfCopies" class="form-control" value="1"></div><div class="form-group text-right no-margin"><input type="submit" class="btn btn-primary" value="Add Accession Number"></div></form>', '');
        openModal();
    });

    onDynamicDataFormSubmit('add-barcode-form', function() {
        var serializedForm = $(this).serialize();

        setModalLoader();

        $.ajax({
            url: '/generate_barcode',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: serializedForm,
            dataType: 'json',
            success: function(response) {
                setModalContent('Add Accession Number Status', response['message'], '');

                setTimeout(function() {
                    closeModal();

                    location.reload();
                }, 2000);
            }
        });

        return false;
    });
});