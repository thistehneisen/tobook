/*jslint browser: true, nomen: true, unparam: true, node: true*/
/*global $, jQuery, alertify*/
'use strict';

(function ($) {
    $(function () {
        var categories = $('div.as-category'),
            services = $('div.as-service'),
            form = $('#varaa-as-bookings'),
            step1 = $('#as-step-1'),
            step2 = $('#as-step-2'),
            step3 = $('#as-step-3'),
            step4 = $('#as-step-4'),
            title1 = $('#as-title-1'),
            title2 = $('#as-title-2'),
            title3 = $('#as-title-3'),
            title4 = $('#as-title-4');

        form.on('click', 'a.collapsable', function (e) {
            e.preventDefault();
            var $this = $(this);
            $($this.attr('href')).collapse('toggle');
        });

        $('input[name=category_id]').on('change', function () {
            var $this = $(this);
            categories.hide(function () {
                $('#as-category-'+$this.val()+'-services').show();
            });
        });

        $('p.as-back').on('click', function () {
            services.hide(function () {
                categories.show();
            });
        });

        $('input[name=service_id]').on('change', function () {
            var $this = $(this);
            title1.children('span').text($this.data('service'));
            title1.children('i').removeClass('hide');
            title1.addClass('collapsable');

            step2.collapse('show');
            title2.addClass('collapsable');
        });

    });
}(jQuery));
