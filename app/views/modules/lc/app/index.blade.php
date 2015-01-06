@extends('layouts.app')

@section ('styles')
    @parent
    {{ HTML::style(asset_path('lc/styles/main.css')) }}
@stop

@section ('scripts')
    @parent
<script>
    VARAA.addRoute('consumers', "{{ route('app.lc.show') }}");
</script>
    {{ HTML::script(asset_path('lc/scripts/main.js')) }}
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
                <i class="fa fa-plus"></i> {{ trans('common.add') }}
            </button>
            {{ Form::open(['route' => 'app.lc.index', 'method' => 'get', 'class' => 'form-inline pull-left']) }}
                {{ Form::text('search', Input::get('search'), ['class' => 'form-control']) }}
                <button class="btn btn-default" type="submit">
                    <i class="fa fa-search"></i> {{ trans('common.search') }}
                </button>
            {{ Form::close() }}
            &nbsp;
            @if (Input::get('search', '') !== '')
                <a href="{{ route('app.lc.index') }}" class="btn btn-default">
                    <i class="fa fa-caret-left"></i> {{ trans('common.back') }}
                </a>
            @endif
        </div>

        <div class="panel panel-default">
            <table class="table table-hover" id="consumer-table">
                <thead>
                    <tr>
                        <th>{{ trans('lc.consumer.index') }}</th>
                        <th>{{ trans('lc.last_visited') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $key => $value)
                    <tr data-consumerid="{{{ $value->lc ? $value->lc->id : 0 }}}" data-coreconsumerid="{{{ $value->id }}}">
                        <td>
                            {{ $value->first_name }} {{ $value->last_name }}
                        </td>
                        <td>
                            {{ $value->updated_at }}
                        </td>
                        {{--
                        <td class="no-display">
                            <a data-href="{{ URL::route('app.lc.delete', ['id' => $value->id]) }}" data-toggle="modal" data-target="#js-confirmDeleteModal" href="#">
                                <button class="btn btn-sm btn-danger" type="button">
                                    <span class="glyphicon glyphicon-trash"></span> {{ trans('common.delete') }}
                                </button>
                            </a>
                        </td>
                        --}}
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="pull-right">{{ $items->links() }}</div>
        </div>
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
                    <h4 class="modal-title">{{ trans('lc.consumer_info') }}</h4>
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

    {{--
    <div class="modal fade" id="js-confirmDeleteModal" role="dialog" aria-labelledby="js-confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">{{ trans('lc.delete_confirmation') }}</h4>
                </div>
                <div class="modal-body">
                    <p>{{ trans('lc.delete_question') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('common.cancel') }}</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteConsumer">{{ trans('common.delete') }}</button>
                </div>
            </div>
        </div>
    </div>
    --}}

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
