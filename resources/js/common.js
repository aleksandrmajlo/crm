jQuery(document).ready(function ($) {
    let count = 50;
    if ($('#searchTable').length) {
        $('#searchTable').DataTable({
            "pageLength": count
            /*
            "columnDefs": [{
                "targets": [1, 6, 7],
                "orderable": false,
                "searchable": false
            }, ]
            */
        });
    }

    if ($('#work_table').length) {
        $('#work_table').DataTable({
            "pageLength": count
        });
    }

    if ($('#done_table').length) {
        $('#done_table').DataTable({
            "pageLength": count
        });
    }

    if ($('#done_faileds').length) {
        $('#done_faileds').DataTable({
            "pageLength": count
        });
    }
});
