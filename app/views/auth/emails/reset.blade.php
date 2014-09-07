@extends ('layouts.email')

@section('content')
    <h1>{{ trans('auth.emails.reset.title') }}</h1>

    <p>{{ sprintf(
            trans('auth.emails.reset.body'),
            $user->first_name,
            route('auth.reset', ['token' => $token]),
            route('auth.reset', ['token' => $token])
        ) }}
    </p>
@stop
