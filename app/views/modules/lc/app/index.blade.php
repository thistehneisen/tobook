@extends('layouts.default')

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
        <?php $app = true; ?>
        @include('modules.lc._consumer_list')
    </div>

    <!-- Right panel with consumer info -->
    <div class="col-lg-4 col-md-4 col-sm-4">
        <!-- Consumer info appear using ajax here -->
        <h3 id="consumerName"></h3>
        <span id="consumerEmail"></span>
        <span id="consumerPhone"></span>
        <p id="consumerPoints"></p>
        <hr />
        <button class="btn btn-default btn-success" id="givePoints">{{ trans('Give Points') }}</button>
        <h3>{{ trans('Awards') }}</h3>
        <div id="pointList"></div>
        <div id="usePoint">
            <div class="col-md-7">
                <div id="pointName"></div>
                <div id="scoreRequired"></div>
            </div>
            <div class="col-md-5"><button class="btn btn-default btn-info" id="use">{{ trans('Use point') }}</button></div>
        </div>
        <h3>{{ trans('Stamps') }}</h3>
        <div id="stampList"></div>
        <div id="useStamp">
            <div class="col-md-4">
                <button class="btn btn-default btn-info">{{ trans('Add') }}</button>
                <button class="btn btn-default btn-info">{{ trans('Use') }}</button>
            </div>
            <div class="col-md-8">
                <span id="stampRequired"></span>&nbsp;<span id="stampName"></span>
            </div>
        </div>
        <button class="btn btn-default btn-success" id="createCard">{{ trans('Create new loyalty card') }}</button>
        <button class="btn btn-default btn-success">{{ trans('Back') }}</button>
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
                    <!-- Create form goes here -->
                    {{ Form::open(['route' => 'lc.consumers.store', 'class' => 'form-horizontal']) }}
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
                        <div class="col-sm-3">
                            {{ $errors->first($key) }}
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="cancel">{{ trans('common.cancel') }}</button>
                    <button type="button" class="btn btn-success" id="confirm">{{ trans('common.create') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="js-confirmDeleteModal" role="dialog" aria-labelledby="js-confirmDeleteModalLabel" aria-hidden="true">
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
                    <button type="button" class="btn btn-danger" id="confirm">{{ trans('common.delete') }}</button>
                </div>
            </div>
        </div>
    </div>
@stop
