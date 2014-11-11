/*jslint browser: true, nomen: true, unparam: true*/
/*global jQuery */

(function ($) {
    'use strict';

    $(function () {
        var $body = $('body'),
            $main = $('#as-main-panel'),
            $timetable = $('#as-timetable'),
            $elSelect = $('#as-select'),
            $elCheckout = $('#as-checkout'),
            $elSuccess = $('#as-success'),
            dataStorage = {hash: $body.data('hash')},
            fnLoadTimetable;

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
            $('.js-loading').show();
        };

        /**
         * Change mouse cursor back to normal
         *
         * @return {void}
         */
        $body.hideLoadding = function () {
            $(this).css('cursor', 'default');
            $('.js-loading').hide();
        };

        // When user clicks on a category name
        $('a.as-category').on('click', function (e) {
            e.preventDefault();
            var $this = $(this),
                $service = $('#as-category-'+$this.data('category-id')+'-services');

            // Highlight it
            $('a.as-category.active').removeClass('active');
            $this.addClass('active');

            $('div.as-services').hide();
            $service.slideDown();
        });

        // When user clicks on a service
        $('a.as-service').on('click', function (e) {
            e.preventDefault();
            var $this = $(this),
                serviceId = $this.data('service-id'),
                serviceTimeId = $this.data('service-time-id'),
                $serviceTime = $this.siblings('.as-service-time'),
                $employee = $('#as-service-' + serviceId + '-employees');

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
            $('#as-service-' + serviceId + '-extra-services').show();

            dataStorage.serviceId     = serviceId;
            dataStorage.serviceTimeId = serviceTimeId;

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

        fnLoadTimetable = function () {
            $body.showLoading();
            $.ajax({
                url: $('input[name=timetable-url]').val(),
                type: 'POST',
                data: dataStorage
            }).done(function (data) {
                $body.hideLoadding();
                $timetable.html(data);
                var startDate = $timetable.find('input[name=start-date]').val();
                $timetable.find('a.btn-as-timetable[data-date='+startDate+']')
                    .removeClass('btn-default')
                    .addClass('btn-selected');
            });
        };

        $main.on('click', 'button.btn-service-time', function () {
            var $this = $(this);
            // Attach data
            if (typeof $this.data('service-id') !== 'undefined') {
                dataStorage.serviceId = $this.data('service-id');
            }

            if (typeof $this.data('service-time-id') !== 'undefined') {
                dataStorage.serviceTimeId = $this.data('service-time-id');
            }

            // Change style
            $main.find('button.btn-service-time.active').removeClass('active');
            $this.addClass('active');
            $this.siblings('button').addClass('active');

            // Reload the timetable
            if ($timetable.is(':empty') === false) {
                fnLoadTimetable();
            }
        });

        // When user clicks on an employee
        $main.on('click', 'a.as-employee', function (e) {
            e.preventDefault();
            var $this = $(this),
                selectedDate = $timetable.find('a.btn-as-timetable:first');

            // Assign data
            dataStorage.employeeId = $this.data('employee-id');
            if (selectedDate.length > 0) {
                dataStorage.date = selectedDate.data('date');
            }

            // Highlight selected employee
            $main.find('a.as-employee.active').removeClass('active');
            $this.addClass('active');

            fnLoadTimetable();
        });

        // When user clicks on a date in nav
        $timetable.on('click', 'a.btn-as-timetable', function (e) {
            e.preventDefault();

            dataStorage.date = $(this).data('date');
            fnLoadTimetable();
        });

        // When user clicks on a time in timetable
        $timetable.on('click', 'a.as-time', function (e) {
            e.preventDefault();
            var $this = $(this), token;

            // Extra start time from selected time
            token = $this.text().split(' - ');

            // Assign data
            dataStorage.date       = $this.data('date');
            dataStorage.time       = token[0] || '';
            dataStorage.employeeId = $this.data('employee-id');

            // Highlight
            $timetable.find('a.as-time.active').removeClass('active');
            $this.addClass('active');


            $body.showLoading();
            // Send AJAX request to add booking service
            $.ajax({
                url: $('input[name=booking-url]').val(),
                type: 'POST',
                data: {
                    service_id   : dataStorage.serviceId,
                    service_time : dataStorage.serviceTimeId,
                    employee_id  : dataStorage.employeeId,
                    hash         : dataStorage.hash,
                    booking_date : dataStorage.date,
                    start_time   : dataStorage.time
                }
            }).fail(function (e) {
                if (typeof e.responseJSON.message !== 'undefined') {
                    alert(e.responseJSON.message);
                }
            }).then(function(e) {
                // Get cart ID
                dataStorage.cartId = e.cart_id;

                // Load checkout form
                return $.ajax({
                    url: $('input[name=checkout-url]').val(),
                    data: dataStorage
                });
            }).done(function (data) {
                $body.hideLoadding();

                $elSelect.hide();
                // Show checkout form
                $elCheckout.html(data).show();

                // Scroll to the beginning of form
                $(document).scrollTop(0);
            });
        });

        // Remove item from cart
        $elCheckout.on('click', 'a.as-remove-cart', function (e) {
            e.preventDefault();
            var $this = $(this);
            $body.showLoading();
            $.ajax({
                url: $this.attr('href'),
                type: 'POST',
                data: {
                    hash           : $this.data('hash'),
                    uuid           : $this.data('uuid'),
                    cart_id        : $this.data('cart-id'),
                    cart_detail_id : $this.data('cart-detail-id')
                }
            }).done(function () {
                $body.hideLoadding();
                $('#as-cart-item-' + $this.data('cart-detail-id')).slideUp();
            });
        });

        // When user submits information form
        $elCheckout.on('submit', '#frm-customer-info', function (e) {
            e.preventDefault();
            var $this = $(this);

            $body.showLoading();
            $.ajax({
                url: $this.attr('action'),
                type: $this.attr('method'),
                data: $this.serialize()
            }).done(function (data) {
                $body.hideLoadding();
                $elCheckout.find('#as-frm-confirm').html(data);
            });
        });

        // When user confirms booking
        $elCheckout.on('submit', '#frm-confirm', function (e) {
            e.preventDefault();
            var $this = $(this);

            $body.showLoading();
            $.ajax({
                url: $this.attr('action'),
                type: $this.attr('method'),
                data: $this.serialize(),
                dataType: 'json'
            }).done(function (data) {
                $body.hideLoadding();

                if (data.success === true) {
                    $elCheckout.hide();
                    $elSuccess.find('p').text(data.message);
                    $elSuccess.show();
                }
            });
        });

        // When user clicks on Cancel button
        $elCheckout.on('click', 'a.btn-as-cancel', function (e) {
            e.preventDefault();

            $elCheckout.hide();
            $elSelect.show();
        });
    });
}(jQuery));
