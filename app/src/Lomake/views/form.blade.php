{{ Form::open($opt['form']) }}
    <!-- Fields -->
    @foreach ($fields as $name => $field)
        <div class="form-group {{ Form::errorCSS($name, $errors) }}">
            {{ Form::label($name, $field->getLabel(), ['class' => 'col-sm-2 col-sm-offset-1 control-label']) }}
            <div class="col-sm-6">
            {{ $field }}
            <!-- Validation error -->
            {{ Form::errorText($name, $errors) }}
            </div>
        </div>
    @endforeach

    <!-- Action buttons -->
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-6">
            <button type="submit" class="btn btn-sm btn-primary">{{ trans('common.save') }}</button>
        </div>
    </div>
{{ Form::close() }}
