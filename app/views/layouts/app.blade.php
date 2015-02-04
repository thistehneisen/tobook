@extends ('layouts.default')

@section ('styles')
    @parent
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.3/css/bootstrapValidator.min.css') }}
@stop

@section ('scripts')
    @parent
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.3/js/bootstrapValidator.min.js') }}
@stop

@section('main-nav')
<div class="collapse navbar-collapse pull-right" id="main-menu">
    <ul class="nav navbar-nav">
    @if (Confide::user())
        <li><p>{{ trans('common.welcome') }}, <strong>{{{ Confide::user()->username }}}</strong>!</p></li>
        <li><a href="{{ route('app.lc.logout') }}">{{ trans('common.sign_out') }}</a></li>
    @endif
    </ul>
</div>
@stop

@section('main-search')
@stop

@section('footer')
@stop

@section('header-logo')
<a href="{{ route('app.lc.index') }}" class="logo pull-left">
    <img src="{{ asset_path('core/img/mainlogo.png') }}" alt="">
</a>
@stop
