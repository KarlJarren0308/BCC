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

    onDataButtonClick('delete-button', function() {
        setModalContent('Manage Accession Numbers', 'Do you really want to delete this copy of book? This action cannot be undone.<br><br><div class="form-group"><label for="">Reason for Weeding: *</label><input type="text" class="form-control" maxlength="1000" id="reason-field"></div>', '<button class="btn btn-danger" data-button="yes-button">Yes</button>&nbsp;<button class="btn btn-default" data-button="no-button">No</button>');
        openModal('static');

        id = $(this).data('var-id');
        accession = $(this).data('var-accession');
    });

    onDynamicDataButtonClick('yes-button', function() {
        var reason = $('#reason-field').val();

        if(reason != '') {
            setModalLoader();

            $.ajax({
                url: '/delete_record/barcodes/' + id,
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    accession: accession,
                    reason: reason
                },
                dataType: 'json',
                success: function(response) {
                    setModalContent('Weeding Status', response['message'], '');
                }
            });
        } else {
            $('#reason-field').focus();
        }

        return false;
    });

    onDynamicDataButtonClick('no-button', function() {
        closeModal();
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