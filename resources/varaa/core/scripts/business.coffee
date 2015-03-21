do ($ = jQuery) ->
  'use strict'

  $ ->
    # Contact form
    formContact = $ '#form-contact-business'
    formRequest = $ '#form-request-business'
    $ '#js-business-booking-request'
      .on 'click', (e) ->
        e.preventDefault()
        formContact.hide()
        formRequest.show()

    $ '#js-cancel-business-request'
      .on 'click', (e) ->
        e.preventDefault()
        formRequest.hide()
        formContact.show()

    # Init the booking widget
    VARAA.initLayout3 isAutoSelectEmployee: false
