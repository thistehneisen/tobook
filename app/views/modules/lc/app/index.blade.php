@extends('layouts.app')

@section ('styles')
@parent
{{ HTML::style(asset('assets/css/lc/lcapp.css?v=0001')) }}
@stop

@section ('scripts')
@parent
<script>
    VARAA.addRoute('consumers', "{{ route('app.lc.show') }}");
</script>
{{ HTML::script(asset('assets/js/modules/lcapp.js?v=0001')) }}
@stop

@section ('title')
NFC Desktop App
@stop

@section('logo')
<h1 class="text-header">{{ trans('dashboard.loyalty') }}</h1>
@stop

@section('content')
    <div class="col-lg-7 col-md-7 col-sm-7">
        <div class="top-buttons clearfix">
            <button class="btn btn-default btn-success pull-right" data-toggle="modal" data-target="#js-createConsumerModal">
                <span class="glyphicon glyphicon-plus"></span> {{ trans('common.add') }}
            </button>
            {{ Form::open(['route' => 'app.lc.index', 'method' => 'get', 'class' => 'form-inline pull-left']) }}
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
                    <div id="js-alert" class="alert alert-danger hidden"></div>
                    {{ Form::open(['route' => 'app.lc.store', 'class' => 'form-horizontal', 'id' => 'js-createConsumerForm']) }}
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
                            {{ Form::text($key, Input::old($key), ['class' => 'form-control', 'id' => $key, 'name' => $key]) }}
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id='js-cancelCreateConsumer'>{{ trans('common.cancel') }}</button>
                    <button type="submit" class="btn btn-success" id="js-confirmCreateConsumer">{{ trans('common.create') }}</button>
                    {{ Form::close() }}
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
