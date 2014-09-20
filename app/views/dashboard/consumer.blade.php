@extends ('layouts.dashboard')

@section('title')
    @parent :: {{ trans('common.dashboard') }}
@stop

@section('content')
    <h1>{{ trans('common.dashboard') }}</h1>
@stop
