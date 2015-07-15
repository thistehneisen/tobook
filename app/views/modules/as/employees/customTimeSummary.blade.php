@extends ('modules.as.layout')
@section ('styles')
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css') }}
    {{ HTML::style(asset_path('as/styles/main.css')) }}
@stop

@section ('scripts')
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
 <div class="form-group row">
        <div class="col-sm-3 hidden-print"><a href="{{ route('as.employees.employeeCustomTime.summary', ['date'=> with(clone $current->startOfMonth())->subMonth()->format('Y-m') ])}}">{{ Str::upper(trans('common.prev')) }}</a></div>
        <div class="col-sm-3 hidden-print">
           {{ Str::upper(trans(strtolower('common.' . $current->format('F')))); }}
        </div>
        <div class="col-sm-3 hidden-print"><a href="{{ route('as.employees.employeeCustomTime.summary', ['date'=> with(clone $current->startOfMonth())->addMonth()->format('Y-m') ])}}">{{ Str::upper(trans('common.next')) }}</a></div>
        <div class="col-sm-3 hidden-print">
             <button class="btn btn-primary pull-right" onclick="window.print();"><i class="fa fa-print"> {{ trans('as.index.print') }}</i></button>
        </div>
</div>
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
                    <div class="workshift-editable" data-editable="true" data-employee-id="{{$employee->id}}" data-date="{{$item['date']->toDateString()}}">
                        {{ $item['employees'][$employee->id]->customTime->name }} ({{$item['employees'][$employee->id]->customTime->getStartAt()->format('H:i')}} - {{$item['employees'][$employee->id]->customTime->getEndAt()->format('H:i')}}})
                    </div>
                    @else
                    <div class="workshift-editable" data-editable="true" data-employee-id="{{$employee->id}}" data-date="{{$item['date']->toDateString()}}">--</div>
                    @endif
                </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td>{{ trans('as.employees.saturday_hours') }}</td>
            <td>&nbsp;</td>
            @foreach ($employees as $employee)
            <td>
                @if (isset($sarturdayHours[$employee->id])) {{ $sarturdayHours[$employee->id] }}
                @else
                --
                @endif
            </td>
            @endforeach
        </tr>
        <tr>
            <td>{{ trans('as.employees.sunday_hours') }}</td>
            <td>&nbsp;</td>
            @foreach ($employees as $employee)
            <td>
                @if (isset($sundayHours[$employee->id])) {{ $sundayHours[$employee->id] }}
                @else
                --
                @endif
            </td>
            @endforeach
        </tr>
        <tr>
            <td>{{ trans('as.employees.monthly_hours') }}</td>
            <td>&nbsp;</td>
            @foreach ($employees as $employee)
            <td>
                @if (isset($montlyHours[$employee->id])) {{ $montlyHours[$employee->id] }}
                @else
                --
                @endif
            </td>
            @endforeach
        </tr>
    </tfoot>
</table>
<input type="hidden" id="update_workshift_url" value="{{ route('as.employees.employeeCustomTime.updateWorkshift') }}"/>
@stop
