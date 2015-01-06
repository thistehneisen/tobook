<?php $consumer = $cart->consumer; ?>
<div class="list-group">
    <div class="list-group-item">
        <form id="form-confirm-booking">
            <div class="form-group row">
                <div class="col-sm-2">{{ trans('as.bookings.first_name') }} (*)</div>
                <div class="col-sm-10">
                    <span id="display_first_name">{{ (!empty($consumer->first_name)) ? $consumer->first_name : trans('as.bookings.empty') }}</span>
                    {{ Form::hidden('first_name', (!empty($consumer->first_name)) ? $consumer->first_name :'', ['class' => 'form-control input-sm', 'id' => 'first_name']) }}
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2">{{ trans('as.bookings.last_name') }}</div>
                <div class="col-sm-10">
                    <span id="display_last_name">{{(!empty($consumer->last_name)) ? $consumer->last_name : trans('as.bookings.empty')  }}</span>
                    {{ Form::hidden('last_name', (!empty($consumer->last_name)) ? $consumer->last_name :'', ['class' => 'form-control input-sm', 'id' => 'last_name']) }}
                </div>
            </div>
            @if((int)$user->asOptions['email'] >= 2)
            <div class="form-group row">
                <div class="col-sm-2">{{ trans('as.bookings.email') }} @if((int)$user->asOptions['email'] === 3)(*)@endif</div>
                <div class="col-sm-10">
                    <span id="display_email">{{ (!empty($consumer->email)) ? $consumer->email : trans('as.bookings.empty')  }}</span>
                    {{ Form::hidden('email', (!empty($consumer->email)) ? $consumer->email :'', ['class' => 'form-control input-sm', 'id' => 'email']) }}
                </div>
            </div>
            @endif
            <div class="form-group row">
                <div class="col-sm-2">{{ trans('as.bookings.phone') }} (*)</div>
                <div class="col-sm-10">
                    <span id="display_phone">{{ (!empty($consumer->phone)) ? $consumer->phone : trans('as.bookings.empty')  }}</span>
                    {{ Form::hidden('phone', (!empty($consumer->phone)) ? $consumer->phone :'', ['class' => 'form-control input-sm', 'id' => 'phone']) }}
                </div>
            </div>
            <?php $fields = ['address', 'postcode','city', 'country', 'notes']; ?>
            @foreach($fields as $field)
                @if((int)$user->asOptions[$field] >= 2)
                <div class="form-group row">
                    <div class="col-sm-2">{{ trans("as.bookings.$field") }}  @if((int)$user->asOptions[$field] === 3)(*)@endif</div>
                    <div class="col-sm-10">
                        <?php $item = ($field === 'notes') ? $cart : $consumer; ?>
                        <span id="display_{{ $field }}">{{ (!empty($item->$field)) ? $item->$field : trans('as.bookings.empty')  }}</span>
                        {{ Form::hidden($field, (!empty($consumer->$field)) ? $item->$field :'', ['class' => 'form-control input-sm', 'id' => $field]) }}
                    </div>
                </div>
                @endif
            @endforeach
             <div class="form-group row">
                <div class="col-sm-offset-2 col-sm-10">
                    @if($isRequestedEmployee)
                    <i class="glyphicon glyphicon-ok text-success"></i> {{ trans('as.bookings.request_employee') }}
                    @else
                    <i class="glyphicon glyphicon-remove text-danger"></i> {{ trans('as.bookings.request_employee') }}
                    @endif
                </div>
            </div>
            <input type="hidden" name="hash" value="{{ $hash }}">
            <input type="hidden" name="cart_id" value="{{ $cart->id }}">
            <input type="hidden" name="source" value="layout1">
            <input type="hidden" name="is_requested_employee" value="{{ $isRequestedEmployee }}">
        </form>
    </div>
    <br>
    <div class="form-group row">
        <div class="col-sm-6"><a href="{{ route('as.embed.embed', ['hash' => $hash, 'action' => 'checkout', 'cart_id' => $cart->id, 'is_requested_employee' => $isRequestedEmployee]) }}" id="btn-cancel" class="btn btn-default">{{ trans('common.cancel') }}</a></div>
        <div class="col-sm-6">
            <button data-success-msg="Varauksesi on vahvistettu! Kiitos varauksestasi." data-success-url="{{ route('as.embed.embed', ['hash'=> $hash]) }}" data-action-url="{{ route('as.bookings.frontend.add') }}" id="btn-confirm-booking" class="btn btn-success pull-right">{{ trans('as.bookings.confirm_booking') }}</button>
        </div>
    </div>
</div>
