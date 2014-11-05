@extends ('modules.as.layout')
@section ('styles')
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css') }}
    {{ HTML::style(asset('assets/css/as/appointment.css?v=00005')) }}
@stop

@section ('scripts')
{{ HTML::script(asset('assets/js/jquery.fixedTableHeader.js?v=00001')) }}
<script type="text/javascript">
    $(document).ready(function () {
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
                    @if(!empty($item['employees'][$employee->id]))
                    {{ $item['employees'][$employee->id]->customTime->name }}
                    @else
                    --
                    @endif
                </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
@stop
