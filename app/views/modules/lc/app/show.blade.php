@if (isset($error))
    <p class="alert alert-warning">{{ $error }}</p>
@else
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>
                <i class="fa fa-user"></i>
                {{ $consumer->first_name }} {{ $consumer->last_name }}
                <button type="button" class="btn btn-default btn-sm pull-right" id="js-back">&times;</button>
            </h3>
        </div>
        <div class="panel-body">
            <p><i class="fa fa-envelope"></i> {{ $consumer->email }}</p>
            <p><i class="fa fa-phone"></i> {{ $consumer->phone }}</p>
            <p><i class="fa fa-star"></i> {{ trans('lc.points') }}: <span id="js-currentPoint">{{ $consumer->total_points }}</span></p>
            <button class="btn btn-success btn-block" id="js-writeCard" data-consumerid="{{{ $consumer->id }}}">{{ trans('lc.write_card') }}</button>
            <hr>

            @if ($offers->count() > 0)
                <h4><i class="fa fa-gift"></i> {{ trans('lc.offers') }}</h4>
                @foreach ($offers as $key => $value)
                    <div class="line clearfix">
                        <div class="col-md-6">
                            <div>{{ $value->name }}</div>
                            <div class="orange">
                                <span>{{ trans('lc.stamps') }} :</span>
                                @if ($stampInfo === null)
                                <span id="js-currentStamp{{ $value->id }}">0</span>
                                @else
                                    @if (array_key_exists ($value->id, $stampInfo))
                                <span id="js-currentStamp{{ $value->id }}">{{ $stampInfo[$value->id] }}</span>
                                    @else
                                <span id="js-currentStamp{{ $value->id }}">0</span>
                                    @endif
                                @endif
                                <span> / {{ $value->required }}</span>
                            </div>
                        </div>
                        <div class="btn-group pull-right">
                            <button class="btn btn-info" id="js-addStamp" data-url="{{ URL::route('app.lc.update', ['id' => $consumer->id]) }}" data-offerid="{{{ $value->id }}}">{{ trans('lc.add_stamp') }}</button>
                            <button class="btn btn-warning" id="js-useOffer" data-url="{{ URL::route('app.lc.update', ['id' => $consumer->id]) }}" data-offerid="{{{ $value->id }}}">{{ trans('lc.use_offer') }}</button>
                        </div>
                    </div>
                @endforeach
                <hr>
            @endif

            @if ($vouchers->count() > 0)
                <h4><i class="fa fa-ticket"></i> {{ trans('lc.vouchers') }}</h4>
                @foreach ($vouchers as $key => $value)
                    <div class="line clearfix">
                        <div class="col-md-6">
                            <div>{{ $value->name }}</div>
                            <div class="orange">
                                <span>{{ trans('lc.required') }}:</span>
                                <span>{{ $value->required }}</span>
                            </div>
                        </div>
                        <div class="btn-group pull-right">
                            <button class="btn btn-info" data-toggle="modal" data-url="{{ URL::route('app.lc.update', ['id' => $consumer->id]) }}" data-target="#js-givePointModal">{{ trans('lc.give_points') }}</button>
                            <button class="btn btn-warning pull-right" id="js-useVoucher" data-url="{{ URL::route('app.lc.update', ['id' => $consumer->id]) }}" data-required="{{{ $value->required }}}" data-voucherid="{{{ $value->id }}}">{{ trans('lc.use_voucher') }}</button>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endif
