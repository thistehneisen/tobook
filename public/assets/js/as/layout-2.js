/*jslint browser: true, nomen: true, unparam: true*/
/*global $, jQuery */
'use strict';

(function ($) {
    $(function () {
        var $body = $('body'),
            $main = $('#as-main-panel'),
            $timetable = $('#as-timetable'),
            dataStorage = {hash: $body.data('hash')};

        //----------------------------------------------------------------------
        // Custom method
        //----------------------------------------------------------------------
        /**
         * Change mouse cursor to indicate AJAX loading
         *
         * @return {void}
         */
        $body.showLoading = function () {
            $(this).css('cursor', 'progress');
        };

        /**
         * Change mouse cursor back to normal
         *
         * @return {void}
         */
        $body.hideLoadding = function () {
            $(this).css('cursor', 'default');
        };

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

                // Select the first service time
                $serviceTime.find('.btn-group:first button').addClass('active');
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
                $body.showLoading();
                // Send AJAX to load employees of this service
                $.ajax({
                    url: $('input[name=employee-url]').val(),
                    type: 'POST',
                    data: dataStorage
                }).done(function (data) {
                    $main.append(data);
                    $body.hideLoadding();
                }).fail(function () {

                });
            }
        });

        // When user clicks on an employee
        $main.on('click', 'a.as-employee', function (e) {
            e.preventDefault();
            var $this = $(this);
            dataStorage.employeeId = $this.data('employee-id');

            $body.showLoading();
            $.ajax({
                url: $('input[name=timetable-url]').val(),
                type: 'POST',
                data: dataStorage
            }).done(function (data) {
                $body.hideLoadding();
                $timetable.html(data);
                var startDate = $timetable.find('input[name=start-date]').val();
                $timetable.find('a[data-date='+startDate+']')
                    .removeClass('btn-default')
                    .addClass('btn-selected');
            });
        });

    });
}(jQuery));
