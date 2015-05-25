@extends ('layouts.email')

@section('content')
    <h1>@lang('home.contact.subject')</h1>

    <p>{{ trans('home.contact.body', ['email' => $email, 'content' => $content]) }}
    </p>
@stop
