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
                            <p class="text-center"><a data-target="#fd-modal" href="#" data-url="{{ route('fd.view', ['id' => $deal->flash_deal_date_id]) }}" class="btn btn-orange countdown btn-fd" data-date="{{ $deal->expire->toISO8601String() }}"></a></p>
                        </div>
                    </li>
                    @endif
                @endforeach
            </ul>
            <div class="panel-footer">{{ trans('fd.front.explore') }}</div>
        </div>
    </div>
    @endforeach
</div>

<div class="modal fade" id="fd-modal" role="dialog" aria-labelledby="fd-modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{{ trans('common.close') }}</span></button>
                <h4 class="modal-title" id="fd-modal-label">{{ trans('fd.index') }}</h4>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="fa fa-spinner fa-spin fa-2x"><div class="sr-only">Loading&hellip;</div></i>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('common.close') }}</button>
            </div>
        </div>
    </div>
</div>
