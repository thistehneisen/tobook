$ ->
  'use strict'

  $document = $ document
  $searchInput = $ '#js-queryInput'
  $locationInput = $ '#js-locationInput'
  $languageSwitcher = $ '#js-languageSwitcher'
  $cart = $ '#header-cart'

  #-------------------------------------------------
  # Typeahead
  #-------------------------------------------------
  initTypeahead = (selector, name) ->
    collection = new Bloodhound
      datumTokenizer: Bloodhound.tokenizers.obj.whitespace 'name'
      queryTokenizer: Bloodhound.tokenizers.whitespace
      limit: 10
      prefetch:
        url: '/search/'+name+'.json'
        filter: (list) ->
          if (typeof list[0] == 'string')
            return $.map list, (item) -> name: item
          return list

    collection.clearPrefetchCache()
    collection.initialize()

    selector.typeahead
      highlight: true
      hint: true
    ,
      name: name
      displayKey: 'name'
      source: collection.ttAdapter()

  # Init typeahead
  if ($searchInput? and $locationInput? and $searchInput.length > 0 and $locationInput.length > 0)
    initTypeahead $searchInput, 'services'
    initTypeahead $locationInput, 'locations'

  # Determine if we should ask for location
  $form = $ '#main-search-form'
  if $form? and $form.length > 0
    $latInput = $form.find '[name=lat]'
    $lngInput = $form.find '[name=lng]'
    shouldAskGeolocation = not ($latInput.val().length > 0 and $lngInput.val().length > 0)

    $searchInput.on 'focus', (e) ->
      unless navigator.geolocation and shouldAskGeolocation
        return

      $info = $ '#js-geolocation-info'
      # Show the information panel
      $info.slideDown()

      success = (position) ->
        $latInput.val position.coords.latitude
        $lngInput.val position.coords.longitude
        # Hide the information since we've already get the coords
        $info.slideUp()

      error = (err) ->
        console.log err

      navigator.geolocation.getCurrentPosition success, error, timeout: 10000
      # We only ask for once
      shouldAskGeolocation = false

  #-------------------------------------------------
  # Change language
  #-------------------------------------------------
  $languageSwitcher.change ->
    window.location = @.value

  #-------------------------------------------------
  # Cart
  #-------------------------------------------------
  $cart.popover
    placement: 'bottom'
    trigger: 'click'
    html: true

  $document.on 'click', (e) ->
    $target = $ e.target
    if $target.data 'toggle' != 'popover' and $target.parents('#header-cart').length == 0 and $target.parents('.popover.in').length == 0
      $cart.popover 'hide'

  # When an item added to cart, we need to reload it
  $document.on 'cart.reload', (e, showAfterFinish) ->
    $.ajax
      url: $cart.data 'cart-url'
      dataType: 'JSON'
    .done (e) ->
      $cart.find '.content'
        .html e.totalItems

      $cart.attr 'data-content', e.content

      if showAfterFinish
        $cart.popover 'show'
        $document.scrollTop 0

  $document.on 'click', 'a.js-btn-cart-remove', (e) ->
    e.preventDefault()
    self = $ @

    self.find 'i.fa'
      .removeClass 'fa-close'
      .addClass 'fa-spinner fa-spin'

    $.ajax
      url: self.attr 'href'
    .done (e) ->
      $('tr.cart-detail-' + $this.data 'detail-id').fadeOut()
      $document.trigger 'cart.reload', true

  # Load cart content when page load
  $document.trigger 'cart.reload', false

  #-------------------------------------------------
  # Global methods
  #-------------------------------------------------
  VARAA.applyCountdown = (elems) ->
    elems.each ->
      self = $ @
      self.countdown
        until: new Date self.data 'date'
        compact: true
        layout: '{hnn}{sep}{mnn}{sep}{snn}'

  VARAA.equalize = (elem) ->
    tallest = 0
    $(elem).each ->
      h = $(@).outerHeight()
      if h > tallest
        tallest = h
    .css 'height', tallest
