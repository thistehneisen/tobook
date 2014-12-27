/*jslint browser: true, nomen: true, unparam: true*/
/*global $*/
'use strict';

$(document).ready(function () {
    $('.js-showHistory').on('click', function (e) {
        e.preventDefault();
        var service = $(this).data('service');
        $.ajax({
            url: $(this).prop('href'),
            type: 'get',
            data: {
                id: $(this).data('consumerid'),
                service: service,
            },
            success: function (history_view) {
                $('#js-historyModal .modal-body').html(history_view);
                $('#js-historyModal').modal('show');
            }
        });
    });

    // inject ids input to bypass bulk validation
    $('#olut-mass-action').on('change', function () {
        var $this = $(this);

        if ($this.val() === 'send_all_email' || $this.val() === 'send_all_sms') {
            $this.after('<input id="all_ids" type="hidden" name="ids[]" value="all" />');
        } else {
            $('#all_ids').remove();
        }
    });
});
