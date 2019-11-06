jQuery(document).ready(function ($) {
    let count = 50;
    if ($('#searchTable').length) {
        $('#searchTable').DataTable({
            "pageLength": count
        });
    }

    if ($('#work_table').length) {
        $('#work_table').DataTable({
            "pageLength": count
        });
    }

    if ($('#orderLog').length) {

        $('#orderLog').DataTable({
            "pageLength": count,
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
            "columnDefs": [{
                "targets": [5, 7, 8],
                "orderable": false,
                "searchable": false
            }, ]
        });
    }
    $('.serial-popover').popover({});

    /*********************************************888888 */
    $('#orderaddCommentAdminForm').submit(function (e) {
        e.preventDefault();
        let myForm = document.getElementById('orderaddCommentAdminForm');
        let formData = new FormData(myForm);
        axios.post('/ordercomment',
                formData
            )
            .then(response => {
                if (response.data.success) {
                    $('#orderAddCommentAdmin').modal('hide');
                    alert('Update');
                }
            });
    });
    $('#SetUsertaskForm').submit(function (e) {
        e.preventDefault();
        let myForm = document.getElementById('SetUsertaskForm');
        let formData = new FormData(myForm);
        axios.post('/order/SetUserOrder',
                formData
            )
            .then(response => {
                if (response.data.success) {
                    location.reload();
                }
            });
    })
});
