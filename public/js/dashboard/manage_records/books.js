function padZeros(number, length) {
    var output = number.toString();

    while(output.length < length) {
        output = '0' + output;
    }

    return output;
}

$(document).ready(function() {
    $(function() {
        if($('input[name="acquisitionMethod"]:checked').val() == 'Purchased') {
            $('input[name="price"]').prop('disabled', false);
        } else {
            $('input[name="price"]').prop('disabled', true);
        }
    });

    $('#books-table').dataTable({
        aoColumnDefs: [
            { bSearchable: false, bSortable: false, aTargets: [5] }
        ]
    });

    validateDataForm('add-book-form', {
        callNumber: {
            validators: {
                regexp: {
                    regexp: /^([a-z0-9.\s])+$/i,
                    message: 'The value must contain letters, numbers and the following symbol(s) only: .,-:'
                }
            }
        },
        edition: {
            validators: {
                regexp: {
                    regexp: /^[1-9]*(1st|2nd|3rd|[4-9|0]th)$/i,
                    message: 'The value should be a valid ordinal number.'
                }
            }
        },
        location: {
            validators: {
                regexp: {
                    regexp: /^([a-z0-9,.\s])+$/i,
                    message: 'The value must contain letters, numbers and the following symbol(s) only: .,-:'
                }
            }
        },
        copyrightYear: {
            validators: {
                numeric: {
                    message: 'The value must contain numbers only.'
                },
                lessThan: {
                    value: currentYear,
                    message: 'The value should on or before this year.'
                }
            }
        },
        yearPublished: {
            validators: {
                numeric: {
                    message: 'The value must contain numbers only.'
                },
                lessThan: {
                    value: currentYear,
                    message: 'The value should on or before this year.'
                }
            }
        },
        price: {
            validators: {
                numeric: {
                    message: 'The value must contain numbers and a dot symbol only.'
                }
            }
        },
        isbn: {
            validators: {
                isbn: {
                    message: 'The value is not a valid ISBN.'
                }
            }
        }
    });

    validateDataForm('edit-book-form', {
        callNumber: {
            validators: {
                regexp: {
                    regexp: /^([a-z0-9.\s])+$/i,
                    message: 'The value must contain letters, numbers and the following symbol(s) only: .,-:'
                }
            }
        },
        edition: {
            validators: {
                regexp: {
                    regexp: /^[1-9]*(1st|2nd|3rd|[4-9|0]th)$/i,
                    message: 'The value should be a valid ordinal number.'
                }
            }
        },
        location: {
            validators: {
                regexp: {
                    regexp: /^([a-z0-9,.\s])+$/i,
                    message: 'The value must contain letters, numbers and the following symbol(s) only: .,-:'
                }
            }
        },
        copyrightYear: {
            validators: {
                numeric: {
                    message: 'The value must contain numbers only.'
                },
                lessThan: {
                    value: currentYear,
                    message: 'The value should on or before this year.'
                }
            }
        },
        yearPublished: {
            validators: {
                numeric: {
                    message: 'The value must contain numbers only.'
                },
                lessThan: {
                    value: currentYear,
                    message: 'The value should on or before this year.'
                }
            }
        },
        price: {
            validators: {
                numeric: {
                    message: 'The value must contain numbers and a dot symbol only.'
                }
            }
        },
        isbn: {
            validators: {
                isbn: {
                    message: 'The value is not a valid ISBN.'
                }
            }
        }
        /*BookISBN: {
            validators: {
                notEmpty: {
                    message: 'ISBN is required.'
                }
            }
        }*/
    });

    onDataButtonClick('view-button', function() {
        setModalLoader();
        openModal();

        $.ajax({
            url: '/data/d4cf32e8303053a4d7ba0f0859297f83',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: {
                id: $(this).data('var-id')
            },
            dataType: 'json',
            success: function(response) {
                var info = '';

                info = '<table class="table table-bordered table-striped"><tbody>';
                info += '<tr><td class="text-right" width="25%">Call Number:</td><td>' + response['data']['book']['Call_Number'] + '</td></tr>';
                info += '<tr><td class="text-right" width="25%">Title:</td><td>' + response['data']['book']['Title'] + '</td></tr>';
                info += '<tr><td class="text-right" width="25%">Description:</td><td>' + response['data']['book']['Description'] + '</td></tr>';
                info += '<tr><td class="text-right" width="25%">Edition:</td><td>' + response['data']['book']['Edition'] + '</td></tr>';
                info += '<tr><td class="text-right" width="25%">ISBN:</td><td>' + response['data']['book']['ISBN'] + '</td></tr>';
                info += '<tr><td class="text-right" width="25%">Author(s):</td><td>';

                for(var i = 0; i < response['data']['authors'].length; i++) {
                    if(response['data']['authors'][i]['Middle_Name'].length > 1) {
                        info += response['data']['authors'][i]['First_Name'] + ' ' + response['data']['authors'][i]['Middle_Name'].substring(0, 1) + '. ' + response['data']['authors'][i]['Last_Name'];
                    } else {
                        info += response['data']['authors'][i]['First_Name'] + ' ' + response['data']['authors'][i]['Last_Name'];
                    }

                    info += '<br>';
                }

                info += '</td></tr>';
                info += '</tbody></table>';

                setModalContent('View Book Information', info, '');
            }
        });

        return false;
    });

    onDataButtonClick('add-author-button', function() {
        var dTags = '';

        inputTagsCount += 1;

        for(var i = 0; i < authors.length; i++) {
            if(authors[i]['Middle_Name'].length > 1) {
                authorName = authors[i]['First_Name'] + ' ' + authors[i]['Middle_Name'].substring(0, 1) + '. ' + authors[i]['Last_Name'];
            } else {
                authorName = authors[i]['First_Name'] + ' ' + authors[i]['Last_Name'];
            }

            dTags += '<option value="' + authors[i]['Author_ID'] + '">' + authorName + '</option>';
        }

        $('#authors-well').append('<div class="form-group"><label for="author-' + inputTagsCount + '">Author\'s Name:</label><div class="input-group"><select name="authors[]" id="author-' + inputTagsCount + '" class="form-control"><option value="" selected disabled>Select an option...</option>' + dTags + '</select><div class="input-group-btn"><button type="button" class="btn btn-danger" data-button="remove-author-button">Remove</button></div></div></div>');
    });

    onDynamicDataButtonClick('remove-author-button', function() {
        $(this).parent().parent().parent().remove();
    });

    onDataButtonClick('delete-button', function() {
        setModalContent('Manage Book(s)', 'Do you really want to delete this book? This action cannot be undone.<br><br><div class="form-group"><label for="">Reason for Weeding: *</label><input type="text" class="form-control" maxlength="1000" id="reason-field"></div>', '<button class="btn btn-danger" data-button="yes-button">Yes</button>&nbsp;<button class="btn btn-default" data-button="no-button">No</button>');
        openModal('static');

        id = $(this).data('var-id');
    });

    onDynamicDataButtonClick('yes-button', function() {
        var reason = $('#reason-field').val();

        if(reason != '') {
            setModalLoader();

            $.ajax({
                url: '/delete_record/books/' + id,
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    reason: reason
                },
                dataType: 'json',
                success: function(response) {
                    setModalContent('Weeding Status', response['message'], '');

                    setTimeout(function() {
                        closeModal();

                        location.reload();
                    }, 2000);
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

    $('input[name="numberOfCopies"]').on('change paste keyup', function() {
        var numberOfCopies = $(this).val();

        $('#generated-accession-numbers').text('');
        
        if($.isNumeric($(this).val())) {
            $.ajax({
                url: '/data/531a84f73335d5abb30232cdbb7c2bd1',
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                dataType: 'json',
                success: function(response) {
                    var lastBarcode = response['data'];

                    for(var i = 1; i <= numberOfCopies; i++) {
                        $('#generated-accession-numbers').append('<div class="list-group-item">C' + padZeros(lastBarcode+i, 4) + '</div>');
                    }
                },
                error: function(arg0, arg1, arg2) {
                    console.log(arg0.responseText);
                }
            });

            return false;
        }
    });

    $('input[name="acquisitionMethod"]').change(function() {
        switch($(this).val()) {
            case 'Donated':
                $('input[name="price"]').prop('disabled', true);
                break;
            case 'Purchased':
                $('input[name="price"]').prop('disabled', false);
                break;
            default:
                break;
        }
    });
});