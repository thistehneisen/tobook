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

    # Take out the first business in the list. This will be use in single
    # business page
    @single = @businesses[0] if @businesses[0]?

  run: ->
    if @categoryId and @serviceId
      @selectBookingForm()

    # Just show the map of this business and done
    if @businesses.length == 1
      @showSingleBusiness()
    # Else this is the search result page, we should render the map
    # and set markers of all businesses
    else
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

  renderMap: (mapId, lat, lng, markers) ->
    gmap = new GMaps
      div: mapId
      lat: lat
      lng: lng
      zoom: 8

    if typeof markers isnt `undefined`
      for marker in markers
        gmap.addMarkers markers
    gmap

  showBusinesses: ->
    $loading = $ '#js-loading'
    $businessContent = $ '#js-business-content'
    $map = $ '#map-canvas'

    $map.show()
    # Render the map
    markers = @extractMarkers @businesses
    @renderMap '#map-canvas', @lat, @lng, markers

    # Attach event handler
    $ 'div.result-row'
      .on 'click', (e) ->
        $$ = $ @
        e.preventDefault();

        # open result as a full page load instead of ajax if the browser width
        # is too small
        if $(window).width() < 768
          # sidebar should be less than a third of the container!
          window.location = $$.data 'url'
          return

        # If the current content is of this business, we don't need to fire
        # another AJAX
        return true if $businessContent.data('currentBusiness') == $$.data 'id'

        # Highlight selected row
        $ 'div.result-row'
          .removeClass 'selected'
        $$.addClass 'selected'

        $loading.show()

        # Load information of this business
        $.ajax
          url: $$.data 'url'
          type: 'GET'
        .done (html) ->
          $loading.hide()
          $map.hide()
          $businessContent.html html

          VARAA.initLayout3()

          # Set current business flag
          $businessContent.data 'currentBusiness', $$.data 'id'

          # Render the map
          mapId = '#js-map-' + $$.data 'id'
          lat = $(mapId).data 'lat'
          lng = $(mapId).data 'lng'

          gmap = new GMaps
            div: mapId
            lat: lat
            lng: lng
            zoom: 15

          gmap.addMarkers [lat: lat, lng: lng]
          # Apply countdown for flash deals
          VARAA.applyCountdown $businessContent.find 'span.countdown'

  extractMarkers: (businesses) ->
    markers = []
    for business in businesses
      markers.push
        lat: business.lat
        lng: business.lng
        title: business.name
    markers

  showSingleBusiness: ->
    @renderMap "#js-map-#{@single.user_id}", @lat, @lng, [lat: @lat, lng: @lng]

# Start the game
search = new VaraaSearch VARAA.Search
search.run()
