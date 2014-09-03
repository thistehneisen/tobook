(function ($) {
  $(function () {
    'use strict';

    $('.customer-tooltip').tooltip({
      'selector': '',
      'placement': 'top',
      'container': 'body'
    });
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
      var target = $(event.target);
      if (target.is('td')) {//fix bug cannot click to the actual checkbox
        var $this = $(this),
          checkbox = $this.find('td:first input:checkbox'),
          checked = checkbox.prop('checked');

        checkbox.prop('checked', !checked);
      }
    });

    // Date picker
    $('.date-picker').datepicker({
      format: 'yyyy-mm-dd'
    });

    // Backend Calendar
    $('.active').click(function (){
      var employee_id = $(this).data('employee-id');
      var booking_date = $(this).data('booking-date');
      var start_time = $(this).data('start-time');
      $('#employee_id').val(employee_id);
      $('#date').val(booking_date);
      $('#start_time').val(start_time);
      var time = $(this).data('time');
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
      var category_id = $(this).val();
      var employee_id = $('#employee_id').val();
      $.ajax({
        type: 'GET',
        url: $('#get_services_url').val(),
        data: {
          category_id : category_id,
          employee_id : employee_id
        },
        dataType: 'json'
      }).done(function (data) {
        $('#services').empty();
        $('#services').append(
          $('<option>', {
            value: 0,
            text: '-- Valitse --'//TODO need to get somewhere else
          })
        );
        var i;
        for (i = 0; i < data.length; i++) {
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
          service_id : service_id
        },
        dataType: 'json'
      }).done(function (data) {
        $('#service_times').empty();
        $('#service_times').append(
          $('<option>', {
            value: 0,
            text: '-- Valitse --'//TODO need to get somewhere else
          })
        );
        var i;
        for (i = 0; i < data.length; i++) {
          $('#service_times').append(
            $('<option>', {
              value: data[i].id,
              text: data[i].length
            })
          );
        }
      });
    });
    $(document).on('click', '#btn-add-service', function (e) {
      e.preventDefault();
      var service_id   = $('#services').val();
      var employee_id  = $('#employee_id').val();
      var service_time = $('#service_times').val();
      var modify_times = $('#modify_times').val();
      var booking_date = $('#booking_date').val();
      var start_time   = $('#start_time').val();
      var uuid         = $('#booking_uuid').val();
      $.ajax({
        type: 'POST',
        url: $('#add_service_url').val(),
        data: {
          service_id   : service_id,
          service_time : service_time,
          employee_id  : employee_id,
          modify_times : modify_times,
          booking_date : booking_date,
          start_time   : start_time,
          uuid         : uuid
        },
        dataType: 'json'
      }).done(function (data) {
        $('#added_service_name').text(data.service_name);
        $('#added_employee_name').text(data.employee_name);
        $('#added_booking_date').text(data.datetime);
        $('#added_service_price').text(data.price);
        $('#added_services').show();
      });
    });
    $(document).on('click', '#btn-remove-service-time', function (e) {
      e.preventDefault();
    });
    $(document).on('click', '#btn-save-booking', function (e) {
      e.preventDefault();
      $.ajax({
        type: 'POST',
        url: $('#add_booking_url').val(),
        data: $('#booking_form').serialize(),
        dataType: 'json'
      }).done(function (data) {
          console.log(data);
      });
    });
    $('#btn-continute-action').click(function (e) {
      e.preventDefault();
      var employee_id = $('#employee_id').val();
      var booking_date =  $('#date').val();
      var start_time = $('#start_time').val();
      var selected_action = $('input[name="action_type"]:checked').val();
      if (selected_action === 'book') {
        $.fancybox.open({
          padding: 5,
          width: 850,
          title: '',
          autoSize: false,
          autoScale: true,
          autoWidth: false,
          autoHeight: true,
          fitToView : false,
          href: $('#get_booking_form_url').val(),
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
          autoCenter : false
        });
      } else if (selected_action === 'freetime') {
        //TODO
      }
    });

  });
}(jQuery));
