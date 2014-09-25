{{ Form::open(['route' => 'as.bookings.service.front.add', 'class' => 'form-horizontal', 'id' => 'as-form-confirm', 'role' => 'form', 'data-post-url' => route('as.bookings.frontend.add')]) }}
    <div class="form-group">
        <label class="form-label col-sm-2">{{ trans('as.embed.layout_3.service') }}:</label>
        <div class="col-sm-3">{{ $service->name }} ({{ $service->length }} {{ trans('common.minutes')}})</div>
        <div class="col-sm-3"><i class="glyphicon glyphicon-tag"></i> {{ $service->price }}&euro;</div>
        <div class="col-sm-4"><i class="glyphicon glyphicon-time"></i> {{ $date->format(trans('common.format.date')) }} {{ $time }}</div>
    </div>

    <div class="form-group">
        <label class="form-label col-sm-2">{{ trans('as.embed.layout_3.employee') }}:</label>
        <div class="col-sm-10">{{ $employee->name }}</div>
    </div>

    <div class="form-group">
        <label class="form-label col-sm-2">{{ trans('as.embed.layout_3.name') }}:</label>
        <div class="col-sm-10">{{ $firstname }} {{ $lastname }}</div>
    </div>

    <div class="form-group">
        <label class="form-label col-sm-2">{{ trans('as.embed.layout_3.contact') }}:</label>
        <div class="col-sm-3">{{ $phone }}</div>
        <div class="col-sm-7">{{ $email }}</div>
    </div>

    <input type="hidden" name="service_id" value="{{ $service->id }}">
    <input type="hidden" name="employee_id" value="{{ $employee->id }}">
    <input type="hidden" name="booking_date" value="{{ $date->toDateString() }}">
    <input type="hidden" name="start_time" value="{{ $time }}">
    <input type="hidden" name="hash" value="{{ $hash }}">
    <input type="hidden" name="firstname" value="{{ $firstname }}">
    <input type="hidden" name="lastname" value="{{ $lastname }}">
    <input type="hidden" name="email" value="{{ $email }}">
    <input type="hidden" name="phone" value="{{ $phone }}">

    <div class="form-group">
        <div class="col-sm-12">
            <button type="submit" id="btn-checkout-submit" class="btn btn-success">{{ trans('common.save') }}</button>
            <span class="as-loading">
                <i class="glyphicon glyphicon-refresh text-info"></i> {{ trans('as.embed.loading') }}
            </span>
        </div>
    </div>

    <div class="error-msg hide">{{ trans('as.bookings.error.unknown') }}</div>
{{ Form::close() }}
