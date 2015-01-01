do ($ = jQuery) ->
  'use strict'

  $ ->
    datePickerOptions =
      format: 'yyyy-mm-dd'
      weekStart: 1
      autoclose: true
      startDate: new Date
      language: $('body').data 'locale'
    # Activate datepicker
    $('.date-picker').datepicker datePickerOptions

    guid = ->
      Math.floor((1 + Math.random()) * 0x10000).toString(16).substring 1

    $ '#js-fd-add-date'
      .on 'click', (e) ->
        e.preventDefault()

        tpl = $ '#js-fd-date-template'
        clone = $ tpl.clone()

        uuid = guid()
        clone.find('.date-picker').datepicker datePickerOptions
        clone.find('input:text').attr 'name', 'date[' + uuid + ']'
        clone.find('input:radio').attr 'name', 'time[' + uuid + ']'

        $('div.js-fd-date:last').after clone
        return

    $ 'a.js-fd-delete-date'
      .on 'click', (e) ->
        e.preventDefault()
        $me = $ @

        return if confirm($me.data('confirm')) is false

        $me.find 'i'
          .removeClass 'fa-close'
          .addClass 'fa-refresh fa-spin'

        $.ajax
          url: $me.attr 'href'
          type: 'GET'
        .done ->
          $ '#js-fd-date-' + $me.data('id')
            .fadeOut()
