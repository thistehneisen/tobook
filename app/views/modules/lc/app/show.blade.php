<h3>{{ $consumer->consumer->getNameAttribute() }}</h3>
<p>
    {{ $consumer->consumer->email }} / {{ $consumer->consumer->phone }}
</p>
{{{ trans('Points') }}}:
<span id="js-currentPoint">{{ $consumer->total_points }}</span>
<hr />
<button class="btn btn-default btn-success full" data-toggle="modal" data-target="#js-givePointModal" data-consumerid="{{{ $consumer->consumer->id }}}">{{ trans('Give Points') }}</button>

<div class="full"><h3>{{ trans('Vouchers') }}</h3>
    @foreach ($vouchers as $key => $value)
    <div class="data-row">
        <div class="col-md-7">
            <div>{{ $value->name }}</div>
            <div class="required">
                <span>{{ trans('Point required: ') }}</span>
                <span>{{ $value->required }}</span>
            </div>
        </div>
        <div class="col-md-5"><button class="btn btn-default btn-info" id="js-useVoucher" data-required="{{{ $value->required }}}" data-voucherid="{{{ $value->id }}}" data-consumerid="{{{ $consumer->consumer->id }}}">{{ trans('Use voucher') }}</button></div>
    </div>
    @endforeach
</div>
<div class="full"><h3>{{ trans('Offers') }}</h3>
    @foreach ($offers as $key => $value)
    <div class="data-row">
        <div class="col-md-6">
            <div>{{ $value->name }}</div>
            <div class="required">
                <span>{{ trans('Stamps: ') }}</span>
                @if ($stampInfo === null)
                <span id="js-currentStamp{{$value->id}}">0</span>
                @else
                    @if (array_key_exists ($value->id, $stampInfo))
                <span id="js-currentStamp{{$value->id}}">{{ $stampInfo[$value->id][0] }}</span>
                    @else
                <span id="js-currentStamp{{$value->id}}">{{ 0 }}</span>
                    @endif
                @endif
                <span> / {{{ $value->required }}}</span>
            </div>
        </div>
        <div class="col-md-6">
            <button class="btn btn-default btn-info col-md-6" id="js-addStamp" data-offerid="{{{ $value->id }}}" data-consumerid="{{{ $consumer->consumer->id }}}">{{ trans('Add Stamp') }}</button>
            <button class="btn btn-default btn-info col-md-5 col-md-offset-1" id="js-useOffer" data-offerid="{{{ $value->id }}}" data-consumerid="{{{ $consumer->consumer->id }}}">{{ trans('Use Offer') }}</button>
        </div>
    </div>
    @endforeach
</div>
<button class="btn btn-default btn-success col-md-6" id="js-writeCard" data-consumerid="{{{ $consumer->consumer->id }}}">{{ trans('Create new loyalty card') }}</button>
<button class="btn btn-default btn-success col-md-offset-1 col-md-5" id="js-back">{{ trans('Back') }}</button>
