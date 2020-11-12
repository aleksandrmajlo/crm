jQuery(document).ready(function ($) {
    let count = 50;
    if ($('#searchTable').length) {
        $('#searchTable').DataTable({
            "pageLength": count
        });
    }
    if ($('#work_table').length) {

        $('#work_table').DataTable({
            "pageLength": count,
            'order':[[1,"desc"]],
            "columnDefs": [{
                "targets": [6, 7],
                "orderable": false,
                "searchable": false
            }, ]
        });

    }
    if ($('#orderLog').length) {

        $('#orderLog').DataTable({
            // "pageLength": count,
            "bPaginate": false,
            "columnDefs": [{
                "targets": 3,
                "orderable": false,
                "searchable": false
            }, ]
        });
    }
    if ($('#done_table').length) {
        $('#done_table').DataTable({
            "pageLength": count,
            'order':[[1,"desc"]],
            "columnDefs": [{
                "targets": [7],
                "orderable": false,
                "searchable": false
            }, ]
        });
    }
    if ($('#done_faileds').length) {
        $('#done_faileds').DataTable({
            "pageLength": count,
            'order':[[1,"desc"]],
            "columnDefs": [{
                "targets": [5, 7, 8],
                "orderable": false,
                "searchable": false
            }, ]
        });
    }
    /*
    if ($('#adminComments').length) {
        var oTable = $('#adminComments').DataTable({
            "pageLength": count,
            order: [
                [0, 'desc']
            ]
        });
    }
    */
    $('.serial-popover').popover({});
});
