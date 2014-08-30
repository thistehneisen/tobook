@extends('layouts.default')

@section ('scripts')
{{ HTML::script(asset('assets/js/modules/lcapp.js')) }}
@stop

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
        <button class="btn btn-default btn-success" data-toggle="modal" data-target="#js-createConsumerModal"><span class="glyphicon glyphicon-plus"></span> {{ trans('common.add') }}</button>
        @include('modules.lc._consumer_list')
    </div>

    <!-- Right panel with consumer info -->
    <div class="col-lg-4 col-md-4 col-sm-4">
        <!-- Consumer info appear using ajax here -->
    </div>

    <!-- Modal -->
    <div class="modal fade" id="js-createConsumerModal" role="dialog" aria-labelledby="js-createConsumerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">{{ trans('') }}</h4>
                </div>
                <div class="modal-body">
                    <!-- Create form goes here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('common.cancel') }}</button>
                    <button type="button" class="btn btn-success" id="confirm">{{ trans('common.create') }}</button>
                </div>
            </div>
        </div>
    </div>
@stop
