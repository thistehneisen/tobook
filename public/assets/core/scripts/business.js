(function() {
  (function($) {
    'use strict';
    return $(function() {
      var $wrapper, swiper;
      swiper = new Swiper('.swiper-container', {
        autoplay: 3000,
        loop: true,
        autoplayDisableOnInteraction: false
      });
      $wrapper = $('#js-search-results');
      $wrapper.on('click', '#js-business-booking-request', function(e) {
        e.preventDefault();
        $('#form-contact-business').hide();
        return $('#form-request-business').show();
      });
      return $wrapper.on('click', '#js-cancel-business-request', function(e) {
        e.preventDefault();
        $('#form-request-business').hide();
        return $('#form-contact-business').show();
      });
    });
  })(jQuery);

}).call(this);
