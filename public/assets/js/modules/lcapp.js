/*jslint browser: true, nomen: true, unparam: true*/
/*global $, jQuery*/
'use strict';

$(document).ready(function () {
    // TODO: if #consumers is only used as selector in JS, use this format: js-consumersTable
    $('#consumers tr').click(function () {
        // TODO: this is wrong
        // use data-consumerid="XX" in HTML and you can get it like $(this).data('consumerid') in jQuery
        // if it's broken in IE, consider using id="XX" in HTML and you can get $(this).prop('id') in jQuery
        var consumerID = $(this).index() + 1;

        // TODO: add class active to the row (with different background color)
        // so user know which row is selected
        $.ajax({
            url: '/loyalty-card/consumers/' + consumerID,
            dataType: 'html',
            type: 'GET',
            success: function (data) {
                console.log(data);
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
