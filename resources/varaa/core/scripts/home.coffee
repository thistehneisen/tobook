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

    # Prepare datetime picker for search form
    $ 'div.datetime-control'
      .each (_, item) ->
        $ item
        .datetimepicker
          format: $(this).data 'format'
          inline: true
          stepping: 15
          minDate: new Date()

    $ '.datetime-link'
      .on 'click', (e) ->
        e.preventDefault()
      .on 'focus', (e) ->
        e.preventDefault()
        $(this).siblings '.datetime-control'
          .show()
      .on 'blur', (e) ->
        e.preventDefault()
        $(this).siblings '.datetime-control'
          .hide()

    #
    # Auxilary function to apply toggle function to list
    #
    # @param object $el   jQuery instance of an UL
    # @param int    limit Number of LI children to be shown
    #
    applyToogleList = ($el, limit) ->
      # First we split the LI children into 2 parts
      $head = $el.find 'li:not(.toggle):lt('+limit+')'
      $tail = $el.find 'li:not(.toggle):gt('+(limit-1)+')'

      # Including "More" and "Less"
      $toggle = $el.find '.toggle'

      # The link to show "More"
      $more = $el.find '.more'

      # We show the first part
      $head.show()
      # Then show More link if the tail part has items
      $more.show() if $tail.length > 0

      # When clicking on the toggle links
      $toggle.on 'click', (e) ->
        e.preventDefault()
        $me = $ this

        # Show the rest
        $tail.slideToggle()
        # Hide the current link
        $me.hide()
        # Toggle the counterpart link
        $me.siblings '.toggle'
          .show()

    # Show only first 3 business categories
    $ 'ul.list-categories'
      .each (i, item) ->
        applyToogleList $(item), 3

    # Show only first 6 items in category filter
    applyToogleList $('#js-category-filter'), 6
