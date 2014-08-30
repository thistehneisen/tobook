/*jslint browser: true, nomen: true, unparam: true*/
/*global $, jQuery*/
'use strict';

$(document).ready(function () {
    $('#js-confirmDeleteModal').on('show.bs.modal', function (e) {
        var form = $(e.relatedTarget).closest('form');

        // Pass form reference to modal for submisison on yes/ok
        $(this).find('.modal-footer #confirm').data('form', form);
    });

    // Form confirm (yes/ok) handler, submits form
    $('#js-confirmDeleteModal').on('click', '.modal-footer #confirm', function () {
        $(this).data('form').submit();
    });
});
