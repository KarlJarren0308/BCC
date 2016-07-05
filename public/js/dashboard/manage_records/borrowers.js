$(document).ready(function() {
    $('#borrowers-table').dataTable({
        aoColumnDefs: [
            { bSearchable: false, bSortable: false, aTargets: [3] }
        ]
    });
});