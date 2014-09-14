/*jslint browser: true, nomen: true, unparam: true, node: true*/
/*global $, jQuery, document, alertify, location, window*/
'use strict';

(function ($) {
    $(function () {
        $('.customer-tooltip').tooltip({
            'selector': '',
            'placement': 'top',
            'container': 'body'
        });

        $('.selectpicker').selectpicker();

        $('.toggle-check-all-boxes').click(function () {
            var checkboxClass = ($(this).data('checkbox-class')) || 'checkbox';
            $('.' + checkboxClass).prop('checked', this.checked);
        });

        $('#form-bulk').on('submit', function (e) {
            e.preventDefault();
            var $this = $(this);
            alertify.confirm($this.data('confirm'), function (e) {
                if (e) {
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
                }
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
        $('body').on('focus', '.date-picker', function () {
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
        // Backend Calendar
        $('li.active').click(function () {
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
        $('a.btn-select-modify-action').click(function () {
            $('#booking_id').val($(this).data('booking-id'));
            $('.fancybox').fancybox({
                padding: 5,
                width: 350,
                title: '',
                autoSize: false,
                autoWidth: false,
                autoHeight: true
            });
        });
        $(document).on('change', '#service_categories', function () {
            var category_id = $(this).val(),
                employee_id = $('#employee_id').val();
            $.ajax({
                type: 'GET',
                url: $('#get_services_url').val(),
                data: {
                    category_id: category_id,
                    employee_id: employee_id
                },
                dataType: 'json'
            }).done(function (data) {
                $('#services').empty();
                $('#service_times').empty();
                $('#services').append(
                    $('<option>', {
                        value: 0,
                        text: '-- Valitse --' //TODO need to get somewhere else
                    })
                );
                var i;
                for (i = 0; i < data.length; i = i + 1) {
                    $('#services').append(
                        $('<option>', {
                            value: data[i].id,
                            text: data[i].name
                        })
                    );
                }
            });
        });
        $(document).on('change', '#services', function () {
            var service_id = $(this).val();
            $.ajax({
                type: 'GET',
                url: $('#get_service_times_url').val(),
                data: {
                    service_id: service_id
                },
                dataType: 'json'
            }).done(function (data) {
                $('#service_times').empty();
                $('#service_times').append(
                    $('<option>', {
                        value: 0,
                        text: '-- Valitse --' //TODO need to get somewhere else
                    })
                );
                var i;
                for (i = 0; i < data.length; i = i + 1) {
                    $('#service_times').append(
                        $('<option>', {
                            value: data[i].id,
                            text: data[i].length
                        })
                    );
                }
            });
        });
        $(document).on('change', '#service_times', function () {
            var start_time = $('#start_time').val();  //TODO ?
            //var service_time = $
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
                uuid = $('#booking_uuid').val();
            $.ajax({
                type: 'POST',
                url: $('#add_service_url').val(),
                data: {
                    service_id: service_id,
                    service_time: service_time,
                    employee_id: employee_id,
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
                $('#added_service_price').text(data.price);
                $('#added_services').show();
            }).fail(function (data) {
                alertify.alert(data.responseJSON.message);
            });
        });
        $(document).on('click', '#btn-remove-service-time', function (e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: $(this).data('remove-url'),
                data: {
                    uuid: $(this).data('uuid')
                },
                dataType: 'json'
            }).done(function (data) {
                if (data.success) {
                    $('#added_service_name').text();
                    $('#added_employee_name').text();
                    $('#added_booking_date').text();
                    $('#added_service_price').text();
                    $('#added_services').hide();
                }
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
                //TODO there are two views default and week view
                location.reload();
            });
        });
        $(document).on('click', '#btn-change-status', function (e) {
            e.preventDefault();
            var postData = $('#add_change_status_form').serialize(),
                action_url = $(this).data('action-url');
            $.ajax({
                type: 'POST',
                url: action_url,
                data: postData,
                dataType: 'json'
            }).done(function (data) {
                //TODO there are two views default and week view
                location.reload();
            });
        });
        $('#btn-continue-modify').click(function (e) {
            var selected_action = $('input[name="modify_type"]:checked').val(),
                booking_id = $('#booking_id').val(),
                action_url = $('#change_status_form_url').val();
            if (selected_action === 'change_total') {

            } else if (selected_action === 'change_status') {
                $.fancybox.open({
                    padding: 5,
                    width: 400,
                    title: '',
                    autoSize: false,
                    autoScale: true,
                    autoWidth: false,
                    autoHeight: true,
                    fitToView: false,
                    href: action_url,
                    type: 'ajax',
                    ajax: {
                        type: 'GET',
                        data: {
                            booking_id: booking_id
                        }
                    },
                    helpers: {
                        overlay: {
                            locked: false
                        }
                    },
                    autoCenter: false
                });
            } else if (selected_action === 'add_extra_service') {
                var action_url = $('#add_extra_service_url').val(),
                    booking_id = $('#booking_id').val();
                $.fancybox.open({
                    padding: 5,
                    width: 400,
                    title: '',
                    autoSize: false,
                    autoScale: true,
                    autoWidth: false,
                    autoHeight: true,
                    fitToView: false,
                    href: action_url,
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
            }
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
            }
        });
        $('a.js-btn-view-booking').click(function (e) {
            e.preventDefault();
            var booking_id = $(this).data('booking-id');
            $('#employee_id').val($(this).data('employee-id'));
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
        $('a.btn-add-extra-service').click(function (e) {
            e.preventDefault();
            var booking_id = $(this).data('booking-id'),
                action_url = $(this).data('action-url');
            $.fancybox.open({
                padding: 5,
                width: 400,
                title: '',
                autoSize: false,
                autoScale: true,
                autoWidth: false,
                autoHeight: true,
                fitToView: false,
                href: action_url,
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
    });
}(jQuery));
