/*jslint browser: true, nomen: true, unparam: true*/
/*global $, jQuery */
'use strict';

(function ($) {
    $(function () {
        var dataStorage = {},
            main = $('#main-panel');

        // When user clicks on a category name
        $('a.as-category').on('click', function (e) {
            e.preventDefault();
            var $this = $(this);

            // Highlight it
            $('a.as-category.active').removeClass('active');
            $this.addClass('active');

            $('div.as-services').hide();
            $('#as-category-'+$this.data('category-id')+'-services').slideDown();
        });

        // When user clicks on a service
        $('a.as-service').on('click', function (e) {
            e.preventDefault();
            var $this = $(this),
                serviceId = $this.data('service-id'),
                $serviceTime = $this.siblings('.as-service-time'),
                $employee = $('#as-service-'+serviceId+'-employees');

            // Show/hide service time
            if ($serviceTime.is(':visible')) {
                $this.removeClass('active');
                $serviceTime.hide();
            } else {
                $('a.as-service.active').removeClass('active');
                $this.addClass('active');
                $serviceTime.show();
            }

            // Show extra services if available
            $('div.as-extra-services').hide();
            $('#as-service-'+serviceId+'-extra-services').show();

            dataStorage.serviceId = serviceId;
            // Hide all visible employee elements
            $('div.as-employees').hide();
            if ($employee.length > 0) {
                $employee.show();
            } else {
                // Send AJAX to load employees of this service
                $.ajax({
                    url: $('input[name=employee-url]').val(),
                    type: 'POST',
                    data: dataStorage
                }).done(function (data) {
                    main.append(data);
                }).fail(function () {

                });
            }
        });

    });
}(jQuery));
