/*jslint browser: true, nomen: true, unparam: true, node: true*/
/*global $, jQuery, alertify*/
'use strict';

(function ($) {
    $(function () {
        var categories = $('div.as-category'),
            services = $('div.as-service'),
            body = $('body'),
            form = $('#varaa-as-bookings'),
            step1 = $('#as-step-1'),
            step2 = $('#as-step-2'),
            step3 = $('#as-step-3'),
            step4 = $('#as-step-4'),
            title1 = $('#as-title-1'),
            title2 = $('#as-title-2'),
            title3 = $('#as-title-3'),
            title4 = $('#as-title-4'),
            dataStorage = {
                hash: body.data('hash')
            };

        var fnLoadTimeTable = function() {
            $.ajax({
                url: step3.data('url'),
                type: 'POST',
                data: dataStorage
            }).done(function (data) {
                step3.find('div.panel-body').html(data);
            }).fail(function () {

            });
        };

        form.on('click', 'div.collapsable', function (e) {
            e.preventDefault();
            if ($(e.target).is('#as-datepicker') === false) {
                var $this = $(this);
                $($this.attr('href')).collapse('toggle');
            }
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
            title1.find('span').text($this.data('service'));
            title1.find('i').removeClass('hide');
            title1.addClass('collapsable');

            step2.collapse('show');
            title2.addClass('collapsable');

            // Assign serviceId to dataStorage
            dataStorage.serviceId = $this.val();

            $.ajax({
                url: step2.data('url'),
                type: 'POST',
                data: dataStorage
            }).done(function (data) {
                step2.find('.panel-body').html(data);
            }).fail(function (err) {
                console.log(err);
            });
        });

        $('#as-datepicker').on('click', function () {
            var $this = $(this);
            $this.datepicker({
                format: 'yyyy-mm-dd',
                startDate: new Date(),
                todayBtn: true,
                todayHighlight: true,
                weekStart: 1,
                language: body.data('locale')
            }).datepicker('show');
        });

        form.on('change', 'input[name=employee_id]', function () {
            var $this = $(this);
            title2.find('i').removeClass('hide');
            step3.collapse('show');
            title3.addClass('collapsable');

            // Asign employeeId to dataStorage
            dataStorage.employeeId = $this.val();
            fnLoadTimeTable();
        });

        form.on('click', 'a.as-date', function (e) {
            e.preventDefault();
            var $this = $(this);

            // Show loading
            step3.find('div.as-timetable-content').hide();
            step3.find('div.as-loading').show();

            // Assign date to dataStorage
            dataStorage.date = $this.data('date');

            fnLoadTimeTable();
        });

    });
}(jQuery));
