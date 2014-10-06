/*jslint browser: true, nomen: true, unparam: true, node: true*/
/*global $, jQuery, alertify, purl*/
'use strict';

(function ($) {
    $(function () {
        $('.accordion').collapse();

        $('.list-group-item-heading').on('click', function (e) {
            e.preventDefault();
            $(this).siblings('div').find('div.services').slideToggle('fast');
        });

        $('.btn-fancybox').fancybox();

        $('a.btn-add-extra-service').click(function (e) {
            e.preventDefault();
            var service_id = $(this).data('service-id'),
                hash = $(this).data('hash'),
                service_time = $('body').data('service-time');
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
                        service_time : service_time,
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

        $('li.active').click(function (e) {
            $('ul > li').removeClass('slot-selected');
            if ($(this).hasClass('inactive')) {
                return;
            }
            //The service length must be divisible by 15
            var slots = (parseInt($(this).data('booking-length'), 10) / 15) - 1,
                siblings = $(this).nextAll(),
                employee_id = $(this).data('employee-id'),
                end_time = null,
                plustime = (parseInt($(this).data('plustime'), 10) / 15),
                time,
                minute,
                hour,
                new_end_time;

            slots += plustime;

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

            //Does not have enough slot to book
            if (slots > 0) {
                $('ul > li').removeClass('slot-selected');
                return;
            }

            time = end_time.split(':');
            //increase 15 minutes to end time text
            minute = (parseInt(time[1], 10) + 15);
            hour = (minute === 60) ? parseInt(time[0], 10) + 1 : time[0];
            minute = (minute === 60) ? '00' : minute;
            new_end_time = hour + ':' + minute;
            $('.li-start-time').hide();
            $('.li-end-time').hide();
            $('.li-start-time-' + employee_id).show();
            $('.li-end-time-' + employee_id).show();
            $('.start-time-' + employee_id).text($(this).data('start-time'));
            $('.end-time-' + employee_id).text(new_end_time);
        });

        $('.btn-select-service-time').click(function (e) {
            e.preventDefault();
            $('.btn-select-service-time').removeClass('active');
            $(this).parent().find('a').addClass('active');
            $("body").data("service-time", $(this).data('service-time'));
            if ($('#btn-add-service-' + $(this).data('service-id')).length) {
                var url = $('#btn-add-service-' + $(this).data('service-id')).prop('href'),
                    service_time = purl(url).param('service_time');
                url = url.replace("service_time=" + service_time, "service_time=" + $(this).data('service-time'));
                $('#btn-add-service-' + $(this).data('service-id')).prop('href', url);
            }
        });

        $('a.btn-make-appointment').click(function (e) {
            e.preventDefault();
            var checkout_url = $(this).data('checkout-url'),
                employee_id = $(this).data('employee-id');
            $.ajax({
                type: 'POST',
                url: $('#add_service_url').val(),
                data: $('#form-employee-' + employee_id).serialize(),
                dataType: 'json'
            }).done(function (data) {
                window.location.href = checkout_url + '&cart_id=' + data.cart_id;
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
                if (data.success) {
                    window.location.href = success_url;
                }
            }).fail(function (data) {
                alertify.alert(data.responseJSON.message);
            });
        });

        $('a.btn-remove-item-from-cart').click(function (e) {
            e.preventDefault();
            var uuid = $(this).data('uuid'),
                action_url = $(this).data('action-url'),
                cart_id = $(this).data('cart-id'),
                cart_detail_id = $(this).data('cart-detail-id'),
                hash = $(this).data('hash');
            $.ajax({
                type: 'POST',
                url: action_url,
                data: {
                    uuid           : uuid,
                    hash           : hash,
                    cart_id        : cart_id,
                    cart_detail_id : cart_detail_id
                },
                dataType: 'json'
            }).done(function (data) {
                if (data.success_url) {
                    window.location.href = data.success_url;
                }
                window.location.reload();
            }).fail(function (data) {
                alertify.alert(data.responseJSON.message);
            });
        });

        $('#toggle_term').click(function (e) {
            e.preventDefault();
            $('#terms_body').slideToggle();
        });

        $('#btn-checkout-submit').click(function (e) {
            e.preventDefault();
            var term_enabled = parseInt($(this).data('term-enabled'), 10);
            //yes and required
            if (term_enabled === 3 && !$('#terms').is(':checked')) {
                return alertify.alert($(this).data('term-error-msg'));
            }
            $('#form-confirm-booking').submit();
        });
    });
}(jQuery));
