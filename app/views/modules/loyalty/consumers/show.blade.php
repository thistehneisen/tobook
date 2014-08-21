@extends('modules.loyalty.layout')

@section('sub-content')
<nav class="navbar navbar-inverse">
    <div class="navbar-header">
        <a class="navbar-brand" href="{{ URL::to('consumers') }}">Consumers Alert</a>
    </div>
    <ul class="nav navbar-nav">
        <li><a href="{{ URL::to('consumers') }}">View All Consumers</a></li>
        <li><a href="{{ URL::to('consumers/create') }}">Create a Consumer</a>
    </ul>
</nav>

<h1>Showing {{ $consumer->name }}</h1>

    <div class="jumbotron text-center">
        <h2>{{ $consumer->name }}</h2>
        <p>
            <strong>First Name: </strong> {{ $consumer->first_name }}<br>
            <strong>Last Name: </strong> {{ $consumer->last_name }}<br>
            <strong>Email:</strong> {{ $consumer->email }}<br>
            <strong>Phone: </strong> {{ $consumer->phone }}<br>
            <strong>Address: </strong> {{ $consumer->address }}<br>
        </p>
    </div>
</div>
@stop
