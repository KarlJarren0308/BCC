$(document).ready(function() {
    $('#books-table').dataTable({
        aoColumnDefs: [
            { bSearchable: false, bSortable: false, aTargets: [5] }
        ]
    });

    onDataButtonClick('loan-book-button', function() {
        setModalLoader();
        openModal();

        var dataVarID = $(this).data('var-id');
        var dataVarTitle = $(this).data('var-title');

        $.ajax({
            url: '/data/07489691941dcd1830a96d9f61121278',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: 'json',
            success: function(response) {
                var info = '<option value="" selected disabled>Select an option...</option>';
                var name = '';

                for(var i = 0; i < response['data'].length; i++) {
                    if(response['data'][i]['Middle_Name'].length > 1) {
                        name = response['data'][i]['First_Name'] + ' ' + response['data'][i]['Middle_Name'].substring(0, 1) + '. ' + response['data'][i]['Last_Name'];
                    } else {
                        name = response['data'][i]['First_Name'] + ' ' + response['data'][i]['Last_Name'];
                    }

                    info += '<option value="' + response['data'][i]['Username'] + '">' + name + '</option>';
                }

                setModalContent('Loan Book', '<form data-form="loan-form"><input type="hidden" name="id" value="' + dataVarID + '"><div class="form-group"><label for="">Book Title:</label><input type="text" class="form-control" value="' + dataVarTitle + '" disabled></div><div class="form-group"><label for="borrower">Borrower:</label><select name="borrower" id="borrower" class="form-control" required>' + info + '</select></div><div class="form-group text-right no-margin"><input type="submit" class="btn btn-primary" value="Loan Book"></div></form>', '');
            }
        });

        return false;
    });

    onDynamicDataFormSubmit('loan-form', function() {
        var serializedForm = $(this).serialize();

        setModalLoader();

        $.ajax({
            url: '/loan_books',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: serializedForm,
            dataType: 'json',
            success: function(response) {
                setModalContent('Loan Book Status', response['message'], '');

                setTimeout(function() {
                    closeModal();

                    location.reload();
                }, 2000);
            }
        });

        return false;
    });
});