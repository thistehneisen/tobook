do ($ = jQuery) ->
  # Activate B3 popover and tooltip
  $ '[data-toggle="popover"]'
    .popover()
  $ '.js-show-tooltip'
    .tooltip()
  #-----------------------------------------------------------------------------
  # Business commissions
  #-----------------------------------------------------------------------------
  $ 'a.js-commission-add'
    .on 'click', (e) ->
      e.preventDefault()
      alertify.prompt 'Prompt', 'Enter the commission amount', (e, value) ->
        console.log value
        alertify.prompt 'Prompt', 'Enter note (optional)', (e, value) ->
          console.log value
