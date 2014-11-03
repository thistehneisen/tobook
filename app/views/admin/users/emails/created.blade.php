@extends ('layouts.email')

@section('content')
    <h1>{{ trans('user.password_reminder.created.heading') }}</h1>
    <p>{{ trans('user.password_reminder.created.body', ['password' => $password]) }}</p>
@stop
