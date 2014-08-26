@extends ('modules.as.layout')

@section ('content')
@include ('modules.as.employees.editTabs')
<br/>
<div id="form-edit-default-time" class="modal-form">
    {{ Form::open(['route' => 'as.employees.defaultTime', 'class' => 'form-horizontal well', 'role' => 'form']) }}
    {{ Form::hidden('employee_id', $employeeId, array('id' => 'employee_id')) }}
    <table class="table table-striped table-bordered table-condensed">
        <thead>
            <tr>
                <th>Viikonpäivä</th>
                <th>Aloitusaika</th>
                <th>Lopetusaika</th>
                <th>Vapaapäivä</th>
            </tr>
        </thead>

    @foreach ($defaultTime as $time)
    <tbody>
        <tr>
            <td>{{ trans('common.'.$time->type) }}
                @if (!isset($time->default))
                    {{ $time->getStartHourIndex() }}
                @endif
            </td>
            <td>
                <div class="row">
                     <div class="col-xs-6">
                     {{ Form::select("start_hour[$time->type]", array_combine(range(0, 23), range(0, 23)), (!isset($time->default)) ? $time->getStartHourIndex() : 8, ['class' => 'form-control', 'id' => $time->type ]) }}
                     </div>
                    <div class="col-xs-6">
                     {{ Form::select("start_minute[$time->type]", array_combine(range(0, 55, 5), range(0, 55, 5)), (!isset($time->default)) ? $time->getStartMinuteIndex() : 0, ['class' => 'form-control', 'id' => $time->type ]) }}
                     </div>
                 </div>
            </td>
            <td>
                 <div class="row">
                     <div class="col-xs-6">
                     {{ Form::select("end_hour[$time->type]", array_combine(range(0, 23), range(0, 23)), (!isset($time->default)) ? $time->getEndHourIndex() : 18, ['class' => 'form-control']) }}
                     </div>
                    <div class="col-xs-6">
                     {{ Form::select("end_minute[$time->type]", array_combine(range(0, 55, 5), range(0, 55, 5)), (!isset($time->default)) ? $time->getEndMinuteIndex() : 0, ['class' => 'form-control']) }}
                     </div>
                 </div>
            </td>
            <td>
                <div class="pull-right">
                {{ Form::checkbox("is_day_off[$time->type]", 1, (isset($time)) ? $time->is_day_off: false ); }}
                </div>
            </td>
        </tr>
    </tbody>
    @endforeach
    <tfoot>
        <tr>
            <td colspan="4">
                <button type="submit" class="btn btn-primary">{{ trans('common.save') }}</button>
            </td>
        </tr>
    </tfoot>
     </table>
    {{ Form::close() }}
</div>
@stop
