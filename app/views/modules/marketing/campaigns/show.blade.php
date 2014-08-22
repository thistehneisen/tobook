@extends('modules.marketing.layout')

@section('sub-content')
<nav class="navbar navbar-inverse">
    <div class="navbar-header">
        <a class="navbar-brand" href="{{ URL::route('modules.mt.campaigns.index') }}">Campaign Alert</a>
    </div>
    <ul class="nav navbar-nav">
        <li><a href="{{ URL::route('modules.mt.campaigns.index') }}">View All Campaigns</a></li>
        <li><a href="{{ URL::route('modules.mt.campaigns.create') }}">Create a Campaign</a></li>
    </ul>
</nav>

<h1>Showing {{ $campaign->subject }}</h1>

    <div class="jumbotron text-center">
        <h2>{{ $campaign->subject }}</h2>
        <p>
            <strong>Subject: </strong> {{ $campaign->subject }}<br>
            <strong>From Email: </strong> {{ $campaign->from_email }}<br>
            <strong>From Name:</strong> {{ $campaign->from_name }}<br>
            <strong>Status: </strong> {{ $campaign->status }}<br>
        </p>
    </div>
</div>
@stop
