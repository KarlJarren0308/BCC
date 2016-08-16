$(document).ready(function() {
    $('#holidays-table').dataTable({
        aoColumnDefs: [
            { bSearchable: false, bSortable: false, aTargets: [3] }
        ]
    });

    validateDataForm('add-holiday-form', {
        holidayDescription: {
            validators: {
                regexp: {
                    regexp: /^([a-z0-9,.-\s])+$/i,
                    message: 'The value must contain letters, numbers and the following symbol(s) only: ,.-'
                }
            }
        }
    });

    validateDataForm('edit-holiday-form', {
        holidayDescription: {
            validators: {
                regexp: {
                    regexp: /^([a-z0-9,.-\s])+$/i,
                    message: 'The value must contain letters, numbers and the following symbol(s) only: ,.-'
                }
            }
        }
    });
});