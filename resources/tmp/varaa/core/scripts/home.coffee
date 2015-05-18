do ($ = jQuery) ->
  'use strict'

  $ ->
    $formSearch              = $ '#form-search'
    $q                       = $formSearch.find('[name=q]')
    $location                = $formSearch.find('[name=location]')
    $locationDropdownWrapper = $ '#location-dropdown-wrapper'

    doNotShowTooltip = (e) ->
      $(e.target).tooltip 'hide'

    $q.on 'focus', doNotShowTooltip
    $location.on 'focus', doNotShowTooltip
    $location
      .on 'focus', (e) -> $locationDropdownWrapper.addClass 'open'
      .on 'blur', (e) ->
        shouldClose = e.relatedTarget and $(e.relatedTarget).hasClass 'form-search-city'
        $locationDropdownWrapper.removeClass 'open' unless shouldClose

    $formSearch.on 'submit', (e) ->
      # Check if all fields have input
      emptyQ = $q.val().length is 0
      emptyLocation = $location.val().length is 0

      $q.tooltip 'show' if emptyQ
      $location.tooltip 'show' if emptyLocation
      e.preventDefault() if emptyLocation or emptyQ

    # When user clicks on an option in location dropdown list
    $ 'a.form-search-city'
      .on 'click', (e) ->
        e.preventDefault()
        $location.val $(@).text()
        $locationDropdownWrapper.removeClass 'open'

    $ '#ask-current-location'
      .on 'click', (e) ->
        e.preventDefault()
        VARAA.getLocation()
          .then (lat, lng) ->
            $formSearch.find('[name=lat]').val(lat)
            $formSearch.find('[name=lng]').val(lng)

    # Init typeahead on search form
    VARAA.initTypeahead $q, 'services' if $q.length > 0
    $q.bind 'typeahead:selected', (e, selection) ->
      console.log selection
      window.location = selection.url if typeof selection.url isnt 'undefined'

    # When user clicks on navbar, we'll ask for the current location
    $ '#js-navbar'
      .find 'a'
      .on 'click', (e) ->
        e.preventDefault()
        $$ = $ @
        $body = $ 'body'
        lat = $body.data 'lat'
        lng = $body.data 'lng'

        if (lat? and lng? and lat != '' and lng != '')
          window.location = $$.prop 'href'
        else
          # Ask for location
          success = (pos) ->
            lat = pos.coords.latitude
            lng = pos.coords.longitude
            $.ajax
              url: $body.data 'geo-url'
              type: 'POST'
              data:
                lat: lat
                lng: lng
            .done ->
              window.location = $$.prop 'href'

          failed = ->
            window.location = $$.prop 'href'

          navigator.geolocation.getCurrentPosition success, failed, timeout: 10000

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
