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
        <a class="navbar-brand" href="{{ URL::to('consumers') }}">Consumer Alert</a>
    </div>
    <ul class="nav navbar-nav">
        <li><a href="{{ URL::to('consumers') }}">View All Consumer</a></li>
        <li><a href="{{ URL::to('consumers/create') }}">Create a Consumer</a>
    </ul>
</nav>

<h1>Edit {{ $consumer->name }}</h1>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}

{{ Form::model($consumer, array('route' => array('consumers.update', $consumer->id), 'method' => 'PUT')) }}

    <div class="form-group">
        {{ Form::label('first_name', 'First Name') }}
        {{ Form::text('first_name', null, array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('last_name', 'Last Name') }}
        {{ Form::text('last_name', null, array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('email', 'Email') }}
        {{ Form::email('email', null, array('class' => 'form-control')) }}
    </div>

    {{ Form::submit('Edit the Consumer!', array('class' => 'btn btn-primary')) }}

{{ Form::close() }}

</div>
</body>
</html>
