<?php $consumer = $cart->consumer; ?>
<div class="list-group">
    <div class="list-group-item">
        <form id="form-confirm-booking">
            <div class="form-group row">
                <div class="col-sm-2">{{ trans('as.bookings.firstname') }} (*)</div>
                <div class="col-sm-10">
                    <span id="display_firstname">{{ (!empty($consumer->first_name)) ? $consumer->first_name : trans('as.bookings.empty') }}</span>
                    {{ Form::hidden('firstname', (!empty($consumer->first_name)) ? $consumer->first_name :'', ['class' => 'form-control input-sm', 'id' => 'firstname']) }}
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2">{{ trans('as.bookings.lastname') }}</div>
                <div class="col-sm-10">
                    <span id="display_lastname">{{(!empty($consumer->last_name)) ? $consumer->last_name : trans('as.bookings.empty')  }}</span>
                    {{ Form::hidden('lastname', (!empty($consumer->last_name)) ? $consumer->last_name :'', ['class' => 'form-control input-sm', 'id' => 'lastname']) }}
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2">{{ trans('as.bookings.email') }} (*)</div>
                <div class="col-sm-10">
                    <span id="display_email">{{ (!empty($consumer->email)) ? $consumer->email : trans('as.bookings.empty')  }}</span>
                    {{ Form::hidden('email', (!empty($consumer->email)) ? $consumer->email :'', ['class' => 'form-control input-sm', 'id' => 'email']) }}
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2">{{ trans('as.bookings.phone') }} (*)</div>
                <div class="col-sm-10">
                    <span id="display_phone">{{ (!empty($consumer->phone)) ? $consumer->phone : trans('as.bookings.empty')  }}</span>
                    {{ Form::hidden('phone', (!empty($consumer->phone)) ? $consumer->phone :'', ['class' => 'form-control input-sm', 'id' => 'phone']) }}
                </div>
            </div>
            <input type="hidden" name="hash" value="{{ $hash }}">
            <input type="hidden" name="cart_id" value="{{ $cart->id }}">
        </form>
    </div>
    <br>
    <div class="form-group row">
        <div class="col-sm-6"><a href="{{ route('as.embed.embed', ['hash' => $hash, 'action' => 'checkout']) }}" class="btn btn-default">{{ trans('common.cancel') }}</a></div>
        <div class="col-sm-6">
            <button data-success-msg="Varauksesi on vahvistettu! Kiitos varauksestasi." data-success-url="{{ route('as.embed.embed', ['hash'=> $hash]) }}" data-action-url="{{ route('as.bookings.frontend.add') }}" id="btn-confirm-booking" class="btn btn-success pull-right">{{ trans('as.bookings.confirm_booking') }}</button>
        </div>
    </div>
</div>
