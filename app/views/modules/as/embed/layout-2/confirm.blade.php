{{ Form::open(['route' => 'as.bookings.frontend.add', 'class' => 'form-horizontal', 'role' => 'form', 'id' => 'frm-confirm']) }}

    <div class="form-group">
        <label class="form-label col-sm-2">{{ trans('as.embed.layout_2.name') }}:</label>
        <div class="col-sm-10">{{ $first_name }} {{ $last_name }}</div>
    </div>

    <div class="form-group">
        <label class="form-label col-sm-2">{{ trans('as.embed.layout_2.phone') }}:</label>
        <div class="col-sm-10">{{ $phone }}</div>
    </div>

    <div class="form-group">
        <label class="form-label col-sm-2">{{ trans('as.embed.layout_2.email') }}:</label>
        <div class="col-sm-10">{{ $email }}</div>
    </div>

    <input type="hidden" name="hash" value="{{ $hash }}">
    <input type="hidden" name="first_name" value="{{ $first_name }}">
    <input type="hidden" name="last_name" value="{{ $last_name }}">
    <input type="hidden" name="email" value="{{ $email }}">
    <input type="hidden" name="phone" value="{{ $phone }}">
    <input type="hidden" name="cartId" value="{{ $cartId }}">

    <div class="form-group">
        <div class="col-sm-12">
            <a href="#" class="btn btn-default btn-as-cancel">{{ trans('as.embed.cancel') }}</a>
            <button type="submit" class="btn btn-success pull-right">{{ trans('as.embed.confirm') }}</button>
        </div>
    </div>

    <div class="error-msg hide">{{ trans('as.bookings.error.unknown') }}</div>
{{ Form::close() }}
