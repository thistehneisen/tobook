@extends ('layouts.admin')

@section ('content')
    @parent
    
{{ Form::open(['route' => ['admin.crud.edit', Request::segment(2), $item->id], 'class' => 'form-horizontal']) }}
@foreach ($model->visible as $field)
<div class="form-group">
    <label class="col-sm-2 control-label" for="{{ $field }}">{{ studly_case($field) }}</label>
    <div class="col-sm-10">
        {{ Form::text($field, Input::get($field, $item->$field), ['class' => 'form-control', 'id' => $field]) }}
    </div>
</div>
@endforeach

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-sm btn-primary">Submit</button>
        <button type="reset" class="btn btn-sm">Reset</button>
    </div>
</div>

{{ Form::close() }}
@stop
