/*jslint browser: true, nomen: true, unparam: true*/
/*global $, jQuery*/
'use strict';

$(document).ready(function () {
    $('#js-useVoucher').click(function () {
        window.alert($(this).data('consumerid'));
    });

    $('#js-giveScoreModal').on('show.bs.modal', function (e) {
        var span = $(e.relatedTarget).closest('span');

        window.alert($(e.relatedTarget).closest('span').data('consumerid'));

        // Pass form reference to modal for submisison on yes/ok
        $(this).find('.modal-footer #js-confirm').data('span', span);
    });

    $('#js-giveScoreModal').on('click', '.modal-footer #js-confirm', function () {
        var consumerId = $(this).data('span').data('consumerid');

        window.alert(consumerId);
    });

    $('#js-consumerTable tr').click(function () {
        // if it's broken in IE, consider using id="XX" in HTML and you can get $(this).prop('id') in jQuery
        var consumerID = $(this).data('consumerid'), selected = $(this).hasClass('selected');
        $('#js-consumerTable tr').removeClass('selected');

        if (!selected) {
            $(this).addClass('selected');

            $.ajax({
                url: '/loyalty-card/consumers/' + consumerID,
                dataType: 'html',
                type: 'GET',
                success: function (data) {
                    $('#js-consumerDetails').html(data);
                }
            });
        } else {
            $('#js-consumerDetails').html('');
        }
    });

    $('#js-confirmDeleteModal').on('show.bs.modal', function (e) {
        var tr = $(e.relatedTarget).closest('tr');

        // Pass form reference to modal for submisison on yes/ok
        $(this).find('.modal-footer #js-confirm').data('tr', tr);
    });

    // Form confirm (yes/ok) handler, submits form
    $('#js-confirmDeleteModal').on('click', '.modal-footer #js-confirm', function () {
        var consumerID = $(this).data('tr').data('consumerid');
        $.ajax({
            url: '/loyalty-card/consumers/' + consumerID,
            dataType: 'json',
            type: 'delete',
            success: function (data) {
            }
        });
    });

    // reset the form when click cancel
    $('#js-createConsumerModal').on('click', '.modal-footer #cancel', function () {
        $('#js-createConsumerForm').trigger('reset');
    });

    // trigger form submit when click confirm
    $('#js-createConsumerModal').on('click', '.modal-footer #confirm', function () {
        $.ajax({
            url: '/loyalty-card/consumers',
            dataType: 'json',
            type: 'post',
            data: $('#js-createConsumerForm').serialize(),
            success: function (data) {
                if (!data.success) {
                    var errorMsg = '';

                    $.each(data.errors, function (index, error) {
                        errorMsg += '- ' + error + '\n';
                    });

                    window.alert(errorMsg);
                } else {
                    window.alert(data.message);
                    window.location.reload();
                }
            },
        });
    });
});
