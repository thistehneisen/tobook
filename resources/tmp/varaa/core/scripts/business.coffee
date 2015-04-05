do ($ = jQuery) ->
  'use strict'

  $ ->
    # Contact form
    $wrapper = $ '#js-search-results'

    $wrapper.on 'click', '#js-business-booking-request', (e) ->
        e.preventDefault()
        $ '#form-contact-business'
          .hide()
        $ '#form-request-business'
          .show()

    $wrapper.on 'click', '#js-cancel-business-request', (e) ->
        e.preventDefault()
        $ '#form-request-business'
          .hide()
        $ '#form-contact-business'
          .show()
