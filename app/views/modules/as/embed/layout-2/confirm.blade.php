{{ Form::open(['route' => 'as.bookings.frontend.add', 'class' => 'form-horizontal', 'role' => 'form']) }}

    <div class="form-group">
        <label class="form-label col-sm-2">{{ trans('as.embed.layout_2.name') }}:</label>
        <div class="col-sm-10">{{ $firstname }} {{ $lastname }}</div>
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
    <input type="hidden" name="firstname" value="{{ $firstname }}">
    <input type="hidden" name="lastname" value="{{ $lastname }}">
    <input type="hidden" name="email" value="{{ $email }}">
    <input type="hidden" name="phone" value="{{ $phone }}">

    <div class="form-group">
        <div class="col-sm-12">
            <a href="#" class="btn btn-default">{{ trans('as.embed.cancel') }}</a>
            <button type="submit" class="btn btn-success pull-right">{{ trans('as.embed.confirm') }}</button>
        </div>
    </div>

    <div class="error-msg hide">{{ trans('as.bookings.error.unknown') }}</div>
{{ Form::close() }}
