/*jslint browser: true, nomen: true, unparam: true*/
/*global $, jQuery, external, VARAA, confirm*/
'use strict';

// global function to be accessed from the desktop app
function showConsumerInfo(consumerId) {
    $.ajax({
        url: VARAA.getRoute('consumers', {'id': consumerId}),
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
        consumerTr = $('#consumer-table tbody tr'),
        createConsumerForm = $('#js-createConsumerForm');

    // ------ CONSUMER INFO FETCHING ------ //
    consumerTr.click(function () {
        if (!$(this).hasClass('selected')) {
            consumerTr.removeClass('selected');
            $(this).addClass('selected');

            showConsumerInfo($(this).data('consumerid'));
        }
    });

    // ------ CREATE CONSUMER ------ //
    createConsumerForm.bootstrapValidator({
        message: 'This value is not valid',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            first_name: {
                validators: {
                    notEmpty: {
                        message: 'First name is required'
                    }
                }
            },
            last_name: {
                validators: {
                    notEmpty: {
                        message: 'Last name is required'
                    }
                }
            },
            email: {
                validators: {
                    notEmpty: {
                        message: 'Email is required'
                    },
                    emailAddress: {
                        message: 'Not valid email address'
                    }
                }
            },
            phone: {
                validators: {
                    notEmpty: {
                        message: 'Phone number is required'
                    },
                    numeric: {
                        message: 'Phone number must contain only numbers'
                    }
                }
            },
        }
    });

    // reset the form when click cancel
    $('#js-createConsumerModal').on('click', '#js-cancelCreateConsumer', function () {
        createConsumerForm.trigger('reset');
        createConsumerForm.bootstrapValidator('resetForm', true);
        $('#js-alert').addClass('hidden');
    });

    // trigger form submit when click confirm
    createConsumerForm.on('success.form.bv', function (e) {
        e.preventDefault();

        $.ajax({
            url: $(this).prop('action'),
            dataType: 'json',
            type: 'post',
            data: $(this).serialize(),
            success: function (data) {
                if (data.result === true) {
                    window.location.reload();
                } else {
                    $('#js-alert').text('This customer already exists').removeClass('hidden');
                }
            }
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
            url: $(this).data('url'),
            dataType: 'json',
            type: 'put',
            data: {
                action: 'addStamp',
                offerID: offerID,
            },
            success: function (data) {
                showMessage('Add Stamp', data.message);
                $('#js-currentStamp' + offerID).text(data.stamps);
            }
        });
    });

    // ------ USE OFFER ------ //
    $('#consumer-info').on('click', '#js-useOffer', function () {
        var offerID = $(this).data('offerid');
        $.ajax({
            url: $(this).data('url'),
            dataType: 'json',
            type: 'put',
            data: {
                action: 'useOffer',
                offerID: offerID,
            },
            success: function (data) {
                showMessage('Use Offer', data.message);
                $('#js-currentStamp' + offerID).text(data.stamps);
            }
        });
    });

    // ------ ADD POINT ------ //
    $('#js-givePointModal').on('show.bs.modal', function (e) {
        // Pass form reference to modal for submisison on yes/ok
        $(this).find('.modal-footer #js-confirmGivePoint').data('url', $(e.relatedTarget).data('url'));
    });

    $('#js-givePointModal').on('click', '#js-confirmGivePoint', function () {
        $.ajax({
            url: $(this).data('url'),
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
        var voucherId = $(this).data('voucherid'),
            required = parseInt($(this).data('required'), 10),
            currentPoint = parseInt($('#js-currentPoint').text(), 10);

        if (currentPoint >= required) {
            $.ajax({
                url: $(this).data('url'),
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
