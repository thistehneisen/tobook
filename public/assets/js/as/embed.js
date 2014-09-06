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

    $('.btn-add-extra-service').click(function (e) {
        e.preventDefault();
        var service_id = $(this).data('service-id'),
            hash       = $(this).data('hash');
        $.fancybox.open({
            padding: 5,
            width: 400,
            title: '',
            autoSize: false,
            autoScale: true,
            autoWidth: false,
            autoHeight: true,
            fitToView: false,
            href: $('#get_extra_service_form').val(),
            type: 'ajax',
            ajax: {
                type: 'GET',
                data: {
                    service_id: service_id,
                    hash      : hash
                }
            },
            helpers: {
                overlay: {
                    locked: false
                }
            },
            autoCenter: false
        });
    });

    $('.active').click(function (e) {

    });
  });
}(jQuery));
