{{ Form::open(['route' => 'as.embed.l3.payment', 'id' => 'as-form-payment']) }}
    <input type="hidden" name="cart_id" value="{{ $cartId }}">
{{ Form::close() }}

{{ Form::open(['route' => 'as.bookings.frontend.add', 'class' => 'form-horizontal', 'id' => 'as-form-confirm', 'role' => 'form']) }}
    <div class="form-group">
        <label class="form-label col-md-2">{{ trans('as.embed.layout_3.service') }}:</label>
        <div class="col-md-3">{{ $service->name }} ({{ $service->length }} {{ trans('common.minutes')}})</div>
        <div class="col-md-3"><i class="glyphicon glyphicon-tag"></i> {{ $service->price }}{{ Config::get('varaa.currency') }}</div>
        <div class="col-md-4"><i class="glyphicon glyphicon-time"></i> {{ $date->format(trans('common.format.date')) }} {{ $time }}</div>
    </div>

    <div class="form-group">
        <label class="form-label col-md-2">{{ trans('as.embed.layout_3.employee') }}:</label>
        <div class="col-md-10">{{ $employee->name }}</div>
    </div>

    <div class="form-group">
        <label class="form-label col-md-2">{{ trans('as.embed.layout_3.name') }}:</label>
        <div class="col-md-10">{{ $first_name }} {{ $last_name }}</div>
    </div>

    <div class="form-group">
        <label class="form-label col-md-2">{{ trans('as.embed.layout_3.contact') }}:</label>
        <div class="col-md-3">{{ $phone }}</div>
        @if((int)$user->asOptions['email'] >= 2)
        <div class="col-md-7">{{ $email }}</div>
        @endif
    </div>
    @if(!empty($fields))
    <div class="form-group">
        <label class="form-label col-md-2">&nbsp;</label>
            <div class="col-md-10">
                <ul>
                @foreach($fields as $field)
                    @if((int)$user->asOptions[$field] >= 2)
                        <li>{{ $consumer->$field }}</li>
                    @endif
                @endforeach
                </ul>
            </div>
    </div>
    @endif
    @if((int)$user->asOptions['notes'] >= 2)
    <div class="form-group">
        <label class="form-label col-md-2">{{ trans('as.embed.layout_3.notes') }}:</label>
        <div class="col-md-10">{{ $notes }}</div>
    </div>
    @endif
    <div class="form-group row">
        <div class="col-sm-offset-2 col-sm-10">
            @if($isRequestedEmployee)
            <i class="glyphicon glyphicon-ok text-success"></i> {{ trans('as.bookings.request_employee') }}
            @else
            <i class="glyphicon glyphicon-remove text-danger"></i> {{ trans('as.bookings.request_employee') }}
            @endif
        </div>
    </div>

    <input type="hidden" name="service_id" value="{{ $service->id }}">
    <input type="hidden" name="employee_id" value="{{ $employee->id }}">
    <input type="hidden" name="booking_date" value="{{ $date->toDateString() }}">
    <input type="hidden" name="start_time" value="{{ $time }}">
    <input type="hidden" name="hash" value="{{ $hash }}">
    <input type="hidden" name="first_name" value="{{ $first_name }}">
    <input type="hidden" name="last_name" value="{{ $last_name }}">
    @if((int)$user->asOptions['email'] >= 2)
    <input type="hidden" name="email" value="{{ $email }}">
    @endif
    <input type="hidden" name="phone" value="{{ $phone }}">
    <input type="hidden" name="cart_id" value="{{ $cartId }}">
    <input type="hidden" name="inhouse" value="{{ $inhouse }}">
    <input type="hidden" name="is_requested_employee" value="{{ $isRequestedEmployee }}">
    @if ($inhouse)
    <input type="hidden" name="source" value="inhouse">
    @else
    <input type="hidden" name="source" value="layout3">
    @endif

    @if ($inhouse)
        <div class="alert alert-info" role="alert">
            <p>{{ trans('as.embed.layout_3.payment_note') }}</p>
        </div>
    @endif

    <div class="form-group">
        <div class="col-md-12">
            <button type="submit" id="btn-confirm-submit" class="btn btn-success">{{ trans('common.save') }}</button>
            <span class="text-success"></span>
            <span class="as-loading">
                <i class="glyphicon glyphicon-refresh text-info"></i> {{ trans('as.embed.loading') }}
            </span>
        </div>
    </div>

    <div class="error-msg hide">{{ trans('as.bookings.error.unknown') }}</div>
{{ Form::close() }}
