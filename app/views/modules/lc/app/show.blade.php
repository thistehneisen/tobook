@extends('layout.default')

@section('title')
@stop

@section('nav')
@stop

@section('logo')
@stop

@section('content')
<h3 id="consumerName">{{{ $customerName }}}</h3>
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
@stop
