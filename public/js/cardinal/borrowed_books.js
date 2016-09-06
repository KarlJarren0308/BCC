$(document).ready(function() {
    $('#borrowed-books-table').dataTable();

    $('.penalty-computation-block').each(function() {
        $(this).text(computePenalty($(this).data('var-date-loaned'), $(this).data('var-holidays'), $(this).data('var-penalty-per-day'), $(this).data('var-loan-period')));
    });
});