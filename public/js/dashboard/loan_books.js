function activateLoanButton() {
    $('[data-button="loan-button"]').prop('disabled', false);
}

function deactivateLoanButton() {
    $('[data-button="loan-button"]').prop('disabled', true);
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
        $('#search-borrower-list').html('');

        showCrankLoader('borrower-list-crank');

        $.ajax({
            url: '/data/e22d6930d5a3d304e7f190fc75c3d43c',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                var output = '';
                var name = '';
                var onHand = 0;

                if(response['status'] == 'Success') {
                    if(response['data']['borrower']['Middle_Name'].length > 1) {
                        name = response['data']['borrower']['First_Name'] + ' ' + response['data']['borrower']['Middle_Name'].substring(0, 1) + '. ' + response['data']['borrower']['Last_Name'];
                    } else {
                        name = response['data']['borrower']['First_Name'] + ' ' + response['data']['borrower']['Last_Name'];
                    }

                    for(var i = 0; i < response['data']['loan_history'].length; i++) {
                        if(response['data']['loan_history']['Loan_Status'] == 'active') {
                            onHand++;
                        }
                    }

                    onLoans = response['data']['loan_history'].length;

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
                    output += '<tr>';
                    output += '<td class="text-right" width="80%">Number of books loaned:</td>';
                    output += '<td>' + onLoans + '</td>';
                    output += '</tr>';
                    output += '</tbody>';
                    output += '</table>';
                    output += '<div class="text-right"><button class="btn btn-danger btn-xs" data-button="select-borrower-button" data-var-username="' + response['data']['borrower']['Username'] + '" data-var-type="' + response['data']['borrower']['Type'] + '" data-var-name="' + name + '">Select</button></div>';
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

                        borrowerInfo = {
                            username: $(this).data('var-username'),
                            type: $(this).data('var-type'),
                            full_name: $(this).data('var-name')
                        };

                        if(!$.isEmptyObject(borrowerInfo) && bookInfo.length > 0) {
                            activateLoanButton();
                        } else {
                            deactivateLoanButton();
                        }
                    });
                });
            },
            error: logAjaxError
        });

        return false;
    });

    onDataFormSubmit('search-book-form', function() {
        $('#search-book-list').html('');

        showCrankLoader('book-list-crank');

        $.ajax({
            url: '/data/ac5196ad2cc23d528a09e0d171cebbe4',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                var output = '';
                var authorName = '';
                var isFirst = true;

                if(response['status'] == 'Success') {
                    loanLimit = response['data']['loan_limit'];

                    output += '<div class="item">';
                    output += '<div class="item-body">';
                    output += '<h6 class="no-margin">' + response['data']['book']['Edition'] + ' Edition</h6>';
                    output += '<h3 class="no-margin">' + response['data']['book']['Title'] + '</h3>';
                    output += '<table class="table" style="margin-top: 10px;">';
                    output += '<tbody>';
                    output += '<tr>';
                    output += '<td class="text-right" width="30%">Description:</td>';
                    output += '<td>' + response['data']['book']['Description'] + '</td>';
                    output += '</tr>';
                    output += '<tr>';
                    output += '<td class="text-right">Author(s):</td>';
                    output += '<td>';

                    for(var i = 0; i < response['data']['authors'].length; i++) {
                        if(response['data']['authors'][i]['Middle_Name'].length > 1) {
                            authorName = response['data']['authors'][i]['First_Name'] + ' ' + response['data']['authors'][i]['Middle_Name'].substring(0, 1) + '. ' + response['data']['authors'][i]['Last_Name'];
                        } else {
                            authorName = response['data']['authors'][i]['First_Name'] + ' ' + response['data']['authors'][i]['Last_Name'];
                        }

                        if(isFirst) {
                            output += authorName;

                            isFirst = false;
                        } else {
                            output += '<br>' + authorName;
                        }
                    }

                    output += '</td>';
                    output += '</tr>';
                    output += '<tr>';
                    output += '<td class="text-right" width="30%">Status:</td>';
                    output += '<td>' + response['data']['book']['Status'] + '</td>';
                    output += '</tr>';
                    output += '</tbody>';
                    output += '</table>';

                    inBookInfo = false;

                    for(var j = 0; j < bookInfo.length; j++) {
                        if(bookInfo[j]['id'] == response['data']['book']['Book_ID']) {
                            inBookInfo = true;
                        }
                    }

                    if(response['data']['book']['Status'] == 'available') {
                        if(!inBookInfo) {
                            output += '<div class="text-right"><button class="btn btn-danger btn-xs" data-button="add-book-button" data-var-id="' + response['data']['book']['Book_ID'] + '" data-var-title="' + response['data']['book']['Title'] + '" data-var-status="' + response['data']['book']['Status'] + '" data-var-accession="' + response['data']['book']['Accession_Number'] + '">Add to Cart</button></div>';
                        }
                    }

                    output += '</div>';
                    output += '</div>';
                } else {
                    output += '<div class="item">';
                    output += '<div class="item-body">' + response['message'] + '</div>';
                    output += '</div>';
                }

                hideCrankLoader('book-list-crank').promise().done(function() {
                    $('#search-book-list').html(output);

                    $('[data-button="add-book-button"]').click(function() {
                        $(this).parent().parent().parent().remove();
                        $('#books-block').append('<div class="list-group-item"><h3 class="list-group-item-heading">' + $(this).data('var-title') + '</h3><div>Accession Number: <em>C' + padZeros($(this).data('var-accession'), 4) + '</em></div><div>Status: <em>' + $(this).data('var-status') + '</em></div><div class="text-right"><button class="btn btn-danger btn-xs" data-button="remove-from-cart-button" data-var-id="' + $(this).data('var-id') + '">Remove</button></div></div>');
                        $('[data-form="search-book-form"] input[name="searchKeyword"]').val('').focus();

                        bookInfo.push({
                            id: $(this).data('var-id'),
                            accession: $(this).data('var-accession'),
                            title: $(this).data('var-title'),
                            status: $(this).data('var-status'),
                        });

                        if(!$.isEmptyObject(borrowerInfo) && bookInfo.length > 0) {
                            activateLoanButton();
                        } else {
                            deactivateLoanButton();
                        }
                    });
                });
            }
        });

        return false;
    });

    onDataButtonClick('loan-button', function() {
        var output = '';

        if(!$.isEmptyObject(borrowerInfo)) {
            if(bookInfo.length > 0) {
                output += 'Do you want to lend the following book to ' + borrowerInfo['full_name'] + '?<br><br><table class="table table-striped table-bordered"><thead><tr><th>Book Title</th><th>Accession Number</th></tr></thead><tbody>';

                for(var i = 0; i < bookInfo.length; i++) {
                    output += '<tr>';
                    output += '<td>' + bookInfo[i]['title'] + '</td>';
                    output += '<td>C' + padZeros(bookInfo[i]['accession'], 4) + '</td>';
                    output += '</tr>';
                }

                output += '</tbody></table>';

                if(borrowerInfo['type'] == 'Student') {
                    if(onLoans < loanLimit) {
                        if(onLoans + bookInfo.length <= loanLimit) {
                            setModalContent('Loan Status', output, '<button class="btn btn-danger" data-button="yes-button">Yes</button>&nbsp;<button class="btn btn-default" data-button="no-button">No</button>');
                            openModal('static');
                        } else {
                            setModalContent('Loan Status', 'Oops! Borrower will exceed the loan limit if this transaction is continued. Please remove ' + ((onLoans + bookInfo.length) - loanLimit) + ' book(s) from the cart before continuing this transaction.', '');
                            openModal();
                        }
                    } else {
                        setModalContent('Loan Status', 'Oops! Borrower has reached the loan limit.', '');
                        openModal();
                    }
                } else {
                    setModalContent('Loan Status', output, '<button class="btn btn-danger" data-button="yes-button">Yes</button>&nbsp;<button class="btn btn-default" data-button="no-button">No</button>');
                    openModal('static');
                }
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

        setModalLoader();

        $.ajax({
            url: '/loan_books',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: {
                borrower: borrowerInfo['username'],
                accessions: bookInfo
            },
            dataType: 'json',
            success: function(response) {
                if(response['status'] == 'Success') {
                    output += '<table class="table table-striped table-bordered"><thead><tr><th>Book Title</th><th>Accession Number</th></tr></thead><tbody>';

                    for(var j = 0; j < response['data']['barcodes'].length; j++) {
                        for(var k = 0; k < bookInfo.length; k++) {
                            if(bookInfo[k]['accession'] == response['data']['barcodes'][j]) {
                                output += '<tr>';
                                output += '<td>' + bookInfo[k]['title'] + '</td>';
                                output += '<td>C' + padZeros(bookInfo[k]['accession'], 4) + '</td>';
                                output += '</tr>';

                                receipt += '<li>' + bookInfo[k]['title'] + ' [C' + padZeros(bookInfo[k]['accession'], 4) + ']</li>';
                            }
                        }
                    }

                    output += '</tbody></table>';

                    setModalContent('Loan Books Status', 'The following book(s) has been successfully loaned:<br>' + output, '<button class="btn btn-danger btn-sm" data-button="print-button">Print Receipt</button>');

                    $('[data-button="print-button"]').click(function() {
                        var tab = window.open();

                        tab.document.write('<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>Binangonan Catholic College</title><style>.block { border: 1px solid #ddd; display: inline-block; font-family: "Helvetica"; font-size: 12px; padding: 10px 15px; width: 200px; } .header >.school-name { font-size: 18px; text-align: center; } .header >.system-type { font-size: 10px; text-align: center; } .body { margin-top: 15px; } .body >.entry-info { text-indent: -15px; padding-left: 15px; } ul { margin-top: 2px; }</style></head><body><div class="block"><div class="header"><div class="school-name">Binangonan Catholic College</div><div class="system-type">Library System</div></div><div class="body"><div class="entry-info"><strong>Borrower ID: </strong>' + borrowerInfo['username'] + '</div><div class="entry-info"><strong>Borrower: </strong>' + borrowerInfo['full_name'] + '</div><div class="entry-info"><strong>Type: </strong>' + borrowerInfo['type'] + '</div><br><br><strong>Borrower Book(s):</strong><ul>' + receipt + '</ul><div class="entry-info"><strong>Date Borrowed: </strong>' + moment().format('MMMM DD, YYYY') + '</div></div></div></body></html>');
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
                    setModalContent('Loan Books Status', response['message'], '');
                }
            }
        });

        return false;
    });

    onDynamicDataButtonClick('no-button', function() {
        closeModal();
    });

    onDynamicDataButtonClick('remove-from-cart-button', function() {
        $(this).parent().parent().remove();

        removeByAttr(bookInfo, 'id', $(this).data('var-id'));

        if(!$.isEmptyObject(borrowerInfo) && bookInfo.length > 0) {
            activateLoanButton();
        } else {
            deactivateLoanButton();
        }
    });
});