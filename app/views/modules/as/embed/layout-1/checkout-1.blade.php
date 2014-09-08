<div class="list-group">
    <div class="list-group-item">
        <form id="form-confirm-booking" action="{{ route('as.embed.confirm') }}" method="POST">
            <div class="form-group row">
                <div class="col-sm-2">Etumimi (*)</div>
                <div class="col-sm-10"> {{ Form::text('firstname', (isset($booking_info['firstname'])) ? $booking_info['firstname'] : ''  , ['class' => 'form-control input-sm', 'id' => 'firstname']) }}</div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2">Sukunimi</div>
                <div class="col-sm-10"> {{ Form::text('lastname', (isset($booking_info['lastname'])) ? $booking_info['lastname'] : ''  , ['class' => 'form-control input-sm', 'id' => 'lastname']) }}</div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2">Sähköposti (*)</div>
                <div class="col-sm-10">{{ Form::text('email', (isset($booking_info['email'])) ? $booking_info['email'] : ''  , ['class' => 'form-control input-sm', 'id' => 'email']) }}</div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2">Puhelinnumero (*)</div>
                <div class="col-sm-10">{{ Form::text('phone', (isset($booking_info['phone'])) ? $booking_info['phone'] : ''  , ['class' => 'form-control input-sm', 'id' => 'phone']) }}</div>
            </div>
            <input type="hidden" name="hash" value="{{ $hash }}">
    </div>
    <br>
    <div class="form-group row">
        <div class="col-sm-6"><a href="{{ route('as.embed.embed', ['hash' => $hash]) }}" class="btn btn-default">{{ trans('common.cancel') }}</a></div>
        <div class="col-sm-6">
            <button type="submit" class="btn btn-success pull-right">{{ trans('common.continue') }}</button>
        </div>
    </div>
    </form>
</div>
