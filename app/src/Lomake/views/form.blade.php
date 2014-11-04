{{ Form::open($opt['form']) }}
    <!-- Fields -->
    @foreach ($fields as $field)
        @include('varaa-lomake::fields.group')
    @endforeach

    <!-- Action buttons -->
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-6">
            <button type="submit" class="btn btn-sm btn-primary" id="btn-save">{{ trans('common.save') }}</button>
        </div>
    </div>
{{ Form::close() }}
