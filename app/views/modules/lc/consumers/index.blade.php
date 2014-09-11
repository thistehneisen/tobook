@extends('modules.lc.layout')

@section('top-buttons')
<a href="{{ URL::route('lc.consumers.create') }}" class="btn btn-default btn-success"><span class="glyphicon glyphicon-plus"></span> {{ trans('common.add') }}</a>
@stop

@section('scripts')
    {{ HTML::script('assets/js/modules/lc.js') }}
@stop

@section('sub-content')
    @include('modules.lc._consumer_list')
@stop
