<h3>{{ $consumer->consumer->getNameAttribute() }}</h3>
<p>
    {{ $consumer->consumer->email }} / {{ $consumer->consumer->phone }}
</p>
{{{ trans('Points') }}}:
<span id='js-currentPoint'>{{ $consumer->total_points }}</span>
<hr />
<button class="btn btn-default btn-success full" data-toggle="modal" data-target="#js-givePointModal" data-consumerid='{{{ $consumer->consumer->id }}}'>{{ trans('Give Points') }}</button>

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
        <div class="col-md-5"><button class="btn btn-default btn-info" id="js-useVoucher" data-required='{{{ $value->required }}}' data-voucherid='{{{ $value->id }}}' data-consumerid='{{{ $consumer->consumer->id }}}'>{{ trans('Use voucher') }}</button></div>
    </div>
    @endforeach
</div>
<div class="full"><h3>{{ trans('Offers') }}</h3>
    @foreach ($offers as $key => $value)
    <div class="data-row">
        <div class="col-md-5">
            <button class="btn btn-default btn-info" id='js-addStamp' data-offerid='{{{ $value->id }}}' data-consumerid='{{{ $consumer->consumer->id }}}'>{{ trans('Add') }}</button>
            <button class="btn btn-default btn-info" id='js-useStamp' data-offerid='{{{ $value->id }}}' data-consumerid='{{{ $consumer->consumer->id }}}'>{{ trans('Use') }}</button>
        </div>
        <div class="col-md-7">
            @if ($stampInfo === null)
            <span class="col-md-4 required">0/{{{ $value->required }}}</span>
            @else
            <div class="col-md-5 required">
                <span id='js-currentStamp{{$value->id}}'>{{ $stampInfo[$value->id][0] }}</span>
                <span>/{{{ $value->required }}}</span>
                </div>
            @endif
            <span class="col-md-7">{{{ $value->name }}}</span>
        </div>
    </div>
    @endforeach
</div>
<button class="btn btn-default btn-success full">{{ trans('Create new loyalty card') }}</button>
<button class="btn btn-default btn-success" id="js-back">{{ trans('Back') }}</button>
