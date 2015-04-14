do ($ = jQuery) ->
  'use strict'

  $ ->
    swiper = new Swiper '.swiper-container',
      autoplay: 3000
      loop: true
      pagination: '.swiper-pagination'
      nextButton: '.swiper-button-next'
      prevButton: '.swiper-button-prev'

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
