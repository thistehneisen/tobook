<div class="list-group">
    <div class="list-group-item">
        <form id="form-confirm-booking">
            <div class="form-group row">
                <div class="col-sm-2">Nimi (*)</div>
                <div class="col-sm-10"> {{ Form::text('consumer_firstname', '' , ['class' => 'form-control input-sm', 'id' => 'consumer_firstname']) }}</div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2">Sähköposti (*)</div>
                <div class="col-sm-10">{{ Form::text('consumer_email', '' , ['class' => 'form-control input-sm', 'id' => 'consumer_email']) }}</div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2">Puhelinnumero (*)</div>
                <div class="col-sm-10">{{ Form::text('consumer_phone', '' , ['class' => 'form-control input-sm', 'id' => 'consumer_phone']) }}</div>
            </div>
        </form>
    </div>
    <br>
    <div class="form-group row">
        <div class="col-sm-6"><button class="btn btn-default">{{ trans('common.cancel') }}</button></div>
        <div class="col-sm-6"><button data-success-url="{{ route('as.embed.embed', ['hash'=> $hash]) }}" data-action-url="{{ route('as.bookings.frontend.add') }}" id="btn-confirm-booking" class="btn btn-success pull-right">{{ trans('common.continue') }}</button></div>
    </div>
</div>
