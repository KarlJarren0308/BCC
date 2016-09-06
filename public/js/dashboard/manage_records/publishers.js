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
        },
        publisherAddress: {
            validators: {
                regexp: {
                    regexp: /^([a-z0-9,.-\s])+$/i,
                    message: 'The value must contain letters, numbers and the following symbol(s) only: ,.-'
                }
            }
        },
        publisherTelephoneNumber: {
            validators: {
                regexp: {
                    regexp: /^([0-9]){7}$/i,
                    message: 'The value must be a valid telephone number.'
                }
            }
        },
        publisherEmailAddress: {
            validators: {
                emailAddress: {
                    message: 'The value must be a valid e-mail address.'
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
        },
        publisherAddress: {
            validators: {
                regexp: {
                    regexp: /^([a-z0-9,.-\s])+$/i,
                    message: 'The value must contain letters, numbers and the following symbol(s) only: ,.-'
                }
            }
        },
        publisherTelephoneNumber: {
            validators: {
                regexp: {
                    regexp: /^([0-9]){7}$/i,
                    message: 'The value must be a valid telephone number.'
                }
            }
        },
        publisherEmailAddress: {
            validators: {
                emailAddress: {
                    message: 'The value must be a valid e-mail address.'
                }
            }
        }
    });
});