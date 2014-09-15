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
        $('#from_date, #to_date').datepicker({
            format        : 'yyyy-mm-dd',
            weekStart     : 1,
            autoclose     : true,
            calendarWeeks : true,
            language      : $('body').data('locale'),
            startDate     : new Date('{{ $startOfMonth }}'),
            endDate       : new Date('{{ $endOfMonth}}')
        })

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
        <div class="col-sm-3"><a href="{{ route('as.employees.employeeCustomTime', ['employeeId'=> $employee->id, 'date'=> with(clone $current->startOfMonth())->subMonth()->format('Y-m') ])}}">{{ Str::upper(trans('common.prev')) }}</a></div>
        <div class="col-sm-3">
           {{ Str::upper($current->format('F')); }}
        </div>
        <div class="col-sm-3"><a href="{{ route('as.employees.employeeCustomTime', ['employeeId'=> $employee->id, 'date'=> with(clone $current->startOfMonth())->addMonth()->format('Y-m') ])}}">{{ Str::upper(trans('common.next')) }}</a></div>
        <div class="col-sm-3">
            <select id="employees" name="employees" class="form-control input-sm">
                @foreach ($employees as $item)
                    <option data-url="{{ route('as.employees.employeeCustomTime', ['employeeId'=> $item->id, 'date'=> $current->format('Y-m') ])}}" value="{{ $item->id }}" @if($employee->id == $item->id) selected="selected" @endif>{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
</div>
<div class="well">
     <div class="form-group row">
        <label for="custom_times" class="col-sm-2 control-label">{{ trans('as.employees.custom_time') }}</label>
        <div class="col-sm-4">
            {{ Form::select('custom_times', $customTimes, 0, ['class' => 'form-control input-sm', 'id' => 'custom_times']) }}
        </div>
    </div>
    <div class="form-group">
        <label for="start_at" class="col-sm-2 control-label">{{ trans('as.employees.from_date') }}</label>
        <div class="col-sm-4{{ Form::errorCSS('from_date', $errors) }}">
            {{ Form::text('from_date', '', ['class' => 'form-control input-sm date-picker', 'id' => 'from_date']) }}
        </div>
    </div>

    <div class="form-group">
        <label for="to_date" class="col-sm-2 control-label">{{ trans('as.employees.to_date') }}</label>
        <div class="col-sm-4 {{ Form::errorCSS('to_date', $errors) }}">
            {{ Form::text('to_date', '', ['class' => 'form-control input-sm date-picker', 'id' => 'to_date']) }}
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-5">
            <button type="submit" class="btn btn-primary">{{ trans('common.save') }}</button>
        </div>
    </div>
</div>
{{ Form::close() }}
@if(!$items->isEmpty())
<table class="table table-striped">
    <thead>
        <th>{{ trans('as.employees.weekday')}}</th>
        <th>{{ trans('as.employees.date')}}</th>
        <th>{{ trans('as.employees.custom_time')}}</th>
        <th>{{ trans('as.employees.employee')}}</th>
        <th></th>
    </thead>
    <tbody>
        @foreach($items as $item)
        <tr>
            <td>{{ with(new Carbon\Carbon($item->date))->format('l') }}</td>
            <td>{{ $item->date }}</td>
            <td>{{ $item->customTime->name }}</td>
            <td>{{ $employee->name }}</td>
            <td><a href="#" class="btn btn-default"><i class="glyphicon glyphicon-remove"></i></a></td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@stop
