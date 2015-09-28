do ($ = jQuery) ->
  'use strict'

  $ ->
    swiper = new Swiper '.swiper-container',
      autoplay: 3000
      loop: true
      autoplayDisableOnInteraction: false

    $businessModal = $ '#js-business-modal'
    $w = $ window
    if $businessModal.length > 0
      $businessModal.modal()
        .on 'shown.bs.modal', (e) ->
          $businessModal.css('top', ($w.height() - $businessModal.find('.modal-content').height()) / 2)

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
