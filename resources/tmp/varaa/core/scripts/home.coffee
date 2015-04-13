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

    # Make boxes to have equal heights
    VARAA.equalize '.available-slot .info'
    VARAA.equalize '.list-group-item'

    # Prepare datetime picker for search form
    $ 'div.datetime-control'
      .each (_, item) ->
        $ item
        .datetimepicker
          format: $(@).data 'format'
          inline: true
          stepping: 15

    $ '.datetime-link'
      .on 'click', (e) ->
        e.preventDefault()
      .on 'focus', (e) ->
        e.preventDefault()
        $(@).siblings '.datetime-control'
          .show()
      .on 'blur', (e) ->
        e.preventDefault()
        $(@).siblings '.datetime-control'
          .hide()

    # If user clicks on "Choose category" in navigation, scroll to the list of
    # categories
    $ '#js-choose-category'
      .on 'click', (e) ->
        e.preventDefault()
        $.scrollTo '#js-home-categories', duration: 1000

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
        $me = $ @

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

    # We get all deal elements
    $allDeals = $ '.js-deal'

    # When clicking on a filter link
    $ 'a.js-filter-link'
      .on 'click', (e) ->
        e.preventDefault()
        $me = $ @

        # Mark this filter as enabled
        $me.toggleClass 'active'
        # Get all active filters
        $active = $ 'a.js-filter-link.active'

        # If we have active filters
        if $active.length
          # Hide all deals
          $allDeals.hide()

          # Then go through all filters and enable corresponding deals
          $active.each (i, item) ->
            $item = $ item
            id = $item.data 'id'
            deals = $ ".js-deal-category-#{id}"
            deals.fadeIn()
        else
          # If we don't have any active filters, just show all deals
          $allDeals.show()
