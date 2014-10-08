/*jslint browser: true, nomen: true, unparam: true*/
/*global $, jQuery, alertify, olut*/
'use strict';

$(document).ready(function () {
    $(function () {
        $('table.table-crud').find('a.btn-danger').click('on', function (event) {
            event.preventDefault();
            var $this = $(this);

            alertify.confirm('{{ trans('olut::olut.confirm') }}', function (e) {
                if (e) {
                    window.location = $this.attr('href');
                }
            });
        });
    });
});
