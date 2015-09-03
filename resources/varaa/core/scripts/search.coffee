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

    @title = $('title').text()

    # Prepare for pushState
    History.Adapter.bind window, 'statechange', ->
      State = History.getState()
      return

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
      zoom: 13

    if markers?
      @addMarkers gmap, markers
    return gmap

  addMarkers: (gmap, markers) ->
    for marker in markers
      do (marker) ->
        obj = gmap.addMarker marker
        google.maps.event.addListener gmap.map, 'center_changed', (e) ->
          center = gmap.map.getCenter()
          if center.equals new google.maps.LatLng marker.lat, marker.lng
            obj.infoWindow.open gmap.map, obj

  ###*
   * Display businesses on the map
   *
   * @return {void}
  ###
  showBusinesses: ->
    self         = @
    $loading     = $ '#js-loading'
    $list        = $ '#js-business-list'
    $map         = $ '#js-map-canvas'
    $single      = $ '#js-business-single'
    $heading     = $ '#js-business-heading'
    $leftSidebar = $ '#js-left-sidebar'

    # Fixed top
    $ '#js-hot-offers'
      .sticky topSpacing: 10

    # Show the map containing all businesses in the result
    $map.show()

    # Render the map
    markers = @extractMarkers @businesses
    gmap = @renderMap $map.attr('id'), @lat, @lng, markers

    # When user clicks on the heading to return back to business listing
    $heading.on 'click', (e) =>
      e.preventDefault()
      $single.hide()
      $list.find('.panel').each -> $(@).show()

      $map.show()
      $heading.find('i').hide()

      $('title').text @title

      History.back()

    # When user clicks on Show more button
    $leftSidebar.on 'click', '#js-show-more', (e) ->
      e.preventDefault()
      $$ = $ @

      # Hide the text, show the loading
      $$.children 'span'
        .hide()
      $$.children 'i'
        .show()

      $.ajax
        url: $$.attr 'href'
        dataType: 'JSON'
      .done (data) ->
        # Remove the old Show more button
        $leftSidebar.find 'nav.show-more'
          .remove()
        $leftSidebar.append data.html
        # Add markers on the map
        self.addMarkers gmap, self.extractMarkers data.businesses

    # Define event handlers for business in result list

    ###*
     * Click on a business will load its details and show immediately
     *
     * @param  {EventObject} e
     *
     * @return {void}
    ###
    businessOnClick = (e) ->
      e.preventDefault()
      $$ = $ @
      businessId = $$.data 'id'
      hidePanel = -> $list.find('.panel').hide()

      # open result as a full page load instead of ajax if the browser width
      # is too small
      if $(window).width() < 768
        # sidebar should be less than a third of the container!
        window.location = $$.data 'url'
        return

      # Push state the current business
      History.pushState {businessId: businessId}, '', $$.data('url')

      # If the current content is of this business, we don't need to fire
      # another AJAX
      if $list.data('current-business-id') == businessId
        hidePanel()
        $single.show()
        # Show chevron as indicator to click back
        $heading.find 'i'
          .show()
        return

      # Highlight selected row
      $ 'div.js-business'
        .removeClass 'selected'
      $$.addClass 'selected'

      $loading.show()
      $map.hide()

      # Load information of this business
      $.ajax
        url: $$.data 'url'
        type: 'GET'
      .done (html) ->
        $loading.hide()
        hidePanel()
        # Replace the whole page with business page
        $single.html html
        $single.show()

        # Show chevron as indicator to click back
        $heading.find 'i'
          .show()

        app.VaraaCPLayout(document.getElementById('js-cp-booking-form'), $$.data 'hash')

        # Set current business flag
        $list.data 'current-business-id', businessId

        # Render the map
        $bmap = $ "#js-map-#{businessId}"
        lat = $bmap.data 'lat'
        lng = $bmap.data 'lng'
        self.renderMap $bmap.attr('id'), lat, lng, [lat: lat, lng: lng]

        $ 'title'
          .text $$.data 'title'

        # Scroll the page
        $.scrollTo '#js-search-results', duration: 300

        swiper = $ "#js-swiper-#{businessId}"
        slider = null
        if swiper.length
          slider = new Swiper swiper.get(),
            autoplay: 3000
            loop: true
            autoplayDisableOnInteraction: false

        slider.update().slideNext() if slider isnt null
    ###*
     * Hover on a business will highlight its position on the map
     *
     * @param  {EventObject} e
     *
     * @return {void}
    ###
    businessOnMouseEnter = (e) ->
      $$ = $ @
      lat = $$.data 'lat'
      lng = $$.data 'lng'

      for marker in gmap.markers
        do (marker) ->
          marker.infoWindow.close()

      gmap.setCenter lat, lng

    # Attach event handlers when user hovers or clicks on a business in the
    # result list
    $leftSidebar.on 'click', 'div.js-business', businessOnClick
      .on 'meouseenter', 'div.js-business', businessOnMouseEnter

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
