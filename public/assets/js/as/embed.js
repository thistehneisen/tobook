/*jslint browser: true, nomen: true, unparam: true, node: true*/
/*global $, jQuery, document, alertify, location, window*/
'use strict';

(function ($) {
    $(function () {
        $('#datepicker').datepicker({
            format: 'yyyy-mm-dd',
            startDate: new Date(),
            todayBtn: true,
            todayHighlight: true
        }).on('changeDate', function (e) {
            $('#txt-date').val(e.format());
        });

        $('.accordion').collapse();

        $('.list-group-item-heading').on('click', function (e) {
            e.preventDefault();
            $(this).siblings('div').find('div.services').slideToggle();
        });

        $('.btn-fancybox').fancybox();

        $('a[class=btn-add-extra-service]').click(function (e) {
            e.preventDefault();
            var service_id = $(this).data('service-id'),
                hash = $(this).data('hash');
            $.fancybox.open({
                padding: 5,
                width: 400,
                title: '',
                autoSize: false,
                autoScale: true,
                autoWidth: false,
                autoHeight: true,
                fitToView: false,
                href: $('#extra-service-url').val(),
                type: 'ajax',
                ajax: {
                    type: 'GET',
                    data: {
                        service_id: service_id,
                        hash: hash,
                        date: $('#txt-date').val()
                    }
                },
                helpers: {
                    overlay: {
                        locked: false
                    }
                },
                autoCenter: false
            });
        });

        $('li[class=active]').click(function (e) {
            $('ul > li').removeClass('slot-selected');
            //The service length must be divisible by 15
            var slots = (parseInt($(this).data('booking-length'), 10) / 15) - 1,
                siblings = $(this).nextAll(),
                employee_id = $(this).data('employee-id'),
                end_time = null;
            $('#start_time-' + employee_id).val($(this).data('start-time'));
            $(this).addClass('slot-selected');
            siblings.each(function () {
                if (slots <= 0) {
                    return;
                }
                $(this).addClass('slot-selected');
                end_time = $(this).data('start-time');
                if ($(this).hasClass('booked')) {
                    $('ul > li').removeClass('slot-selected');
                }
                slots -= 1;
            });
            //Does not have enought slot to book
            if (slots > 0) {
                $('ul > li').removeClass('slot-selected');
                return;
            }
            $('.li-start-time').hide();
            $('.li-end-time').hide();
            $('.li-start-time-' + employee_id).show();
            $('.li-end-time-' + employee_id).show();
            $('.start-time-' + employee_id).text($(this).data('start-time'));
            $('.end-time-' + employee_id).text(end_time);
        });

        $('.btn-select-service-time').click(function (e) {
            e.preventDefault();
            $('.btn-select-service-time').removeClass('active');
            $(this).parent().find('a').addClass('active');
            var url = $('#btn-add-service-' + $(this).data('service-id')).prop('href') + '&service_time=' + $(this).data('service-time');
            $('#btn-add-service-' + $(this).data('service-id')).prop('href', url);
        });

        $('a[class=btn-make-appointment]').click(function (e) {
            e.preventDefault();
            var checkout_url = $(this).data('checkout-url'),
                employee_id = $(this).data('employee-id');
            $.ajax({
                type: 'POST',
                url: $('#add_service_url').val(),
                data: $('#form-employee-' + employee_id).serialize(),
                dataType: 'json'
            }).done(function (data) {
                window.location.href = checkout_url;
            }).fail(function (data) {
                alertify.alert(data.responseJSON.message);
            });
        });

        $('#btn-confirm-booking').click(function (e) {
            e.preventDefault();
            var action_url = $(this).data('action-url'),
                success_url = $(this).data('success-url');
            $.ajax({
                type: 'POST',
                url: action_url,
                data: $('#form-confirm-booking').serialize(),
                dataType: 'json'
            }).done(function (data) {
                window.location.href = success_url;
            }).fail(function (data) {
                alertify.alert(data.responseJSON.message);
            });
        });

        $('a[class=btn-remove-item-from-cart]').click(function (e) {
            e.preventDefault();
            var uuid = $(this).data('uuid'),
                action_url = $(this).data('action-url'),
                hash = $(this).data('hash');
            $.ajax({
                type: 'POST',
                url: action_url,
                data: {
                    uuid: uuid,
                    hash: hash
                },
                dataType: 'json'
            }).done(function (data) {
                if (data.success_url) {
                    window.location.href = data.success_url;
                }
                location.reload();
            }).fail(function (data) {
                alertify.alert(data.responseJSON.message);
            });
        });
    });
}(jQuery));
