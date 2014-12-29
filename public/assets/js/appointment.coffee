#jslint browser: true, nomen: true, unparam: true, node: true

#global $, jQuery, alertify, location, window, VARAA
"use strict"
(($) ->
  $ ->

    # user clicked "ok"

    # Allow to click on TR to select checkbox
    #fix bug cannot click to the actual checkbox

    # Date picker

    #use data-index-url attribute to prevent append date to date like yyyy-mm-dd/yyyy-mm-dd
    calculateTotalMinute = ->
      total = parseInt($("#during").val(), 10) + parseInt($("#after").val(), 10) + parseInt($("#before").val(), 10)
      $("#total").val total
      return

    # calculate total time

    # ------------------------ Backend Calendar ------------------------ //

    # ------------------------ Button handlers ------------------------ //

    #the 'is' for buttons that trigger popups
    #the 'has' for icons within a button that triggers a popup
    details_in_popup = (link, div_id, booking_id) ->
      $.ajax(
        url: link
        data:
          booking_id: booking_id

        success: (response) ->
          $("#" + div_id).html response
          return
      ).fail (data) ->
        alertify.alert "Alert", data.responseJSON.message, ->
          alertify.message "OK"
          return

        return

      "<div class=\"popover_form\" id=\"" + div_id + "\"><img src=\"/assets/img/busy.gif\"></div>"

    # Hide previous open popover
    fixedCalendarHeader = ->
      return  if $(".as-col-header").length is 0
      colHeaderTop = $(".as-col-header").offset().top  if colHeaderTop is -1
      if $(window).scrollTop() > colHeaderTop
        $(".as-col-header").css "position", "fixed"
        $(".as-col-header").css "height", 25
        $(".as-col-header").css "width", 163
        $(".as-col-header").css "top", 0
      if $(window).scrollTop() <= colHeaderTop
        if scrolledLeft
          if $(window).scrollTop() is 0
            $(".as-col-header").css "top", colHeaderTop
          else
            $(".as-col-header").css "top", colHeaderTop - $(window).scrollTop()
            $(".as-col-left-header").css "margin-top", "25px"
            $("#as-ul").css "margin-top", "25px"
        else
          $(".as-col-header").css "position", "relative"
      return
    $doc = $(document)
    $(".customer-tooltip").tooltip
      selector: ""
      placement: "top"
      container: "body"
      trigger: "hover"
      html: true

    $(".selectpicker").selectpicker()
    $(".toggle-check-all-boxes").click ->
      checkboxClass = ($(this).data("checkbox-class")) or "checkbox"
      $("." + checkboxClass).prop "checked", @checked
      return

    $doc.bind("ajaxSend", ->
      $("#loading").show()
      return
    ).bind "ajaxComplete", ->
      $("#loading").hide()
      return

    $("#form-bulk").on "submit", (e) ->
      e.preventDefault()
      $this = $(this)
      alertify.confirm "Confirm", $this.data("confirm"), (->
        $.ajax(
          type: "POST"
          url: $this.attr("action")
          data: $this.serialize()
          dataType: "json"
        ).done(->
          alertify.alert "OK"
          if $("#mass-action").val() is "destroy"
            $("#form-bulk [type=checkbox]:checked").each ->
              $("#row-" + $(this).val()).remove()
              return

          return
        ).fail ->
          alertify.alert "Something went wrong"
          return

        return
      ), ->
        alertify.error "Cancel"
        return

      return

    $("table.table-crud tr").on "click", (event) ->
      target = $(event.target)
      $this = $(this)
      checkbox = $this.find("td:first input:checkbox")
      checked = checkbox.prop("checked")
      checkbox.prop "checked", not checked  if target.is("td")
      return

    $doc.on "focus", ".date-picker", ->
      $(this).datepicker
        format: "yyyy-mm-dd"
        weekStart: 1
        autoclose: true
        language: $("body").data("locale")

      return

    $("#calendar_date").datepicker(
      format: "yyyy-mm-dd"
      weekStart: 1
      autoclose: true
      calendarWeeks: true
      language: $("body").data("locale")
    ).on "changeDate", ->
      window.location.href = $(this).data("index-url") + "/" + $(this).val()
      return

    $("#before, #during, #after").change ->
      calculateTotalMinute()
      return

    $("button").click (e) ->
      calculateTotalMinute()
      return

    $(".as-calendar li.active, .as-calendar li.inactive").click ->
      employee_id = $(this).data("employee-id")
      booking_date = $(this).data("booking-date")
      start_time = $(this).data("start-time")
      $("#employee_id").val employee_id
      $("#date").val booking_date
      $("#start_time").val start_time
      $(".fancybox").fancybox
        padding: 5
        width: 350
        title: ""
        autoSize: false
        autoWidth: false
        autoHeight: true

      return

    $(".as-calendar li.active, .as-calendar li.inactive").hover (->
      _this = $(this)
      start_time = _this.data("start-time")
      _this.append "<span class=\"hover\">{0} {1}</span>".format(VARAA.trans("common.book"), start_time)
      return
    ), ->
      $(this).find(".hover").remove()
      return

    $("body").on "click", (e) ->
      $("a.popup-ajax").each ->
        $(this).popover "hide"  if not $(this).is(e.target) and $(this).has(e.target).length is 0 and $(".popover").has(e.target).length is 0
        return

      return

    $("a.popup-ajax").click((e) ->
      e.preventDefault()
      $("a.popup-ajax").popover "hide"
      return
    ).popover
      html: true
      placement: (context, source) ->
        position = $(source).position()
        width = $(source).width()
        fullwidth = $(".as-calendar").width()
        popover_width = $(".popover-content").width()
        placement = "right"
        placement = "left"  if position.left + width + popover_width > fullwidth
        placement

      template: "<div class=\"popover\" role=\"tooltip\"><div class=\"arrow\"></div><div class=\"popover-content\"></div></div>"
      content: ->
        div_id = "tmp-id-" + $.now()
        booking_id = $(this).data("booking-id")
        details_in_popup $(this).attr("href"), div_id, booking_id

    $("[data-toggle=popover]").on "shown.bs.popover", ->
      if $(".popover").position().top < 0
        $(".popover").css "top", parseInt($(".popover").css("top"), 10) + 85 + "px"
        $(".popover .arrow").css "top", "15px"
      return

    $doc.on "click", "#btn-submit-modify-form", (e) ->
      e.preventDefault()
      booking_id = $(this).data("booking-id")
      url = $(this).data("action-url")
      $.ajax(
        type: "POST"
        url: url
        data: $("#modify_booking_form_" + booking_id).serialize()
        dataType: "json"
      ).done (data) ->
        if data.success
          location.reload()
        else
          alertify.alert data.message
        return

      return

    $doc.on "click", "#btn-add-employee-freetime", (e) ->
      e.preventDefault()
      $.ajax(
        type: "POST"
        url: $("#add_freetime_url").val()
        data: $("#freetime_form").serialize()
        dataType: "json"
      ).done (data) ->
        if data.success
          location.reload()
        else
          alertify.alert data.message
        return

      return

    $(".btn-delete-employee-freetime").click (e) ->
      e.preventDefault()
      $self = $(this)
      alertify.confirm $(this).data("confirm"), (e) ->
        if e
          $.ajax(
            type: "POST"
            url: $self.data("action-url")
            data:
              freetime_id: $self.data("freetime-id")

            dataType: "json"
          ).done (data) ->
            location.reload()  if data.success
            return

        return

      return

    $doc.on "click", "#btn-add-service", (e) ->
      e.preventDefault()
      service_id = $("#services").val()
      employee_id = $("#employee_id").val()
      service_time = $("#service_times").val()
      modify_times = $("#modify_times").val()
      booking_date = $("#booking_date").val()
      start_time = $("#start_time").val()
      uuid = $("#booking_uuid").val()
      booking_id = $("#booking_id").val()
      $.ajax(
        type: "POST"
        url: $("#add_service_url").val()
        data:
          service_id: service_id
          service_time: service_time
          employee_id: employee_id
          booking_id: booking_id
          modify_times: modify_times
          booking_date: booking_date
          start_time: start_time
          uuid: uuid

        dataType: "json"
      ).done((data) ->
        $("#added_service_name").text data.service_name
        $("#added_employee_name").text data.employee_name
        $("#added_booking_date").text data.datetime
        $("#added_booking_modify_time").text data.modify_time
        $("#added_booking_plustime").text data.plustime
        $("#added_service_price").text data.price
        $("#added_services").show()
        return
      ).fail (data) ->
        alertify.alert "Alert", data.responseJSON.message, ->
          alertify.message "OK"
          return

        return

      return

    $doc.on "click", "#btn-save-booking", (e) ->
      e.preventDefault()
      postData = $("#booking_form").serializeArray()
      postData.push
        name: "employee_id"
        value: $("#employee_id").val()

      $.ajax(
        type: "POST"
        url: $("#add_booking_url").val()
        data: postData
        dataType: "json"
      ).done (data) ->
        if data.success
          location.reload()
        else
          alertify.alert data.message
        return

      return

    $("#btn-continute-action").click (e) ->
      e.preventDefault()
      employee_id = $("#employee_id").val()
      booking_date = $("#date").val()
      start_time = $("#start_time").val()
      selected_action = $("input[name=\"action_type\"]:checked").val()
      action_url = undefined
      if selected_action is "book"
        $.fancybox.open
          padding: 5
          width: 850
          title: ""
          autoSize: false
          autoScale: true
          autoWidth: false
          autoHeight: true
          fitToView: false
          href: $("#get_booking_form_url").val()
          type: "ajax"
          ajax:
            type: "GET"
            data:
              employee_id: employee_id
              booking_date: booking_date
              start_time: start_time

          helpers:
            overlay:
              locked: false

          autoCenter: false

      else if selected_action is "freetime"
        $.fancybox.open
          padding: 5
          width: 550
          title: ""
          autoSize: false
          autoScale: true
          autoWidth: false
          autoHeight: true
          fitToView: false
          href: $("#get_freetime_form_url").val()
          type: "ajax"
          ajax:
            type: "GET"
            data:
              employee_id: employee_id
              booking_date: booking_date
              start_time: start_time

          helpers:
            overlay:
              locked: false

          autoCenter: false

      else if selected_action is "paste_booking"
        action_url = $("#get_paste_booking_url").val()
        $.ajax(
          type: "POST"
          url: action_url
          data:
            employee_id: employee_id
            booking_date: booking_date
            start_time: start_time

          dataType: "json"
        ).done (data) ->
          if data.success
            location.reload()
          else
            alertify.alert data.message
          return

      else if selected_action is "discard_cut_booking"
        action_url = $("#get_discard_cut_booking_url").val()
        $.ajax(
          type: "POST"
          url: action_url
          dataType: "json"
        ).done (data) ->
          if data.success
            location.reload()
          else
            alertify.alert data.message
          return

      return

    $doc.on "click", "a.js-btn-view-booking", (e) ->
      e.preventDefault()
      booking_id = $(this).data("booking-id")
      $("#employee_id").val $(this).data("employee-id")
      $("#start_time").val $(this).data("start-time")
      $.fancybox.open
        padding: 5
        width: 850
        title: ""
        autoSize: false
        autoScale: true
        autoWidth: false
        autoHeight: true
        fitToView: false
        href: $("#get_booking_form_url").val()
        type: "ajax"
        ajax:
          type: "GET"
          data:
            booking_id: booking_id

        helpers:
          overlay:
            locked: false

        autoCenter: false

      return

    $doc.on "click", "a.js-btn-cut-booking", (e) ->
      e.preventDefault()
      booking_id = $(this).data("booking-id")
      action_url = $(this).data("action-url")
      $.ajax(
        type: "POST"
        url: action_url
        data:
          booking_id: booking_id

        dataType: "json"
      ).done (data) ->
        if data.success
          $(".booked").removeAttr "style"
          $(".booking-id-" + booking_id).attr "style", "background-color:grey"
          $("#row_paste_booking").show()
          $("#row_discard_cut_booking").show()
        else
          alertify.alert data.message
        return

      return

    colHeaderTop = -1
    originalOffset = []
    scrolledLeft = false
    $(window).scroll ->
      fixedCalendarHeader()
      return

    #Only allow user scroll when full page is loaded
    $("body").css "overflow", "hidden"

    $(window).load ->
      $("body").css "overflow", "auto"
      $(".as-calendar").scroll ->
        scrolledLeft = true
        if $.isEmptyObject(originalOffset)
          $(".as-col-header").each (key, item) ->
            originalOffset.push $(item).offset().left
            return

        $(".as-col-header").each (key, item) ->
          offset = parseInt(originalOffset[key], 10) - parseInt($(".as-calendar").scrollLeft(), 10)
          $(item).css "left", offset
          if offset < 15
            $(item).css "opacity", 0.2
          else
            $(item).css "opacity", 1
          return

        return

      return

    return

  return

) jQuery
