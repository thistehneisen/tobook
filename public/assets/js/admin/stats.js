(function($) {
  'use strict';
  return $(function() {
    var $from, $to;
    new Morris.Line({
      element: 'fd-chart',
      data: [
        {
          year: '2008',
          value: 20
        }, {
          year: '2009',
          value: 9
        }, {
          year: '2010',
          value: 11
        }, {
          year: '2011',
          value: 25
        }
      ],
      xkey: 'year',
      ykeys: ['value'],
      labels: ['Values']
    });
    $('.datepicker').datetimepicker({
      pickTime: false
    });
    $from = $('[name=from]');
    $to = $('[name=to]');
    $from.on('dp.change', function(e) {
      return $to.data('DateTimePicker').setMinDate(e.date);
    });
    return $to.on('dp.change', function(e) {
      return $from.data('DateTimePicker').setMaxDate(e.date);
    });
  });
})(jQuery);
