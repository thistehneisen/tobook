@extends ('layouts.email')

@section ('content')
    <h1>{{ trans('auth.emails.confirm.title') }}</h1>

    <p>{{ sprintf(
            trans('auth.emails.confirm.body'),
            $user->first_name,
            route('auth.confirm', ['code' => $user->confirmation_code]),
            route('auth.confirm', ['code' => $user->confirmation_code])
        ) }}
    </p>
@stop
