do ($ = jQuery) ->
  'use strict'

  $ ->
    # Flashdeal modal
    $fdModal = $ '#fd-modal'
    $fdModal.modal show: false

    $fdModal.changeContent = (content) ->
      $fdModal.find 'div.modal-content'
        .html content
      return @

    $fdModal.loading = (content) ->
      div = $ '<div/>'
        .addClass 'text-center'
        .html '<i class="fa fa-spinner fa-spin fa-2x"></i>'

      $fdModal.find 'div.modal-body'
        .html div
      return @

    # When user clicks to view detail of a deal
    $('a.btn-fd').on 'click', (e) ->
      e.preventDefault()
      $me = $ @

      $fdModal.modal 'show'
        .loading()

      $.ajax url: $me.data 'url'
        .done (content) ->
          $fdModal.changeContent content

    # When user clicks to add a deal into cart
    $fdModal.on 'click', 'button.btn-fd-cart', (e) ->
      e.preventDefault()
      $me = $ @

      $.ajax
        url: $me.data 'url'
        type: 'POST'
        dataType: 'JSON'
        data:
          business_id: $me.data 'business-id'
          deal_id: $me.data 'deal-id'
      .done ->
        $(document).trigger 'cart.reload', true
        $fdModal.modal 'hide'
      .fail (e) ->
        if e.responseJSON.hasOwnProperty 'message'
          div = $ '<div/>'
            .addClass 'alert alert-danger'
            .html e.responseJSON.message
          $fdModal.find 'div.modal-body'
            .html div

    # Apply countdown
    VARAA.applyCountdown $ 'a.countdown'

    # Change business category
    $ '#business_category'
      .on 'change', ->
        window.location = @.value
        return

    # Make boxes to have equal heights
    VARAA.equalize '.available-slot .info'
    VARAA.equalize '.list-group-item'

    # Show only first 3 business categories
    $ 'ul.list-categories'
      .each (i, item) ->
        $item = $ item
        $first = $item.find 'li:lt(3)'
        $rest = $item.find 'li:gt(3)'
        $arrow = $item.find '.arrow'
        $more = $item.find '.more'

        # Show the first 3 sub-categories
        $first.show()
        # If a category has more than 3 sub-categories, show link to toggle
        # the rest
        $more.show() if $rest.length > 3

        $arrow.on 'click', (e) ->
          e.preventDefault()
          $this = $ this
          $rest.slideToggle()
          $this.hide()
          $this.siblings '.arrow'
            .show()
