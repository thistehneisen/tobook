@extends ('layouts.email')

@section('content')
    <h1>{{ trans('user.password_reminder.reset.heading') }}</h1>
    <p>{{ trans('user.password_reminder.reset.body', ['password' => $password]) }}</p>
@stop
