@extends('layouts.default')

@section ('styles')
{{ HTML::style(asset('assets/css/lcapp.css')) }}
@stop

@section ('scripts')
{{ HTML::script(asset('assets/js/modules/lcapp.js')) }}
@stop

@section ('title')
NFC Desktop App
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
            <li><a href="{{ route('app.lc.logout') }}">{{ trans('common.sign_out') }}</a></li>
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
    <div class="col-lg-7 col-md-7 col-sm-7">
        <div class="row-fluid">
            <button class="btn btn-default btn-success" data-toggle="modal" data-target="#js-createConsumerModal">
                <span class="glyphicon glyphicon-plus"></span> {{ trans('common.add') }}
            </button>
            {{ Form::open(['route' => 'app.lc.index', 'method' => 'get', 'class' => 'form-inline']) }}
                {{ Form::text('search', null, ['class' => 'form-control']) }}
                {{ Form::submit(trans('Search'), ['class' => 'btn btn-default']) }}
            {{ Form::close() }}
        </div>
        @include('modules.lc._consumer_list')
    </div>

    <div class="col-lg-5 col-md-5 col-sm-5" id="consumer-info">
        <!-- Consumer info appear using ajax here -->
    </div>

    <!-- Modal -->
    <div class="modal fade" id="js-createConsumerModal" role="dialog" aria-labelledby="js-createConsumerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">{{ trans('loyalty-card.consumer_info') }}</h4>
                </div>
                <div class="modal-body">
                    {{ Form::open(['route' => 'lc.consumers.store', 'class' => 'form-horizontal', 'id' => 'js-createConsumerForm']) }}
                    @foreach ([
                        'first_name'    => trans('co.first_name'),
                        'last_name'     => trans('co.last_name'),
                        'email'         => trans('co.email'),
                        'phone'         => trans('co.phone'),
                        'address'       => trans('co.address'),
                        'postcode'      => trans('co.postcode'),
                        'city'          => trans('co.city'),
                        'country'       => trans('co.country'),
                    ] as $key => $value)
                    <div class="form-group">
                        <label class="col-sm-4 control-label">{{ Form::label($key, $value) }}</label>
                        <div class="col-sm-6">
                            {{ Form::text($key, Input::old($key), ['class' => 'form-control', 'id' => $key]) }}
                        </div>
                    </div>
                    @endforeach
                    {{ Form::close() }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id='js-cancelCreateConsumer'>{{ trans('common.cancel') }}</button>
                    <button type="button" class="btn btn-success" id="js-confirmCreateConsumer">{{ trans('common.create') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="modal fade" id="js-confirmDeleteModal" role="dialog" aria-labelledby="js-confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">{{ trans('loyalty-card.delete_confirmation') }}</h4>
                </div>
                <div class="modal-body">
                    <p>{{ trans('loyalty-card.delete_question') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('common.cancel') }}</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteConsumer">{{ trans('common.delete') }}</button>
                </div>
            </div>
        </div>
    </div> -->

    <div class="modal fade" id="js-givePointModal" role="dialog" aria-labelledby="js-givePointModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">{{{ trans('Give Points') }}}</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {{ Form::open(['class' => 'form-horizontal', 'id' => 'js-givePointForm']) }}
                        <div class="form-group">
                            <label class="col-sm-3 col-sm-offset-2 control-label">{{ Form::label('points', 'Points ') }}</label>
                            <div class="col-sm-6">
                                {{ Form::text('points', Input::old('points'), ['class' => 'form-control', 'id' => 'points']) }}
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default col-sm-2 col-sm-offset-4" data-dismiss="modal" id='js-cancelGivePoint'>{{ trans('common.cancel') }}</button>
                    <button type="button" class="btn btn-success col-sm-2" id="js-confirmGivePoint">{{{ trans('Give') }}}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="js-messageModal" role="dialog" aria-labelledly="js-messageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('OK') }}</button>
                </div>
            </div>
        </div>
    </div>
@stop
