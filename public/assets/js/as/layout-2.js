/*jslint browser: true, nomen: true, unparam: true*/
/*global $, jQuery */
'use strict';

(function ($) {
    $(function () {

        // When user clicks on a category name
        $('a.as-category').on('click', function (e) {
            e.preventDefault();
            var $this = $(this);

            // Highlight it
            $('a.as-category.active').removeClass('active');
            $this.addClass('active');

            $('div.as-services').hide();
            $('#'+$this.attr('rel')).slideDown();
        });

        $('a.as-service').on('click', function (e) {
            e.preventDefault();
            var $this = $(this),
                serviceTime = $this.siblings('.as-service-time');

            if (serviceTime.is(':visible')) {
                $this.removeClass('active');
                serviceTime.hide();
            } else {
                $('a.as-service.active').removeClass('active');
                $this.addClass('active');
                serviceTime.show();
            }
        });

    });
}(jQuery));
