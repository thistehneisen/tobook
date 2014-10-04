@extends ('layouts.default')

@section ('styles')
    @parent
    {{ HTML::style(asset('assets/css/lc/bootstrapValidator.css')) }}
@stop

@section ('scripts')
    @parent
    {{ HTML::script(asset('assets/js/modules/bootstrapValidator.js')) }}
@stop

@section('main-nav')
<div class="collapse navbar-collapse pull-right" id="main-menu">
    <ul class="nav navbar-nav">
    @if (Confide::user())
        <li><p>{{ trans('common.welcome') }}, <strong>{{ Confide::user()->username }}</strong>!</p></li>
        <li><a href="{{ route('app.lc.logout') }}">{{ trans('common.sign_out') }}</a></li>
    @endif
    </ul>
</div>
@stop

@section('main-search')
@stop

@section('footer')
@stop
