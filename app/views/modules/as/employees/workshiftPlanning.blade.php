@extends ('modules.as.layout')
@section ('styles')
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css') }}
    {{ HTML::style(asset_path('as/styles/main.css')) }}
@stop

@section ('scripts')
{{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js') }}
@if (App::getLocale() !== 'en') {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/locales/bootstrap-datepicker.'.App::getLocale().'.min.js') }}
@endif
{{ HTML::script(asset_path('core/scripts/jquery.fixedTableHeader.js')) }}
{{ HTML::script(asset_path('as/scripts/workshift.js')) }}
<script type="text/javascript">
    $(window).load(function () {
        $('#workshift-summary').fixedTableHeader();
        CUSTOM_TIME = {{ $customTimes }};
    });
</script>
@stop

@section ('title')
    {{ trans('as.employees.custom_time') }}
@stop

@section ('content')
{{ Form::open(['class' => 'form-inline', 'role' => 'form', 'method' => 'GET']) }}
    <div class="input-daterange input-group date-picker">
        <input type="text" class="input-sm form-control" name="start" placeholder="{{ trans('as.reports.start') }}" value="{{{ $startOfMonth->toDateString() }}}">
        <span class="input-group-addon">&ndash;</span>
        <input type="text" class="input-sm form-control" name="end" placeholder="{{ trans('as.reports.end') }}" value="{{{ $endOfMonth->toDateString() }}}">
    </div>
    <button type="submit" class="btn btn-primary btn-sm hidden-print">{{ trans('as.reports.generate') }}</button>
    <div class="from-control pull-right hidden-print">
    <button class="btn btn-primary btn-sm" onclick="location.reload();"><i class="fa fa-refresh"> {{ trans('as.index.refresh') }}</i></button>
    &nbsp;
    <button class="btn btn-primary btn-sm" onclick="window.print();"><i class="fa fa-print"> {{ trans('as.index.print') }}</i></button>
    </div>
{{ Form::close() }}
<br/>
<table id="workshift-summary" class="table table-striped table-bordered">
    <thead>
        <th>{{ trans('as.employees.weekday')}}</th>
        <th>{{ trans('as.employees.date')}}</th>
        @foreach ($employees as $employee)
        <th>{{ $employee->name }}</th>
        @endforeach
    </thead>
    <tbody>
        @foreach($currentMonths as $item)
             <tr @if($item['date']->dayOfWeek === \Carbon\Carbon::SUNDAY) class="sunday-row" @endif>
                <td>{{ trans(strtolower('common.' . $item['date']->format('l'))) }}</td>
                <td>{{ $item['date']->toDateString() }}</td>
                @foreach ($employees as $employee)
                <td>
                    @if (!empty($item['employees'][$employee->id]->customTime))
                    <div class="workshift-editable" data-custom-time-id="{{$item['employees'][$employee->id]->customTime->id}}" data-editable="true" data-employee-id="{{$employee->id}}" data-date="{{$item['date']->toDateString()}}">
                        {{ $item['employees'][$employee->id]->customTime->name }} ({{$item['employees'][$employee->id]->customTime->getStartAt()->format('H:i')}} - {{$item['employees'][$employee->id]->customTime->getEndAt()->format('H:i')}})
                    </div>
                    @else
                    <div class="workshift-editable" data-editable="true" data-employee-id="{{$employee->id}}" data-date="{{$item['date']->toDateString()}}">--</div>
                    @endif
                </td>
                @endforeach
            </tr>
            @if($item['date']->dayOfWeek === \Carbon\Carbon::SUNDAY)
            <tr class="weekly-row">
                <td>{{ trans('as.employees.weekly_hours') }}</td>
                <td>&nbsp;</td>
                @foreach ($employees as $employee)
                @if(isset($weekSummary[$item['date']->weekOfYear][$employee->id]))
                <td>{{ $weekSummary[$item['date']->weekOfYear][$employee->id]}}</td>
                @else
                <td>0</td>
                @endif
                @endforeach
            </tr>
            @endif
            @if(($item['date']->toDateString() === $item['date']->copy()->endOfMonth()->toDateString())
            || ($item['date']->toDateString() === $endOfMonth->toDateString()))
            <tr class="monthly-row">
                <td>{{ trans('as.employees.monthly_hours') }}</td>
                <td>&nbsp;</td>
                @foreach ($employees as $employee)
                @if(isset($monthSummary[$item['date']->month][$employee->id]))
                <td>{{ $monthSummary[$item['date']->month][$employee->id]}}</td>
                @else
                <td>0</td>
                @endif
                @endforeach
            </tr>
            @endif
        @endforeach
    </tbody>
</table>
<input type="hidden" id="update_workshift_url" value="{{ route('as.employees.employeeCustomTime.updateWorkshift') }}"/>
@stop
