$(document).ready(function() {
    $('#authors-table').dataTable({
        aoColumnDefs: [
            { bSearchable: false, bSortable: false, aTargets: [1] }
        ]
    });

    validateDataForm('add-author-form', {
        firstName: {
            validators: {
                regexp: {
                    regexp: /^([a-z\s])+$/i,
                    message: 'The value must contain letters, numbers and the following symbol(s) only: .,-:'
                }
            }
        },
        middleName: {
            validators: {
                regexp: {
                    regexp: /^([a-z\s])+$/i,
                    message: 'The value must contain letters, numbers and the following symbol(s) only: .,-:'
                }
            }
        },
        lastName: {
            validators: {
                regexp: {
                    regexp: /^([a-z\s])+$/i,
                    message: 'The value must contain letters, numbers and the following symbol(s) only: .,-:'
                }
            }
        }
    });

    validateDataForm('edit-author-form', {
        firstName: {
            validators: {
                regexp: {
                    regexp: /^([a-z\s])+$/i,
                    message: 'The value must contain letters only.'
                }
            }
        },
        middleName: {
            validators: {
                regexp: {
                    regexp: /^([a-z\s])+$/i,
                    message: 'The value must contain letters only.'
                }
            }
        },
        lastName: {
            validators: {
                regexp: {
                    regexp: /^([a-z\s])+$/i,
                    message: 'The value must contain letters only.'
                }
            }
        }
    });
});