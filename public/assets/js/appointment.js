/*jslint browser: true, nomen: true, unparam: true, node: true*/
/*global $, jQuery, document, alertify, location, window*/
'use strict';

(function ($) {
    $(function () {
        $('.customer-tooltip').tooltip({
            selector: '',
            placement: 'top',
            container: 'body',
            trigger: 'hover',
            html: true
        });

        $('.selectpicker').selectpicker();

        $('.toggle-check-all-boxes').click(function () {
            var checkboxClass = ($(this).data('checkbox-class')) || 'checkbox';
            $('.' + checkboxClass).prop('checked', this.checked);
        });

        $(document).bind("ajaxSend", function () {
            $("#loading").show();
        }).bind("ajaxComplete", function () {
            $("#loading").hide();
        });
        $('#form-bulk').on('submit', function (e) {
            e.preventDefault();
            var $this = $(this);
            alertify.confirm('Confirm', $this.data('confirm'), function () {
                // user clicked "ok"
                $.ajax({
                    type: 'POST',
                    url: $this.attr('action'),
                    data: $this.serialize(),
                    dataType: 'json'
                }).done(function () {
                    alertify.alert('OK');
                    if ($('#mass-action').val() === 'destroy') {
                        $("#form-bulk [type=checkbox]:checked").each(function () {
                            $('#row-' + $(this).val()).remove();
                        });
                    }
                }).fail(function () {
                    alertify.alert('Something went wrong');
                });
            }, function () {
                //do cancel action
            });
        });

        // Allow to click on TR to select checkbox
        $('table.table-crud tr').on('click', function (event) {
            var target = $(event.target),
                $this = $(this),
                checkbox = $this.find('td:first input:checkbox'),
                checked = checkbox.prop('checked');
            if (target.is('td')) { //fix bug cannot click to the actual checkbox
                checkbox.prop('checked', !checked);
            }
        });

        // Date picker
        $(document).on('focus', '.date-picker', function () {
            $(this).datepicker({
                format: 'yyyy-mm-dd',
                weekStart: 1,
                autoclose: true,
                language: $('body').data('locale')
            });
        });
        $('#calendar_date').datepicker({
            format: 'yyyy-mm-dd',
            weekStart: 1,
            autoclose: true,
            calendarWeeks: true,
            language: $('body').data('locale')
        }).on('changeDate', function () {
            //use data-index-url attribute to prevent append date to date like yyyy-mm-dd/yyyy-mm-dd
            window.location.href = $(this).data('index-url') + "/" + $(this).val();
        });

        function calculateTotalMinute() {
            var total = parseInt($('#during').val(), 10)
                + parseInt($('#after').val(), 10)
                + parseInt($('#before').val(), 10);
            $('#total').val(total);
        }

        // calculate total time
        $('#before, #during, #after').change(function () {
            calculateTotalMinute();
        });

        $('button').click(function (e) {
            calculateTotalMinute();
        });

        // ------------------------ Backend Calendar ------------------------ //
        $('.as-calendar li.active, .as-calendar li.inactive').click(function () {
            var employee_id = $(this).data('employee-id'),
                booking_date = $(this).data('booking-date'),
                start_time = $(this).data('start-time');
            $('#employee_id').val(employee_id);
            $('#date').val(booking_date);
            $('#start_time').val(start_time);
            $('.fancybox').fancybox({
                padding: 5,
                width: 350,
                title: '',
                autoSize: false,
                autoWidth: false,
                autoHeight: true
            });
        });

        $('.as-calendar li.active, .as-calendar li.inactive').hover(function () {
            var _this = $(this),
                start_time = _this.data('start-time');

            //TODO: get text Varaa from somewhere else
            _this.append('<span class="hover">{0} {1}</span>'.format('Varaa', start_time));
        }, function () {
            $(this).find('.hover').remove();
        });

        // ------------------------ Button handlers ------------------------ //
        $('body').on('click', function (e) {
            $('a.popup-ajax').each(function () {
                //the 'is' for buttons that trigger popups
                //the 'has' for icons within a button that triggers a popup
                if (!$(this).is(e.target)
                        && $(this).has(e.target).length === 0
                        && $('.popover').has(e.target).length === 0) {
                    $(this).popover('hide');
                }
            });
        });
        function details_in_popup(link, div_id, booking_id) {
            $.ajax({
                url: link,
                data: { booking_id : booking_id },
                success: function (response) {
                    $('#' + div_id).html(response);
                }
            });
            return '<div class="popover_form" id="' + div_id + '"><img src="/assets/img/busy.gif"></div>';
        }
        $('a.popup-ajax').click(function (e) {
            e.preventDefault();
            // Hide previous open popover
            $('a.popup-ajax').popover('hide');
        }).popover({
            html: true,
            placement: function (context, source) {
                var position = $(source).position(),
                    width    = $(source).width(),
                    fullwidth = $('.as-calendar').width(),
                    popover_width = $('.popover-content').width(),
                    placement = 'right';
                if (position.left + width + popover_width > fullwidth) {
                    placement = 'left';
                }
                return placement;
            },
            template: '<div class="popover" role="tooltip"><div class="arrow"></div><div class="popover-content"></div></div>',
            content: function () {
                var div_id =  "tmp-id-" + $.now(),
                    booking_id = $(this).data('booking-id');
                return details_in_popup($(this).attr('href'), div_id, booking_id);
            }
        });
        $('[data-toggle=popover]').on('shown.bs.popover', function () {
            if ($('.popover').position().top < 0) {
                $('.popover').css('top', parseInt($('.popover').css('top'), 10) + 85 + 'px');
                $('.popover .arrow').css('top', '15px');
            }
        });
        $(document).on('click', '#btn-submit-modify-form', function (e) {
            e.preventDefault();
            var booking_id = $(this).data('booking-id'),
                url = $(this).data('action-url');
            $.ajax({
                type: 'POST',
                url: url,
                data: $('#modify_booking_form_' + booking_id).serialize(),
                dataType: 'json'
            }).done(function (data) {
                if (data.success) {
                    location.reload();
                } else {
                    alertify.alert(data.message);
                }
            });
        });
        $(document).on('click', '#btn-add-employee-freetime', function (e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: $('#add_freetime_url').val(),
                data: $('#freetime_form').serialize(),
                dataType: 'json'
            }).done(function () {
                location.reload();
            });
        });
        $('.btn-delete-employee-freetime').click(function (e) {
            e.preventDefault();
            var $self = $(this);
            alertify.confirm($(this).data('confirm'), function (e) {
                if (e) {
                    $.ajax({
                        type: 'POST',
                        url: $self.data("action-url"),
                        data: { freetime_id : $self.data('freetime-id') },
                        dataType: 'json'
                    }).done(function (data) {
                        if (data.success) {
                            location.reload();
                        }
                    });
                }
            });
        });
        $(document).on('click', '#btn-add-service', function (e) {
            e.preventDefault();
            var service_id = $('#services').val(),
                employee_id = $('#employee_id').val(),
                service_time = $('#service_times').val(),
                modify_times = $('#modify_times').val(),
                booking_date = $('#booking_date').val(),
                start_time = $('#start_time').val(),
                uuid = $('#booking_uuid').val(),
                booking_id = $('#booking_id').val();
            $.ajax({
                type: 'POST',
                url: $('#add_service_url').val(),
                data: {
                    service_id: service_id,
                    service_time: service_time,
                    employee_id: employee_id,
                    booking_id : booking_id,
                    modify_times: modify_times,
                    booking_date: booking_date,
                    start_time: start_time,
                    uuid: uuid
                },
                dataType: 'json'
            }).done(function (data) {
                $('#added_service_name').text(data.service_name);
                $('#added_employee_name').text(data.employee_name);
                $('#added_booking_date').text(data.datetime);
                $('#added_booking_modify_time').text(data.modify_time);
                $('#added_booking_plustime').text(data.plustime);
                $('#added_service_price').text(data.price);
                $('#added_services').show();
            }).fail(function (data) {
                alertify.alert(data.responseJSON.message);
            });
        });
        $(document).on('click', '#btn-save-booking', function (e) {
            e.preventDefault();
            var postData = $('#booking_form').serializeArray();
            postData.push({
                name: 'employee_id',
                value: $('#employee_id').val(),
            });
            $.ajax({
                type: 'POST',
                url: $('#add_booking_url').val(),
                data: postData,
                dataType: 'json'
            }).done(function (data) {
                if (data.success) {
                    location.reload();
                } else {
                    alertify.alert(data.message);
                }
            });
        });
        $('#btn-continute-action').click(function (e) {
            e.preventDefault();
            var employee_id = $('#employee_id').val(),
                booking_date    = $('#date').val(),
                start_time      = $('#start_time').val(),
                selected_action = $('input[name="action_type"]:checked').val();
            if (selected_action === 'book') {
                $.fancybox.open({
                    padding: 5,
                    width: 850,
                    title: '',
                    autoSize: false,
                    autoScale: true,
                    autoWidth: false,
                    autoHeight: true,
                    fitToView: false,
                    href: $('#get_booking_form_url').val(),
                    type: 'ajax',
                    ajax: {
                        type: 'GET',
                        data: {
                            employee_id: employee_id,
                            booking_date: booking_date,
                            start_time: start_time
                        }
                    },
                    helpers: {
                        overlay: {
                            locked: false
                        }
                    },
                    autoCenter: false
                });
            } else if (selected_action === 'freetime') {
                $.fancybox.open({
                    padding: 5,
                    width: 550,
                    title: '',
                    autoSize: false,
                    autoScale: true,
                    autoWidth: false,
                    autoHeight: true,
                    fitToView: false,
                    href: $('#get_freetime_form_url').val(),
                    type: 'ajax',
                    ajax: {
                        type: 'GET',
                        data: {
                            employee_id : employee_id,
                            booking_date: booking_date,
                            start_time  : start_time
                        }
                    },
                    helpers: {
                        overlay: {
                            locked: false
                        }
                    },
                    autoCenter: false
                });
            } else if (selected_action === 'paste_booking') {
                var action_url = $('#get_paste_booking_url').val();
                $.ajax({
                    type: 'POST',
                    url: action_url,
                    data: {
                        employee_id: employee_id,
                        booking_date: booking_date,
                        start_time: start_time
                    },
                    dataType: 'json'
                }).done(function (data) {
                    if (data.success) {
                        location.reload();
                    } else {
                        alertify.alert(data.message);
                    }
                });
            } else if (selected_action === 'discard_cut_booking') {
                var action_url = $('#get_discard_cut_booking_url').val();
                $.ajax({
                    type: 'POST',
                    url: action_url,
                    dataType: 'json'
                }).done(function (data) {
                    if (data.success) {
                        location.reload();
                    } else {
                        alertify.alert(data.message);
                    }
                });
            }
        });
        $(document).on('click', 'a.js-btn-view-booking', function (e) {
            e.preventDefault();
            var booking_id = $(this).data('booking-id');
            $('#employee_id').val($(this).data('employee-id'));
            $('#start_time').val($(this).data('start-time'));
            $.fancybox.open({
                padding: 5,
                width: 850,
                title: '',
                autoSize: false,
                autoScale: true,
                autoWidth: false,
                autoHeight: true,
                fitToView: false,
                href: $('#get_booking_form_url').val(),
                type: 'ajax',
                ajax: {
                    type: 'GET',
                    data: {
                        booking_id: booking_id,
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
        $(document).on('click', 'a.js-btn-cut-booking', function (e) {
            e.preventDefault();
            var booking_id = $(this).data('booking-id'),
                action_url = $(this).data('action-url');
            $.ajax({
                type: 'POST',
                url: action_url,
                data: { booking_id : booking_id },
                dataType: 'json'
            }).done(function (data) {
                if (data.success) {
                    $('.booked').removeAttr('style');
                    $('.booking-id-' + booking_id).attr('style', 'background-color:grey');
                    $('#row_paste_booking').show();
                    $('#row_discard_cut_booking').show();
                } else {
                    alertify.alert(data.message);
                }
            });
        });
        var colHeaderTop  = -1,
            originalOffset = [],
            scrolledLeft = false;

        function fixedCalendarHeader() {
            if ($('.as-col-header').length === 0) {
                return;
            }

            if (colHeaderTop === -1) {
                colHeaderTop = $('.as-col-header').offset().top;
            }

            if ($(window).scrollTop() > colHeaderTop) {
                $('.as-col-header').css('position', 'fixed');
                $('.as-col-header').css('height', 25);
                $('.as-col-header').css('width', 163);
                $('.as-col-header').css('top', 0);
            }

            if ($(window).scrollTop() <= colHeaderTop) {
                if (scrolledLeft) {
                    if ($(window).scrollTop() === 0) {
                        $('.as-col-header').css('top', colHeaderTop);
                    } else {
                        $('.as-col-header').css('top', colHeaderTop - $(window).scrollTop());
                        $('.as-col-left-header').css('margin-top', '25px');
                        $('#as-ul').css('margin-top', '25px');
                    }
                } else {
                    $('.as-col-header').css('position', 'relative');
                }
            }
        }

        $(window).scroll(function () {
            fixedCalendarHeader();
        });

        $('.as-calendar').scroll(function () {
            scrolledLeft = true;
            if ($.isEmptyObject(originalOffset)) {
                $('.as-col-header').each(function (key, item) {
                    originalOffset.push($(item).offset().left);
                });
            }
            $('.as-col-header').each(function (key, item) {
                var offset = parseInt(originalOffset[key], 10) -  parseInt($('.as-calendar').scrollLeft(), 10);
                $(item).css('left', offset);
                if (offset < 15) {
                    $(item).css('opacity', 0.2);
                } else {
                    $(item).css('opacity', 1);
                }
                // console.log($(item).offset().left);
            });
        });
    });
}(jQuery));
