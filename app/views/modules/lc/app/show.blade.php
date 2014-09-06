@if (isset($error))
    <p class="alert alert-warning">{{ $error }}</p>
@else
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3><i class="fa fa-user"></i> {{ $consumer->consumer->getNameAttribute() }}</h3>
        </div>
        <div class="panel-body">
            <p><i class="fa fa-envelope"></i> {{ $consumer->consumer->email }}</p>
            <p><i class="fa fa-phone"></i> {{ $consumer->consumer->phone }}</p>
            <p><i class="fa fa-star"></i> {{ trans('loyalty-card.points') }}: <span id="js-currentPoint">{{ $consumer->total_points }}</span></p>
            <button class="btn btn-success btn-block" data-toggle="modal" data-target="#js-givePointModal" data-consumerid="{{{ $consumer->id }}}">{{ trans('loyalty-card.give_points') }}</button>
            <hr>

            @if ($offers->count() > 0)
                <h3><i class="fa fa-gift"></i> {{ trans('loyalty-card.offers') }}</h3>
                @foreach ($offers as $key => $value)
                <div class="line clearfix">
                    <div class="col-md-6">
                        <div>{{ $value->name }}</div>
                        <div class="orange">
                            <span>{{ trans('loyalty-card.stamps') }} :</span>
                            @if ($stampInfo === null)
                            <span id="js-currentStamp{{ $value->id }}">0</span>
                            @else
                                @if (array_key_exists ($value->id, $stampInfo))
                            <span id="js-currentStamp{{ $value->id }}">{{ $stampInfo[$value->id][0] }}</span>
                                @else
                            <span id="js-currentStamp{{ $value->id }}">0</span>
                                @endif
                            @endif
                            <span> / {{ $value->required }}</span>
                        </div>
                        <div class="orange">
                            <span>{{ trans('loyalty-card.free_service') }} :</span>
                            @if ($stampInfo === null)
                            <span id="js-free{{ $value->id }}">0</span>
                            @else
                                @if (array_key_exists ($value->id, $stampInfo))
                            <span id="js-free{{ $value->id }}">{{ $stampInfo[$value->id][1] }}</span>
                                @else
                            <span id="js-free{{ $value->id }}">0</span>
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="btn-group pull-right">
                        <button class="btn btn-info" id="js-addStamp" data-offerid="{{{ $value->id }}}" data-consumerid="{{{ $consumer->id }}}">{{ trans('loyalty-card.add_stamp') }}</button>
                        <button class="btn btn-warning" id="js-useOffer" data-offerid="{{{ $value->id }}}" data-consumerid="{{{ $consumer->id }}}">{{ trans('loyalty-card.use_offer') }}</button>
                    </div>
                </div>
                @endforeach
                <hr>
            @endif

            @if ($vouchers->count() > 0)
                <h3><i class="fa fa-ticket"></i> {{ trans('loyalty-card.vouchers') }}</h3>
                @foreach ($vouchers as $key => $value)
                <div class="line clearfix">
                    <div class="col-md-7">
                        <div>{{ $value->name }}</div>
                        <div class="orange">
                            <span>{{ trans('loyalty-card.required') }}:</span>
                            <span>{{ $value->required }}</span>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <button class="btn btn-warning pull-right" id="js-useVoucher" data-required="{{{ $value->required }}}" data-voucherid="{{{ $value->id }}}" data-consumerid="{{{ $consumer->id }}}">{{ trans('loyalty-card.use_voucher') }}</button>
                    </div>
                </div>
                @endforeach
                <hr>
            @endif

            <button class="btn btn-success col-md-6" id="js-writeCard" data-consumerid="{{{ $consumer->id }}}">{{ trans('loyalty-card.write_card') }}</button>
            <button class="btn btn-default col-md-offset-1 col-md-5" id="js-back">{{ trans('common.back') }}</button>
        </div>
    </div>
@endif