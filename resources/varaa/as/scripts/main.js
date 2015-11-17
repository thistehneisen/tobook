/*jslint browser: true, nomen: true, unparam: true, node: true*/
/* global jQuery, alertify, location, window, VARAA, CUSTOM_TIME */
(function ($) {
  'use strict'
  $(function () {
    var $doc = $(document),
      $w = $(window),
      dataStorage = { booking_service_id: 0},
      colHeaderTop = -1,
      originalOffset = [],
      scrolledLeft = false,
      showBookingServiceResult,
      handleScroll,
      fixedCalendarHeader

    $('.backend-tooltip').tooltip({
      selector: '',
      placement: 'top',
      container: 'body',
      trigger: 'hover',
      html: true
    })

    $('.selectpicker').selectpicker()

    $('.toggle-check-all-boxes').click(function () {
      var checkboxClass = ($(this).data('checkbox-class')) || 'checkbox'
      $('.' + checkboxClass).prop('checked', this.checked)
    })

    $doc.bind('ajaxSend', function () {
      $('#loading').show()
    }).bind('ajaxComplete', function () {
      $('#loading').hide()
    })
    $('#form-bulk').on('submit', function (e) {
      e.preventDefault()
      var $this = $(this)
      alertify.confirm('Confirm', $this.data('confirm'), function () {
        // user clicked "ok"
        $.ajax({
          type: 'POST',
          url: $this.attr('action'),
          data: $this.serialize(),
          dataType: 'json'
        }).done(function () {
          alertify.alert('OK')
          if ($('#mass-action').val() === 'destroy') {
            $('#form-bulk [type=checkbox]:checked').each(function () {
              $('#row-' + $(this).val()).remove()
            })
          }
        }).fail(function () {
          alertify.alert('Something went wrong')
        })
      }, function () {
        alertify.error('Cancel')
      })
    })

    // Allow to click on TR to select checkbox
    $('table.table-crud tr').on('click', function (event) {
      var target = $(event.target),
        $this = $(this),
        checkbox = $this.find('td:first input:checkbox'),
        checked = checkbox.prop('checked')
      if (target.is('td')) { // fix bug cannot click to the actual checkbox
        checkbox.prop('checked', !checked)
      }
    })

    // Date picker
    $doc.on('focus', '.date-picker', function () {
      $(this).datepicker({
        format: 'dd.mm.yyyy',
        weekStart: 1,
        autoclose: true,
        language: $('body').data('locale')
      })
    })
    $('#calendar_date').datepicker({
      format: 'dd.mm.yyyy',
      weekStart: 1,
      autoclose: true,
      calendarWeeks: true,
      language: $('body').data('locale')
    }).on('changeDate', function () {
      // use data-index-url attribute to prevent append date to date like yyyy-mm-dd/yyyy-mm-dd
      window.location.href = $(this).data('index-url') + '/' + $(this).val()
    })

    function calculateTotalMinute () {
      var total = parseInt($('#during').val(), 10) +
        parseInt($('#after').val(), 10) +
        parseInt($('#before').val(), 10)
      $('#total').val(total)
    }

    // calculate total time
    $('#before, #during, #after').change(function () {
      calculateTotalMinute()
    })

    $('button').click(function (e) {
      calculateTotalMinute()
    })

    // ------------------------ Backend Calendar ------------------------ //
    $('.as-calendar li.active, .as-calendar li.inactive').click(function () {
      var employee_id = $(this).data('employee-id'),
        booking_date = $(this).data('booking-date'),
        start_time = $(this).data('start-time')
      $('#employee_id').val(employee_id)
      $('#date').val(booking_date)
      $('#start_time').val(start_time)
      $('.fancybox').fancybox({
        padding: 5,
        width: 350,
        title: '',
        autoSize: false,
        autoWidth: false,
        autoHeight: true
      })
    })

    $('.as-calendar li.active, .as-calendar li.inactive').hover(function () {
      var _this = $(this),
        start_time = _this.data('start-time')

      _this.append('<span class="hover">{0} {1}</span>'.format(VARAA.trans('common.book'), start_time))
    }, function () {
      $(this).find('.hover').remove()
    })

    // ------------------------ Button handlers ------------------------ //
    $('body').on('click', function (e) {
      $('a.popup-ajax').each(function () {
        // the 'is' for buttons that trigger popups
        // the 'has' for icons within a button that triggers a popup
        if (!$(this).is(e.target) &&
          $(this).has(e.target).length === 0 &&
          $('.popover').has(e.target).length === 0) {
          $(this).popover('hide')
        }
      })
    })
    function details_in_popup (link, div_id, booking_id) {
      $.ajax({
        url: link,
        data: { booking_id: booking_id },
        success: function (response) {
          $('#' + div_id).html(response)
        }
      }).fail(function (data) {
        alertify.alert('Alert', data.responseJSON.message, function () {
          alertify.message('OK')
        })
      })
      return '<div class="popover_form" id="' + div_id + '"><img src="/built/varaa/core/img/busy.gif"></div>'
    }
    $('a.popup-ajax').click(function (e) {
      e.preventDefault()
    // Hide previous open popover
    // $('a.popup-ajax').popover('hide')
    }).popover({
      html: true,
      placement: function (context, source) {
        var position = $(source).position(),
          width = $(source).width(),
          fullwidth = $('.as-calendar').width(),
          popover_width = ($('.popover-content').width()) ? $('.popover-content').width()  : 350,
          placement = 'right'
        if (position.left + width + popover_width > fullwidth) {
          placement = 'left'
        }
        return placement
      },
      template: '<div class="popover" role="tooltip"><div class="arrow"></div><div class="popover-content"></div></div>',
      content: function () {
        var div_id = 'tmp-id-' + $.now(),
          booking_id = $(this).data('booking-id')
        return details_in_popup($(this).attr('href'), div_id, booking_id)
      }
    })
    $('[data-toggle=popover]').on('shown.bs.popover', function () {
      if ($('.popover').position().top < 0) {
        $('.popover').css('top', parseInt($('.popover').css('top'), 10) + 85 + 'px')
        $('.popover .arrow').css('top', '15px')
      }
    })
    $doc.on('click', '#btn-submit-modify-form', function (e) {
      e.preventDefault()
      var booking_id = $(this).data('booking-id'),
        url = $(this).data('action-url')
      $.ajax({
        type: 'POST',
        url: url,
        data: $('#modify_booking_form_' + booking_id).serialize(),
        dataType: 'json'
      }).done(function (data) {
        if (data.success) {
          location.reload()
        } else {
          alertify.alert(data.message)
        }
      })
    })
    $doc.on('click', '#btn-upsert-employee-freetime', function (e) {
      e.preventDefault()
      var freetime_id = $('input[name="freetime_id"]').val()
      var url = $('#add_freetime_url').val()
      if (freetime_id !== '0') {
        url = $('#edit_freetime_url').val()
      }
      $.ajax({
        type: 'POST',
        url: url,
        data: $('#freetime_form').serialize(),
        dataType: 'json'
      }).done(function (data) {
        if (data.success) {
          location.reload()
        } else {
          alertify.alert(data.message)
        }
      })
    })
    $('.btn-delete-employee-freetime').click(function (e) {
      e.preventDefault()
      var $self = $(this)
      alertify.confirm($(this).data('confirm'), function (e) {
        if (e) {
          $.ajax({
            type: 'POST',
            url: $self.data('action-url'),
            data: { freetime_id: $self.data('freetime-id') },
            dataType: 'json'
          }).done(function (data) {
            if (data.success) {
              location.reload()
            }
          })
        }
      })
    })

    $doc.on('click', '#btn-add-new-booking-service', function (e) {
      e.preventDefault()
      dataStorage.booking_service_id = 0
      var $services = $('#service_categories')
      $services.val($services.find('option:eq(1)').val()).trigger('change')
      $('#added_services').find('tbody').find('tr').removeAttr('style')
      $('#btn-add-service').text($(this).data('add-text'))
      $('#btn-add-service').removeClass('btn-success')
    })

    $doc.on('click', '.btn-delete-booking-service', function (e) {
      e.preventDefault()
      var booking_service_id = $(this).data('booking-service-id'),
        booking_id = $(this).data('booking-id'),
        uuid = $(this).data('uuid'),
        start_time = $(this).data('start-time'),
        $table = $('#added_services')
      dataStorage.booking_service_id = booking_service_id
      dataStorage.booking_id = booking_id
      dataStorage.uuid = uuid
      dataStorage.start_time = start_time
      $.ajax({
        type: 'POST',
        url: $('#delete_booking_service_url').val(),
        data: dataStorage,
        dataType: 'json'
      }).done(function (data) {
        $('#booking-service-id-' + booking_service_id).remove()
        $('#btn-add-service').text($table.data('add-text'))
        $('#total_length').val(data.total_length)
        $('#total_price').val(data.total_price)
      }).fail(function (data) {
        alertify.alert('Alert', data.responseJSON.message, function () {
          alertify.message('OK')
        })
      })
      dataStorage.booking_service_id = 0
    })

    showBookingServiceResult = function (data) {
      var $table = $('#added_services')
      var $tbody = $table.find('tbody')
      var $tr
      var $td
      var $tds
      var i
      var classes = [
        'added_booking_plustime',
        'added_booking_service_length',
        'added_booking_date',
        'added_service_price'
      ]

      for (i = 0; i < data.extras.length; i++) {
        // Don't add duplicate options
        if ($('#extra_services option[value=' + data.extras[i].id + ']').length === 0) {
          $('<option>', {'value': data.extras[i].id, text: data.extras[i].name}).appendTo($('#extra_services'))
        }
      }
      $('#extra_services').selectpicker('refresh')

      if (!$tbody.find('#booking-service-id-' + data.booking_service_id).length) {
        $tr = $('<tr/>', {
          id: 'booking-service-id-' + data.booking_service_id
        }).appendTo($tbody)

        $td = $('<td/>').appendTo($tr)
        $('<span>', { class: 'added_service_name'}).appendTo($td)
        $('<br>').appendTo($td)
        $('<span>', { class: 'added_employee_name'}).appendTo($td)

        for (i = 0; i < classes.length; i += 1) {
          $td = $('<td/>').appendTo($tr)
          $('<span>', { class: classes[i]}).appendTo($td)
        }

        $td = $('<td/>').appendTo($tr)

        $('<a>', {
          'href': '#',
          'class': 'btn-delete-booking-service',
          'data-booking-service-id': data.booking_service_id,
          'data-booking-id': data.booking_id,
          'data-start-time': data.start_time,
          'data-uuid': data.uuid
        }).append('<i class="fa fa-trash"></i>').appendTo($td)
      }

      $tds = $('#booking-service-id-' + data.booking_service_id + ' > td')
      $tds.find('span.added_service_name').text(data.service_name)
      $tds.find('span.added_employee_name').text(data.employee_name)
      $tds.find('span.added_booking_plustime').text(data.plustime)
      $tds.find('span.added_booking_service_length').text(data.service_length)
      $tds.find('span.added_booking_date').text(data.datetime)
      $tds.find('span.added_service_price').text(data.price)
      $('#total_length').val(data.total_length)
      $('#total_price').val(data.total_price)
      $('#added_services').show()
    }

    $doc.on('click', '#btn-add-service', function (e) {
      e.preventDefault()
      dataStorage.service_id = $('#services').val()
      dataStorage.employee_id = $('#employee_id').val()
      dataStorage.service_time = $('#service_times').val()
      dataStorage.booking_date = $('#booking_date').val()
      dataStorage.start_time = $('#start_time').val()
      dataStorage.uuid = $('#booking_uuid').val()
      dataStorage.booking_id = $('#booking_id').val()
      dataStorage.modify_time = $('#modify_times').val()
      $.ajax({
        type: 'POST',
        url: $('#add_service_url').val(),
        data: dataStorage,
        dataType: 'json'
      }).done(function (data) {
        showBookingServiceResult(data)
      }).fail(function (data) {
        alertify.alert('Alert', data.responseJSON.message, function () {
          alertify.message('OK')
        })
      })
    })

    $doc.on('click', '#btn-save-booking', function (e) {
      e.preventDefault()
      var postData = $('#booking_form').serializeArray()
      postData.push({
        name: 'employee_id',
        value: $('#employee_id').val()
      })
      $.ajax({
        type: 'POST',
        url: $('#add_booking_url').val(),
        data: postData,
        dataType: 'json'
      }).done(function (data) {
        if (data.success) {
          location.reload()
        } else {
          alertify.alert(data.message)
        }
      })
    })
    $('.btn-continute-action').click(function (e) {
      e.preventDefault()
      var employee_id = $('#employee_id').val(),
        booking_date = $('#date').val(),
        start_time = $('#start_time').val(),
        selected_action = $(this).data('action'),
        action_url

      if (selected_action === 'book') {
        $.fancybox.open({
          padding: 5,
          margin: 10,
          title: '',
          autoSize: true,
          autoScale: true,
          autoWidth: true,
          autoHeight: true,
          fitToView: true,
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
        })
      } else if (selected_action === 'freetime') {
        $.fancybox.open({
          padding: 5,
          margin: 10,
          title: '',
          autoSize: true,
          autoScale: true,
          autoWidth: true,
          autoHeight: true,
          fitToView: true,
          href: $('#get_freetime_form_url').val(),
          type: 'ajax',
          ajax: {
            type: 'GET',
            data: {
              employee_id: employee_id,
              date: booking_date,
              start_time: start_time
            }
          },
          helpers: {
            overlay: {
              locked: false
            }
          },
          autoCenter: false
        })
      } else if (selected_action === 'paste_booking') {
        action_url = $('#get_paste_booking_url').val()
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
            location.reload()
          } else {
            alertify.alert(data.message)
          }
        })
      } else if (selected_action === 'discard_cut_booking') {
        action_url = $('#get_discard_cut_booking_url').val()
        $.ajax({
          type: 'POST',
          url: action_url,
          dataType: 'json'
        }).done(function (data) {
          if (data.success) {
            location.reload()
          } else {
            alertify.alert(data.message)
          }
        })
      }
    })

    $doc.on('click', 'a.js-btn-view-booking', function (e) {
      e.preventDefault()
      var booking_id = $(this).data('booking-id')
      $('#employee_id').val($(this).data('employee-id'))
      $('#start_time').val($(this).data('start-time'))
      $.fancybox.open({
        padding: 5,
        margin: 10,
        width: 850,
        title: '',
        autoSize: true,
        autoScale: true,
        autoWidth: true,
        autoHeight: true,
        fitToView: true,
        href: $('#get_booking_form_url').val(),
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
      })
    })
    $doc.on('click', 'a.js-btn-cut-booking', function (e) {
      e.preventDefault()
      var booking_id = $(this).data('booking-id'),
        action_url = $(this).data('action-url')
      $.ajax({
        type: 'POST',
        url: action_url,
        data: { booking_id: booking_id },
        dataType: 'json'
      }).done(function (data) {
        if (data.success) {
          $('.booked').removeAttr('style')
          $('.booking-id-' + booking_id).attr('style', 'background-color:grey')
          $('#row_paste_booking').show()
          $('#row_discard_cut_booking').show()
        } else {
          alertify.alert(data.message)
        }
      })
    })

    if (colHeaderTop === -1) {
      if($('.as-col-header').length){
        colHeaderTop = $('.as-col-header').offset().top
      }
    }
    
    fixedCalendarHeader = function () {
      if ($('.as-col-header').length === 0) {
        return
      }

      if ($w.scrollTop() > colHeaderTop) {
        $('.as-col-header').each(function () {
          var $this = $(this)
          $this.css({
            position: 'fixed',
            width: $this.parent('ul').width(),
            top: 0
          })
        })

        $('#as-left-col-header').css('width', $('.as-col-left-header').first().width())
      }

      if ($w.scrollTop() <= colHeaderTop) {
        if (scrolledLeft) {
          if ($w.scrollTop() === 0) {
            $('.as-col-header').css('top', colHeaderTop)
          } else {
            $('.as-col-left-header').css('margin-top', '25px')
            $('.as-ul').css('margin-top', '25px')
          }
        } else {
          $('.as-col-header').removeAttr('style');
        }

        $('.as-col-header').each(function () {
          var $this = $(this)
          if ($this.css('position') !== 'relative') {
            $this.css('top', colHeaderTop - $w.scrollTop())
          } else {
            $this.css('top', 0)
          }
        })
      }
    }

    handleScroll = function () {
      scrolledLeft = true
      if ($.isEmptyObject(originalOffset)) {
        $('.as-col-header').each(function (key, item) {
          originalOffset.push($(item).offset().left)
        })
      }
      $('.as-col-header').each(function (key, item) {
        var $this = $(this)

        $this.css({
          position: 'fixed',
          width: $this.parent('ul').width(),
          top: function(){
            var parentTop = 0
            // Adjust the column header height to match the rest cells
            if($w.scrollTop() < colHeaderTop) {
              parentTop = colHeaderTop - $w.scrollTop()
            }
            return parentTop
          }
        })

        $('#as-left-col-header').css('width', $('.as-col-left-header').first().width())

        var offset = 0
        if ($(item).css('position') !== 'relative') {
          offset = parseInt(originalOffset[key], 10) - parseInt($('.as-calendar').scrollLeft(), 10)
          if (parseInt(key, 10) > 0) {
            offset += 1
          }
        } else {
          offset = originalOffset[key]
        }
        $(item).css('left', offset)

        if (offset < 0) {
          $(item).css('opacity', 0.2)
        } else {
          $(item).css('opacity', 1)
        }
      })
    }

    $w.scroll(fixedCalendarHeader);
    
    $w.resize(function(){
      if($('.as-col-header').length){
        $w.scrollTop(5);
        $('.as-calendar').scrollLeft(0);
        $('.as-col-header').removeAttr('style');
        colHeaderTop = $('.as-col').offset().top;
        originalOffset = [];
        $('.as-col-header').each(function (key, item) {
            originalOffset.push($(item).offset().left);
        })
        $w.scrollTop(5);
        $('.as-calendar').scrollLeft(0);
      }
    });

    $w.load(function(){
      $w.scrollTop(5);
      $('.as-calendar').scrollLeft(0);
      $('.as-calendar').scroll(handleScroll);
    });

  
    // @see resources/varaa/co/scripts/main.coffee
    $doc.on('click', 'a.js-showHistory', function (e) {
      e.preventDefault()
      var $this = $(this)
      $.ajax({
        type: 'GET',
        url: $this.prop('href'),
        data: {
          id: $this.data('consumerid'),
          service: $this.data('service')
        },
        dataType: 'html'
      }).done(function (data) {
        $('#js-historyModal').find('.modal-body').html(data)
        $('#js-historyModal').modal('show')
      })
      return false
    })

    $doc.on('click', 'a.js-show-consumer-info', function (e) {
      e.preventDefault()
      var $this = $(this)
      $.ajax({
        type: 'GET',
        url: $this.prop('href'),
        data: {
          id: $this.data('consumerid'),
        },
        dataType: 'json'
      }).done(function (data) {
        console.log(data);
        $('#js-consumer-info-modal').find('.modal-body').text(data.notes)
        $('#js-consumer-info-modal').modal('show')
      })
      return false
    })

    $doc.keyup(function (e) {
      if (e.keyCode === 27) {
        $('.modal-backdrop').remove()
      }
    })

    $doc.on('click', 'li.freetime', function (e) {
      var employee_id = $(this).data('employee-id')
      var freetime_id = $(this).data('freetime-id')
      $.fancybox.open({
        padding: 5,
        width: 590,
        margin: 10,
        title: '',
        autoSize: true,
        autoScale: true,
        autoWidth: true,
        autoHeight: true,
        fitToView: true,
        href: $('#get_freetime_form_url').val(),
        type: 'ajax',
        ajax: {
          type: 'GET',
          data: {
            employee_id: employee_id,
            freetime_id: freetime_id
          }
        },
        helpers: {
          overlay: {
            locked: false
          }
        },
        autoCenter: false
      })
    })

    $doc.on('click', 'a.btn-workshift-switch', function (e) {
      e.preventDefault()
      var custom_time = CUSTOM_TIME
      var header = $(this).closest('.as-col-header')
      var employee_id = $(this).data('employee-id')
      var custom_id = $(this).data('custom-time-id')
      var date = $(this).data('date')
      $('.as-col-header').each(function (index) {
        $(this).css('height', '70px')
      })
      if ($('#change_workshift-' + employee_id).length !== 0) {
        $('#change_workshift-' + employee_id).remove()
        $('.as-col-header').each(function (index) {
          $(this).removeAttr('style')
        })
        return
      }
      var dropdown = $('<select/>', {
        'class': 'form-control change-workshift',
        'id': 'change_workshift-' + employee_id,
        'style': 'display:block',
        'data-employee-id': employee_id,
        'data-date': date
      })
      for (var val in custom_time) {
        $('<option />', {
          value: val.replace('@', ''),
          text: custom_time[val]
        }).appendTo(dropdown)
      }

      if (custom_id !== -1) {
        dropdown.val(custom_id)
      }
      dropdown.appendTo(header)
    })

    $doc.on('change', '.change-workshift', function (e) {
      var custom_time_id = $(this).val()
      var url = $('#update_workshift_url').val()
      var employee_id = $(this).data('employee-id')
      var date = $(this).data('date')
      $.post(url, {
        'custom_time_id': custom_time_id,
        'employee_id': employee_id,
        'date': date
      }).fail(function (data) {
        console.log(data);
      }).done(function (data) {
        location.reload()
      })
    })

    var iframe = $('#print-all-frame');
    iframe.css('width', $doc.width());
    iframe.css('height', $doc.height());

    $doc.on('click', '#print-calendar', function (e) {
      iframe.attr('src', function() {
        return $(this).data('src');
      });
     
      iframe.on("load", function () {
        frames['calendar'].print();
      });
    });
  })
}(jQuery))
