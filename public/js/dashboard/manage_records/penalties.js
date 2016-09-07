$(document).ready(function() {
    $('#loans-table').dataTable({
        aoColumnDefs: [
            { bSearchable: false, bSortable: false, aTargets: [8] }
        ]
    });
});