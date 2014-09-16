    <div class="row">
        <h3 class="comfortaa">{{ trans('fd.index') }}</h3>
        @foreach ($deals as $category)
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $category->name }}</div>
                <ul class="list-group">
                    @foreach ($category->deals as $deal)
                        @if ($deal->flashDeal)
                        <li class="list-group-item">
                            <div class="flashdeal-item text-left">
                                <h4 class="text-center">
                                    {{{ $deal->flashDeal->service->name }}}
                                    <span class="orange">
                                        -{{ $deal->flashDeal->discount_percent }}%
                                    </span>
                                </h4>
                                <h5>
                                    {{ trans('common.price') }}: {{ $deal->flashDeal->discounted_price }}&euro; ({{ trans('common.normal') }} {{ $deal->flashDeal->service->price }}&euro;)
                                </h5>
                                <p><strong class="orange">{{{ $deal->flashDeal->service->user->business_name }}}</strong></p>
                                <p>{{{ $deal->flashDeal->service->user->full_address }}}</p>
                                <p></p>
                                <p class="text-center"><a href="#" class="btn btn-orange countdown" data-date="{{ $deal->expire->toISO8601String() }}"></a></p>
                            </div>
                        </li>
                        @endif
                    @endforeach
                </ul>
                <div class="panel-footer">Explore more</div>
            </div>
        </div>
        @endforeach
    </div>
