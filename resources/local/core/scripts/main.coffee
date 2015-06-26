$ ->
  'use strict'

  $document = $ document
  $searchInput = $ '#js-queryInput'
  $locationInput = $ '#js-locationInput'

  # Init typeahead
  if ($searchInput? and $locationInput? and $searchInput.length > 0 and $locationInput.length > 0)
    VARAA.initTypeahead $searchInput, 'services'
    VARAA.initTypeahead $locationInput, 'locations'

  # Determine if we should ask for location
  $form = $ '#main-search-form'
  if $form.length
    $latInput = $form.find '[name=lat]'
    $lngInput = $form.find '[name=lng]'
    shouldAskGeolocation = not ($latInput.val().length > 0 and $lngInput.val().length > 0)

    $searchInput.on 'focus', (e) ->
      unless navigator.geolocation and shouldAskGeolocation
        return

      $info = $ '#js-geolocation-info'
      # Show the information panel
      $info.show() if shouldAskGeolocation

      VARAA.getLocation()
        .then (lat, lng) ->
          $latInput.val lat
          $lngInput.val lng

      # We only ask for once
      shouldAskGeolocation = false

  #-------------------------------------------------
  # Change language
  #-------------------------------------------------
  $languageSwitcher = $ '#js-languageSwitcher'
  $languageSwitcher.change ->
    window.location = @.value

  #-------------------------------------------------
  # Cart
  #-------------------------------------------------
  $cart = $ '#header-cart'
  $cart.popover
    placement: 'bottom'
    trigger: 'click'
    html: true

  $document.on 'click', (e) ->
    $target = $ e.target
    $cart.popover 'hide' if $target.data('toggle') isnt 'popover' and $target.parents('#header-cart').length is 0 and $target.parents('.popover.in').length is 0
    return

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
        # Show the cart content
        $cart.popover 'show'
        $document.scrollTop 0

  $document.on 'click', 'a.js-btn-cart-remove', (e) ->
    e.preventDefault()
    $$ = $ @

    $$.find 'i.fa'
      .removeClass 'fa-close'
      .addClass 'fa-spinner fa-spin'

    $.ajax
      url: $$.attr 'href'
    .done (e) ->
      $('tr.cart-detail-' + $$.data 'detail-id').fadeOut()
      $document.trigger 'cart.reload', true

  # Load cart content when page load
  $document.trigger 'cart.reload', false
