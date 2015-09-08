do ($ = jQuery) ->
  'use strict'

  $ ->
    $formSearch              = $ '#form-search'
    $q                       = $formSearch.find '[name=q]'
    $location                = $formSearch.find '[name=location]'
    $forceSelection          = $formSearch.find '.force-selection'
    $locationDropdownWrapper = $ '#location-dropdown-wrapper'

    # --------------------------------------------------------------------------
    # Homepage modal
    # --------------------------------------------------------------------------
    $modal = $ '#js-homepage-modal'
    if $modal.length > 0
      $modal.modal()

    # --------------------------------------------------------------------------
    # Use MithrilJS to write the location dropdown
    # --------------------------------------------------------------------------

    # Simple i18n method
    __ = (path) ->
      val = app.lang
      path.split '.'
        .forEach (key) -> val = if val[key]? then val[key] else null
      return if val? then val else path

    # Ugly variable to keep track of visible locations in the dropdown
    locations = app.prefetch.districts.map (name) -> type: 'district', name: name
        .concat app.prefetch.cities.map (name) -> type: 'city', name: name
    visible = locations

    LocationDropdown = {}

    LocationDropdown.view = (ctrl, args) ->
      return [
        m('li[role=presentation]', [m('a.form-search-city[data-current-location=1][href=#]', [m('strong', __('home.search.current_location'))])])
        m('li.divider[role=presentation]')
        m('li[role=presentation]', {class: if visible.length then 'soft-hidden' else 'disabled'}, [m('a[href=#]', [m('em', 'Empty')])])
        visible.map (location) ->
          return m 'li[role=presentation]',
            m("a.form-search-city[href=#][data-current-location=0][data-type=#{location.type}]", location.name)
      ]

    m.mount document.getElementById('big-cities-dropdown'),
      m.component LocationDropdown
    # --------------------------------------------------------------------------

    engine = new Bloodhound
      queryTokenizer: Bloodhound.tokenizers.whitespace
      local: locations
      datumTokenizer: (i) -> Bloodhound.tokenizers.whitespace i.name
    engine.initialize()

    $location.on 'keyup', (e) ->
      keyword = e.target.value
      if keyword.length is 0
        visible = locations
        m.redraw()
        return

      engine.get keyword, (results) ->
        visible = results
        m.redraw()

    # --------------------------------------------------------------------------

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

    $location
      .on 'focus', (e) ->
        doNotShowTooltip e
        $locationDropdownWrapper.addClass 'open'
        @value = ''

    $formSearch.on 'submit', (e) ->
      if $formSearch.data('disableSubmission') is true
        e.preventDefault()
        return;

      return true if $formSearch.data('bypass') is true

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
      if $location.data('current-location') is 1
        VARAA.getLocation()
          .then (lat, lng) ->
            $formSearch.find('[name=lat]').val(lat)
            $formSearch.find('[name=lng]').val(lng)
            bypassAndSubmit()
      else
        bypassAndSubmit()

    # When user clicks on an option in location dropdown list
    $ '#big-cities-dropdown'
      .on 'click', 'a.form-search-city', (e) ->
        e.preventDefault()
        $me = $ @

        $formSearch.find('[name=type]').val($me.data('type'))
        $location.data 'current-location', $me.data('current-location')
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
          VARAA.getLocation()
            .then (lat, lng) ->
              $.ajax
                url: $body.data 'geo-url'
                type: 'POST'
                data:
                  lat: lat
                  lng: lng
              .done -> window.location = $$.prop 'href'
            .fail -> window.location = $$.prop 'href'

    # If user clicks on "Choose category" in navigation, scroll to the list of
    # categories
    $ '#js-choose-category'
      .on 'click', (e) ->
        e.preventDefault()
        $.scrollTo '#js-home-categories', duration: 1000

    $ 'a.view-video'
      .on 'click', (e) ->
        e.preventDefault()
        $.fancybox
              'padding'       : 0,
              'autoScale'     : false,
              'transitionIn'  : 'none',
              'transitionOut' : 'none',
              'title'         : this.title,
              'width'         : 680,
              'height'        : 495,
              'href'          : this.href.replace(new RegExp("watch\\?v=", "i"), 'v/'),
              'type'          : 'swf',
              'swf'           :
                  'wmode'        : 'transparent',
                  'allowfullscreen'   : 'true'

        return false;

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
