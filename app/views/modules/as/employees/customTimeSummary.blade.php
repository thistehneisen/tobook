@extends ('modules.as.layout')
@section ('styles')
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css') }}
    {{ HTML::style(asset_path('as/styles/main.css')) }}
@stop

@section ('scripts')
{{ HTML::script(asset_path('core/scripts/jquery.fixedTableHeader.js')) }}
<script type="text/javascript">
    $(window).load(function() {
        $('#workshift-summary').fixedTableHeader();
    });
</script>
@stop

@section ('title')
    {{ trans('as.employees.custom_time') }} :: @parent
@stop

@section ('content')
 <div class="form-group row">
        <div class="col-sm-3 hidden-print"><a href="{{ route('as.employees.employeeCustomTime.summary', ['date'=> with(clone $current->startOfMonth())->subMonth()->format('Y-m') ])}}">{{ Str::upper(trans('common.prev')) }}</a></div>
        <div class="col-sm-3 hidden-print">
           {{ Str::upper($current->format('F')); }}
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
                <td>{{ $item['date']->format('l') }}</td>
                <td>{{ $item['date']->toDateString() }}</td>
                @foreach ($employees as $employee)
                <td>
                    @if (!empty($item['employees'][$employee->id]->customTime)) {{ $item['employees'][$employee->id]->customTime->name }}
                    @else
                    --
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
@stop
