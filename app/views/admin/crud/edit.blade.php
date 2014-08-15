@extends ('layouts.admin')

@section ('content')
    @parent
    
<h3 class="comfortaa text-success">Edit {{ Request::segment(2) }} #{{ $item->id }}</h3>
@include ('el.messages')

{{ Form::open(['route' => ['admin.crud.edit', Request::segment(2), $item->id], 'class' => 'form-horizontal']) }}
@foreach ($model->visible as $field)
    @if ($field !== 'id')
<div class="form-group">
    <label class="col-sm-2 control-label" for="{{ $field }}">{{ studly_case($field) }}</label>
    <div class="col-sm-10">
        {{ Form::text($field, Input::get($field, $item->$field), ['class' => 'form-control', 'id' => $field]) }}
    </div>
</div>
    @endif
@endforeach

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-sm btn-primary">Submit</button>
        <a href="{{ route('admin.crud.index', ['model' => Request::segment(2)]) }}" class="btn btn-link">Back</a>
    </div>
</div>

{{ Form::close() }}
@stop
