@extends ('modules.as.layout')
@section ('styles')
    @parent
    {{ HTML::style(asset('packages/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css')) }}
@stop

@section ('scripts')
    @parent
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.2/moment-with-locales.min.js') }}
    {{ HTML::script(asset('packages/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js')) }}
    <script>
$(function () {
    $('input.time-picker').datetimepicker({
        pickDate: false,
        minuteStepping: 15,
        format: 'HH:mm',
        language: '{{ App::getLocale() }}'
    });
});
    </script>
@stop

@section ('content')

@include ('modules.as.employees.tab', $employee)
@include ('el.messages')
<div id="form-add-custom-time">
{{ Form::open(['route' => ['as.employees.customTime', (isset($employee->id)) ? $employee->id: null], 'class' => 'form-horizontal well', 'role' => 'form']) }}
 <div class="form-group">
    <div class="col-sm-5">
        <h4 class="comfortaa">{{ trans('as.employees.custom_time') }}</h4>
    </div>
</div>
<div class="form-group">
    <label for="phone" class="col-sm-2 control-label">{{ trans('as.employees.date') }}</label>
    <div class="col-sm-10 {{ Form::errorCSS('phone', $errors) }}">
        <div class="input-group">
            {{ Form::text('date', (isset($customTime)) ? $customTime->date:'', ['class' => 'form-control input-sm date-picker', 'id' => 'date']) }}
        </div>
    </div>
</div>
<div class="form-group">
    <label for="start_at" class="col-sm-2 control-label">{{ trans('as.employees.start_at') }}</label>
    <div class="col-sm-10 {{ Form::errorCSS('start_at', $errors) }}">
        <div class="input-group">
            {{ Form::text('start_at', (isset($customTime)) ? $customTime->start_at:'', ['class' => 'form-control input-sm time-picker', 'id' => 'start_at']) }}
        </div>
    </div>
</div>
<div class="form-group">
    <label for="end_at" class="col-sm-2 control-label">{{ trans('as.employees.end_at') }}</label>
    <div class="col-sm-10 {{ Form::errorCSS('end_at', $errors) }}">
        <div class="input-group">
            {{ Form::text('end_at', (isset($customTime)) ? $customTime->end_at:'', ['class' => 'form-control input-sm time-picker', 'id' => 'end_at']) }}
        </div>
    </div>
</div>
<div class="form-group">
    <label for="start_at" class="col-sm-2 control-label">{{ trans('as.employees.is_day_off') }}</label>
    <div class="col-sm-10 {{ Form::errorCSS('is_day_off', $errors) }}">
        <label>{{ Form::checkbox('is_day_off', 1, (isset($customTime)) ? $customTime->is_day_off: false ); }}</label>
    </div>
</div>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-5">
        <button type="submit" class="btn btn-primary">{{ trans('common.save') }}</button>
    </div>
</div>
{{ Form::close() }}
</div>
<table class="table table-hover">
    <thead>
        <tr>
            <th><input type="checkbox" class="toggle-check-all-boxes" data-checkbox-class="checkbox"></th>
            @foreach ($fields as $field)
            <th>{{ trans($langPrefix. '.' .$field) }}</th>
            @endforeach
            <th>&nbsp;</th>
        </tr>
    </thead>
     <tbody id="js-crud-tbody">
    @foreach ($items as $item)
        <tr id="row-{{ $item->id }}" data-id="{{ $item->id }}" data-toggle="tooltip" data-placement="top" data-title="{{ trans('as.crud.sortable') }}">
            <td><input type="checkbox" class="checkbox" name="ids[]" value="{{ $item->id }}"></td>
        @foreach ($fields as $field)
            @if ($field === 'is_active')
                <td>
                @if ((bool) $item->$field === true)
                    <span class="label label-success">{{ trans('common.active') }}</span>
                @else
                    <span class="label label-danger">{{ trans('common.inactive') }}</span>
                @endif
                </td>
            @elseif (starts_with($field, 'is_'))
                <td>
                @if ((bool) $item->$field === true)
                    <span class="label label-success">{{ trans('common.yes') }}</span>
                @else
                    <span class="label label-danger">{{ trans('common.no') }}</span>
                @endif
                </td>
            @else
                <td>{{ $item->$field }}</td>
            @endif
        @endforeach
            <td>
            </td>
        </tr>
    @endforeach
      @if (empty($items->getTotal()))
        <tr>
            <td colspan="{{ count($fields) + 2 }}">{{ trans('common.no_records') }}</td>
        </tr>
        @endif
    </tbody>
</table>

<div class="row">
    <div class="col-md-4">
        @if (!empty($bulkActions))
        <div class="form-group">
            <label>@lang('as.with_selected')</label>
            <select name="action" id="mass-action" class="form-control input-sm">
            @foreach ($bulkActions as $action)
                <option value="{{ $action }}">{{ trans('common.'.$action) }}</option>
            @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary btn-sm btn-submit-mass-action">{{ trans('common.save') }}</button>
        @endif
    </div>
    <div class="col-md-6 text-right">
        {{  $items->appends(Input::only('perPage'))->links() }}
    </div>

    <div class="col-md-2 text-right">
        <div class="btn-group">
            <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
            @lang('as.items_per_page') <span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-right">
                <li><a href="{{ route('as.employees.customTime', ['id' => $employee->id, 'perPage' => 5]) }}">5</a></li>
                <li><a href="{{ route('as.employees.customTime', ['id' => $employee->id, 'perPage' => 10]) }}">10</a></li>
                <li><a href="{{ route('as.employees.customTime', ['id' => $employee->id, 'perPage' => 10]) }}">20</a></li>
                <li><a href="{{ route('as.employees.customTime', ['id' => $employee->id, 'perPage' => 50]) }}">50</a></li>
            </ul>
        </div>
    </div>
</div>

@stop
