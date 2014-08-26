$(document).ready(function () {
    var spinner = $( ".spinner" ).spinner();
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

    $('#form-bulk').on('submit', function(e) {
        e.preventDefault();
        var $this = $(this);
        alertify.confirm($this.data('confirm'), function (e) {
            if (e) {
                // user clicked "ok"
                $.ajax({
                    type: 'POST',
                    url: $this.attr('action'),
                    data: $this.serialize(),
                    dataType: 'json'
                }).done(function(data) {
                    alertify.alert('OK');
                }).fail(function() {
                    alertify.alert('Something went wrong');
                });
            }
        });
    });

    // Date picker
    $('.date-picker').datepicker({
        format: 'yyyy-mm-dd'
    });
 });
