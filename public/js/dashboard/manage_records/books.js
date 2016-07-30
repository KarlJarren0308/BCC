function padZeros(number, length) {
    var output = number.toString();

    while(output.length < length) {
        output = '0' + output;
    }

    return output;
}

$(document).ready(function() {
    $('#books-table').dataTable({
        aoColumnDefs: [
            { bSearchable: false, bSortable: false, aTargets: [5] }
        ]
    });

    validateDataForm('add-book-form', {
        title: {
            validators: {
                regexp: {
                    regexp: /^([a-z0-9%&!?,.-:\/\s])+$/i,
                    message: 'The value must contain letters, numbers and the following symbol(s) only: .,-:'
                }
            }
        },
        callNumber: {
            validators: {
                regexp: {
                    regexp: /^([a-z0-9.\s])+$/i,
                    message: 'The value must contain letters, numbers and the following symbol(s) only: .,-:'
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
                }
            }
        },
        yearPublished: {
            validators: {
                numeric: {
                    message: 'The value must contain numbers only.'
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
        title: {
            validators: {
                regexp: {
                    regexp: /^([a-z0-9%&!?,.-:\/\s])+$/i,
                    message: 'The value must contain letters, numbers and the following symbol(s) only: .,-:'
                }
            }
        },
        callNumber: {
            validators: {
                regexp: {
                    regexp: /^([a-z0-9.\s])+$/i,
                    message: 'The value must contain letters, numbers and the following symbol(s) only: .,-:'
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
                }
            }
        },
        yearPublished: {
            validators: {
                numeric: {
                    message: 'The value must contain numbers only.'
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

    $('input[name="numberOfCopies"]').on('change paste keyup', function() {
        $('#generated-accession-numbers').text('');
        
        if($.isNumeric($(this).val())) {
            for(var i = 0; i < $(this).val(); i++) {
                $('#generated-accession-numbers').append('<div class="list-group-item">C' + padZeros(i + 1, 4) + '</div>');
            }
        }
    });
});