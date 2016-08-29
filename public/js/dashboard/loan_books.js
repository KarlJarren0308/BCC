function activateLoanButton() {
    $('[data-button="loan-button"]').prop('disabled', false);
}

function deactivateLoanButton() {
    $('[data-button="loan-button"]').prop('disabled', true);
}

$(document).ready(function() {
    var arg0 = false;
    var arg1 = false;
    var bookTitle = '';
    var bookStatus = '';
    var borrower = '';

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

                    output += '<div class="item" data-value="' + response['data']['borrower']['Username'] + '">';
                    output += '<div class="item-body">';
                    output += '<h6 class="no-margin">' + response['data']['borrower']['Type'] + ' - ' + response['data']['borrower']['Username'] + '</h6>';
                    output += '<h3 class="no-margin">' + name + '</h3>';
                    output += '<table class="table" style="margin-top: 10px;">';
                    output += '<tbody>';
                    output += '<tr>';
                    output += '<td class="text-right" width="50%">Number of books on hand:</td>';
                    output += '<td>' + onHand + '</td>';
                    output += '</tr>';
                    output += '<tr>';
                    output += '<td class="text-right">Number of books loaned:</td>';
                    output += '<td>' + response['data']['loan_history'].length + '</td>';
                    output += '</tr>';
                    output += '</tbody>';
                    output += '</table>';
                    output += '';
                    output += '</div>';
                    output += '</div>';

                    arg0 = true;
                    borrower = name;
                } else {
                    output += '<div class="item">';
                    output += '<div class="item-body">' + response['message'] + '</div>';
                    output += '</div>';

                    arg0 = false;
                }

                hideCrankLoader('borrower-list-crank').promise().done(function() {
                    $('#search-borrower-list').html(output);

                    if(arg0 && arg1) {
                        activateLoanButton();
                    } else {
                        deactivateLoanButton();
                    }
                });
            }
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
                    output += '</div>';
                    output += '</div>';

                    arg1 = true;
                    bookTitle = response['data']['book']['Title'];
                    bookStatus = response['data']['book']['Status'];
                } else {
                    output += '<div class="item">';
                    output += '<div class="item-body">' + response['message'] + '</div>';
                    output += '</div>';

                    arg1 = false;
                }

                hideCrankLoader('book-list-crank').promise().done(function() {
                    $('#search-book-list').html(output);

                    if(arg0 && arg1) {
                        activateLoanButton();
                    } else {
                        deactivateLoanButton();
                    }
                });
            }
        });

        return false;
    });

    onDataButtonClick('loan-button', function() {
        if(arg0) {
            if(arg1) {
                if(bookStatus == 'available') {
                    setModalContent('Loan Status', 'Are you want to lend this book to the borrower?<br><table class="table"><tbody><tr><td class="text-right" width="30%">Book:</td><td>' + bookTitle + '</td></tr><tr><td class="text-right">Borrower:</td><td>' + borrower + '</td></tr></tbody></table>', '<button class="btn btn-danger" data-button="yes-button">Yes</button>&nbsp;<button class="btn btn-default" data-button="no-button">No</button>');
                } else {
                    setModalContent('Loan Status', 'Oops! This book is currently on-loan.', '');
                }
            } else {
                setModalContent('Loan Status', 'You motherfucka hacker.', '');
            }
        } else {
            setModalContent('Loan Status', 'You motherfucka hacker.', '');
        }
    });
});