(($) ->
    'use strict'
    $ ->
        # Chart
        new Morris.Line
            element: 'fd-chart'
            data: [
                { year: '2008', value: 20 }
                { year: '2009', value: 9 }
                { year: '2010', value: 11 }
                { year: '2011', value: 25 }
            ]
            xkey: 'year'
            ykeys: ['value']
            labels: ['Values']

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
