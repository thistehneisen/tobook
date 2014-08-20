@extends('modules.loyalty.layout')

@section('sub-content')
<nav class="navbar navbar-inverse">
    <div class="navbar-header">
        <a class="navbar-brand" href="{{ URL::to('consumer') }}">Consumer Alert</a>
    </div>
    <ul class="nav navbar-nav">
        <li><a href="{{ URL::to('consumers') }}">View All Consumers</a></li>
        <li><a href="{{ URL::to('consumers/create') }}">Create a Consumer</a>
    </ul>
</nav>

<h1>Create a consumer</h1>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}

{{ Form::open(array('url' => 'consumers')) }}
    @foreach ([
        'first_name'    => trans('First Name'),
        'last_name'     => trans('Last Name'),
        'owner_id'      => trans('Owner ID'),
        'email'         => trans('Email'),
        'phone'         => trans('Phone'),
        'address'       => trans('Address'),
        'city'          => trans('City'),
        'score'         => trans('Score'),
    ] as $key => $value)
    <div class="form-group">
        {{ Form::label($key, $value) }}
        {{ Form::text($key, Input::old($key), array('class' => 'form-control')) }}
    </div>
    @endforeach

    {{ Form::submit(trans('Create the consumer!'), array('class' => 'btn btn-primary')) }}

{{ Form::close() }}

</div>
@stop
