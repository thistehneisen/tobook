<div id="form-add-custom-time">
    {{ Form::open(['route' => ['as.employees.customTime.upsert', (isset($customTime)) ? $customTime->id : null], 'class' => 'form-horizontal well', 'role' => 'form']) }}
    <h4 class="comfortaa">{{ trans('as.employees.workshifts') }}</h4>
    @include ('el.messages')
    <div class="form-group">
        <label for="name" class="col-sm-2 control-label">{{ trans('as.employees.name') }}</label>
        <div class="col-sm-6 {{ Form::errorCSS('name', $errors) }}">
            {{ Form::text('name', (isset($customTime)) ? $customTime->name : '', ['class' => 'form-control input-sm', 'id' => 'name']) }}
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
