@extends ('modules.as.layout')

@section ('styles')
    @parent
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.0/morris.css') }}
@stop

@section ('scripts')
    @parent
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.0/morris.min.js') }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js') }}
    <script>
$(function () {
new Morris.Line({
  // ID of the element in which to draw the chart.
  element: 'employees-chart',
  // Chart data records -- each entry in this array corresponds to a point on
  // the chart.
  data: [
    { year: '2008', value: 20 },
    { year: '2009', value: 10 },
    { year: '2010', value: 5 },
    { year: '2011', value: 5 },
    { year: '2012', value: 20 }
  ],
  // The name of the data record attribute that contains x-values.
  xkey: 'year',
  // A list of names of data record attributes that contain y-values.
  ykeys: ['value'],
  // Labels for the ykeys -- will be displayed when you hover over the
  // chart.
  labels: ['Value']
});
});
    </script>
@stop

@section ('title')
    {{ trans('as.reports.employees') }} :: @parent
@stop

@section ('content')
    <h2>{{ trans('as.reports.employees') }}</h2>

    {{ Form::open(['class' => 'form-inline', 'role' => 'form', 'method' => 'GET']) }}
        <div class="form-group">
            <label class="sr-only" for="services">{{ trans('as.reports.services') }}</label>
            {{ Form::select('service', $services, null, ['class' => 'form-control input-sm']) }}
        </div>
        <div class="form-group">
            <label class="sr-only" for="index">{{ trans('as.reports.idx') }}</label>
            {{ Form::select('service', ['count' => trans('as.reports.count'), 'quantity' => trans('as.reports.quantity')], null, ['class' => 'form-control input-sm']) }}
        </div>
        <div class="form-group">
            <div class="input-daterange input-group date-picker">
                <input type="text" class="input-sm form-control" name="start" placeholder="{{ trans('as.reports.start') }}">
                <span class="input-group-addon">&ndash;</span>
                <input type="text" class="input-sm form-control" name="end" placeholder="{{ trans('as.reports.end') }}">
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-sm">{{ trans('as.reports.generate') }}</button>
    {{ Form::close() }}

    <div id="employees-chart" style="height: 350px;"></div>

    <table class="table table-stripped table-bordered">
        <thead>
            <tr>
                <th>{{ trans('as.employees.name') }}</th>
                <th>{{ trans('as.reports.booking.total') }}</th>
                <th>{{ trans('as.reports.booking.confirmed') }}</th>
                <th>{{ trans('as.reports.booking.unconfirmed') }}</th>
                <th>{{ trans('as.reports.booking.cancelled') }}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>ABa</td>
                <td>ABa</td>
                <td>ABa</td>
                <td>ABa</td>
                <td>ABa</td>
            </tr>
        </tbody>
    </table>
@stop
