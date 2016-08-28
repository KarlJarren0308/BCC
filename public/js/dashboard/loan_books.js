$(document).ready(function() {
    onDataButtonClick('search-borrower-button', function() {
        showCrankLoader('borrower-list-crank');

        $.ajax({
            url: '/data/e22d6930d5a3d304e7f190fc75c3d43c',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                var output = '';

                $('#search-borrower-list').html(output);
            }
        });

        return false;
    });

    onDataButtonClick('search-book-button', function() {
        showCrankLoader('book-list-crank');

        $.ajax({
            url: '/data/ac5196ad2cc23d528a09e0d171cebbe4',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                var output = '';

                $('#search-book-list').html(output);
            }
        });

        return false;
    });
});