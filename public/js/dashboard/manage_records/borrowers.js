$(document).ready(function() {
    $('#borrowers-table').dataTable({
        aoColumnDefs: [
            { bSearchable: false, bSortable: false, aTargets: [4] }
        ]
    });

    validateDataForm('add-borrower-form', {
        borrowerID: {
            validators: {
                regexp: {
                    regexp: /^([a-z0-9-])+$/i,
                    message: 'The value must contain letters, numbers, and a dash symbol only.'
                }
            }
        },
        birthDate: {
            validators: {
                date: {
                    message: 'Borrower should be 15 year old and above. The date value should be in DD/MM/YYYY format.'
                }
            }
        },
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
        },
        address: {
            validators: {
                regexp: {
                    regexp: /^([a-z0-9,.-\s])+$/i,
                    message: 'The value must contain letters, numbers and the following symbol(s) only: ,.-'
                }
            }
        },
        telephoneNumber: {
            validators: {
                regexp: {
                    regexp: /^([0-9]){7}$/i,
                    message: 'The value must be a valid telephone number.'
                }
            }
        },
        cellphoneNumber: {
            validators: {
                regexp: {
                    regexp: /^(\+63|63|0)([0-9]){10}$/i,
                    message: 'The value must be a valid cellphone number.'
                }
            }
        }
    });

    validateDataForm('edit-borrower-form', {
        borrowerID: {
            validators: {
                regexp: {
                    regexp: /^([a-z0-9-])+$/i,
                    message: 'The value must contain letters, numbers, and a dash symbol only.'
                }
            }
        },
        birthDate: {
            validators: {
                date: {
                    message: 'Borrower should be 15 year old and above. The date value should be in DD/MM/YYYY format.'
                }
            }
        },
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
        },
        address: {
            validators: {
                regexp: {
                    regexp: /^([a-z0-9,.-\s])+$/i,
                    message: 'The value must contain letters, numbers and the following symbol(s) only: ,.-'
                }
            }
        },
        telephoneNumber: {
            validators: {
                regexp: {
                    regexp: /^([0-9]){7}$/i,
                    message: 'The value must be a valid telephone number.'
                }
            }
        },
        cellphoneNumber: {
            validators: {
                regexp: {
                    regexp: /^(\+63|63|0)([0-9]){10}$/i,
                    message: 'The value must be a valid cellphone number.'
                }
            }
        }
    });

    $('select[name="type"]').change(function() {
        switch($(this).val()) {
            case 'Faculty':
                $('fieldset[data-for="Student"]').prop('disabled', true);
                break;
            case 'Student':
                $('fieldset[data-for="Student"]').prop('disabled', false);
                break;
            default:
                break;
        }
    });
});