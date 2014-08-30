@extends('layouts.default')

@section('nav')
<nav class="row">
    <div class="col-md-6 text-left">
        <i class="fa fa-globe"></i>
        @foreach (Config::get('varaa.languages') as $locale)
        <a class="language-swicher {{ Config::get('app.locale') === $locale ? 'active' : '' }}" href="{{ UrlHelper::localizedCurrentUrl($locale) }}" title="">{{ strtoupper($locale) }}</a>
        @endforeach
    </div>
    <div class="col-md-6 text-right">
        <ul class="list-inline nav-links">
            @if (Confide::user())
            <li><a href="{{ route('auth.logout') }}">{{ trans('common.sign_out') }}</a></li>
            @endif
        </ul>
    </div>
</nav>
@stop

@section('logo')
<h1 class="text-header">{{ trans('dashboard.loyalty') }}</h1>
@stop

@section('footer')
@stop

@section('content')
    <div class="col-lg-8 col-md-8 col-sm-8">
        <button class="btn btn-default btn-success"><span class="glyphicon glyphicon-plus"></span> {{ trans('common.add') }}</button>
        @include('modules.lc._consumer_list')
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4">
        <!-- Consumer info appear here -->
    </div>
@stop
