$(document).ready(function() {
    var id = '';

    $('#books-table').dataTable({
        aoColumnDefs: [
            { bSearchable: false, bSortable: false, aTargets: [5] }
        ]
    });

    onDataButtonClick('delete-button', function() {
        setModalContent('Loan Status', 'Do you really want to delete this book? This action cannot be undone.<br><br><div class="form-group"><label for="">Reason for Weeding: *</label><input type="text" class="form-control" maxlength="1000" id="reason-field"></div>', '<button class="btn btn-danger" data-button="yes-button">Yes</button>&nbsp;<button class="btn btn-default" data-button="no-button">No</button>');
        openModal('static');

        id = $(this).data('var-id');
    });

    onDynamicDataButtonClick('yes-button', function() {
        var reason = $('#reason-field').val();

        if(reason != '') {
            setModalLoader();

            $.ajax({
                url: '/delete_record/books/' + id,
                method: 'GET',
                data: {
                    reason: reason
                },
                dataType: 'json',
                success: function(response) {}
            });
        } else {
            $('#reason-field').focus();
        }

        return false;
    });

    onDynamicDataButtonClick('no-button', function() {
        closeModal();
    });
});