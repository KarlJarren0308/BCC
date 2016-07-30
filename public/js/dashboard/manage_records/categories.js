$(document).ready(function() {
    $('#categories-table').dataTable({
        aoColumnDefs: [
            { bSearchable: false, bSortable: false, aTargets: [1] }
        ]
    });

    validateDataForm('edit-publisher-form', {
        firstName: {
            validators: {
                regexp: {
                    regexp: /^([a-z\s])+$/i,
                    message: 'The value must contain letters only.'
                }
            }
        }
    });

    validateDataForm('add-category-form', {
        categoryName: {
            validators: {
                regexp: {
                    regexp: /^([a-z\s])+$/i,
                    message: 'The value must contain letters only.'
                }
            }
        }
    });

    validateDataForm('edit-category-form', {
        categoryName: {
            validators: {
                regexp: {
                    regexp: /^([a-z\s])+$/i,
                    message: 'The value must contain letters only.'
                }
            }
        }
    });
});