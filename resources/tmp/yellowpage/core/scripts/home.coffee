do ($ = jQuery) ->
  'use strict'

  $ ->
    $formSearch              = $ '#form-search'
    $q                       = $formSearch.find '[name=q]'
    $location                = $formSearch.find '[name=location]'
    $forceSelection          = $formSearch.find '.force-selection'
    $locationDropdownWrapper = $ '#location-dropdown-wrapper'

    # Init typeahead on search form
    VARAA.initTypeahead $q, 'services' if $q.length > 0
    $q.bind 'typeahead:selected', (e, selection) ->
      $formSearch.data 'disableSubmission', false
      if selection.type is 'category'
        $formSearch.data 'suggestion', $q.val()
        $formSearch.data('old-action', $formSearch.attr('action'))
        $formSearch.attr('action', selection.url)
      else
        window.location = selection.url if typeof selection.url isnt 'undefined'

    doNotShowTooltip = (e) ->
      $(e.target).tooltip 'hide'

    $q.on 'focus', doNotShowTooltip
      .on 'blur', (e) ->
        $me = $ @
        val = $me.val()

        bloodhound = $q.data 'bloodhound'
        bloodhound.get val, (results) ->
          results = results.filter (result) ->
            return result.name is val

          if results.length is 0
            $forceSelection.show()
            $formSearch.data 'disableSubmission', true
          else
            $forceSelection.hide()
            $formSearch.data 'disableSubmission', false

    $location.on 'focus', doNotShowTooltip
    $location
      .on 'focus', (e) -> $locationDropdownWrapper.addClass 'open'

    $formSearch.on 'submit', (e) ->
      if $formSearch.data('disableSubmission') is true
        e.preventDefault()
        return;

      return true if $formSearch.data('bypass') is true
      # If the action has been modified by selecting MC/TM
      if $formSearch.data 'old-action'
        if $q.val() isnt $formSearch.data('suggestion')
          # Revert old action, submit as normal
          $formSearch.attr('action', $formSearch.data('old-action'))
        return true

      e.preventDefault()

      bypassAndSubmit = ->
        $formSearch.data 'bypass', true
        $formSearch.submit()

      # Check if all fields have input
      emptyQ = $q.val().length is 0
      emptyLocation = $location.val().length is 0

      $q.tooltip 'show' if emptyQ
      $location.tooltip 'show' if emptyLocation
      return if emptyLocation or emptyQ

      # Should ask for location
      if $location.data 'current-location' == '1'
        VARAA.getLocation()
          .then (lat, lng) ->
            $formSearch.find('[name=lat]').val(lat)
            $formSearch.find('[name=lng]').val(lng)
          .always bypassAndSubmit
      else
        bypassAndSubmit()

    # When user clicks on an option in location dropdown list
    $ 'a.form-search-city'
      .on 'click', (e) ->
        e.preventDefault()
        $me = $ @

        $location.attr 'data-current-location', $me.data('current-location')
        $location.val $me.text()
        $locationDropdownWrapper.removeClass 'open'

    # Contact form submit
    $formContact = $ '#form-contact'
    if $formContact.length > 0
      $formContact.on 'submit', (e) ->
        e.preventDefault()
        $me = $ @
        $success = $me.find '.alert-success'
        $danger = $me.find '.alert-danger'
        $submit = $me.find '[type=submit]'

        $.ajax
          url: $me.attr 'action'
          method: 'POST'
          dataType: 'JSON'
          data: $me.serialize()
        .then ->
          $danger.hide()
          $success.show()
          $submit.attr 'disabled', true
        .fail (e) ->
          data = e.responseJSON

          if e.status is 422
            # Validation error
            $danger.empty()
            for name, errors of data
              do (errors) ->
                $danger.append($('<p/>').html(errors.join('<br>')))
          else
            $danger.html data.message

          $danger.show()

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
