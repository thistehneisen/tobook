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
});
