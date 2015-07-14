/*jslint browser: true, nomen: true, unparam: true, node: true*/
/*global $, jQuery, alertify, location, window, VARAA*/
'use strict';

(function ($) {
    $(function () {
        $('.workshift-editable').click(function(){
            var $this = $(this);
            var custom_time = CUSTOM_TIME;
            if($this.data('editable') === true) {
                $this.data('editable', false);
                var dropdown = $('<select/>', {
                    class: 'form-control',
                    style: 'max-width:70%; display:inline'
                });
                for(var val in custom_time) {
                    $('<option />', {
                        value: val,
                        text: custom_time[val]
                    }).appendTo(dropdown);
                }
                var btnOk = $('<input/>', {
                    value: 'OK',
                    type: 'button',
                    class: 'btn btn-primary btn-change-workshift',
                });
                $this.empty();
                dropdown.appendTo($this);
                btnOk.appendTo($this);
            }
        });

        $(document).on('click', '.btn-change-workshift', function(e) {
            e.preventDefault();
            var $this = $(this);
            var parentSpan = $this.closest('span');
            var workshiftSelect  = $this.prev('select');
            var custom_time_id   = workshiftSelect.val();
            var custom_time_text = workshiftSelect.find("option:selected").text()
            var url = $('#update_workshift_url').val();
            $.post(url, {
                'custom_time_id': custom_time_id,
                'employee_id'   : parentSpan.data('employee-id'),
                'date'          : parentSpan.data('date'),
            }).done(function(data){
                parentSpan.data('editable', true);
                parentSpan.empty();
                parentSpan.text(custom_time_text);
            });
        });
    });
}(jQuery));
