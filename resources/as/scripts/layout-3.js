/*jslint browser: true, nomen: true, unparam: true, node: true*/
/*global jQuery, VARAA*/
(function ($) {
  'use strict'

  $(function () {
    VARAA.initLayout3 = function (settings) {
      var $categories = $('div.as-category'),
        $services = $('div.as-service'),
        $body = $('body'),
        $form = $('#varaa-as-bookings'),
        $step2 = $('#as-step-2'),
        $step3 = $('#as-step-3'),
        $step4 = $('#as-step-4'),
        $title1 = $('#as-title-1'),
        $title2 = $('#as-title-2'),
        $title3 = $('#as-title-3'),
        $title4 = $('#as-title-4'),
        $dp = $('#as-datepicker'),
        fnLoadTimeTable,
        settings = settings || { isAutoSelectEmployee: false},
        dataStorage = {
          hash: $body.data('hash')
      }

      fnLoadTimeTable = function () {
        if (dataStorage.hasOwnProperty('serviceId')) {
          // Show loading indicator
          $step3.find('div.as-timetable-content').hide()
          $step3.find('div.as-loading').show()

          $.ajax({
            url: $step3.data('url'),
            type: 'POST',
            data: dataStorage
          }).done(function (data) {
            $step3.find('div.panel-body').html(data)
            dataStorage.date = $step3.find('li.active > a').data('date')
            $step3.trigger('afterShow')
          })
        }
      }

      // Attach an `inhouse` indicator
      // If it's truthy, we'll show a login/register section in step 4
      dataStorage.inhouse = $form.data('inhouse')

      // Assign datepicker
      $dp.datepicker({
        format: 'yyyy-mm-dd',
        startDate: new Date(),
        todayBtn: true,
        todayHighlight: true,
        autoclose: true,
        weekStart: 1,
        language: $body.data('locale')
      }).on('changeDate', function (e) {
        dataStorage.date = e.format(0, 'yyyy-mm-dd')
        fnLoadTimeTable()
      })

      // Setup tooltip
      $('i[data-toggle=tooltip]').tooltip()

      $form.on('click', 'div.collapsable', function (e) {
        e.preventDefault()
        if ($(e.target).is('#as-datepicker') === false) {
          var $this = $(this)
          $($this.attr('href')).collapse('toggle')
        }
      })

      // When user selects a category
      $('input[name=category_id]').on('change', function () {
        var $this = $(this)
         // Highlight this button as selected
        $('label.as-service-category').removeClass('btn-success');
        $this.closest('label.as-service-category').addClass('btn-success');
        $categories.hide(function () {
          $('#as-category-' + $this.val() + '-services').show()
        })
      })

      // When user wants to return to category list
      $('p.as-back').on('click', function () {
        $services.hide(function () {
          $categories.show()
        })
      })

      // When user clicks on a service name to see its service time
      $('p.as-service-name').on('click', function () {
        var $this = $(this)
        $this.siblings('div.as-service-time').toggle()
      })

      // When user selects a service time
      $('input[name=service_id]').on('change', function () {
        var $this = $(this)
        $title1.find('span').text($this.data('service'))
        $title1.find('i').removeClass('hide')
        $title1.addClass('collapsable')

        // Highlight this button as selected
        $('.as-service-time').find('label').removeClass('btn-success');
        $this.closest('label').addClass('btn-success');

        // Assign serviceId to dataStorage
        dataStorage.serviceId = $this.val()
        dataStorage.serviceTimeId = $this.data('service-time-id')
        if(!dataStorage.hash) {
          dataStorage.hash = $this.data('hash')
        }

        dataStorage.l = 3
        if(settings.isAutoSelectEmployee || ($('#auto-select-employee').val() === 'true')) {
          $step3.collapse('show')
          $title3.addClass('collapsable')
          dataStorage.employeeId = '-1'
          fnLoadTimeTable()
        } else {
          $step2.collapse('show')
          $title2.addClass('collapsable')

          $.ajax({
            url: $step2.data('url'),
            type: 'POST',
            data: dataStorage
          }).done(function (data) {
            $step2.find('.panel-body').html(data)
            $this.trigger('afterSelect')
          })
        }
      })

      $form.on('change', 'input[name=employee_id]', function () {
        var $this = $(this)
        $title2.find('i').removeClass('hide')
        $step3.collapse('show')
        $title3.addClass('collapsable')

         // Highlight this button as selected
        $('.as-employee').find('label').removeClass('btn-success');
        $this.closest('label').addClass('btn-success');

        // Asign employeeId to dataStorage
        dataStorage.employeeId = $this.val()
        fnLoadTimeTable()
      })

      // When user clicks on date in timetable
      $form.on('click', 'a.as-date', function (e) {
        e.preventDefault()
        var $this = $(this)

        // Assign date to dataStorage
        dataStorage.date = $this.data('date')
        fnLoadTimeTable()
      })

      // When user clicks to select a time
      $form.on('click', 'button.btn-as-time', function () {
        var $this = $(this),
          panel = $step4.find('div.panel-body'),
          fnProcessToStep4

        // Assign selected time to dataStorage
        dataStorage.time = $this.text()
        // Also assign the employee ID
        dataStorage.employeeId = $this.data('employee-id')

        // Highlight this button as selected
        $('button.btn-as-time.btn-success').removeClass('btn-success')
        $this.addClass('btn-success')

        fnProcessToStep4 = function (data) {
          $step4.collapse('show')
          $title4.addClass('collapsable')

          // Empty existing content first
          panel.empty()
          panel.html(data)
        }

        if (dataStorage.inhouse === 1) {
          // Stop right here, so that we don't have duplicate booking
          // in the same cart
          $.ajax({
            url: $step4.data('url'),
            type: 'POST',
            data: dataStorage
          }).done(fnProcessToStep4)
          return
        }

        // Add selected time
        $.ajax({
          url: $('#add-service-url').val(),
          type: 'POST',
          dataType: 'json',
          data: {
            service_id: dataStorage.serviceId,
            service_time: dataStorage.serviceTimeId,
            employee_id: dataStorage.employeeId,
            hash: dataStorage.hash,
            booking_date: dataStorage.date,
            start_time: dataStorage.time
          }
        }).then(function (data) {
          // Attach cart ID
          dataStorage.cartId = data.cart_id

          // Then make a request to load the form
          return $.ajax({
            url: $step4.data('url'),
            type: 'POST',
            data: dataStorage
          })
        }).done(fnProcessToStep4)
      })

      $form.on('submit', '#as-confirm', function (e) {
        e.preventDefault()
        var $this = $(this)

        $this.find('.as-loading').show()
        $.ajax({
          url: $this.attr('action'),
          type: $this.attr('method'),
          data: $this.serialize()
        }).done(function (data) {
          $step4.find('div.panel-body').html(data)
        })
      })

      // When user adds booking to cart
      $form.on('click', '#btn-add-cart', function (e) {
        e.preventDefault()
        e.stopPropagation()

        var $this = $(this),
          $outerform = $this.parents('form')

        if (dataStorage.hash === undefined) {
          dataStorage.hash = $('#business_hash').val()
        }

        // Create booking service first
        $.ajax({
          url: $this.data('service-url'),
          type: 'POST',
          dataType: 'JSON',
          data: {
            service_id: dataStorage.serviceId,
            service_time: dataStorage.serviceTimeId,
            employee_id: dataStorage.employeeId,
            booking_date: dataStorage.date,
            start_time: dataStorage.time,
            hash: dataStorage.hash,
            inhouse: dataStorage.inhouse
          }
        }).then(function (e) {
          if (e.cart_id !== undefined) {
            $outerform.find('input[name=cart_id]')
              .val(e.cart_id)
          }

          if (e.booking_service_id !== undefined) {
            $outerform.find('input[name=booking_service_id]')
              .val(e.booking_service_id)
          }

          $outerform.find('input[name=business_id]')
            .val($('#business_id').val())

          return $.ajax({
            url: $outerform.attr('action'),
            type: $outerform.attr('method'),
            data: $outerform.serialize()
          })
        }).done(function (e) {
          if (e.booking_id !== undefined) {
            dataStorage.bookingId = e.booking_id
          }

          $(document).trigger('cart.reload', true)
        })
      })

      $form.on('click', '#terms', function (e) {
        var $this = $(this),
            submit = $('#as-form-checkout').find(':submit'),
            term_enabled = parseInt($('#as-form-checkout').data('term-enabled'), 10);

        //yes and required
        if (term_enabled === 3) {
            if($this.is(':checked') === false) {
                submit.attr('disabled', 'disabled');
                submit.addClass('btn-disabled').
                    removeClass('btn-success');
                submit.siblings('span.text-success')
                  .removeClass('text-success')
                  .addClass('text-danger')
                  .html('<ul class="no-bullet"><li><h6>'+$('#as-form-checkout').data('term-error-msg')+'</h6></li></ul>');
            } else {
                submit.removeAttr('disabled');
                submit.addClass('btn-success').
                    removeClass('btn-disabled');
                submit.siblings('span.text-danger')
                  .removeClass('text-danger')
                  .addClass('text-success').html('');
            }
        }
      });

      // When user submits the confirmation form
      $form.on('submit', '#as-form-checkout', function (e) {
        e.preventDefault()
        var $this = $(this),
          data = $this.serialize(),
          loading = $this.find('.as-loading'),
          submit = $this.find('button[type=submit]'),
          src = $this.find('input[name=source]').val(),
          fnFail = function (e) {
            $this.removeAttr('disabled')

            var res = e.responseJSON,
              message = $this.find('div.error-msg').text()

            if (res.message !== undefined) {
              message = res.message
            }
            loading.hide()
            submit.removeClass('btn-success')
              .addClass('btn-danger')
            submit.siblings('span.text-success')
              .removeClass('text-success')
              .addClass('text-danger')
              .html(message)
        }

        var term_enabled = parseInt($(this).data('term-enabled'), 10);
        //yes and required
        if (term_enabled === 3 && !$('#terms').is(':checked')) {
            return alertify.alert($(this).data('term-error-msg'));
        }

        // Prevent user double click to the submit button
        $this.attr('disabled', 'disabled')

        loading.show()
        submit.prop('disabled', true)
        // Create booking service
        $.ajax({
          url: $this.attr('action'),
          type: 'POST',
          data: data,
          dataType: 'json'
        }).done(function (e) {
          if (e.success === true) {
            // If we're in Varaa website, submit as normal to
            // redirect user to paygate
            if (dataStorage.inhouse === 1) {
              $body.css('cursor', 'progress')
              $('#as-form-payment').submit()
              return
            }

            console.log(e)
            if (typeof e.checkout_url !== 'undefined') {
              window.location = e.checkout_url
              return
            }

            // Hide loading
            loading.hide()
            var $overlay = $('#as-overlay-message')
            $overlay.empty()
            for (var i in e.message) {
              $overlay.append(e.message[i])
            }
            $overlay.show()

            if (src !== 'inhouse') {
              var counter = 9
              var id = setInterval(function () {
                $('#as-counter').html(counter)
                if (counter-- === 0) {
                  clearInterval(id)
                  window.location = $this.data('success-url')
                }
              }, 1000)
            }
          }
        }).fail(fnFail)
      })

      $form.on('click', '#toggle_term', function (e) {
        e.preventDefault()
        var source = $("input[name='source']").val();
        if(source !== 'inhouse') {
            $('#terms_body').toggle()
        } else {
            alertify.alert($("input[name='booking_terms']").val())
            .set('title', $("input[name='alert_title']").val());
        }
      })
    }
  })
}(jQuery))
