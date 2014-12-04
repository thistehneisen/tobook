@extends ('layouts.admin')

@section('styles')
    @parent
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.3/css/bootstrap-datetimepicker.min.css') }}
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css') }}
@stop

@section('scripts')
    @parent
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment-with-locales.min.js') }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.3/js/bootstrap-datetimepicker.min.js') }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js') }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js') }}
    {{ HTML::script(asset('assets/js/admin/stats.js')) }}

    <script>
var DataSet = DataSet || {};
DataSet.totalSold = {{ json_encode($dataset) }};
    </script>

@stop

@section('content')
<h3>Flash Deals Statistics</h3>

<form method="GET" class="form-inline" role="form">
    <div class="form-group">
        <label class="sr-only" for="from">{{ trans('admin.stats.from') }}</label>
        {{ Form::text('from', Input::get('from'), ['data-date-format' => 'DD-MM-YYYY', 'class' => 'form-control datepicker', 'placeholder' => trans('admin.stats.from')]) }}
    </div>
    <div class="form-group">
        <label class="sr-only" for="to">{{ trans('admin.stats.to') }}</label>
        {{ Form::text('to', Input::get('to'), ['data-date-format' => 'DD-MM-YYYY', 'class' => 'form-control datepicker', 'placeholder' => trans('admin.stats.to')]) }}
    </div>
    <div class="form-group">
        <button class="btn btn-primary">{{ trans('common.submit') }}</button>
    </div>
</form>

<div id="fd-chart" style="height: 300px;"></div>

<h3>Sold flash deals</h3>
<table class="table table-hovered table-stripped">
    <thead>
        <tr>
            <th>Deal</th>
            <th>Business</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>abc</td>
            <td>ja</td>
        </tr>
    </tbody>
</table>
@stop
