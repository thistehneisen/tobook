@extends ('modules.as.layout')

@section ('sub-content')

@include ('modules.as.services.resource.tabs')
<br>


@if (Route::currentRouteName() === 'as.services.resources.create')
{{ Form::open(['route' => 'as.services.resources.create', 'class' => 'form-horizontal well', 'role' => 'form']) }}
 @else
{{ Form::open(['route' => ['as.services.resources.edit', (isset($resource)) ? $resource->id: null], 'class' => 'form-horizontal well', 'role' => 'form']) }}
@endif
     @include ('el.messages')
     <div class="form-group">
         <div class="col-sm-5">
              @if (Route::currentRouteName() === 'as.services.resources.create')
              <h4 class="comfortaa">{{ trans('as.services.add_resource') }}</h4>
              @else
              <h4 class="comfortaa">{{ trans('as.services.edit_resource') }}</h4>
              @endif
         </div>
     </div>
    <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Resurssi</label>
        <div class="col-sm-5">
            {{ Form::text('name', (isset($resource)) ? $resource->name:'', ['class' => 'form-control input-sm', 'id' => 'name']) }}
        </div>
    </div>
    <div class="form-group">
        <label for="description" class="col-sm-2 control-label">Kuvaus</label>
        <div class="col-sm-5">
            {{ Form::textarea('description', (isset($resource)) ? $resource->description:'', ['class' => 'form-control input-sm', 'id' => 'description']) }}
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-5">
            <button type="submit" class="btn btn-primary">{{ trans('common.save') }}</button>
        </div>
    </div>
{{ Form::close() }}
@stop
