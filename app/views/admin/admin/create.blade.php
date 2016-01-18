@extends ('layouts.admin')

@section('content')
    <h3>{{ trans('admin.create_heading', ['model' => Str::lower(trans('common.administrator')) ]) }}</h3>

    @include ('el.messages')

    {{ Form::open(['route' => 'admin.create', 'method' => 'POST', 'class' => 'well form-horizontal']) }}

    <div class="form-group">
        <label class="col-sm-2 col-sm-offset-1 control-label" for="email">{{ trans('common.email') }}</label>
        <div class="col-sm-6">
            {{ Form::text('email', Input::get('email'), ['class' => 'form-control', 'id' => 'email']) }}
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 col-sm-offset-1 control-label" for="password">{{ trans('common.password') }}</label>
        <div class="col-sm-6">
            {{ Form::password('password', ['class' => 'form-control', 'id' => 'password']) }}
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-6 col-sm-offset-3">
            <button class="btn btn-primary">{{ trans('common.save') }}</button>
        </div>
    </div>

    {{ Form::close() }}
@stop
