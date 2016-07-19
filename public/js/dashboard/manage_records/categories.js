$(document).ready(function() {
    $('#categories-table').dataTable({
        aoColumnDefs: [
            { bSearchable: false, bSortable: false, aTargets: [1] }
        ]
    });
});