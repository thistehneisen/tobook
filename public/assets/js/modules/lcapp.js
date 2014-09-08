/*jslint browser: true, nomen: true, unparam: true*/
/*global $, jQuery, external, GL_VARAA*/
'use strict';

// global function to be accessed from the desktop app
function showConsumerInfo(consumerId) {
    $.ajax({
        url: GL_VARAA.getRoute('consumers', {'id': consumerId}),
        dataType: 'html',
        type: 'GET',
        success: function (data) {
            $('#consumer-info').html(data);
        }
    });
}

$(document).ready(function () {
    var showMessage = function (title, body) {
            $('#js-messageModal .modal-title').text(title);
            $('#js-messageModal .modal-body').html(body);
            $('#js-messageModal').modal('show');
        },
        consumerTr = $('#consumer-table tbody tr');

    // ------ CONSUMER INFO FETCHING ------ //
    consumerTr.click(function () {
        if (!$(this).hasClass('selected')) {
            consumerTr.removeClass('selected');
            $(this).addClass('selected');

            showConsumerInfo($(this).data('consumerid'));
        }
    });

    // ------ CREATE CONSUMER ------ //
    // reset the form when click cancel
    $('#js-createConsumerModal').on('click', '#js-cancelCreateConsumer', function () {
        $('#js-createConsumerForm').trigger('reset');
    });

    // trigger form submit when click confirm
    $('#js-createConsumerModal').on('click', '#js-confirmCreateConsumer', function () {
        $.ajax({
            url: '/loyalty-card/consumers',
            dataType: 'json',
            type: 'post',
            data: $('#js-createConsumerForm').serialize(),
            success: function (data) {
                if (!data.success) {
                    var errorMsg = '';

                    $.each(data.errors, function (index, error) {
                        errorMsg += '<p> - ' + error + '</p>';
                    });

                    showMessage('Create New Consumer', errorMsg);
                } else {
                    showMessage('Create New Consumer', data.message);
                    window.location.reload();
                }
            },
        });
    });

    // ------ HIDE CONSUMER INFO ------ //
    $('#consumer-info').on('click', '#js-back', function () {
        $('#consumer-info').html('');
        $('#consumer-table tr').removeClass('selected');
    });

    // ------ ADD STAMP ------ //
    $('#consumer-info').on('click', '#js-addStamp', function () {
        var offerID = $(this).data('offerid');
        $.ajax({
            url: '/loyalty-card/consumers/' + $(this).data('consumerid'),
            dataType: 'json',
            type: 'put',
            data: {
                action: 'addStamp',
                offerID: offerID,
            },
            success: function (data) {
                showMessage('Add Stamp', data.message);
                $('#js-currentStamp' + offerID).text(data.stamps);
                $('#js-free' + offerID).text(data.free);
            }
        });
    });

    // ------ USE OFFER ------ //
    $('#consumer-info').on('click', '#js-useOffer', function () {
        var offerID = $(this).data('offerid');
        $.ajax({
            url: '/loyalty-card/consumers/' + $(this).data('consumerid'),
            dataType: 'json',
            type: 'put',
            data: {
                action: 'useOffer',
                offerID: offerID,
            },
            success: function (data) {
                showMessage('Use Offer', data.message);
                $('#js-free' + offerID).text(data.free);
            }
        });
    });

    // ------ ADD POINT ------ //
    $('#js-givePointModal').on('show.bs.modal', function (e) {
        // Pass form reference to modal for submisison on yes/ok
        $(this).find('.modal-footer #js-confirmGivePoint').data('consumerid', $(e.relatedTarget).data('consumerid'));
    });

    $('#js-givePointModal').on('click', '#js-confirmGivePoint', function () {
        var consumerID = $(this).data('consumerid');

        $.ajax({
            url: '/loyalty-card/consumers/' + consumerID,
            dataType: 'json',
            type: 'put',
            data: {
                action : 'addPoint',
                points : $('#points').val(),
            },
            success: function (data) {
                if (!data.success) {
                    var errorMsg = '';

                    $.each(data.errors, function (index, error) {
                        errorMsg += '- ' + error + '\n';
                    });

                    showMessage('Give Points', errorMsg);
                } else {
                    $('#js-givePointModal').modal('hide');
                    $('#js-currentPoint').text(data.points);
                    showMessage('Give Points', data.message);
                }
            }
        });
        $('#js-givePointForm').trigger('reset');
    });

    $('#js-givePointModal').on('click', '#js-cancelGivePoint', function () {
        $('#js-givePointForm').trigger('reset');
    });

    // ------ USE POINT ------//
    $('#consumer-info').on('click', '#js-useVoucher', function (e) {
        var consumerId = $(this).data('consumerid'),
            voucherId = $(this).data('voucherid'),
            required = parseInt($(this).data('required'), 10),
            currentPoint = parseInt($('#js-currentPoint').text(), 10);

        if (currentPoint >= required) {
            $.ajax({
                url: '/loyalty-card/consumers/' + consumerId,
                dataType: 'json',
                type: 'put',
                data: {
                    action: 'usePoint',
                    voucherID: voucherId,
                },
                success: function (data) {
                    $('#js-givePointModal').modal('hide');
                    $('#js-currentPoint').text(data.points);

                    showMessage('Use Points', data.message);
                },
            });
        } else {
            showMessage('Use Points', 'Not enough point');
        }
    });

    // ------ DELETE CONSUMER ------ //
    // $('#js-confirmDeleteModal').on('show.bs.modal', function (e) {
    //     var tr = $(e.relatedTarget).closest('tr');

    //     // Pass form reference to modal for submisison on yes/ok
    //     $(this).find('.modal-footer #js-confirmDeleteConsumer').data('tr', tr);
    // });

    // // Form confirm (yes/ok) handler, submits form
    // $('#js-confirmDeleteModal').on('click', '.modal-footer #js-confirmDeleteConsumer', function () {
    //     var consumerID = $(this).data('tr').data('consumerid');
    //     $.ajax({
    //         url: '/loyalty-card/consumers/' + consumerID,
    //         dataType: 'json',
    //         type: 'delete',
    //         success: function (data) {
    //         }
    //     });
    // });

    // ------ WRITE CARD ------ //
    $('#consumer-info').on('click', '#js-writeCard', function () {
        var consumerId = $(this).data('consumerid');
        external.SetCardWriteMode(true);

        if (!confirm('Put the card near the NFC card reader and press OK')) {
            external.SetCardWriteMode(false);
            return false;
        }

        $(this).prop('disabled', true);

        if (external.WriteCard(consumerId) === true) {
            showMessage('Write to card', 'Successful!');
        } else {
            showMessage('Write to card', 'Error writing to card!');
        }

        $(this).prop('disabled', false);
        return false;
    });
});
