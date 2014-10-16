{{ Form::open(['route' => 'as.bookings.service.front.add', 'class' => 'form-horizontal', 'id' => 'as-form-confirm', 'role' => 'form']) }}
<div class="form-group row">
    <label class="col-md-3">{{ trans('as.embed.layout_3.service') }}:</label>
    <div class="col-md-9">{{ $service->name }} ({{ $service->length }} {{ trans('common.minutes')}})</div>
</div>
<div class="form-group row">
    <div class="col-md-4 col-md-offset-3"><i class="glyphicon glyphicon-tag"></i> {{ $service->price }}&euro;</div>
    <div class="col-md-5"><i class="glyphicon glyphicon-time"></i> {{ $date->format(trans('common.format.date')) }} {{ $time }}</div>
</div>

<div class="form-group row">
    <label class="col-md-3">{{ trans('as.embed.layout_3.employee') }}:</label>
    <div class="col-md-9">{{ $employee->name }}</div>
</div>

<input type="hidden" name="service_id" value="{{ $service->id }}">
<input type="hidden" name="employee_id" value="{{ $employee->id }}">
<input type="hidden" name="booking_date" value="{{ $date->toDateString() }}">
<input type="hidden" name="start_time" value="{{ $time }}">
<input type="hidden" name="hash" value="{{ $hash }}">
<input type="hidden" name="inhouse" value="{{ $inhouse }}">
<input type="hidden" name="source" value="{{ ($inhouse) ? 'inhouse' : 'layout3' }}">

<div class="form-group row">
    <div class="col-md-12">
        <button id="btn-add-cart" class="btn btn-success">{{ trans('home.cart.add') }}</button>
    </div>
</div>

{{ Form::close() }}
