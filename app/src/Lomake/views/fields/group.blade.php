<div class="form-group {{ Form::errorCSS($field->name, $errors) }}">
    {{ Form::label($field->name, $field->label, ['class' => 'col-sm-2 col-sm-offset-1 control-label']) }}
    <div class="col-sm-6">
    {{ $field }}
    <!-- Validation error -->
    {{ Form::errorText($field->name, $errors) }}
    </div>
</div>
