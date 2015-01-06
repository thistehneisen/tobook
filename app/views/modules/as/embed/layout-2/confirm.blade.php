{{ Form::open(['route' => 'as.bookings.frontend.add', 'class' => 'form-horizontal', 'role' => 'form', 'id' => 'frm-confirm']) }}

    <div class="form-group">
        <label class="form-label col-sm-2">{{ trans('as.embed.layout_2.name') }}:</label>
        <div class="col-sm-10">{{ $first_name }} {{ $last_name }}</div>
    </div>

    <div class="form-group">
        <label class="form-label col-sm-2">{{ trans('as.embed.layout_2.phone') }}:</label>
        <div class="col-sm-10">{{ $phone }}</div>
    </div>

    @if((int)$user->asOptions['email'] >= 2)
    <div class="form-group">
        <label class="form-label col-sm-2">{{ trans('as.embed.layout_2.email') }}:</label>
        <div class="col-sm-10">{{ $email }}</div>
    </div>
    @endif

    <?php $fields = ['address', 'postcode','city', 'country', 'notes']; ?>
    @foreach($fields as $field)
        @if((int)$user->asOptions[$field] >= 2)
        <div class="form-group">
            <label class="form-label col-sm-2">{{ trans("as.bookings.$field") }}:</label>
            <div class="col-sm-10">
                <?php $item = ($field === 'notes') ? $cart : $consumer; ?>
                {{ (!empty($item->$field)) ? $item->$field : trans('as.bookings.empty')  }}
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
    <input type="hidden" name="first_name" value="{{ $first_name }}">
    <input type="hidden" name="last_name" value="{{ $last_name }}">
    <input type="hidden" name="email" value="{{ $email }}">
    <input type="hidden" name="phone" value="{{ $phone }}">
    <input type="hidden" name="cart_id" value="{{ $cartId }}">
    <input type="hidden" name="source" value="layout2">
    <input type="hidden" name="is_requested_employee" value="{{ $isRequestedEmployee }}">

    <div class="form-group">
        <div class="col-sm-12">
            <a href="#" class="btn btn-default btn-as-cancel">{{ trans('as.embed.cancel') }}</a>
            <button type="submit" class="btn btn-success pull-right" id="btn-confirm">{{ trans('as.embed.confirm') }}</button>
        </div>
    </div>

    <div class="error-msg hide">{{ trans('as.bookings.error.unknown') }}</div>
{{ Form::close() }}
