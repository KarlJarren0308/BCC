function activateReceiveButton() {
    $('[data-button="receive-button"]').prop('disabled', false);
}

function deactivateReceiveButton() {
    $('[data-button="receive-button"]').prop('disabled', true);
}

function removeByAttr(arr, attr, value) {
    var i = arr.length;

    while(i--) {
        if(arr[i] && arr[i].hasOwnProperty(attr) && (arguments.length > 2 && arr[i][attr] === value)) {
            arr.splice(i, 1);
        }
    }

    return arr;
}

function padZeros(number, length) {
    var output = number.toString();

    while(output.length < length) {
        output = '0' + output;
    }

    return output;
}

$(document).ready(function() {
    var onLoans = 0;
    var loanLimit = 1;
    var borrowerInfo = {};
    var bookInfo = [];
    var inBookInfo = false;

    onDataFormSubmit('search-borrower-form', function() {
        var searchKeyword = $(this).serialize();

        $('#search-borrower-list').html('');

        showCrankLoader('borrower-list-crank');

        $.ajax({
            url: '/data/e22d6930d5a3d304e7f190fc75c3d43c',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: searchKeyword,
            dataType: 'json',
            success: function(response) {
                var output = '';
                var name = '';
                var onHand = 0;
                var onL = 0;

                if(response['status'] == 'Success') {
                    if(response['data']['borrower']['Middle_Name'].length > 1) {
                        name = response['data']['borrower']['First_Name'] + ' ' + response['data']['borrower']['Middle_Name'].substring(0, 1) + '. ' + response['data']['borrower']['Last_Name'];
                    } else {
                        name = response['data']['borrower']['First_Name'] + ' ' + response['data']['borrower']['Last_Name'];
                    }

                    for(var i = 0; i < response['data']['loan_history'].length; i++) {
                        if(response['data']['loan_history'][i]['Loan_Status'] == 'active') {
                            onHand++;
                        }
                    }

                    onL = response['data']['loan_history'].length;

                    output += '<div class="item" data-value="' + response['data']['borrower']['Username'] + '">';
                    output += '<div class="item-body">';
                    output += '<h6 class="no-margin">' + response['data']['borrower']['Type'] + ' - ' + response['data']['borrower']['Username'] + '</h6>';
                    output += '<h3 class="no-margin">' + name + '</h3>';
                    output += '<table class="table" style="margin-top: 10px;">';
                    output += '<tbody>';
                    output += '<tr>';
                    output += '<td class="text-right" width="80%">Number of books on hand:</td>';
                    output += '<td>' + onHand + '</td>';
                    output += '</tr>';
                    output += '</tbody>';
                    output += '</table>';
                    output += '<div class="text-right"><button class="btn btn-danger btn-xs" data-button="select-borrower-button" data-var-username="' + response['data']['borrower']['Username'] + '" data-var-type="' + response['data']['borrower']['Type'] + '" data-var-name="' + name + '" data-var-onloans="' + onL + '">Select</button></div>';
                    output += '</div>';
                    output += '</div>';
                } else {
                    output += '<div class="item">';
                    output += '<div class="item-body">' + response['message'] + '</div>';
                    output += '</div>';
                }

                hideCrankLoader('borrower-list-crank').promise().done(function() {
                    $('#search-borrower-list').html(output);

                    $('[data-button="select-borrower-button"]').click(function() {
                        $(this).parent().parent().parent().remove();
                        $('#borrower-block').html('<div class="list-group-item"><h3 class="list-group-item-heading">' + $(this).data('var-name') + '</h3><div>Borrower ID: <em>' + $(this).data('var-username') + '</em></div><div>Type: <em>' + $(this).data('var-type') + '</em></div></div>');

                        onLoans = $(this).data('var-onloans');

                        borrowerInfo = {
                            username: $(this).data('var-username'),
                            type: $(this).data('var-type'),
                            full_name: $(this).data('var-name')
                        };

                        showCrankLoader('book-list-crank');

                        $.ajax({
                            url: '/data/64d808802eda41de389118b03d15ccb9',
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            data: searchKeyword,
                            dataType: 'json',
                            success: function(response2) {
                                var booksOutput = '';

                                if(response2['status'] == 'Success') {
                                    for(var j = 0; j < response2['data']['loan_history'].length; j++) {
                                        booksOutput += '<div class="item">';
                                        booksOutput += '<div class="item-body">';
                                        booksOutput += '<h6 class="no-margin">C' + padZeros(response2['data']['loan_history'][j]['Accession_Number'], 4) + '</h6>';
                                        booksOutput += '<h3 class="no-margin">' + response2['data']['loan_history'][j]['Title'] + '</h3>';
                                        booksOutput += '<table class="table" style="margin-top: 10px;">';
                                        booksOutput += '<tbody>';
                                        booksOutput += '<tr>';
                                        booksOutput += '<td class="text-right" width="30%">Description:</td>';
                                        booksOutput += '<td>' + response2['data']['loan_history'][j]['Description'] + '</td>';
                                        booksOutput += '</tr>';
                                        booksOutput += '</tbody>';
                                        booksOutput += '</table>';
                                        booksOutput += '<div class="text-right"><button class="btn btn-danger btn-xs" data-button="add-book-button" data-var-id="' + response2['data']['loan_history'][j]['Loan_ID'] + '" data-var-price="' + response2['data']['loan_history'][j]['Price'] + '" data-var-acquisition="' + response2['data']['loan_history'][j]['Acquisition_Method'] + '" data-var-condition="' + response2['data']['loan_history'][j]['Condition'] + '" data-var-date-loaned="' + (response2['data']['loan_history'][j]['Loan_Date_Stamp'] + ' ' + response2['data']['loan_history'][j]['Loan_Time_Stamp']) + '" data-var-title="' + response2['data']['loan_history'][j]['Title'] + '" data-var-accession="' + response2['data']['loan_history'][j]['Accession_Number'] + '">Add to Cart</button></div>';
                                        booksOutput += '</div>';
                                        booksOutput += '</div>';
                                    }
                                } else {
                                    booksOutput += '<div class="item">';
                                    booksOutput += '<div class="item-body">' + response2['message'] + '</div>';
                                    booksOutput += '</div>';
                                }

                                hideCrankLoader('book-list-crank').promise().done(function() {
                                    $('#search-book-list').html(booksOutput);

                                    $('[data-button="add-book-button"]').click(function() {
                                        $(this).parent().parent().parent().remove();
                                        $('#books-block').append('<div class="list-group-item"><h3 class="list-group-item-heading">' + $(this).data('var-title') + '</h3><div>Accession Number: <em>C' + padZeros($(this).data('var-accession'), 4) + '</em></div><div class="text-right"><button class="btn btn-danger btn-xs" data-button="remove-from-cart-button" data-var-id="' + $(this).data('var-id') + '">Remove</button></div></div>');

                                        bookInfo.push({
                                            id: $(this).data('var-id'),
                                            condition: $(this).data('var-condition'),
                                            price: $(this).data('var-price'),
                                            acquisition: $(this).data('var-acquisition'),
                                            date_loaned: $(this).data('var-date-loaned'),
                                            accession: $(this).data('var-accession'),
                                            title: $(this).data('var-title'),
                                            status: $(this).data('var-status'),
                                        });

                                        if(!$.isEmptyObject(borrowerInfo) && bookInfo.length > 0) {
                                            activateReceiveButton();
                                        } else {
                                            deactivateReceiveButton();
                                        }
                                    })
                                });
                            },
                            error: logAjaxError
                        });
                    });
                });
            },
            error: logAjaxError
        });

        return false;
    });

    onDataButtonClick('receive-button', function() {
        var output = '';

        if(!$.isEmptyObject(borrowerInfo)) {
            if(bookInfo.length > 0) {
                output += 'Are you sure you are going to receive the following book to ' + borrowerInfo['full_name'] + '?<br><br>';
                output += '<table class="table table-striped table-bordered"><thead><tr><th>Book Title</th><th>Accession Number</th><th>Condition</th></tr></thead><tbody>';

                for(var i = 0; i < bookInfo.length; i++) {
                    output += '<tr>';
                    output += '<td>' + bookInfo[i]['title'] + '</td>';
                    output += '<td>C' + padZeros(bookInfo[i]['accession'], 4) + '</td>';
                    output += '<td><div class="form-group">';
                    output += '<select class="copy-condition form-control" data-var-id="' + bookInfo[i]['id'] + '" data-var-date-loaned="' + bookInfo[i]['date_loaned'] + '" data-var-accession="' + bookInfo[i]['accession'] + '"><option value="" selected disabled>Select an option...</option><option value="good">Good Condition</option><option value="damaged">Damaged</option><option value="lost">Lost</option></select>';
                    output += '<input type="text" class="copy-amount form-control" data-var-acquisition="' + bookInfo[i]['acquisition'] + '" data-var-price="' + bookInfo[i]['price'] + '" readonly>';
                    output += '</div></td>';
                    output += '</tr>';
                }

                output += '</tbody></table>';

                setModalContent('Loan Status', output, '<button class="btn btn-danger" data-button="yes-button">Yes</button>&nbsp;<button class="btn btn-default" data-button="no-button">No</button>');
                openModal('static');

                $('.copy-condition').change(function() {
                    if($(this).val() == 'good') {
                        $(this).parent().find('.copy-amount').val(0).attr('readonly', true);
                    } else if($(this).val() == 'damaged') {
                        $(this).parent().find('.copy-amount').val(0).removeAttr('readonly');
                    } else {
                        if($(this).parent().find('.copy-amount').data('var-acquisition') == 'Purchased') {
                            $(this).parent().find('.copy-amount').val($(this).parent().find('.copy-amount').data('var-price')).attr('readonly', true);
                        } else {
                            $(this).parent().find('.copy-amount').val(0).removeAttr('readonly');
                        }
                    }
                });
            } else {
                setModalContent('Loan Status', 'You motherfucka hacker.', '');
                openModal();
            }
        } else {
            setModalContent('Loan Status', 'You motherfucka hacker.', '');
            openModal();
        }
    });

    onDynamicDataButtonClick('yes-button', function() {
        var output = '';
        var receipt = '';
        var emptyFields = 0;
        var superInfo = [];
        var superCup = [];

        $('.copy-condition').each(function() {
            if(!$.trim($(this).val())) {
                emptyFields++;

                $(this).focus();
            }
        });

        if(emptyFields == 0) {
            $('.copy-condition').each(function() {
                superInfo.push({
                    id: $(this).data('var-id'),
                    penalty: computePenalty($(this).data('var-date-loaned'), $('.data-holder').data('var-holidays'), $('.data-holder').data('var-penalty-per-day'), $('.data-holder').data('var-loan-period')),
                    accession: $(this).data('var-accession'),
                    condition: $(this).val()
                });
            });

            $('.copy-amount').each(function() {
                superCup.push({
                    additional: $(this).val()
                });
            });

            setModalLoader();

            $.ajax({
                url: '/receive_books',
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    infos: superInfo,
                    cups: superCup
                },
                dataType: 'json',
                success: function(response) {
                    var totalPenalty = 0;

                    if(response['status'] == 'Success') {
                        output += '<table class="table table-striped table-bordered"><thead><tr><th>Book Title</th><th>Accession Number</th></tr></thead><tbody>';

                        for(var j = 0; j < response['data']['barcodes'].length; j++) {
                            for(var k = 0; k < bookInfo.length; k++) {
                                if(bookInfo[k]['accession'] == response['data']['barcodes'][j]['accession']) {
                                    output += '<tr>';
                                    output += '<td>' + bookInfo[k]['title'] + '</td>';
                                    output += '<td>C' + padZeros(bookInfo[k]['accession'], 4) + '</td>';
                                    output += '</tr>';

                                    receipt += '<li>';
                                    receipt += bookInfo[k]['title'] + ' [C' + padZeros(bookInfo[k]['accession'], 4) + ']';
                                    receipt += '<div>Condition: ' + response['data']['barcodes'][j]['Condition'] + '</div>';

                                    if(response['data']['barcodes'][j]['condition'] == 'good') {
                                        receipt += '<div>Penalty: ' + response['data']['barcodes'][j]['penalty'] + '</div>';
                                    }

                                    receipt += '</li>';

                                    totalPenalty += response['data']['barcodes'][j]['penalty'];
                                }
                            }
                        }

                        output += '</tbody></table>';

                        setModalContent('Receive Books Status', 'The following book(s) has been successfully received:<br>' + output, '<button class="btn btn-danger btn-sm" data-button="print-button">Print Receipt</button>');

                        $('[data-button="print-button"]').click(function() {
                            var tab = window.open();

                            tab.document.write('<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>Binangonan Catholic College</title><style>.block { border: 1px solid #ddd; display: inline-block; font-family: "Helvetica"; font-size: 12px; padding: 10px 15px; width: 200px; } .header >.school-name { font-size: 18px; text-align: center; } .header >.system-type { font-size: 10px; text-align: center; } .body { margin-top: 15px; } .body >.entry-info { text-indent: -15px; padding-left: 15px; } ul { margin-top: 2px; }</style></head><body><div class="block"><div class="header"><div class="school-name">Binangonan Catholic College</div><div class="system-type">Library System</div></div><div class="body"><div class="entry-info"><strong>Borrower ID: </strong>' + borrowerInfo['username'] + '</div><div class="entry-info"><strong>Borrower: </strong>' + borrowerInfo['full_name'] + '</div><div class="entry-info"><strong>Type: </strong>' + borrowerInfo['type'] + '</div><br><br><strong>Borrowed Book(s):</strong><ul>' + receipt + '</ul><div class="entry-info"><strong>Date Returned: </strong>' + moment().format('MMMM DD, YYYY') + '</div><div class="entry-info"><strong>Total Penalty: </strong>' + totalPenalty + '</div></div></div></body></html>');
                            tab.print();
                            tab.close();

                            $('.modal').click(function() {
                                location.reload();

                                $('.modal').modal('hide');
                            });

                            $('.modal .modal-content').click(function(e) {
                                e.stopPropagation();
                            });
                        });
                    } else {
                        setModalContent('Receive Books Status', response['message'], '');
                    }
                },
                error: logAjaxError
            });

            return false;

            console.log('Hooray!');
        }
    });

    onDynamicDataButtonClick('no-button', function() {
        closeModal();
    });

    onDynamicDataButtonClick('remove-from-cart-button', function() {
        $(this).parent().parent().remove();

        bookInfo.splice(bookInfo.indexOf($(this).data('var-id')),1);
        console.log(bookInfo);

        if(!$.isEmptyObject(borrowerInfo) && bookInfo.length > 0) {
            activateReceiveButton();
        } else {
            deactivateReceiveButton();
        }
    });
});