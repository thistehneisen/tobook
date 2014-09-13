<div id="form-add-custom-time">
    {{ Form::open(['route' => ['as.employees.customTime.upsert', $employee->id, (isset($customTime)) ? $customTime->id : null], 'class' => 'form-horizontal well', 'role' => 'form']) }}
    <h4 class="comfortaa">{{ trans('as.employees.custom_time') }}</h4>
    @include ('el.messages')

    <div class="form-group">
        <label for="phone" class="col-sm-2 control-label">{{ trans('as.employees.date') }}</label>
        <div class="col-sm-6 {{ Form::errorCSS('phone', $errors) }}">
            {{ Form::text('date', (isset($customTime)) ? $customTime->date : $now->toDateString(), ['class' => 'form-control input-sm date-picker', 'id' => 'date']) }}
        </div>
    </div>

    <div class="form-group">
        <label for="start_at" class="col-sm-2 control-label">{{ trans('as.employees.start_at') }}</label>
        <div class="col-sm-6 {{ Form::errorCSS('start_at', $errors) }}">
            {{ Form::text('start_at', (isset($customTime)) ? $customTime->start_at : '', ['class' => 'form-control input-sm time-picker', 'id' => 'start_at']) }}
        </div>
    </div>

    <div class="form-group">
        <label for="end_at" class="col-sm-2 control-label">{{ trans('as.employees.end_at') }}</label>
        <div class="col-sm-6 {{ Form::errorCSS('end_at', $errors) }}">
            {{ Form::text('end_at', (isset($customTime)) ? $customTime->end_at : '', ['class' => 'form-control input-sm time-picker', 'id' => 'end_at']) }}
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-6 {{ Form::errorCSS('is_day_off', $errors) }}">
            <label>{{ Form::checkbox('is_day_off', 1, (isset($customTime)) ? $customTime->is_day_off: false ); }} {{ trans('as.employees.is_day_off') }}</label>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-5">
            <button type="submit" class="btn btn-primary">{{ trans('common.save') }}</button>
        </div>
    </div>
    {{ Form::close() }}
</div>
