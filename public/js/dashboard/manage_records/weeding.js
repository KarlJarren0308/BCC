$(document).ready(function() {
    var id = '';

    $('#books-table').dataTable({
        aoColumnDefs: [
            { bSearchable: false, bSortable: false, aTargets: [6] }
        ]
    });
});