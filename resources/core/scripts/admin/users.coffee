do ($ = jQuery) ->
  # Activate B3 popover and tooltip
  $ '[data-toggle="popover"]'
    .popover()
  $ '.js-show-tooltip'
    .tooltip()
  #-----------------------------------------------------------------------------
  # Business commissions
  #-----------------------------------------------------------------------------

  # Create a DIV to contain commission modal
  $ 'body'
    .append $('<div/>').attr 'id', 'js-commission-modal'
  $commissionModal = $ '#js-commission-modal'

  $ 'a.js-commission-action'
    .on 'click', (e) ->
      e.preventDefault()
      $me = $ @

      notifier = alertify.notify '<i class="fa fa-2x fa-spinner fa-spin"></i>'

      # Get the modal form
      $.ajax
        url: $me.attr 'href'
        type: 'GET'
      .done (res) ->
        $commissionModal.html res
        $commissionModal.children '.modal'
          .modal show: true

        notifier.dismiss()

