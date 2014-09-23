@extends('layouts.default')

@section ('styles')
{{ HTML::style(asset('assets/css/lcapp.css')) }}
@stop

@section ('scripts')
<script>
    VARAA.addRoute('consumers', "{{ route('app.lc.show') }}");
</script>
{{ HTML::script(asset('assets/js/modules/lcapp.js')) }}
@stop

@section ('title')
NFC Desktop App
@stop

@section('user-nav')
<ul class="user-nav nav nav-pills pull-right">
    <li class="dropdown active">
        @if (Confide::user())
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            @if (Session::get('stealthMode') !== null)
            You're now login as <strong>{{ Confide::user()->username }}</strong>
            @else {{ trans('common.welcome') }}, <strong>{{ Confide::user()->username }}</strong>!
            @endif
            <span class="caret"></span>
        </a>
        @else
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            {{ trans('common.for_business') }}
            <span class="caret"></span>
        </a>
        @endif
        <ul class="dropdown-menu">
            @if (Confide::user())
            <li><a href="{{ route('app.lc.logout') }}">{{ trans('common.sign_out') }}</a></li>
            @endif
        </ul>
    </li>
</ul>
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
                {{ Form::submit(trans('common.search'), ['class' => 'btn btn-default']) }}
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
                    <div id="js-result"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id='js-cancelCreateConsumer'>{{ trans('common.cancel') }}</button>
                    <button type="button" class="btn btn-success" id="js-confirmCreateConsumer">{{ trans('common.create') }}</button>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="modal fade" id="js-confirmDeleteModal" role="dialog" aria-labelledby="js-confirmDeleteModalLabel" aria-hidden="true">
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
    </div> --}}

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
