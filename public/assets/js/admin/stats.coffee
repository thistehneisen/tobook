(($) ->
    'use strict'
    $ ->
        # Chart
        new Morris.Line
            element: 'fd-chart'
            data: DataSet.totalSold
            xkey: 'date'
            ykeys: ['revenue', 'total']
            labels: ['Revenue', 'Total']

        # Activate datepicker
        $('.datepicker').datetimepicker pickTime: false

        # Date picker in range
        $from = $ '[name=from]'
        $to = $ '[name=to]'
        $from.on 'dp.change', (e) ->
            $to.data('DateTimePicker').setMinDate e.date
        $to.on 'dp.change', (e) ->
            $from.data('DateTimePicker').setMaxDate e.date
) jQuery
