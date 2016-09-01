function logAjaxError(arg0, arg1, ar2) {
    console.log(arg0.responseText);
}

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
    var borrowerType = '';
    var borrowerID = '';
    var bookID = '';
    var onLoans = 0;
    var loanLimit = 1;

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
                    output += '<td class="text-right" width="50%">Number of books on hand:</td>';
                    output += '<td>' + onHand + '</td>';
                    output += '</tr>';
                    output += '<tr>';
                    output += '<td class="text-right">Number of books loaned:</td>';
                    output += '<td>' + onLoans + '</td>';
                    output += '</tr>';
                    output += '</tbody>';
                    output += '</table>';
                    output += '';
                    output += '</div>';
                    output += '</div>';

                    arg0 = true;
                    borrower = name;
                    borrowerID = response['data']['borrower']['Username'];
                    borrowerType = response['data']['borrower']['Type'];
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
                    output += '</div>';
                    output += '</div>';

                    arg1 = true;
                    bookTitle = response['data']['book']['Title'];
                    bookStatus = response['data']['book']['Status'];
                    bookID = response['data']['book']['Book_ID'];
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
                    if(borrowerType == 'Student') {
                        if(onLoans < loanLimit) {
                            setModalContent('Loan Status', 'Do you want to lend this book to the borrower?<br><br><table class="table table-striped table-bordered"><tbody><tr><td class="text-right">Borrower:</td><td>' + borrower + '</td></tr><tr><td class="text-right" width="30%">Book:</td><td>' + bookTitle + '</td></tr></tbody></table>', '<button class="btn btn-danger" data-button="yes-button">Yes</button>&nbsp;<button class="btn btn-default" data-button="no-button">No</button>');
                            openModal('static');
                        } else {
                            setModalContent('Loan Status', 'Oops! Borrower has reached the loan limit.', '');
                            openModal();
                        }
                    } else {
                        setModalContent('Loan Status', 'Do you want to lend this book to the borrower?<br><br><table class="table table-striped table-bordered"><tbody><tr><td class="text-right">Borrower:</td><td>' + borrower + '</td></tr><tr><td class="text-right" width="30%">Book:</td><td>' + bookTitle + '</td></tr></tbody></table>', '<button class="btn btn-danger" data-button="yes-button">Yes</button>&nbsp;<button class="btn btn-default" data-button="no-button">No</button>');
                        openModal('static');
                    }
                } else {
                    setModalContent('Loan Status', 'Oops! This book is currently on-loan.', '');
                    openModal();
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
        setModalLoader();

        $.ajax({
            url: '/loan_books',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: {
                id: bookID,
                borrower: borrowerID
            },
            dataType: 'json',
            success: function(response) {
                setModalContent('Loan Books Status', response['message'], '');

                setTimeout(function() {
                    closeModal();

                    location.reload();
                }, 2000);
            }
        });

        return false;
    });

    onDynamicDataButtonClick('no-button', function() {
        closeModal();
    });
});