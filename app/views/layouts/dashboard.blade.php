@extends ('layouts.default')

@section('styles')
    {{ HTML::style('assets/css/dashboard.css') }}
@stop

@section('main-nav-inner')
    @if (Confide::user())
        <li><p>
        @if (Session::get('stealthMode') !== null)
            You're now login as <strong>{{ Confide::user()->username }}</strong>
        @else
            {{ trans('common.welcome') }}, <strong>{{ Confide::user()->username }}</strong>!
        @endif
        </p></li>
    @endif
    @if (Confide::user())
    <li class="dropdown">
        <a href="{{ route('dashboard.index') }}">{{ trans('common.dashboard') }} <span class="caret"></span></a>
        <ul class="dropdown-menu">
            @foreach (Confide::user()->modules as $module => $routeName)
                <li><a href="{{ route($routeName) }}">{{ trans('dashboard.'.$module) }}</a></li>
            @endforeach
        </ul>
    </li>
    <li><a href="{{ route('user.profile') }}">{{ trans('common.my_account') }}</a></li>
    @if (Entrust::hasRole('Admin') || Session::get('stealthMode') !== null)
    <li><a href="{{ route('admin.index') }}">{{ trans('common.admin') }}</a></li>
    @endif
    <li><a href="{{ route('auth.logout') }}">{{ trans('common.sign_out') }}</a></li>
    @endif
@stop

@section('main-search')
@stop
