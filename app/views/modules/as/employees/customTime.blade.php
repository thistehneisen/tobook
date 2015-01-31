@extends ('modules.as.layout')
@section ('styles')
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css') }}
@stop

@section ('title')
    {{ trans('as.employees.custom_time') }} :: @parent
@stop

@section ('scripts')
    @parent
    <script>
    $(function () {
       $('#employees').change(function () {
            window.location = $(this).find(':selected').data('url');
        });
    });
    </script>
@stop

@section ('content')
    @include ('modules.as.employees.tabCustomTime')

{{ Form::open(['route' => ['as.employees.employeeCustomTime.upsert'], 'class' => 'form-horizontal', 'role' => 'form']) }}
 <div class="form-group row">
        <div class="col-sm-3"><a href="{{ route('as.employees.employeeCustomTime', ['employeeId'=> $employee->id, 'date'=> with(clone $current->startOfMonth())->subMonth()->format('Y-m') ])}}" id="prev-month">{{ Str::upper(trans('common.prev')) }}</a></div>
        <div class="col-sm-3">
           {{ Str::upper($current->format('F')); }}
        </div>
        <div class="col-sm-3"><a href="{{ route('as.employees.employeeCustomTime', ['employeeId'=> $employee->id, 'date'=> with(clone $current->startOfMonth())->addMonth()->format('Y-m') ])}}" id="next-month">{{ Str::upper(trans('common.next')) }}</a></div>
        <div class="col-sm-3">
            <select id="employees" name="employees" class="form-control input-sm">
                @foreach ($employees as $item)
                    <option data-url="{{ route('as.employees.employeeCustomTime', ['employeeId'=> $item->id, 'date'=> $current->format('Y-m') ])}}" value="{{ $item->id }}" @if($employee->id == $item->id) selected="selected" @endif>{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
</div>
{{ Form::close() }}

@include ('el.messages')

{{ Form::open(['route' => ['as.employees.employeeCustomTime.massUpdate', $employee->id], 'class' => 'form-horizontal', 'role' => 'form']) }}
<table>
    <tr>
        <td>
            <button type="submit" class="btn btn-primary">{{ trans('common.save') }}</button>
        </td>
    </tr>
</table>
<table class="table table-striped">
    <thead>
        <th>{{ trans('as.employees.weekday')}}</th>
        <th>{{ trans('as.employees.date')}}</th>
        <th>{{ trans('as.employees.custom_time')}}</th>
        <th>{{ trans('as.employees.employee')}}</th>
        <th></th>
    </thead>
    <tbody>
        @foreach($currentMonths as $item)
            @if(empty($item->id))
             <tr>
                <td>{{ $item->format('l') }}</td>
                <td>
                    {{ $item->toDateString() }}
                </td>
                <td>{{ Form::select("custom_times[" . $item->toDateString(). "]", $customTimes, 0 , ['class' => 'form-control input-sm', 'id' => 'custom_times']) }}</td>
                <td>{{ $employee->name }}</td>
                <td>&nbsp;</td>
            </tr>
            @else
            <tr id="custom-time-{{ $item->id }}">
                <td>{{ with(new Carbon\Carbon($item->date))->format('l') }}</td>
                <td>
                    {{ $item->date }}
                </td>
                <td>{{ Form::select("custom_times[" . $item->date. "]", $customTimes, (!empty($item->customTime)) ? $item->customTime->id : '', ['class' => 'form-control input-sm', 'id' => 'custom_times']) }}</td>
                <td>{{ $employee->name }}</td>
                <td>&nbsp;</td>
            </tr>
            @endif
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5">
                <button type="submit" class="btn btn-primary" id="btn-save">{{ trans('common.save') }}</button>
            </td>
        </tr>
    </tfoot>
</table>
{{ Form::close() }}
@stop
