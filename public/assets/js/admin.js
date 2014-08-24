$(document).ready(function () {

    $('.toggle-check-all-boxes').click(function(e){
        var checkboxClass = ($(this).data('checkbox-class')) || 'checkbox';
        if(this.checked) { // check select status
            $('.' + checkboxClass).each(function() {
                this.checked = true;
            });
        } else {
            $('.' + checkboxClass).each(function() {
                this.checked = false;
            });
        }
    });

    $('.btn-submit-mass-action').click(function(e){
        e.preventDefault();
        var actionName = $('#mass-action option:selected').data('action-name') || 'Unknown';
        var actionURL = $('#mass-action option:selected').val() || '';
        var formData = $('.form-table').serialize() || [];
        alertify.confirm("Are you sure to complete "  + actionName + ' action?', function (e) {
            if (e) {
                // user clicked "ok"
                $.ajax({
                    type: 'POST',
                    url: actionURL,
                    data:  formData,
                    dataType: 'json'
                }).success(function(data){
                    if(data.success == true){
                        location.reload();
                    } else {
                        alertify.alert(data.error);
                    }
                });
            }
        });
    });

    $('#categoryTable').dataTable({
        "ajax": $('#categoryTable').data('table-source'),
        "bProcessing": true,
        "bServerSide": true,
        "columnDefs": [ {
            "targets": -1,
            "data": null,
            "defaultContent": "<button>Click!</button>"
        } ]
    });
 });
