(function() {
  (function($) {
    'use strict';
    return $(function() {
      var $chart, $from, $to;
      $chart = $('#fd-chart');
      new Morris.Line({
        element: 'fd-chart',
        data: DataSet.totalSold,
        xkey: 'date',
        ykeys: ['revenue', 'total'],
        labels: [$chart.data('label-revenue'), $chart.data('label-total')]
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

}).call(this);
