<!DOCTYPE html>
<html>
<head>
    <title>Look! I'm CRUDding</title>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
</head>
<body>
<div class="container">

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

    <div class="form-group">
        {{ Form::label('first_name', 'First Name') }}
        {{ Form::text('first_name', Input::old('first_name'), array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('last_name', 'Last Name') }}
        {{ Form::text('last_name', Input::old('last_name'), array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('owner_id', 'Owner ID') }}
        {{ Form::text('owner_id', Input::old('owner_id'), array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('email', 'Email') }}
        {{ Form::email('email', Input::old('email'), array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('phone', 'Phone') }}
        {{ Form::text('phone', Input::old('phone'), array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('address', 'Address') }}
        {{ Form::text('address', Input::old('address'), array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('city', 'City') }}
        {{ Form::text('city', Input::old('city'), array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('score', 'Score') }}
        {{ Form::text('score', Input::old('score'), array('class' => 'form-control')) }}
    </div>

    {{ Form::submit('Create the consumer!', array('class' => 'btn btn-primary')) }}

{{ Form::close() }}

</div>
</body>
</html>
