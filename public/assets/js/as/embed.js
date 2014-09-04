(function ($) {
  'use strict';

  $(function() {
    $('.datepicker').datepicker({
      format: 'mm/dd/yyyy',
      startDate: new Date,
      todayBtn: true,
      todayHighlight: true
    });

    $('.accordion').collapse();

    $('.list-group-item-heading').on('click', function (e) {
      e.preventDefault();
      $(this).siblings('div').find('div.services').slideToggle();
    });

    $('.btn-fancybox').fancybox();
  });
}(jQuery));
