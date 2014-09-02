/*jslint browser: true, nomen: true, unparam: true*/
/*global $, jQuery*/
'use strict';

$(document).ready(function () {
    $('#js-consumerTable tr').click(function () {
        // if it's broken in IE, consider using id="XX" in HTML and you can get $(this).prop('id') in jQuery
        var consumerID = $(this).data('consumerid'), selected = $(this).hasClass('selected');
        $('#js-consumerTable tr').removeClass('selected');

        if (!selected) {
            $(this).addClass('selected');
        }
        // window.alert(consumerID);
        $.ajax({
            url: '/loyalty-card/consumers/' + consumerID,
            dataType: 'html',
            type: 'GET',
            success: function (data) {
                $('#details').html(data);
            }
        });
    });

    $('#js-confirmDeleteModal').on('show.bs.modal', function (e) {
        var form = $(e.relatedTarget).closest('form');

        // Pass form reference to modal for submisison on yes/ok
        $(this).find('.modal-footer #confirm').data('form', form);
    });

    // Form confirm (yes/ok) handler, submits form
    $('#js-confirmDeleteModal').on('click', '.modal-footer #confirm', function () {
        $(this).data('form').submit();
    });

    // reset the form when click cancel
    $('#js-createConsumerModal').on('click', '.modal-footer #cancel', function () {
        $('#js-createConsumerForm').trigger('reset');
    });

    // trigger form submit when click confirm
    $('#js-createConsumerModal').on('click', '.modal-footer #confirm', function () {
        $.ajax({
            // TODO: DON'T USE API IN THIS APP
            url: "/api/v1.0/lc/consumers",
            dataType: "json",
            type: "POST",
            data: $('#js-createConsumerForm').serialize(),
            success: function (data) {
                // TODO: handle case of validation error and other errors too
                if (data.message === 'Consumer created') {
                    window.location.reload();
                }
            }
        });
    });
});
