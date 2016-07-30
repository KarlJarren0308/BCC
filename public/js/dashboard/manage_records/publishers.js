$(document).ready(function() {
    $('#publishers-table').dataTable({
        aoColumnDefs: [
            { bSearchable: false, bSortable: false, aTargets: [1] }
        ]
    });

    validateDataForm('add-publisher-form', {
        publisherName: {
            validators: {
                regexp: {
                    regexp: /^([a-z\s])+$/i,
                    message: 'The value must contain letters only.'
                }
            }
        }
    });

    validateDataForm('edit-publisher-form', {
        publisherName: {
            validators: {
                regexp: {
                    regexp: /^([a-z\s])+$/i,
                    message: 'The value must contain letters only.'
                }
            }
        }
    });
});