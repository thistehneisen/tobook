class VaraaSearch
  constructor: (input) ->
    @businesses = input.businesses
    @lat = input.lat
    @lng = input.lng
    # If user clicked on a NAT slot in front page and was redirected to this
    # page, we should automatically select booking form
    @categoryId = input.categoryId if input.categoryId?
    @serviceId = input.serviceId if input.serviceId?
    @employeeId = input.employeeId if input.employeeId?
    @time = input.time if input.time?

  run: ->
    if @categoryId and @serviceId
      @selectBookingForm()

    # Dislay businesses on the map
    @showBusinesses()

  selectBookingForm: ->
    $ "input:radio[name=category_id][value=#{@categoryId}], input:radio[name=service_id][value=#{@serviceId}]"
      .click()

    $ 'input[name=service_id]'
      .on 'afterSelect', ->
        $ "input:radio[name=employee_id][value=#{@employeeId}]"
          .click()

    $ '#as-step-3'
      .on 'afterShow', ->
        $ "button[data-time=#{@time}]"
          .click()
  ###*
   * Render the map
   *
   * @param  {string|jQuery} domId
   * @param  {double} lat
   * @param  {double} lng
   * @param  {array} markers Array of pairs of lat/lng
   *
   * @return {void}
  ###
  renderMap: (domId, lat, lng, markers) ->
    gmap = new GMaps
      div: domId
      lat: lat
      lng: lng
      zoom: 8

    if markers?
      for marker in markers
        gmap.addMarkers markers
    gmap

  ###*
   * Display businesses on the map
   *
   * @return {void}
  ###
  showBusinesses: ->
    self     = @
    $loading = $ '#js-loading'
    $list    = $ '#js-business-list'
    $map     = $ '#js-map-canvas'
    $single  = $ '#js-business-single'
    $heading = $ '#js-business-heading'

    $map.show()
    # Render the map
    markers = @extractMarkers @businesses
    @renderMap $map.attr('id'), @lat, @lng, markers

    # When user clicks on the heading to return back to business listing
    $heading.on 'click', (e) ->
      e.preventDefault()
      $single.hide()
      $list.find '.panel'
        .each ->
          $$ = $ @
          $$.show 'slide', direction: $$.data('direction'), 700

      $heading.find 'i'
        .hide()

    # Attach event handler
    $ 'div.js-business'
      .on 'click', (e) ->
        e.preventDefault()
        $$ = $ @
        businessId = $$.data 'id'
        delay = (ms, fn) -> setTimeout fn, ms
        slidePanel = ->
          $list.find '.panel'
            .each ->
              $$ = $ @
              $$.hide 'slide', direction: $$.data('direction'), 700

        # open result as a full page load instead of ajax if the browser width
        # is too small
        if $(window).width() < 768
          # sidebar should be less than a third of the container!
          window.location = $$.data 'url'
          return

        # If the current content is of this business, we don't need to fire
        # another AJAX
        if $list.data('current-business-id') == businessId
          slidePanel()
          delay 700, ->
            $single.show 'fade'
            # Show chevron as indicator to click back
            $heading.find 'i'
              .show()
          return

        # Highlight selected row
        $ 'div.js-business'
          .removeClass 'selected'
        $$.addClass 'selected'

        $loading.show()

        # Load information of this business
        $.ajax
          url: $$.data 'url'
          type: 'GET'
        .done (html) ->
          $loading.hide()
          slidePanel()
          delay 700, ->
            # Replace the whole page with business page
            $single.html html
            $single.show 'fade'
            # Show chevron as indicator to click back
            $heading.find 'i'
              .show()

            VARAA.initLayout3()

            # Set current business flag
            $list.data 'current-business-id', businessId

            # Render the map
            $bmap = $ "#js-map-#{businessId}"
            lat = $bmap.data 'lat'
            lng = $bmap.data 'lng'
            self.renderMap $bmap.attr('id'), lat, lng, [lat: lat, lng: lng]
  ###*
   * Extract pairs of lat and lng values to be show as markers on the map
   *
   * @param  {array} businesses Array of business objects
   *
   * @return {array}
  ###
  extractMarkers: (businesses) ->
    markers = []
    for business in businesses
      console.log business
      markers.push
        lat: business.lat
        lng: business.lng
        title: business.name
        infoWindow:
          content: "<p><strong>#{business.name}</strong></p><p>#{business.full_address}</p>"
    return markers

# Start the game
search = new VaraaSearch VARAA.Search
search.run()
