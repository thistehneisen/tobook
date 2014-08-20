<!DOCTYPE html>
<html>
    <head>
        <title>Testing index</title>
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container">
            <nav class="navbar navbar-inverse">
                <div class="navbar-header">
                    <a class="navbar-brand" href="{{ URL::to('consumers') }}">Consumer</a>
                </div>
                <ul class="nav navbar-nav">
                    <li><a href="{{ URL::to('consumers') }}">View all consumers</a></li>
                    <li><a href="{{ URL::to('consumers/create') }}">Create a consumer</a></li>
                </ul>
            </nav>

            <h1>All the consumers</h1>

            @if (Session::has('message'))
                <div class="alert alert-info">{{ Session::get('message') }}</div>
            @endif

            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <td>ID</td>
                        <td>First name</td>
                        <td>Last name</td>
                        <td>Score</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($consumers as $key => $value)
                        <tr>
                            <td>{{ $value->id }}</td>
                            <td>{{ $value->first_name }}</td>
                            <td>{{ $value->last_name }}</td>
                            <td>{{ $value->score }}</td>
                            <td>
                                {{ Form::open([
                                    'url' => 'consumers/' . $value->id,
                                    'class' => 'pull-right'
                                ]) }}
                                    {{ Form::hidden('_method', 'DELETE') }}
                                    {{ Form::submit('Delete this consumer', ['class' => 'btn btn-warning']) }}
                                {{ Form::close() }}
                                <a class="btn btn-small btn-success" href="{{ URL::to('consumers/' . $value->id) }}">Show this consumer</a>
                                <a class="btn btn-small btn-info" href="{{ URL::to('consumers/' . $value->id . '/edit') }}">Edit this consumer</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </body>
</html>
