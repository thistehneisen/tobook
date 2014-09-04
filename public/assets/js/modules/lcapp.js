/*jslint browser: true, nomen: true, unparam: true*/
/*global $, jQuery*/
'use strict';

$(document).ready(function () {
    // ------ GIVE POINT ------ //
    $('#js-givePointModal').on('show.bs.modal', function (e) {
        // Pass form reference to modal for submisison on yes/ok
        $(this).find('.modal-footer #js-confirmGivePoint').data('consumerid', $(e.relatedTarget).data('consumerid'));
    });

    $('#js-givePointModal').on('click', '.modal-footer #js-confirmGivePoint', function () {
        var consumerID = $(this).data('consumerid');

        $.ajax({
            url: '/loyalty-card/consumers/' + consumerID,
            dataType: 'json',
            type: 'put',
            data: $('#js-givePointForm').serialize(),
            success: function (data) {
                if (!data.success) {
                    var errorMsg = '';

                    $.each(data.errors, function (index, error) {
                        errorMsg += '- ' + error + '\n';
                    });

                    window.alert(errorMsg);
                } else {
                    window.alert(data.message);
                    $('#js-givePointModal').modal('hide');
                    $('#js-currentPoint').text(data.points);
                }
            }
        });
        $('#js-givePointForm').trigger('reset');
    });

    $('#js-givePointModal').on('click', '.modal-footer #js-cancelGivePoint', function () {
        $('#js-givePointForm').trigger('reset');
    });

    // ------ USE VOUCHER ------//
    $('#js-consumerDetails').on('click', '#js-useVoucher', function (e) {
        var consumerID = $(this).data('consumerid'), voucherID = $(this).data('voucherid'), required = parseInt($('#js-required').text(), 10), currentPoint = parseInt($('#js-currentPoint').text(), 10);

        if (currentPoint >= required) {
            $.ajax({
                url: '/loyalty-card/consumers/' + consumerID,
                dataType: 'json',
                type: 'put',
                data: {
                    usePoint : 1,
                    voucherID : voucherID,
                },
                success: function (data) {
                    if (data.success) {
                        window.alert(data.message);
                        $('#js-givePointModal').modal('hide');
                        $('#js-currentPoint').text(data.points);
                    }
                },
            });
        } else {
            window.alert('Not enough point');
        }
    });

    // ------ CONSUMER INFO FETCHING ------ //
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

    // ------ DELETE CONSUMER ------ //
    $('#js-confirmDeleteModal').on('show.bs.modal', function (e) {
        var tr = $(e.relatedTarget).closest('tr');

        // Pass form reference to modal for submisison on yes/ok
        $(this).find('.modal-footer #js-confirmDeleteConsumer').data('tr', tr);
    });

    // Form confirm (yes/ok) handler, submits form
    $('#js-confirmDeleteModal').on('click', '.modal-footer #js-confirmDeleteConsumer', function () {
        var consumerID = $(this).data('tr').data('consumerid');
        $.ajax({
            url: '/loyalty-card/consumers/' + consumerID,
            dataType: 'json',
            type: 'delete',
            success: function (data) {
            }
        });
    });

    // ------ CREATE CONSUMER ------ //
    // reset the form when click cancel
    $('#js-createConsumerModal').on('click', '.modal-footer #js-cancelCreateConsumer', function () {
        $('#js-createConsumerForm').trigger('reset');
    });

    // trigger form submit when click confirm
    $('#js-createConsumerModal').on('click', '.modal-footer #js-confirmCreateConsumer', function () {
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
