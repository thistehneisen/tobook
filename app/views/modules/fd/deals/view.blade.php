<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{{ trans('common.close') }}</span></button>
    <h4 class="modal-title" id="fd-modal-label">{{ $item->flashDeal->service->name }}</h4>
</div>
<div class="modal-body text-left">
    @if (!$item)
    <div class="alert alert-danger">
        <p><strong>{{ trans('fd.front.err.not_found') }}</strong></p>
        <p>{{ trans('fd.front.err.not_found_long') }}</p>
    </div>
    @else
    <div class="row">
        <div class="col-md-8">
            <h3>{{ $item->flashDeal->service->name }}
            <span class="orange"><i class="fa fa-arrow-down"></i>{{ $item->flashDeal->discount_percent }}%</span>
            </h3>

            @if (!empty($item->flashDeal->service->description))
            <div class="well">{{ $item->flashDeal->service->description }}</div>
            @endif

        </div>
        <div class="col-md-4 text-center">
            <h4 class="comfortaa">{{ $item->flashDeal->discounted_price }}&euro; ({{ trans('common.normal') }} {{ $item->flashDeal->service->price }}&euro;)</h4>
            <button class="btn btn-md btn-success text-uppercase comfortaa">{{ trans('home.cart.add') }} <i class="fa fa-shopping-cart"></i></button>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h5>{{ trans('fd.front.business') }}: <a href="{{ $item->flashDeal->service->user->business_url }}" target="_blank">{{ $item->flashDeal->service->user->business_name }}</a></h5>
            <p>{{{ $item->flashDeal->service->user->full_address }}}</p>
        </div>
    </div>
    @endif
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('common.close') }}</button>
</div>
