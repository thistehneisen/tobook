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
