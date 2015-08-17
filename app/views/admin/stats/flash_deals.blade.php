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
    {{ HTML::script(asset_path('core/scripts/admin/stats.js')) }}

    <script>
var DataSet = DataSet || {};
DataSet.totalSold = {{ json_encode($dataset) }};
    </script>
@stop

@section('content')
<h3>{{ trans('admin.stats.fd.heading') }}</h3>

<form method="GET" class="form-inline" role="form">
    <div class="form-group">
        <label class="sr-only" for="from">{{ trans('admin.stats.from') }}</label>
        {{ Form::text('from', Input::get('from', $from->toDateString()), ['data-date-format' => 'YYYY-MM-DD', 'class' => 'form-control datepicker', 'placeholder' => trans('admin.stats.from')]) }}
    </div>
    <div class="form-group">
        <label class="sr-only" for="to">{{ trans('admin.stats.to') }}</label>
        {{ Form::text('to', Input::get('to', $to->toDateString()), ['data-date-format' => 'YYYY-MM-DD', 'class' => 'form-control datepicker', 'placeholder' => trans('admin.stats.to')]) }}
    </div>
    <div class="form-group">
        <button class="btn btn-primary">{{ trans('common.submit') }}</button>
    </div>
</form>

<div id="fd-chart" style="height: 300px;" data-label-total="{{ trans('admin.stats.fd.labels.total') }}" data-label-revenue="{{ trans('admin.stats.fd.labels.revenue') }}"></div>

<h3>{{ trans('admin.stats.fd.sold') }}</h3>
<table class="table table-hovered table-stripped">
    <thead>
        <tr>
            <th>{{ trans('admin.stats.fd.business') }}</th>
            <th>{{ trans('admin.stats.fd.labels.total') }}</th>
            <th>{{ trans('admin.stats.fd.labels.revenue') }}</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($sold as $item)
        <tr>
            <td><a href="{{ $item->business->business_url }}">{{ $item->business->name }}</a></td>
            <td>{{ $item->total }}</td>
            <td>{{ show_money($item->revenue) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
@stop
