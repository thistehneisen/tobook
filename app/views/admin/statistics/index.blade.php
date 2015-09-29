@extends ('layouts.admin')

@section ('styles')
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css') }}
    {{ HTML::style(asset('packages/jquery.tablesorter/themes/plain/style.css')) }}
@stop

@section ('scripts')
    @parent
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js') }}
    @if (App::getLocale() !== 'en') {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/locales/bootstrap-datepicker.'.App::getLocale().'.min.js') }}
    @endif
    {{ HTML::script(asset('packages/jquery.tablesorter/jquery.tablesorter.min.js')) }}
    <script type="text/javascript">
    $(document).ready(function(){
		$('.date-picker').datepicker({
			format: 'dd.mm.yyyy',
			weekStart: 1,
			autoclose: true,
			language: $('body').data('locale')
		});
        $("#statistics").tablesorter(); 
    });
    </script>
@stop

@section('content')
<h3>{{ trans('admin.nav.stats') }}</h3>
	{{ Form::open(['class' => 'form-inline', 'role' => 'form', 'method' => 'GET']) }}
        <div class="form-group">
            <div class="input-daterange input-group date-picker">
                <input type="text" class="input-sm form-control" name="start" placeholder="{{ trans('as.reports.start') }}" value="{{{ $start }}}">
                <span class="input-group-addon">&ndash;</span>
                <input type="text" class="input-sm form-control" name="end" placeholder="{{ trans('as.reports.end') }}" value="{{{ $end }}}">
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-sm">{{ trans('as.reports.generate') }}</button>
    {{ Form::close() }}
	<br>
    <table id="statistics" class="table tablesorter table-stripped table-bordered">
        <thead>
            <tr>
                <th>{{ trans('as.reports.business') }}</th>
                <th>{{ trans('as.reports.booking.total') }}</th>
                <th>{{ trans('as.reports.booking.portal') }}</th>
                <th>{{ trans('as.reports.booking.front-end') }}</th>
                <th>{{ trans('as.reports.booking.backend') }}</th>
            </tr>
            <tr>
                <td><b>&Sigma;</b></td>
                <td><b>{{ $report->getTotal('total')}}</b></td>
                <td><b>{{ $report->getTotal('inhouse')}}</b></td>
                <td><b>{{ $report->getTotal('frontend')}}</b></td>
                <td><b>{{ $report->getTotal('backend')}}</b></td>
            </tr>
        </thead>
        <tbody>
        @foreach ($report->get() as $item)
            <tr>
                <td>{{ $item['user_id'] }}</td>
                <td>{{ $item['total'] }}</td>
                <td>{{ isset($item['inhouse']) ? $item['inhouse'] : 0 }}</td>
                <td>{{ isset($item['frontend']) ? $item['frontend'] : 0 }}</td>
                <td>{{ isset($item['backend']) ? $item['backend'] : 0 }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@stop
