/*jslint browser: true, nomen: true, unparam: true*/
/*global $, jQuery*/
'use strict';

$(document).ready(function () {
    // ------ HIDE CONSUMER INFO ------ //
    $(this).on('click', '#js-back', function () {
        $('#consumer-info').html('');
        $('#consumer-table tr').removeClass('selected');
    });

    // ------ ADD STAMP ------ //
    $(this).on('click', '#js-addStamp', function () {
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
                $('#js-messageModal').find('.modal-title').text('Add Stamp');
                $('#js-messageModal').find('.modal-body').text(data.message);
                $('#js-messageModal').modal('show');
                $('#js-currentStamp' + offerID).text(data.stamps);
                $('#js-free' + offerID).text(data.free);
            }
        });
    });

    // ------ USE OFFER ------ //
    $(this).on('click', '#js-useOffer', function () {
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
                $('#js-messageModal').find('.modal-title').text('Use Offer');
                $('#js-messageModal').find('.modal-body').text(data.message);
                $('#js-messageModal').modal('show');
                $('#js-free' + offerID).text(data.free);
            }
        });
    });

    // ------ ADD POINT ------ //
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
            data: {
                action : 'addPoint',
                points : $('#points').val(),
            },
            success: function (data) {
                $('#js-messageModal').find('.modal-title').text('Give Points');

                if (!data.success) {
                    var errorMsg = '';

                    $.each(data.errors, function (index, error) {
                        errorMsg += '- ' + error + '\n';
                    });

                    $('#js-messageModal').find('.modal-body').text(errorMsg);
                    $('#js-messageModal').modal('show');
                } else {
                    $('#js-givePointModal').modal('hide');
                    $('#js-messageModal').find('.modal-body').text(data.message);
                    $('#js-messageModal').modal('show');
                    $('#js-currentPoint').text(data.points);
                }
            }
        });
        $('#js-givePointForm').trigger('reset');
    });

    $('#js-givePointModal').on('click', '.modal-footer #js-cancelGivePoint', function () {
        $('#js-givePointForm').trigger('reset');
    });

    // ------ USE POINT ------//
    $('#consumer-info').on('click', '#js-useVoucher', function (e) {
        var consumerID = $(this).data('consumerid'), voucherID = $(this).data('voucherid'), required = parseInt($(this).data('required'), 10), currentPoint = parseInt($('#js-currentPoint').text(), 10);
        $('#js-messageModal').find('.modal-title').text('Use Point');

        if (currentPoint >= required) {
            $.ajax({
                url: '/loyalty-card/consumers/' + consumerID,
                dataType: 'json',
                type: 'put',
                data: {
                    action : 'usePoint',
                    voucherID : voucherID,
                },
                success: function (data) {
                    $('#js-givePointModal').modal('hide');
                    $('#js-messageModal').find('.modal-body').text(data.message);
                    $('#js-messageModal').modal('show');
                    $('#js-currentPoint').text(data.points);
                },
            });
        } else {
            $('#js-messageModal').find('.modal-body').text('Not enough point');
            $('#js-messageModal').modal('show');
        }
    });

    // ------ CONSUMER INFO FETCHING ------ //
    $('#consumer-table tbody tr').click(function () {
        var selected = $(this).hasClass('selected');
        $('#consumer-table tr').removeClass('selected');

        if (!selected) {
            $(this).addClass('selected');

            $.ajax({
                url: $(this).data('url'),
                dataType: 'html',
                type: 'GET',
                success: function (data) {
                    $('#consumer-info').html(data);
                }
            });
        } else {
            $('#consumer-info').html('');
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
                $('#js-messageModal').find('.modal-title').text('Create New Consumer');
                if (!data.success) {
                    var errorMsg = '';

                    $.each(data.errors, function (index, error) {
                        errorMsg += '<p> - ' + error + '</p>';
                    });

                    $('#js-messageModal').find('.modal-body').html(errorMsg);
                    $('#js-messageModal').modal('show');
                } else {
                    $('#js-messageModal').find('.modal-body').text(data.message);
                    $('#js-messageModal').modal('show');
                    window.location.reload();
                }
            },
        });
    });

    // ------ WRITE CARD ------ //
    $(this).on('click', '#js-writeCard', function () {
        var consumer_id = $(this).data('consumerid');
        external.SetCardWriteMode(true);

        if (!confirm("Put the card near the NFC card reader and press OK")) {
            external.SetCardWriteMode(false);
            return false;
        }

        $(ctrl).prop("disabled", true);

        if (external.WriteCard(consumer_id) === true) {
            $(ctrl).val("Card written OK!");
        } else {
            alert("Error writing card!");
        }

        $(ctrl).prop("disabled", false);
        return false;
    });
});
