do ($ = jQuery) ->
  # Activate B3 popover and tooltip
  $ '[data-toggle="popover"]'
    .popover()
  $ '.js-show-tooltip'
    .tooltip()
  #-----------------------------------------------------------------------------
  # Business commissions
  #-----------------------------------------------------------------------------
  $ 'a.js-commission-action'
    .on 'click', (e) ->
      e.preventDefault()
      $me = $ @

      # Prompt to ask about amount and note
      amount = prompt('Amount, e.g. 10.99')
      note = prompt('Note') unless amount is null

      $.ajax
        url: $me.attr 'href'
        type: 'POST'
        dataType: 'JSON'
        data:
          amount: amount
          note: note
      .done (res) ->
        alertify.success res.message

