(function ($) {
  'use strict';

  $(function() {
    $('#datepicker').datepicker({
      format: 'yyyy-mm-dd',
      startDate: new Date,
      todayBtn: true,
      todayHighlight: true
    }).on('changeDate', function(e) {
        $('#txt-date').val(e.format());
    });

    $('.accordion').collapse();

    $('.list-group-item-heading').on('click', function (e) {
      e.preventDefault();
      $(this).siblings('div').find('div.services').slideToggle();
    });

    $('.btn-fancybox').fancybox();
  });
}(jQuery));
