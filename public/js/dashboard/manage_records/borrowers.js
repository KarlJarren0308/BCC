$(document).ready(function() {
    $('#borrowers-table').dataTable({
        aoColumnDefs: [
            { bSearchable: false, bSortable: false, aTargets: [4] }
        ]
    });

    $('select[name="type"]').change(function() {
        switch($(this).val()) {
            case 'Faculty':
                $('fieldset[data-for="Student"]').prop('disabled', true);
                break;
            case 'Student':
                $('fieldset[data-for="Student"]').prop('disabled', false);
                break;
            default:
                break;
        }
    });
});