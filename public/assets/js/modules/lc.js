/*jslint browser: true, nomen: true, unparam: true*/
/*global $, jQuery*/
'use strict';

$(document).ready(function () {
    $('#js-confirmDeleteModal').on('show.bs.modal', function (e) {
        $(this).find('.confirm').attr('href', $(e.relatedTarget).data('href'));
    });
});
