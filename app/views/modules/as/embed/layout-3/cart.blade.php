{{ Form::open(['route' => 'as.booking.frontend.cart', 'class' => 'form-horizontal', 'id' => 'as-form-confirm', 'role' => 'form']) }}
<div class="form-group row">
    <label class="col-md-3">{{ trans('as.embed.layout_3.service') }}:</label>
    <div class="col-md-9">{{ $service->name }} (@if(!empty($serviceTime)){{ $serviceTime->during }}@else{{ $service->during }}@endif {{ trans('common.minutes')}})</div>
</div>
<div class="form-group row">
    <div class="col-md-4 col-md-offset-3"><i class="glyphicon glyphicon-tag"></i> @if(!empty($serviceTime)) {{ $serviceTime->price }} @else {{ $service->price }} @endif {{ Settings::get('currency') }}</div>
    <div class="col-md-5"><i class="glyphicon glyphicon-time"></i> {{ $date->format(trans('common.format.date')) }} {{ $time }}</div>
</div>

<div class="form-group row">
    <label class="col-md-3">{{ trans('as.embed.layout_3.employee') }}:</label>
    <div class="col-md-9">{{ $employee->name }}</div>
</div>

<input type="hidden" name="service_id" value="{{ $service->id }}">
@if(!empty($serviceTime))
<input type="hidden" name="service_time" value="{{ $serviceTime->id }}">
@endif
<input type="hidden" name="employee_id" value="{{ $employee->id }}">
<input type="hidden" name="booking_date" value="{{ $date->toDateString() }}">
<input type="hidden" name="start_time" value="{{ $time }}">
<input type="hidden" name="hash" value="{{ $hash }}">
<input type="hidden" name="inhouse" value="{{ $inhouse }}">
<input type="hidden" name="source" value="{{ ($inhouse) ? 'inhouse' : 'layout3' }}">
<input type="hidden" name="cart_id" value="0">
<input type="hidden" name="booking_service_id" value="0">
<input type="hidden" name="business_id" value="0">

<div class="form-group row">
    <div class="col-md-12">
        <button data-service-url="{{ route('as.bookings.service.front.add') }}" id="btn-add-cart" class="btn btn-success">{{ trans('home.cart.add') }}</button>
    </div>
</div>

{{ Form::close() }}
